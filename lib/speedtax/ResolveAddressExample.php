<?php
include_once 'SpeedTaxApi.php';
include_once 'SpeedTaxUtil.php';

try {

	// Note: Case of $Address is important.
	$Address = new address();
	
	$Address->address1 = '670 Park Avenue';
	$Address->address2 = 'Moody, Saint Clair, Alabama, 35004';
	
	$stx = new SpeedTax();
	
	$result = $stx->ResolveAddress($Address);
	
	switch ($result->ResolveAddressResult->resultType)
	{	
		case 'STATE':
			print "Address resolved at STATE level:\n";
			DisplayFullAddress($result->ResolveAddressResult->resolvedAddress, "");
			DisplayJurisdictions($result->ResolveAddressResult->jurisdictions);
			break;
		case 'FALLBACK':
			print "Address resolved at ZIP CODE level:\n";
			DisplayFullAddress($result->ResolveAddressResult->resolvedAddress, "");
			DisplayJurisdictions($result->ResolveAddressResult->jurisdictions);
			break;
		case 'FULL':
			print "Address fully resolved:\n";
			DisplayFullAddress($result->ResolveAddressResult->resolvedAddress, "");
			DisplayJurisdictions($result->ResolveAddressResult->jurisdictions);
			break;
		case 'UNRESOLVED':
			print "Address not resolved:\n";
			DisplayFullAddress($result->ResolveAddressResult->resolvedAddress, "");
			DisplayJurisdictions($result->ResolveAddressResult->jurisdictions);
			break;
	}

} catch (Exception $e) {
    // in case of an error, process the fault
    if ($e instanceof WSFault) {
        printf("Soap Fault: %s\n", $e->Reason);
    } else {
        printf("Exception Message = %s\n", $e->getMessage());
    }
}

?>

