<?php
session_start();
require '../vendor/autoload.php';
include_once '../config/database.php';

$client = new Google_Client();
$client->setClientId('117041298384-5l6trsd5va6mje8ngfs8st0q127c7n7o.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/app/public/google_callback.php');
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    try {
        // Check the code and get the token
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        // If an error occurred, display it for debugging purposes
        if (array_key_exists('error', $token)) {
            throw new InvalidArgumentException('Error fetching access token: ' . $token['error']);
        }

        $client->setAccessToken($token);

        // Get user information
        $google_service = new Google_Service_Oauth2($client);
        $google_user = $google_service->userinfo->get();

        // Checking or creating a user
        $email = $google_user->email;
        $name = $google_user->name;
        $google_id = $google_user->id;

        $query = "SELECT * FROM users WHERE google_id = '$google_id' OR email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows == 0) {
            // New user, add to database
            $query = "INSERT INTO users (name, email, google_id, role) VALUES ('$name', '$email', '$google_id', 'user')";
            $conn->query($query);
            $new_user_id = $conn->insert_id;

            // Get new user data
            $query = "SELECT * FROM users WHERE id = $new_user_id";
            $result = $conn->query($query);
        }

        // Set the user data in the session
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        // Role-based redirection
        if ($user['role'] == 'admin') {
            header('Location: ../admin/index.php');
        } else {
            header('Location: index.php');
        }
        exit;
    } catch (Exception $e) {
        // Display error for debugging
        echo 'Error: ' . $e->getMessage();
    }
} else {
    header('Location: login.php');
    exit;
}
