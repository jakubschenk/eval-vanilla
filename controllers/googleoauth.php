<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

// vytvoreni google_client objektu a konfigurace
$client = new Google_Client();
$client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/client_secrets.json');
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/auth/google');

$root_uri = 'http://' . $_SERVER['HTTP_HOST'];

if (isset($_GET['code'])) { // google presmerovava na zpatky na redirect page s kodem
  $client->authenticate($_GET['code']); //nevim co dela tahle funkce
  if ($client->getAccessToken()) { // zjistujeme jestli jsme dostali access token
    $_SESSION['access_token'] = $client->getAccessToken();
    $objOAuthService = new Google_Service_Oauth2($client); // potrebujeme vytvorit objekt oauth2 pro ziskani dat o uzivateli
    $user_data = $objOAuthService->userinfo->get();

    if($user_data) {
      // volani funkce pro vytvoreni objektu uzivatele podle tabulky importu
      // kdyz uzivatel existuje, funkce vraci objekt s jeho daty, jinak nevraci nic,
      // takto muzeme zjistit jestli je uzivatel v tabulce uzivatele_import
      $exists = Uzivatel::updateAndCheckUser($user_data); 
      if($exists) {
        $_SESSION['jmeno'] = $exists->getJmeno();
        file_put_contents('log.txt', time() . ' : logged IN user' . $_SESSION['jmeno']);
        header('Location: ' . filter_var($root_uri, FILTER_SANITIZE_URL));  
      } else {
        // redirect je tak rychly ze ten alert nema sanci lmao
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
    file_put_contents('log.txt', time() . ' : logged OUT user' . $_SESSION['jmeno']);
    $_SESSION = array();
    session_destroy();
    header('Location: ' . filter_var($root_uri, FILTER_SANITIZE_URL)); //redirect user back to page
  } else {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
  }
}

// tohle cele je zbytecne kurva slozite ale google_client je extremne random