<?
	class WSShipping
	{
		var $config;
		var $db;
		var $wsdl;
		var $endpoint_url;

		function WSShipping($config, $db)
		{
			ini_set("soap.wsdl_cache_enabled", "0");

			$this->config=$config;
			$this->db=$db;
			$this->wsdl = $this->config['dir']."lib/ups/schema/RateWS.wsdl";
			if($this->config['ups']['test_mode'])
				$this->endpoint_url = 'https://wwwcie.ups.com/webservices/Rate';
			else
				$this->endpoint_url = 'https://onlinetools.ups.com/webservices/Rate';
		}

		function getServices($params)
		{
			$request = array();
			//create soap request
			$option['RequestOption'] = 'Shop';
			$request['Request'] = $option;

			/*$pickuptype['Code'] = '01';
			$pickuptype['Description'] = 'Daily Pickup';
			$request['PickupType'] = $pickuptype;

			$customerclassification['Code'] = '01';
			$customerclassification['Description'] = 'Classfication';
			$request['CustomerClassification'] = $customerclassification;*/

			$shipper['Name'] = $this->config['ups']['address']['name'];
			$shipper['ShipperNumber'] = $this->config['ups']['address']['shipper_number'];
			$address['AddressLine'] = $this->config['ups']['address']['line1'];
			$address['City'] = $this->config['ups']['address']['city'];
			$address['StateProvinceCode'] = $this->config['ups']['address']['state'];
			$address['PostalCode'] = $this->config['ups']['address']['postcode'];
			$address['CountryCode'] = 'US';
			$shipper['Address'] = $address;
			$shipment['Shipper'] = $shipper;

			$shipto['Name'] = $params['delivery']['name'];
			$addressTo['AddressLine'] = $params['delivery']['line'];
			$addressTo['City'] = $params['delivery']['city'];
			$addressTo['StateProvinceCode'] = '';
			$addressTo['PostalCode'] = $params['delivery']['postcode'];
			$addressTo['CountryCode'] = 'US';
			$addressTo['ResidentialAddressIndicator'] = '';
			$shipto['Address'] = $addressTo;
			$shipment['ShipTo'] = $shipto;

			$shipfrom['Name'] = $this->config['ups']['address']['name'];
			$addressFrom['AddressLine'] = $this->config['ups']['address']['line1'];
			$addressFrom['City'] = $this->config['ups']['address']['city'];
			$addressFrom['StateProvinceCode'] = $this->config['ups']['address']['state'];
			$addressFrom['PostalCode'] = $this->config['ups']['address']['postcode'];
			$addressFrom['CountryCode'] = 'US';
			$shipfrom['Address'] = $addressFrom;
			$shipment['ShipFrom'] = $shipfrom;

			/*$service['Code'] = '03';
			$service['Description'] = 'Service Code';
			$shipment['Service'] = $service;*/

			$packaging1['Code'] = '02';
			$packaging1['Description'] = 'Rate';
			$package1['PackagingType'] = $packaging1;

			$dunit1['Code'] = 'IN';
			$dunit1['Description'] = 'inches';
			$dimensions1['Length'] = $params['length']+0;
			$dimensions1['Width'] = $params['width']+0;
			$dimensions1['Height'] = $params['height']+0;
			$dimensions1['UnitOfMeasurement'] = $dunit1;
			$package1['Dimensions'] = $dimensions1;

			$punit1['Code'] = 'LBS';
			$punit1['Description'] = 'Pounds';
			/* 1 pound = 0.45359237 kilograms = 453.59237 grams */
			$packageweight1['Weight'] = round($params['weight']/453.59237, 1);
			$packageweight1['UnitOfMeasurement'] = $punit1;
			$package1['PackageWeight'] = $packageweight1;
			
			$declared_value['CurrencyCode'] = 'USD';
			$declared_value['MonetaryValue'] = round($params['total'], 2);
			$package_service_options['DeclaredValue'] = $declared_value;
			$package1['PackageServiceOptions'] = $package_service_options;

			$shipment['Package'] = array($package1);
			$shipment['ShipmentServiceOptions'] = '';
			$shipment['LargePackageIndicator'] = '';
			$request['Shipment'] = $shipment;

			try
			{
				$mode = array(
					'soap_version' => 'SOAP_1_1',  // use soap 1.1 client
					'trace' => 1
				);
				
				// initialize soap client
				$client = new SoapClient($this->wsdl , $mode);

				//set endpoint url
				$client->__setLocation($this->endpoint_url);


				//create soap header
				$usernameToken['Username'] = $this->config['ups']['username'];
				$usernameToken['Password'] = $this->config['ups']['password'];
				$serviceAccessLicense['AccessLicenseNumber'] = $this->config['ups']['access_key'];
				$upss['UsernameToken'] = $usernameToken;
				$upss['ServiceAccessToken'] = $serviceAccessLicense;

				$header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$upss);
				$client->__setSoapHeaders($header);

				//get response
				$response = $client->__soapCall('ProcessRate', array($request));
				$this->log($client, "GET SERVICES");
				if($response->Response->ResponseStatus->Code+0 !== 1)
					return false;

				$ret = array();
				$sql = array();
				foreach($response->RatedShipment as $row)
				{
					if($row->TotalCharges->CurrencyCode != 'USD')
						return false;
						
					$service = array('price'=> $row->TotalCharges->MonetaryValue, 'name'=>$row->Service->Description);
					if(is_array($row->RatedShipmentAlert))
					{
						$service['warning'] = array();
						foreach($row->RatedShipmentAlert as $alert)
							$service['warning'][] = trim($alert->Description);
						$service['warning'] = implode('. ', $service['warning']);
					}
					else
					if(trim($row->RatedShipmentAlert->Description) != '')
						$service['warning'] = trim($row->RatedShipmentAlert->Description);
					if(trim($row->Service->Description) == '')
						$sql[] = $row->Service->Code;
					if(isset($row->GuaranteedDelivery))
					{
						$guarantee = array('days' => $row->GuaranteedDelivery->BusinessDaysInTransit);
						if(isset($row->GuaranteedDelivery->DeliveryByTime))
							$guarantee['time'] = $row->GuaranteedDelivery->DeliveryByTime;
						$service['guarantee'] = $guarantee;
					}
					$ret[$row->Service->Code] = $service;
				}
				
				if(count($sql))
				{
					$sql = array_map(array($this->db, 'Quote'), $sql);
					$results = $this->db->Execute($sql=sprintf("SELECT * FROM ups_services WHERE code IN (%s)", implode(',', $sql)));
					while($row = $results->FetchRow())
						$ret[$row['code']]['name'] = $row['description'];
				}

				return $ret;
			}
			catch(Exception $ex)
			{
				$this->log($client, "EXCEPTION");
				return false;
			}
		}
		
		function getService($params)
		{
			$request = array();
			//create soap request
			$option['RequestOption'] = 'Rate';
			$request['Request'] = $option;

			/*$pickuptype['Code'] = '01';
			$pickuptype['Description'] = 'Daily Pickup';
			$request['PickupType'] = $pickuptype;

			$customerclassification['Code'] = '01';
			$customerclassification['Description'] = 'Classfication';
			$request['CustomerClassification'] = $customerclassification;*/

			$shipper['Name'] = $this->config['ups']['address']['name'];
			$shipper['ShipperNumber'] = $this->config['ups']['address']['shipper_number'];
			$address['AddressLine'] = $this->config['ups']['address']['line1'];
			$address['City'] = $this->config['ups']['address']['city'];
			$address['StateProvinceCode'] = $this->config['ups']['address']['state'];
			$address['PostalCode'] = $this->config['ups']['address']['postcode'];
			$address['CountryCode'] = 'US';
			$shipper['Address'] = $address;
			$shipment['Shipper'] = $shipper;

			$shipto['Name'] = $params['delivery']['name'];
			$addressTo['AddressLine'] = $params['delivery']['line'];
			$addressTo['City'] = $params['delivery']['city'];
			$addressTo['StateProvinceCode'] = '';
			$addressTo['PostalCode'] = $params['delivery']['postcode'];
			$addressTo['CountryCode'] = 'US';
			$addressTo['ResidentialAddressIndicator'] = '';
			$shipto['Address'] = $addressTo;
			$shipment['ShipTo'] = $shipto;

			$shipfrom['Name'] = $this->config['ups']['address']['name'];
			$addressFrom['AddressLine'] = $this->config['ups']['address']['line1'];
			$addressFrom['City'] = $this->config['ups']['address']['city'];
			$addressFrom['StateProvinceCode'] = $this->config['ups']['address']['state'];
			$addressFrom['PostalCode'] = $this->config['ups']['address']['postcode'];
			$addressFrom['CountryCode'] = 'US';
			$shipfrom['Address'] = $addressFrom;
			$shipment['ShipFrom'] = $shipfrom;

			$service['Code'] = $params['service_code'];
			$shipment['Service'] = $service;

			$packaging1['Code'] = '02';
			$packaging1['Description'] = 'Rate';
			$package1['PackagingType'] = $packaging1;

			$dunit1['Code'] = 'IN';
			$dunit1['Description'] = 'inches';
			$dimensions1['Length'] = $params['length']+0;
			$dimensions1['Width'] = $params['width']+0;
			$dimensions1['Height'] = $params['height']+0;
			$dimensions1['UnitOfMeasurement'] = $dunit1;
			$package1['Dimensions'] = $dimensions1;

			$punit1['Code'] = 'LBS';
			$punit1['Description'] = 'Pounds';
			/* 1 pound = 0.45359237 kilograms = 453.59237 grams */
			$packageweight1['Weight'] = round($params['weight']/453.59237, 1);
			$packageweight1['UnitOfMeasurement'] = $punit1;
			$package1['PackageWeight'] = $packageweight1;

			$declared_value['CurrencyCode'] = 'USD';
			$declared_value['MonetaryValue'] = round($params['total'], 2);
			$package_service_options['DeclaredValue'] = $declared_value;
			$package1['PackageServiceOptions'] = $package_service_options;
			
			$shipment['Package'] = array($package1);
			$shipment['ShipmentServiceOptions'] = '';
			$shipment['LargePackageIndicator'] = '';
			$request['Shipment'] = $shipment;

			try
			{
				$mode = array(
					'soap_version' => 'SOAP_1_1',  // use soap 1.1 client
					'trace' => 1
				);
				
				// initialize soap client
				$client = new SoapClient($this->wsdl , $mode);

				//set endpoint url
				$client->__setLocation($this->endpoint_url);


				//create soap header
				$usernameToken['Username'] = $this->config['ups']['username'];
				$usernameToken['Password'] = $this->config['ups']['password'];
				$serviceAccessLicense['AccessLicenseNumber'] = $this->config['ups']['access_key'];
				$upss['UsernameToken'] = $usernameToken;
				$upss['ServiceAccessToken'] = $serviceAccessLicense;

				$header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$upss);
				$client->__setSoapHeaders($header);

				//get response
				$response = $client->__soapCall('ProcessRate', array($request));
				$this->log($client, "GET SERVICE");
				if($response->Response->ResponseStatus->Code+0 !== 1)
					return false;

				if($response->RatedShipment->TotalCharges->CurrencyCode != 'USD')
					return false;
					
				$service = array('code'=>$response->RatedShipment->Service->Code, 'price'=> $response->RatedShipment->TotalCharges->MonetaryValue, 'name'=>trim($response->RatedShipment->Service->Description));
				if(is_array($response->RatedShipment->RatedShipmentAlert))
				{
					$service['warning'] = array();
					foreach($response->RatedShipment->RatedShipmentAlert as $alert)
						$service['warning'][] = trim($alert->Description);
					$service['warning'] = implode('. ', $service['warning']);
				}
				else
				if(trim($response->RatedShipment->RatedShipmentAlert->Description) != '')
					$service['warning'] = trim($response->RatedShipment->RatedShipmentAlert->Description);
				
				if(isset($response->RatedShipment->GuaranteedDelivery))
				{
					$guarantee = array('days' => $response->RatedShipment->GuaranteedDelivery->BusinessDaysInTransit);
					if(isset($response->RatedShipment->GuaranteedDelivery->DeliveryByTime))
						$guarantee['time'] = $response->RatedShipment->GuaranteedDelivery->DeliveryByTime;
					$service['guarantee'] = $guarantee;
				}
				
				if($service['name'] == '')
				{
					$result = $this->db->Execute($sql=sprintf("SELECT * FROM ups_services WHERE code = %s", $this->db->Quote($response->RatedShipment->Service->Code)));
					$result = $result->FetchRow();
					if($result)
						$service['name'] = $result['description'];
				}

				return $service;
			}
			catch(Exception $ex)
			{
				return false;
			}
		}
		
		function log($client, $action)
		{
			$action=str_replace(" ","-",trim($action));
			$action=mb_ereg_replace(
				"[^a-z0-9-]*"
				,""
				,iconv(
					"UTF-8"
					,"UTF-7//TRANSLIT"
					,mb_strtolower($action)
				)
			);
			while(strstr($action,"--"))
				$action=str_replace("--","-",$action);
		
			$filename_log = $this->config['path'].'script/logs/shipping/'.date('Y-m-d').'/';
			if(!is_dir($filename_log))
		   {
				@mkdir($filename_log, 0777);
				@chmod($filename_log, 0777);
			}
			$filename_log .= date('Y-m-d-H.i.s').'-'.$action.'.log';
			
			//save soap request and response to file
			  if($fw = fopen($filename_log, 'a'))
			{
				fwrite($fw , "\nAction: ".$action."\n");
				fwrite($fw , "Request: \n" . $client->__getLastRequest() . "\n");
				fwrite($fw , "Response: \n" . $client->__getLastResponse() . "\n");
				fclose($fw);
			}
		}
	}
?>