<?php

/********************************************	 
	Defines all the global variables 
********************************************/
	
	
$SandboxFlag = true;	// sandbox live


//'------------------------------------
//' PayPal API Credentials
//'------------------------------------
$API_UserName=""; //PayPal API Username
$API_Password=""; //Paypal API password
$API_Signature=""; //Paypal API Signature


//'------------------------------------
//' BN Code 	is only applicable for partners
$sBNCode = "PP-ECxxxxx";
	
	
//'------------------------------------	
//' API version
$version = urlencode('84.0'); // 76.0


//'------------------------------------
//' The currencyCodeType and paymentType 
//' are set to the selections made on the Integration Assistant 
//'------------------------------------
$PayPalCurrencyCode = "SGD";		// Paypal Currency Code
$paymentType = "Sale";				// or 'Sale' or 'Order' or 'Authorization'

	
$domain = 'http://'.$_SERVER['SERVER_NAME'];
//'------------------------------------
//' The returnURL is the location where buyers return to when a
//' payment has been succesfully authorized.
//'
//' This is set to the value entered on the Integration Assistant 
//'------------------------------------	
$PayPalReturnURL 		= $domain.'/paypal/ecsection2/order_review_shipping.php'; //Return URL after user sign in from Paypal

	
//'------------------------------------
//' The cancelURL is the location buyers are sent to when they hit the
//' cancel button during authorization of payment during the PayPal flow
//'
//' This is set to the value entered on the Integration Assistant 
//'------------------------------------	
$PayPalCancelURL 		= $domain.'/paypal/ecsection2/cart.php'; // Cancel URL if user clicks cancel





?>
