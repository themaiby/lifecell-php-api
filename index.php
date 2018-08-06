<?php


require 'vendor/autoload.php';

$client = new \LifecellApi\BaseClient();

var_dump($client->request('signIn', [
    'msisdn' => '380931110201',
    'superPassword' => 241178
]));