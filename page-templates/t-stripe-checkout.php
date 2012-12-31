<?php
	/*
	 * Template Name: Stripe Payment
	 * Sets up the stripe payment form and allows people to checkout
	 */

/* Set up the stripe stuff */

require_once(get_stylesheet_directory() . '/lib/stripe-api/lib/Stripe.php');
Stripe::setApiKey("sk_test_r6QZviM3KMu9ZN3h9koEimq3");

function tl_stripe_scripts(){
?>

<script type="text/javascript" src="https://js.stripe.com/v1/"></script>
<script type="text/javascript">
    // this identifies your website in the createToken call below
    Stripe.setPublishableKey('pk_test_ddiONbSPNKbxCKMCsEJGsDW2');
</script>

<?php

}

function tl_checkout_form(){

// Display the plans

	$plans = Stripe_Plan::all();
	
	echo '<pre>Plans: <br/>';
	var_dump($plans);
	echo '</pre>';
	
	$form_content = <<<EOF

<form action="" method="POST" id="payment-form">
  <div class="form-row">
    <label>Card Number</label>
    <input type="text" size="20" autocomplete="off" class="card-number"/>
  </div>
  <div class="form-row">
    <label>CVC</label>
    <input type="text" size="4" autocomplete="off" class="card-cvc"/>
  </div>
  <div class="form-row">
    <label>Expiration (MM/YYYY)</label>
    <input type="text" size="2" class="card-expiry-month"/>
    <span> / </span>
    <input type="text" size="4" class="card-expiry-year"/>
  </div>
  <button type="submit" class="submit-button">Submit Payment</button>
</form>
EOF;

	echo $form_content;

	echo '<hr><pre>POST: <br/>';
	var_dump($_POST);
	echo '</pre>';
	
	if (isset($_POST['stripeToken'])){
		// we've gotten a valid credit card...
		
		$token = $_POST['stripeToken'];
		
		$customer = Stripe_Customer::create(array(
  		"description" => "Test Subscripter",
  		"email" => 'anca-test1@anca.tv',
  		"card" => $token,
  		'plan' => 'ME-01'
		));
		
	echo '<hr><pre>Customer: <br/>';
	var_dump($customer);
	echo '</pre>';		
		
	}
	

}

/* ---------------------------  Page Rendering ---------------------------*/

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_action('wp_head', 'tl_stripe_scripts');

remove_action('genesis_loop', 'genesis_do_loop');
add_action( 'genesis_loop', 'tl_checkout_form' );

genesis();