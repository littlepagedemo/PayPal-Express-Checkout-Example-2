<?php

session_start();
include_once("config.php");
include_once("paypal_ecfunctions.php");




/* ==================================================================
'  User confirm payment from Order Review page
'  PayPal Express Checkout Call - DoExpressCheckoutPayment()
'  ===================================================================
*/


// Check to see if Session contains a variable named 'token'
// set at order_review.php	
if (isset($_SESSION['token']))
{

	// If have shipping amount, update the Payment Amount in session
	// $_SESSION["Payment_Amount"] = $_SESSION['cart_item_total_amt'] + $shipping_amt;
	

	/*
	'-------------------------------------------------------------------------
	' The paymentAmount is the total value of the shopping cart, that was set 
	' earlier in a session variable by the shopping cart page
	'-------------------------------------------------------------------------
	*/
	
	$finalPaymentAmount =  $_SESSION["Payment_Amount"]; // has been set at cart.php
		
	/*
	'-------------------------------------------------
	' Calls the DoExpressCheckoutPayment API call
	'
	' The ConfirmPayment function is defined in the file paypal_ecfunctions.php,
	' that is included at the top of this file.
	'-------------------------------------------------
	*/

	$resArray = ConfirmPayment ( $finalPaymentAmount );
	$ack = strtoupper($resArray["ACK"]);
	if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" )
	{
	
		//Getting transaction ID from API responce. 
        $TransactionID = urldecode($resArray["PAYMENTINFO_0_TRANSACTIONID"]);
            
		//---------------------------------------
		// Save Transaction Information into DB
		//--------------------------------------- 
		SaveTransaction($resArray);
				
		
		// Clear Session
		$_SESSION = array();
		
		include("header.php");
?>
	<div id="content-container">
	
		<div id="content">		
		<h2>Success</h2>
		<BR>Payment Received! Your product will be sent to you very soon!
		<br><br> Transaction ID: <?php echo $TransactionID; ?>
		<br><br>

<?php
		
	}
	else  
	{
	
		include("header.php");
		echo '<div id="content-container"><div id="content">';
			
		//Display a user friendly Error on the page using any of the following error information returned by PayPal
		DisplayErrorMessage('DoExpressCheckoutDetails',$resArray);

	}
	
	
?>	
	
		</div>
		<!-- content -->
		
		<div id="aside">
			<h3>
				DoEC Return results:
			</h3>						
			<?php 
				
				$resData = reformat_arr($resArray); 
				echo '<p style="font-size:10px">'.$resData.'</p>';
			
			?>
		</div>

<?php

		include("footer.php");

	
 }
 
 // no token
 else {
	
		header("Location: cart.php"); // back to cart if don't have cart items 
		exit;
	
 }
	
	
			
		
?>