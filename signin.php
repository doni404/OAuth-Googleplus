<?php

require_once __DIR__.'/vendor/autoload.php';

const CLIENT_ID = '869398103649-o852k3jbh4ft6ld860b54528nbb3knc9.apps.googleusercontent.com';
const CLIENT_SECRET = 'g4O5fTToqWtBM5mcKABGHg4Y';
const APPLICATION_NAME = "Buang Disini";

session_start();

//Initialization
$client = new Google_Client();
$client->setApplicationName(APPLICATION_NAME);
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);
$client->setRedirectUri('http://localhost/gplus/signin.php');
$client->setScopes('email');

$plus = new Google_Service_Plus($client);

if (isset($_REQUEST['logout'])) {
    session_unset();
}

if (isset($_GET['code'])) {
    // die(var_dump($_GET['code']));
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    $redirect = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    header('Location :'.filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    // die(var_dump($_SESSION['access_token']));
    $client->setAccessToken($_SESSION['access_token']);
    $me = $plus->people->get('me');

    $id = $me['id'];
    $name = $me['displayName'];

    echo "<pre>";
    print_r($me);
}else {
    $authUrl = $client->createAuthUrl();

    echo "<a href='" . $authUrl . "'> Login with google </a>";
    echo "<a href='?logout'> Logout </a>";
}

?>
