<?php
include_once 'SpeedTaxApi.php';

try {

    // call the operation
	$stx = new SpeedTax();
    $response = $stx->Ping();
	
    print "Response: " . $response->return . "\n";

} catch (Exception $e) {
    // in case of an error, process the fault
    if ($e instanceof WSFault) {
        printf("Soap Fault: %s\n", $e->Reason);
    } else {
        printf("Message = %s\n", $e->getMessage());
    }
}

?>

