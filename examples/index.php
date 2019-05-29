<?php

const API_BASE_PATH = 'https://api.mrbframework.com';
// const API_BASE_PATH = 'https://api.staging.mrbframework.com';

require_once '../src/MRBApiClient.php';

$client = new MrbApiClient(
    [
        'client_id'     => '',
        'client_secret' => '',
        'username'      => '',
        'password'      => '',
    ]
);

$data = json_decode(
    $client->get('/web-shop/module/products/site/products/all'),
    true
);

print_r('<PRE>');
print_r($data);
print_r('</PRE>');
