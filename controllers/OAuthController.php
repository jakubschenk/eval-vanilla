<?php

class OAuthController extends Controller {

  protected $title = "Autorizace";
  private $client;
  private $root_uri;

  public function __construct()
  {
    $this->client = new Google_Client(); // vytvoreni google_client objektu a konfigurace
    $this->client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/client_secrets.json');
    $this->client ->addScope(Google_Service_Oauth2::USERINFO_PROFILE); // zvoleni scopes co potrebujeme (pristupove prava basically)
    $this->client ->addScope(Google_Service_Oauth2::USERINFO_EMAIL); // staci nam email a gid, mozna avatar
    $this->client ->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/auth/callback');
    $this->root_uri = 'http://' . $_SERVER['HTTP_HOST'];  

  }

  public function redirectUserToAuth() {
    $auth_url = $this->client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
  }

  public function authUserFromRedirect() {
    if (isset($_GET['code'])) { // google presmerovava na zpatky na redirect page s kodem
      $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
      if ($this->client->getAccessToken()) { // zjistujeme jestli jsme dostali access token
        $_SESSION['access_token'] = $this->client->getAccessToken();
        $objOAuthService = new Google_Service_Oauth2($this->client); // potrebujeme vytvorit objekt oauth2 pro ziskani dat o uzivateli
        $user_data = $objOAuthService->userinfo->get();
    
        if($user_data) {
          if(Uzivatel::typChooser($user_data["email"]) == 'ucitel') {
              $exists = Ucitel::updateAndCheckUser($user_data);
              $_SESSION["druh"] = "ucitel";
          } else {
              $exists = Student::updateAndCheckUser($user_data); 
              $_SESSION["druh"] = "student";
          }
          
          if($exists) {
            $_SESSION['email'] = $exists->getEmail();
            $_SESSION["id"] = $exists::getId($exists->getEmail());
            $_SESSION["avatar"] = $exists->getObrazek();
            $file = fopen("log.txt", 'a');
            fwrite($file, date("d M Y H:i:s") . 'Prihlasen uzivatel '. $exists->getEmail());
            fclose($file);
            header('Location: ' . filter_var($this->root_uri, FILTER_SANITIZE_URL));  
          } else {
            unset($_SESSION['access_token']);
            $_SESSION = array();
            session_destroy();
            $this->client->revokeToken();
            $error_uri = $this->root_uri . '/unauthorized';
            header('Location: ' . filter_var($error_uri, FILTER_SANITIZE_URL));
          }
        }
      }
    }
  }
  
  public function logout()
    {
      unset($_SESSION['access_token']);
      $this->client->revokeToken();
      $file = fopen("log.txt", 'a');
      fwrite($file, date("d M Y H:i:s") . 'Odhlasen uzivatel '. $_SESSION['email']);
      fclose($file);
      $_SESSION = array();
      session_destroy();
      header('Location: ' . filter_var($this->root_uri, FILTER_SANITIZE_URL)); //redirect user back to page
    }
}