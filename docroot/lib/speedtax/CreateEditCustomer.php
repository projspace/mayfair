<?php
include_once 'SpeedTaxApi.php';
include_once 'SpeedTaxUtil.php';

// Generate a unique customer reference for this test
$t = explode(' ',microtime());
$Ref = "PHP Test Cust " . $t[1];		
print "Creating and updating customer reference: $Ref \n";

// ***************
// Create Customer
// ***************
try 
{
	$Customer = new customer();	
	$Customer->customerReference = $Ref; 
	$Customer->name = "PHP Test Customer";
    $Customer->taxNumber = "PHP321";
	
	// Call the web service
	
	$stx = new SpeedTax();
	
	print "Create Customer:" . "\n";
	$result = $stx->CreateCustomer($Customer);

	switch ($result->CreateCustomer->resultType)
	{	
		case 'CREATED':
			print "CREATED.\n";
			break;
		case 'UPDATED':
			print "UPDATED.\n";
			break;
		case 'FAILED_WITH_ERRORS':
			print "FAILED.  Errors:\n";
			DisplayErrors($result->CreateCustomer->errors);
			break;
		default:
			print "Other result type: '" . $result->CreateCustomer->resultType . "'\n";
			break;
	}	
}
catch (Exception $e) 
{
    // in case of an error, process the fault
    if ($e instanceof WSFault) 
    {
        printf("Soap Fault: %s\n", $e->Reason);
    } 
    else 
    {
        printf("Exception Message = %s\n", $e->getMessage());
    }
}

print "Verify that the customer was created, then press ENTER to continue to update...";	
$f = fopen('php://stdin', 'r');
$line = fgets($f);
print "\n";


// ***************
// Update Customer
// ***************
try 
{
	$Customer = new customer();	
	$Customer->customerReference = $Ref; 
	$Customer->name = "PHP Test Customer Updated";
    $Customer->taxNumber = "PHP321UPDATED";
	
	// Call the web service
	
	$stx = new SpeedTax();
	
	print "Update Customer:" . "\n";
	$result = $stx->EditCustomer($Customer);

	switch ($result->EditCustomer->resultType)
	{	
		case 'CREATED':
			print "CREATED.\n";
			break;
		case 'UPDATED':
			print "UPDATED.\n";
			break;
		case 'FAILED_WITH_ERRORS':
			print "FAILED.  Errors:\n";
			DisplayErrors($result->EditCustomer->errors);
			break;
		default:
			print "Other result type: '" . $result->EditCustomer->resultType . "'\n";
			break;
	}	
}
catch (Exception $e) 
{
    // in case of an error, process the fault
    if ($e instanceof WSFault) 
    {
        printf("Soap Fault: %s\n", $e->Reason);
    } 
    else 
    {
        printf("Exception Message = %s\n", $e->getMessage());
    }
}

print "NOTE: This customer can be used for CreateEditExemption.php\n";
print "Number: $Ref\n";
?>

