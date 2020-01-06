<?php

require_once dirname(__FILE__) . '/../src/MRBApiClient.php';
require_once dirname(__FILE__) . '/config.php.inc';

$postData = [
    'setShippingAddress' => true, // required true/false
    'addresses' => [
        'invoice' => [
            "customerNumber" => "", // string optional
            "company" => "", // string optional
            "firstname" => "Firstname", // string required
            "lastname" => "Lastname", // string required
            "street" => "Street", // string required
            "number" => "1 a", // string required
            "zipcode" => "1234 AB", // string required
            "mailbox" => "", // string optional
            "city" => "City", // string required
            "country" => "", // string optional
            "phone" => "0612345678", // string optional
            "email" => "invoice@email.com", // string required
            "comments" => "" // string optional
        ],
        'shipping' => [ // only checked if setDeliveryAddress is set to true
            "customerNumber" => "", // string optional
            "company" => "", // string optional
            "firstname" => "Shipping firstname", // string required if setShippingAddress is true
            "lastname" => "Shipping lastname",  // string required if setShippingAddress is true
            "street" => "Shipping street", // string required if setShippingAddress is true
            "number" => "2 a", // string required if setShippingAddress is true
            "zipcode" => "5678 CD", // string required if setShippingAddress is true
            "mailbox" => "", // string optional
            "city" => "Shipping city", // string required if setShippingAddress is true
            "country" => "", // string optional
            "phone" => "0687654321", // string optional
            "email" => "shipping@email.com", // string required if setShippingAddress is true
            "comments" => "" // string optional
        ]
    ],
    'products' => [
        [
            'id' => '8427',
            'quantity' => 1,
        ],
        [
            'id' => '8476',
            'quantity' => 8,
        ]
    ],
];

print_r($client->post('/web-shop/module/orders/site/orders', $postData));
exit();

$data = json_decode(
    $client->post('/web-shop/module/orders/site/orders', $postData),
    true
);

print_r('<PRE>');
print_r($data);
