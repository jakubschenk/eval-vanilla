<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

Databaze::pripoj('localhost', 'eval_vanilla', 'root', '');

@session_start();

$client = new Google_Client();
$client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/client_secrets.json');
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/auth/google');

$root_uri = 'http://' . $_SERVER['HTTP_HOST'];

// if (isset($_REQUEST['logout'])) {
//   // unset($_SESSION['access_token']);
//   // $client->revokeToken();
//   // file_put_contents('log.txt', 'byl jsem tu');
//   // header('Location: ' . filter_var($root_uri, FILTER_SANITIZE_URL)); //redirect user back to page

//   header('Location:https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://localhost/');
// }

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  if ($client->getAccessToken()) {
    $_SESSION['access_token'] = $client->getAccessToken();
    $objOAuthService = new Google_Service_Oauth2($client);
    $user_data = $objOAuthService->userinfo->get();

    if($user_data) {
      $exists = Uzivatel::updateAndCheckUser($user_data);
      if($exists) {
        $_SESSION['jmeno'] = $exists->getJmeno();
        header('Location: ' . filter_var($root_uri, FILTER_SANITIZE_URL));  
      } else {
        echo("<script type='text/javascript'>alert('Nejste v databázi uživatelů!')</script>");
        unset($_SESSION['access_token']);
        $client->revokeToken();
        $error_uri = $root_uri . '/unauthorized';
        header('Location: ' . filter_var($error_uri, FILTER_SANITIZE_URL));
      }
    }
  }  
} else {
  if(isset($_REQUEST['logout'])) {
    unset($_SESSION['access_token']);
    $client->revokeToken();
    file_put_contents('log.txt', 'byl jsem tu');
    header('Location: ' . filter_var($root_uri, FILTER_SANITIZE_URL)); //redirect user back to page
  } else {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
  }
}