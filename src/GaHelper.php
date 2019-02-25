<?php
// Read more about all params with the documentation below
// https://developers.google.com/analytics/devguides/collection/protocol/v1/parameters#ti

class GAHelper {
    private $base = 'https://www.google-analytics.com/collect';
    private $id;
    private $client_id;
    private $params = [
        'v' => 1
    ];

    public function __construct($ga_id , $url = null){
        $this->id = $ga_id;
        $client_id = $this->getCurrentVisitorId();

        $this->params['tid'] = $this->id;
        $this->params['cid'] = $client_id;
        $this->params['dl'] = $url;
        $this->client_id = $client_id;
    }
    public function pageview($dp){
        $params = $this->params;
        $params['dp'] = $dp;
        $params['t'] = 'pageview';

        return $this->send($params);
    }
    public function event($ec, $ea, $el){
        $params = $this->params;
        $params['t'] = 'event';
        $params['ec'] = $ec;
        $params['ea'] = $ea;
        $params['el'] = $el;

        return $this->send($params);
    }
    /**
     * String $order_id: The order id for the given transaction
     * Float $order_revenue: Specifies the total revenue associated with the transaction. This value should include any shipping or tax costs.
     * Float $order_tax: Specifies the tax.
     * Array $products: Array of products for the transaction
     *      String $products['name']: Name of the product
     *      Float $products['price']: Price of the product with tax and discount
     *      Integer $products['quantity']: Quantity of product
     *      String $products['category']: Category of the product
    */
    public function transaction($order_id, $order_revenue, $order_tax, $products = []){
        $params = $this->params;
        $params['t'] = 'transaction';
        $params['ti'] = $order_id;
        $params['tr'] = $order_revenue;
        $params['tt'] = $order_tax;

        $status = $this->send($params);
        if ($status){
            if (count($products) > 0){
                foreach ($products as $product){
                    $this->transaction_product($order_id, $product['name'], $product['price'], $product['quantity'], $product['category']);
                }
            }
            return true;
        }
        return false;
    }

    public function transaction_product($order_id, $item_name, $item_price, $item_qty = '1', $item_category = ''){
        $params = $this->params;
        $params['t'] = 'item';
        $params['ti'] = $order_id;
        $params['in'] = $item_name;
        $params['ip'] = $item_price;
        $params['iq'] = $item_qty;
        $params['iv'] = $item_category;

        return $this->send($params);
    }

    /**
     * Void which will send a HTTP POST request
     *
     * POST /collect HTTP/2
     * Host: www.google-analytics.com
     * Accept: *
     * Content-Type: application/x-www-form-urlencoded
     */
    private function send($params = []){
        $ch = curl_init();
        $fields_string = '';

        foreach ($params as $key => $value) {
            $fields_string .= $key.'='.$value.'&';
        }
        $fields_string = rtrim($fields_string,'&');

        curl_setopt($ch, CURLOPT_URL, $this->base);
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result ? true : false;
    }

    /**
     * Get cookie "_ga" and extract the visitor id
     * This cookie is generated by Google Analytics JS code
     */
    private function getCurrentVisitorId() {
        $_ga = isset($_COOKIE['_ga']) ? $_COOKIE['_ga'] : null;
        if ($_ga) {
            $arr = explode('.', $_ga);
            return $arr[2] . '.' . $arr[3];
        }
        return null;
    }
}
