<?php

session_start();
include_once("config.php");
include_once("paypal_ecfunctions.php");


/* ==================================================================
'  Order Review Page
'
'  User come back from Paypal site after login - return URL
'  PayPal Express Checkout Call - GetExpressCheckoutDetails()
   ===================================================================
*/

// Check to see if the Request object contains a variable named 'token'	
$token = "";
if (isset($_REQUEST['token']))
{
	$token = $_REQUEST['token'];
	$_SESSION['token'] = $token;	// save in session for DoExpressCheckoutPayment()
}



// If the Request object contains the variable 'token' then it means that the user is coming from PayPal site.	
if ( $token != "" )
{

	/*
	'-------------------------------------------------
	' Calls the GetExpressCheckoutDetails API call
	'
	' The GetShippingDetails function is defined in paypal_ecfunctions.php
	' included at the top of this file.
	'-------------------------------------------------
	*/	

	$resArray = GetShippingDetails( $token );
	$ack = strtoupper($resArray["ACK"]);
	if( $ack == "SUCCESS" || $ack == "SUCESSWITHWARNING") 
	{
			
		
		//---------------------------------------
		// Save user's shipping address into DB
		//--------------------------------------- 
		//SaveShipping_addr($resArray);
				
		
		include("header.php");

		// Check have existing product
		if ($_SESSION['cart_item_arr']) 
		{
			$cart_item_arr = $_SESSION['cart_item_arr'];	
			$cart_no = count($cart_item_arr);
		}
		else { 
			$cart_item_arr[] = array();
			$cart_no=0;
		}
		
				
		// Display Shipping Method Options here 		
		// Display confirm page	
?>
	<div id="content-container">
		
		<div id="content">
			<h2>Review Your Shopping Cart</h2>


<!-- get shipping address from DB -->
<?php 
	// Display Shipping address
	// get_shipping_addr($email);
	
?>
			<br>Shipping Address: (From PayPal GetEc)
			
			<table border='1'>
			<tr><td>Name:</td>
				<td><?php echo $resArray["PAYMENTREQUEST_0_SHIPTONAME"]; ?></td>
			</tr>
			<tr><td>Street:</td>
				<td><?php echo $resArray["PAYMENTREQUEST_0_SHIPTOSTREET"]; ?></td>
			</tr>
			<tr><td>Street 2:</td>
				<td><?php echo $resArray["PAYMENTREQUEST_0_SHIPTOSTREET2"]; ?></td>
			</tr>
			<tr><td>City:</td>
				<td><?php echo $resArray["PAYMENTREQUEST_0_SHIPTOCITY"]; ?></td>
			</tr>
			<tr><td>State:</td>
				<td><?php echo $resArray["PAYMENTREQUEST_0_SHIPTOSTATE"]; ?></td>
			</tr>
			<tr><td>Country Code:</td>
				<td><?php echo $resArray["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"]; ?></td>
			</tr>
			<tr><td>Zip:</td>
				<td><?php echo $resArray["PAYMENTREQUEST_0_SHIPTOZIP"]; ?></td>
			</tr>
			<tr><td>Phone:</td>
				<td><?php echo $resArray["PAYMENTREQUEST_0_SHIPTOPHONENUM"]; ?></td>
			</tr>																					
			</table>

			<div class="carttitle">
				<div class="col1">Product</div>
				<div class="col2">Item Price <?php echo $PayPalCurrencyCode; ?> </div>
				<div class="col3">Item Qty</div>
				<div class="col4">Item Amt <?php echo $PayPalCurrencyCode; ?> </div>
			</div>	
			
<?php 

	//-----------------------
	// Display shopping cart
	//-----------------------
	if($cart_no) // have cart
	{	
		foreach ($cart_item_arr as $c) 
		{
			//print_r($c);
?>					
			<div class="cartrow">
				<div class="col1"><?php echo $c[0];?> (<?php echo $c[2]; ?>)
					<br><?php echo $c[1]; ?>
				</div>
				<div class="col2"> $<?php echo $c[3]; ?></div>
				<div class="col3" style="text-align:center"><?php echo $c[4]; ?></div>
				<div class="col4">$<?php echo $c[5]; ?></div>
			</div>								
<?php
		} // foreach



		//--------------------------------
		// Shopping Cart Item Total Amount
?>			
				<div id="subtotalamt">
					<div class="colspan">&nbsp;</div>
					<div class="col3">Items Total:</div> 
					<div class="col4">$<?php echo number_format($_SESSION['cart_item_total_amt'],2);?></div>
				</div>
				
<?php 
		//---------------------------
		// Show Shipping Amount 
		//===========================
		if($shipping_amt) {		
?>				
				<div id="shippingamt">
					<div class="colspan">&nbsp;</div>
					<div class="col3">Shipping:</div> 
					<div class="col4">$<?php echo $shipping_amt; ?></div>
				</div>
<?php 
		} 
	
		//---------------------------
		// Show Tax Amount
		//===========================
		if($tax_amt) { 
?>				
				<div id="tax_amt">
					<div class="colspan">&nbsp;</div>
					<div class="col3">Tax:</div> 
					<div class="col4">$<?php echo $tax_amt; ?></div>
				</div>
<?php 	} ?>				
				<div id="totalamt">
					<div class="colspan">&nbsp;</div>
					<div class="col3">Total Amount:</div> 
					<div class="col4"><b>$<?php echo number_format($_SESSION["Payment_Amount"],2); ?></b></div>
				</div>			

			
			<form action='order_review.php' METHOD='POST'>
		
				<!-- submit shipping address and amount -->
		
				<br><br>
				Select Shipping methods:		
				
				<table border='1' width='300px'>
				<tr><td>Method 1:</td>
					<td><input type="radio" name="SHIPPING_AMT" value="10.00">$10.00</td>
				</tr>
				<tr><td>Method 2:</td>
					<td><input type="radio" name="SHIPPING_AMT" value="20.00">$20.00</td>
				</tr>
				<tr><td>Method 3:</td>
					<td><input type="radio" name="SHIPPING_AMT" value="30.00">$30.00</td>
				</tr>								
				</table>
						
				<br><br>
				
				Confirm your payment:
				
				
				<input type="hidden" name="SHIPTONAME" value="<?php echo $resArray["PAYMENTREQUEST_0_SHIPTONAME"]; ?>">
				<input type="hidden" name="SHIPTOSTREET" value="<?php echo $resArray["PAYMENTREQUEST_0_SHIPTOSTREET"]; ?>">
				<input type="hidden" name="SHIPTOSTREET2" value="<?php echo $resArray["PAYMENTREQUEST_0_SHIPTOSTREET2"]; ?>">
				<input type="hidden" name="SHIPTOCITY" value="<?php echo $resArray["PAYMENTREQUEST_0_SHIPTOCITY"]; ?>">
				<input type="hidden" name="SHIPTOSTATE" value="<?php echo $resArray["PAYMENTREQUEST_0_SHIPTOSTATE"]; ?>">
				<input type="hidden" name="SHIPTOCOUNTRYCODE" value="<?php echo $resArray["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"]; ?>">
				<input type="hidden" name="SHIPTOZIP" value="<?php echo $resArray["PAYMENTREQUEST_0_SHIPTOZIP"]; ?>">
				<input type="hidden" name="SHIPTOPHONENUM" value="<?php echo $resArray["PAYMENTREQUEST_0_SHIPTOPHONENUM"]; ?>">

										
				<br><input type="submit" class='chkbtn' name="continue" value="Continue">
			</form>		
		
		
<?php		
		} // hv cart items
		
	} 
	else  
	{
		//Display a user friendly Error on the page using any of the following error information returned by PayPal
		DisplayErrorMessage('GetExpressCheckoutDetails',$resArray, $token);
	}


?>	
	
		</div>
		<!-- content -->
		
		
		<div id="aside">
			<h3>Calculate the shipping after get shipping details</h3>
			Update shipping methods at config.php or insert function at paypal_ecfunctions.php
			
			<br><br><h3>
				GetEC Return results:
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
	
		header("Location: index.php"); // back to cart if don't have cart items 
		exit;

 }
	




?>
