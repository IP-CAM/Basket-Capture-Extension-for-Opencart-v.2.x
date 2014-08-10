<?php /* Basket Capture Starts */ ?>
<script src="//basketcapture.com/assets/js/tracker.js" rel="text/javascript"></script>
<script type="text/javascript">

  // Set the basket data
  basketCapture.setBasketData({
    'api_key': '<?php echo $basket_capture_api_key; ?>', // Mandatory: Your API Key
    'email': '<?php echo $basket_capture_email; ?>', // Optional: Customer's email address
    'firstname': '<?php echo $basket_capture_firstname; ?>', // Optional: Customer's first name
    'lastname': '<?php echo $basket_capture_firstname; ?>', // Optional: Customer's last name
    'total': '<?php echo $basket_capture_total; ?>', // Optional: Total basket value
    'currency_rate': '<?php echo $basket_capture_currency_rate; ?>' // Optional: Currency rate
  });

  // Run "addBasketItem()" for each individual item in the basket
  <?php foreach ($basket_capture_products as $product): ?>
    basketCapture.addBasketItem({
      "sku": "<?php echo $product['model']; ?>", // Mandatory: SKU/code
      "name": "<?php echo $product['name']; ?>", // Mandatory: Item mame
      "price": "<?php echo $product['price']; ?>", // Mandatory: Unit price
      "quantity": "<?php echo $product['quantity']; ?>", // Mandatory: Quantity
      "image": "<?php echo $product['image']; ?>" // Optional: Item image
    });
  <?php endforeach; ?>

  <?php if ($basket_capture_order_id): ?>
    // Mark the order as complete
    basketCapture.setBasketData({
      'order_id': '<?php echo $basket_capture_order_id; ?>' // Mandatory: Your order ID for the transaction
    });
  <?php endif; ?>

  // Send the basket information to Basket Capture
  basketCapture.push();

  // Listen to these inputs to detect un-submitted details
  basketCapture.addListeners({
    'firstname': 'input[name=firstname], #firstname',
    'lastname': 'input[name=lastname], #lastname',
    'email': 'input[name=email], input[type=email], #payment_address_email, #email'
  });

</script>
<?php /* Basket Capture Ends */ ?>
