<?php
require '../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('Client id');
$client->setClientSecret('Client secret');
$client->setRedirectUri('http://localhost/app/public/google_callback.php');
$client->addScope('email');
$client->addScope('profile');

$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
