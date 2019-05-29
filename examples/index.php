<?php

const API_BASE_PATH = 'https://api.mrbframework.com';
// const API_BASE_PATH = 'https://api.staging.mrbframework.com';

require_once '../src/MRBApiClient.php';

$client = new MrbApiClient(
    [
        'client_id'     => '5_5yxhp4owymsc0sgs4wsc4k4swo80oog0kks8okg0sgkssco8s4',
        'client_secret' => '5v0llq4czcgsw8gw848k8s8sgcc04cocks8w08co4sgs8ww8cs',
        'username'      => 'api',
        'password'      => 'H6HaZB',
    ]
);

$data = json_decode(
    $client->get('/web-shop/module/products/site/products/all'),
    true
);

print_r('<PRE>');
print_r($data);
