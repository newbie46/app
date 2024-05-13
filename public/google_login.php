<?php
require '../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('117041298384-5l6trsd5va6mje8ngfs8st0q127c7n7o.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/app/public/google_callback.php');
$client->addScope('email');
$client->addScope('profile');

$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
