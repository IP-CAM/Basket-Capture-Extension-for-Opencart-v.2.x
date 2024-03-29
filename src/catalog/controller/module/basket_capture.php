<?php

class ControllerModuleBasketCapture extends Controller {

    public function index() {

        // Load the required modules
        $this->load->model('tool/image');
        $this->load->model('checkout/order');

        // Calculate the currency rate
        $this->data['basket_capture_currency_rate'] = $this->currency->convert(1, $this->currency->getCode(), $this->config->get('config_currency'));
        $this->data['basket_capture_api_key'] = $this->config->get('basket_capture_api_key');

        // Set an order ID if there is one in the session
        // For this to work, ensure you have edited the checkout/success controller as per the readme
        if (!isset($this->session->data['basket_capture_order_id'])) {
            $order = false;
        } elseif ($this->model_checkout_order->getOrder($this->session->data['basket_capture_order_id'])) {
            $order = $this->model_checkout_order->getOrder($this->session->data['basket_capture_order_id']);
        } else {
            $order = false;
        }

        // Fetch the data to send to the BasketCapture API
        if ($order) {
            $this->data['basket_capture_order_id'] = $order['order_id'];
            $this->data['basket_capture_total'] = $order['total'] / $this->data['basket_capture_currency_rate'];
            $this->data['basket_capture_email'] = $order['email'];
            $this->data['basket_capture_firstname'] = $order['firstname'];
            $this->data['basket_capture_lastname'] = $order['lastname'];

            // Fetch the order products
            $this->data['basket_capture_products'] = array();
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order['order_id'] . "'");
            foreach ($query->rows as $product) {
                $product_data = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . (int) $product['product_id'] . "'")->row;
                $this->data['basket_capture_products'][] = array(
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'image' => $product_data ? $this->model_tool_image->resize($product_data['image'], 100, 100) : null,
                    'price' => $product['price'] / $this->data['basket_capture_currency_rate'],
                    'quantity' => $product['quantity'],
                );
            }

            unset($this->session->data['basket_capture_order_id']);

        } else {
            $this->data['basket_capture_order_id'] = false;
            $this->data['basket_capture_total'] = $this->cart->getTotal() / $this->data['basket_capture_currency_rate'];
            $this->data['basket_capture_email'] = isset($this->session->data['guest']['email']) ? $this->session->data['guest']['email'] : $this->customer->getEmail();
            $this->data['basket_capture_firstname'] = isset($this->session->data['guest']['firstname']) ? $this->session->data['guest']['firstname'] : $this->customer->getFirstname();
            $this->data['basket_capture_lastname'] = isset($this->session->data['guest']['lastname']) ? $this->session->data['guest']['lastname'] : $this->customer->getLastname();

            // Fetch the basket products
            $this->data['basket_capture_products'] = array();
            foreach ($this->cart->getProducts() as $product) {
                $this->data['basket_capture_products'][] = array(
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'image' => $this->model_tool_image->resize($product['image'], 100, 100),
                    'price' => $product['price'] / $this->data['basket_capture_currency_rate'],
                    'quantity' => $product['quantity'],
                );
            }

        }

        // Set the template file
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/basket_capture.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/basket_capture.tpl';
        } else {
            $this->template = 'default/template/module/basket_capture.tpl';
        }

        // Render it
        $this->render();

    }
}
