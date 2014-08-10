<?php

if (isset($this->session->data['order_id'])) {
	$this->session->data['basket_capture_order_id'] = $this->session->data['order_id'];
}