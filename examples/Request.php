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

$testArray = $client->get('/web-shop/module/editor/site/document/all');

print_r($testArray);
