<?php
include_once 'SpeedTaxApi.php';
include_once 'SpeedTaxUtil.php';

try {

	// PURPOSE
	// This example will first calculate and display tax, then post the invoice (simulating a checkout)
	// using one of two methods described below.
	// CALCULATE TAX:
	// Call CalculateInvoice() to determine tax for display only.  Pass the invoice number if known.
	// CHECKING OUT:
	// a. Used Invoice Number in CalculateInvoice():
	//    Call PostInvoices() (with an 's') instead when ready to check out / complete order, passing 
	//    just the invoice number previously used for CalculateInvoice.
	// b. Did NOT Use Invoice Number in CalculateInvoice():
	//    Call PostInvoice() (no 's'), passing the entire invoice including invoice number
	// In this example, $calcWithInvoiceNr determines if invoice number known at time of tax calculation.
	
	$calcWithInvoiceNr = false;
	
	// Setup an invoice object
	
	$Invoice = new invoice();	
	$Invoice->customerIdentifier = "PHP Example Customer"; // E.g. customer name, customer ID.  For reference only.	
	
	$t = explode(' ',microtime());
	$InvoiceNr = "INV" . $t[1];		
	
	if ($calcWithInvoiceNr)
	{
		$Invoice->invoiceNumber = $InvoiceNr; // This is your invoice number for this purchase.  Leave this out for a straight query.
	}
	
	$Invoice->invoiceDate = "2010-06-01";
	$Invoice->invoiceType = INVOICE_TYPES::INVOICE;
	
	$Price = new price();
	$Price->decimalValue = 100.00;
	
	// Setup the first line item
	
	$LineItem = new lineItem();	
	$LineItem->lineItemNumber = 1;
	$LineItem->productCode = "GENERAL";
	$LineItem->customReference = "My Custom Reference Info";
	$LineItem->quantity = 1;
	$LineItem->salesAmount = $Price;
	
	$ShipFromAddress = new address();	
	$ShipFromAddress->address1 = '23297 South Pointe Dr.';
	$ShipFromAddress->address2 = 'Laguna Hills, CA 92653';
	
	$LineItem->shipFromAddress = $ShipFromAddress;	
    
	$ShipToAddress = new address();	
	$ShipToAddress->address1 = '2401 Utah Avenue South';
	$ShipToAddress->address2 = 'Seattle, WA 98134-1421';
	
	$LineItem->shipToAddress = $ShipToAddress;

	$Invoice->lineItems[0] = $LineItem;

	// Setup a second line item with a different ship-to address
	
	$ShipToAddress2 = new address();	
	$ShipToAddress2->address1 = '202 C St';
	$ShipToAddress2->address2 = 'San Diego, CA 92101';
	
	$LineItem2 = new lineItem();
	
	$LineItem2->lineItemNumber = 2;
	$LineItem2->productCode = "GENERAL";
	$LineItem2->customReference = "Second line item";
	$LineItem2->quantity = 1;
	$LineItem2->salesAmount = $Price;
	$LineItem2->shipFromAddress = $ShipFromAddress;
	$LineItem2->shipToAddress = $ShipToAddress2;

	$Invoice->lineItems[1] = $LineItem2;
	
	// Call the web service
	
	$stx = new SpeedTax();
	
	DisplayInvoice($Invoice);
	
	print "CalculateInvoice:" . "\n";
	$result = $stx->CalculateInvoice($Invoice);

	switch ($result->CalculateInvoiceResult->resultType)
	{	
		case 'SUCCESS':
			print "SUCCESS.\n";
			DisplayInvoiceResult($result->CalculateInvoiceResult);
			break;
		case 'FAILED_WITH_ERRORS':
			print "FAILED.  Errors:\n";
			DisplayErrors($result->CalculateInvoiceResult->errors);
			break;
		case 'FAILED_INVOICE_NUMBER':
			print "FAILED. The invoice number is incorrectly formatted.\n";
			break;
		default:
			print "Other result type: '" . $result->CalculateInvoiceResult->resultType . "'\n";
			break;
	}	

	print "Press ENTER to continue to post...";	
	$f = fopen('php://stdin', 'r');
	$line = fgets($f);
	print "\n";

	if ($calcWithInvoiceNr)
	{	
		print "PostInvoices:" . "\n";
		$invoiceNumbers[0] = $InvoiceNr;
		$result = $stx->PostInvoices($invoiceNumbers);
		
		switch ($result->PostBatchInvoicesResult->resultType)
		{	
			case 'SUCCESS':
				print "SUCCESS.\n";
				DisplayInvoiceResult($result->PostBatchInvoicesResult);
				break;
			case 'FAILED_WITH_ERRORS':
				print "FAILED.  Errors:\n";
				DisplayErrors($result->PostBatchInvoicesResult->errors);
				break;
			case 'FAILED_INVOICE_NUMBER':
				print "FAILED. The invoice number is incorrectly formatted.\n";
				break;
		}	
	}
	else
	{
		print "PostInvoice:" . "\n";
		$Invoice->invoiceNumber = $InvoiceNr; // Set the Invoice Number since it was not set before.
		$result = $stx->PostInvoice($Invoice);
		/* PHP does not seem to return resultType.  However, if there is a problem, the SpeedTax API will throw a PHP Exception.
		switch ($result->PostInvoiceResult->resultType)
		{	
			case 'SUCCESS':
				print "SUCCESS.\n";
				DisplayInvoiceResult($result->PostInvoiceResult);
				break;
			case 'FAILED_WITH_ERRORS':
				print "FAILED.  Errors:\n";
				DisplayErrors($result->PostInvoiceResult->errors);
				break;
			case 'FAILED_INVOICE_NUMBER':
				print "FAILED. The invoice number is incorrectly formatted.\n";
				break;
		}	
		*/
		print "SUCCESS.\n";
		DisplayInvoiceResult($result->PostInvoiceResult);
	}
        
    // Uncomment the following line to void this invoice        
    //VoidThisInvoice($stx, $InvoiceNr);

} catch (Exception $e) {
    // in case of an error, process the fault
    if ($e instanceof WSFault) {
        printf("Soap Fault: %s\n", $e->Reason);
    } else {
        printf("Exception Message = %s\n", $e->getMessage());
    }
}

function VoidThisInvoice($stx, $InvoiceNr)
{
	print "Press ENTER to continue to void...";	
	$f = fopen('php://stdin', 'r');
	$line = fgets($f);
	print "\n";
    
    $stx->VoidInvoice($InvoiceNr);
}

?>

