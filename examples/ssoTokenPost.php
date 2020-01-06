<?php

require_once dirname(__FILE__) . '/../src/MRBApiClient.php';
require_once dirname(__FILE__) . '/config.php.inc';

$postData = [
    'usernameOrEmail' => 'merlijn@getmarvia.com',
];

$data = json_decode(
    $client->post('/core/module/sso/site/token', $postData),
    true
);

print_r('<PRE>');
print_r($data);
