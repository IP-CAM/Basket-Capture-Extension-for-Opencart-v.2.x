# Basket Capture OpenCart Extension

An extension for OpenCart to automatically re-engage customers who abandon their cart on your website. A [Basket Caputre](http://basketcapture.com) account is required to use this extension.

---
## Basket Capture

Basket Capture is a cart abandonment email remarketing platform for ecommerce websites. When a customer abandons their basket on your website, they will be automatically sent a personalised email enticing them to return and complete their order. For more information visit http://basketcapture.com


----
## Installation

1. Copy the full contents of the `src` folder to your website's root directory
2. If you do not have OpenCart VQMod installed: Edit the file `catalog/controller/checkout/success.php`; Find the line `public function index() {` and on the next line insert `require(DIR_APPLICATION . 'controller/module/basket_capture_checkout_success_include.php');`. If you do have VQMod installed then skip this step as it will be done automatically.
3. In your OpenCart admin, navigate to Extensions > Modules. Find 'Basket Capture' in the list and click 'Install'
4. Click 'Edit' on the Basket Capture module and enter your API Key

---
## Campaign Setup
Visit http://basketcapture.com and login to configure your campaign
