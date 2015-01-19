<?
	class WSTax
	{
		var $config;

		function WSTax($config)
		{
			include_once $config['path'].'lib/speedtax/SpeedTaxApi.php';
			$this->config=$config;
		}

		function getTax($order,$products,$delivery,$billing)
		{
			try {
				$ShipFromAddress = new address();	
				$ShipFromAddress->address1 = $this->config['speedtax']['address1'];
				$ShipFromAddress->address2 = $this->config['speedtax']['address2'];
				
				$Invoice = new invoice();	
				$Invoice->customerIdentifier = $billing['name'];
				$Invoice->invoiceType = INVOICE_TYPES::INVOICE;
				$Invoice->invoiceDate = date('Y-m-d\\TH:i:s', $order['time']);
				
				$index = 0;
				foreach($products as $row)
				{
					$Price = new price();
					$Price->decimalValue = $row['sales_amount'];
					
					$UnitPrice = new price();
					$UnitPrice->decimalValue = $row['unit_price'];
					
					// Setup the first line item
					
					$LineItem = new lineItem();	
					$LineItem->lineItemNumber = $index + 1;
					$LineItem->productCode = $row['upc_code'];
					$LineItem->customReference = $row['id'];
					$LineItem->quantity = $row['quantity'];
					$LineItem->unitPrice = $UnitPrice;
					$LineItem->salesAmount = $Price;
					$LineItem->shipFromAddress = $ShipFromAddress;	
					
					$addr = array();
					if(($line = trim($delivery['city'])) != '')
						$addr[] = $line;
					if(($line = trim($delivery['county'])) != '')
						$addr[] = $line;
					if(($line = trim($delivery['country'])) != '')
						$addr[] = $line;
					if(($line = trim($delivery['postcode'])) != '')
						$addr[] = $line;
						
					$ShipToAddress = new address();
					$ShipToAddress->address1 = trim($delivery['street']);
					$ShipToAddress->address2 = implode(', ', $addr);
					
					$LineItem->shipToAddress = $ShipToAddress;

					$Invoice->lineItems[$index] = $LineItem;
					
					$index++;
				}
				
				$stx = new SpeedTax();
				$result = $stx->CalculateInvoice($Invoice);
				$this->log($Invoice, $result, 'CalculateInvoice');
				if(strtoupper(trim($result->CalculateInvoiceResult->resultType)) != 'SUCCESS')
					$tax = false;
				else
					$tax = $result->CalculateInvoiceResult->totalTax->decimalValue;
			} catch (Exception $e) {
				$tax = false;
			}
			
			return $tax;
		}
		
		function postInvoice($order,$products,$delivery,$billing)
		{
			try {
				$ShipFromAddress = new address();	
				$ShipFromAddress->address1 = $this->config['speedtax']['address1'];
				$ShipFromAddress->address2 = $this->config['speedtax']['address2'];
				
				$Invoice = new invoice();	
				$Invoice->customerIdentifier = $billing['name'];
				$Invoice->invoiceType = INVOICE_TYPES::INVOICE;
				$Invoice->invoiceDate = date('Y-m-d\\TH:i:s', $order['time']);
				$Invoice->invoiceNumber = $order['invoice_number'];
				
				$index = 0;
				foreach($products as $row)
				{
					$Price = new price();
					$Price->decimalValue = $row['sales_amount'];
					
					$UnitPrice = new price();
					$UnitPrice->decimalValue = $row['unit_price'];
					
					// Setup the first line item
					
					$LineItem = new lineItem();	
					$LineItem->lineItemNumber = $index + 1;
					$LineItem->productCode = $row['upc_code'];
					$LineItem->customReference = $row['id'];
					$LineItem->quantity = $row['quantity'];
					$LineItem->unitPrice = $UnitPrice;
					$LineItem->salesAmount = $Price;
					$LineItem->shipFromAddress = $ShipFromAddress;	
					
					$addr = array();
					if(($line = trim($delivery['city'])) != '')
						$addr[] = $line;
					if(($line = trim($delivery['county'])) != '')
						$addr[] = $line;
					if(($line = trim($delivery['country'])) != '')
						$addr[] = $line;
					if(($line = trim($delivery['postcode'])) != '')
						$addr[] = $line;
						
					$ShipToAddress = new address();
					$ShipToAddress->address1 = trim($delivery['street']);
					$ShipToAddress->address2 = implode(', ', $addr);
					
					$LineItem->shipToAddress = $ShipToAddress;

					$Invoice->lineItems[$index] = $LineItem;
					
					$index++;
				}
				
				$stx = new SpeedTax();
				$result = $stx->PostInvoice($Invoice);
				$this->log($Invoice, $result, 'PostInvoice');
				/*if(strtoupper(trim($result->PostInvoiceResult->resultType)) != 'SUCCESS')
					$tax = false;
				else*/
					$tax = $result->PostInvoiceResult->totalTax->decimalValue;
			} catch (Exception $e) {
				$tax = false;
			}
			
			return $tax;
		}
		
		function checkAddress($line1, $line2)
		{
			try {
				$Address = new address();
		
				$Address->address1 = trim($line1);
				$Address->address2 = trim($line2);
				
				$stx = new SpeedTax();
				$result = $stx->ResolveAddress($Address);
				$this->log($Address, $result, 'ResolveAddress');
				return strtoupper(trim($result->ResolveAddressResult->resultType)) == 'FULL';
			} catch (Exception $e) {
				return false;
			}
		}
		
		function log($params, $result, $action)
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
		
			$filename_log = $this->config['path'].'script/logs/tax/'.date('Y-m-d').'/';
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
				fwrite($fw , "Params: \n" . var_export($params, true) . "\n");
				fwrite($fw , "Result: \n" . var_export($result, true) . "\n");
				fclose($fw);
			}
		}
	}
?>