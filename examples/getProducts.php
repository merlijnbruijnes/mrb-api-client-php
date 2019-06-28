<?php

require_once dirname(__FILE__) . '/../src/MRBApiClient.php';
require_once dirname(__FILE__) . '/config.php.inc';

$data = json_decode(
    $client->get('/web-shop/module/products/site/products'),
    true
);

print_r('<PRE>');
print_r($data);
