<?php

session_start();
include_once("config.php");
include_once("paypal_ecfunctions.php");


/* ==================================================================
'  Shipping Order ReviewPage
'
'  User is member and has select shipping method
   ===================================================================
*/

// get shipping method and amount

//Post Data received from product list page.
if($_POST) 
{

	// get submitted shipping address and amount
	$shipToName = $_POST["SHIPTONAME"]; 
	$shipToStreet = $_POST["SHIPTOSTREET"]; 
	$shipToStreet2 = $_POST["SHIPTOSTREET2"]; 
	$shipToCity = $_POST["SHIPTOCITY"]; 
	$shipToState = $_POST["SHIPTOSTATE"]; 
	$shipToCountryCode = $_POST["SHIPTOCOUNTRYCODE"]; 
	$shipToZip = $_POST["SHIPTOZIP"]; 
	$phoneNum = $_POST["SHIPTOPHONENUM"]; 

	$shipping_amt = $_POST["SHIPPING_AMT"];
	
	// update payment amount 
	$_SESSION["Payment_Amount"] =  $_SESSION['cart_item_total_amt'] + $shipping_amt;
}


if ($_SESSION['cart_item_arr']) 
{			
			
	include("header.php");

	$cart_item_arr = $_SESSION['cart_item_arr'];	
	$cart_no = count($cart_item_arr);
		
				
	// Display Shipping Method Options here 		
	// Display confirm page	
?>
	<div id="content-container">
		
		<div id="content">
			<h2>Order Review</h2>
	
			<br>Shipping Address:
			<table border='1'>
			<tr><td>Name:</td>
				<td><?php echo $shipToName; ?></td>
			</tr>
			<tr><td>Street:</td>
				<td><?php echo $shipToStreet; ?></td>
			</tr>
			<tr><td>Street 2:</td>
				<td><?php echo $shipToStreet2; ?></td>
			</tr>
			<tr><td>City:</td>
				<td><?php echo $shipToCity; ?></td>
			</tr>
			<tr><td>State:</td>
				<td><?php echo $shipToState; ?></td>
			</tr>
			<tr><td>Country Code:</td>
				<td><?php echo $shipToCountryCode; ?></td>
			</tr>
			<tr><td>Zip:</td>
				<td><?php echo $SHIPTOZIP; ?></td>
			</tr>
			<tr><td>Phone:</td>
				<td><?php echo $phoneNum; ?></td>
			</tr>																					
			</table>


			<br><div class="carttitle">
				<div class="col1">Product</div>
				<div class="col2">Item Price <?php echo $PayPalCurrencyCode; ?> </div>
				<div class="col3">Item Qty</div>
				<div class="col4">Item Amt <?php echo $PayPalCurrencyCode; ?> </div>
			</div>	
			
<?php 

	//-----------------------
	// Display shopping cart
	//-----------------------	
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

			
			<form action='confirm_payment.php' METHOD='POST'>
		
				<!-- submit shipping address and amount -->			
				<br><br>
				Confirm Payment
				<input type="hidden" name="PaymentOption" value="paypal">								
				<br><input type="submit" class='chkbtn' name="confirm" value="Confirm">

			</form>		
		
			
	
		</div>
		<!-- content -->
		
		
		<div id="aside">
			<h3>
				Review Order and confirm.
			</h3>						

			
			
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
