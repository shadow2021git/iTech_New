<?php
require_once __DIR__ . '/../vendor/autoload.php';

$client = new Google\Client();
$client->setClientId("998353832509-a0beu0npiv3g5earkg0i7ul6smjqvkdb.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/rsms/admin/googleLoginCallback.php");
$client->addScope("email");
$client->addScope("profile");

?>