<?php
session_start();
$clientID = 'wprQYMZBqqx-dgszFUfQG'; 
$tokenURL = 'https://id-sandbox.cashtoken.africa/oauth/token';
$authURL  = 'https://id-sandbox.cashtoken.africa/oauth/authorize';

if (isset($_GET['logout'])) {
  session_destroy();
  session_unset();
  unset($_SESSION);
  header('Location: http://localhost:3000');
  
  die();
}

if(isset($_GET['code'])) {
  if(!isset($_GET['state']) || ($_SESSION['state'] != $_GET['state'])) {
    header('Location: http://localhost:3000?error=state_not_found');
    die();
  }
 
  $ch = curl_init($tokenURL);
  $fields = [
    'grant_type'    => 'authorization_code',
    'client_id'     => $clientID,
    'code_verifier' => $_SESSION['verifier'],
    'redirect_uri'  => 'http://localhost:3000/callback',
    'code'          => $_GET['code']
  ];
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
  $rx = curl_exec($ch);
  $data = json_decode($rx, true);
  curl_close($ch);
  $jwt = explode('.', $data['id_token']);
  $res = json_decode(base64_decode($jwt[1]), true);
 
  $_SESSION['user_data'] = json_encode([
    'user_id'      => $res['sub'],
    'email'        => $res['email'],
    'type'         => $res['type'],
    'display_name' => $res['display_name'],
    'first_name'   => $res['first_name'],
    'last_name'    => $res['last_name'],
    'username'     => $res['username']
  ]);
 
  $_SESSION['access_token'] = $data['access_token'];
  $_SESSION['id_token'] = $data['id_token'];
 
  header('Location: http://localhost:3000/backend/profile.php');
  die();
}

if(!isset($_GET['action'])) {
 
  $_SESSION['state']    = bin2hex(random_bytes(16));

  $verifier_bytes = random_bytes(64);
  $code_verifier = rtrim(strtr(base64_encode($verifier_bytes), "+/", "-_"), "=");
  $_SESSION['verifier'] = $code_verifier;
  $challenge_bytes = hash("sha256", $code_verifier, true);
  $code_challenge = rtrim(strtr(base64_encode($challenge_bytes), "+/", "-_"), "=");

  $params = array(
    'response_type'  => 'code',
    'client_id'      => $clientID,
    'code_challenge' => $code_challenge,
    'code_challenge_method' => 'S256',
    'redirect_uri'   => 'http://localhost:3000/callback',
    'scope'          => 'openid email profile',
    'state'          => $_SESSION['state']
  );
 
  header('Location: '.$authURL.'?'.http_build_query($params));
  die();
}
?>
