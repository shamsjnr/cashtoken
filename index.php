<?php
  session_start();
  if (isset($_SESSION['user_data'])) {
    header('location: http://localhost:3000/backend/profile.php');
    die();
  }

  if ( strstr($_SERVER['REQUEST_URI'], '/callback?') != '' ) {
    header('location: http://localhost:3000/openid.php'.str_replace('/callback', '', $_SERVER['REQUEST_URI']));
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Task 1</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div id="main">
  <p>Sign in with: &nbsp; <a href="http://localhost:3000/openid.php">CashToken</a></p>
</div>
<script>
  $btn = document.querySelector('#login');

  $btn.addEventListener('click', function(e) {
    e.preventDefault();
    
    const form = document.querySelector('#form');
    const mail = document.querySelector('#mail').value;
    const pass = document.querySelector('#pass').value;

    if (mail.trim() == '' || pass.trim() == '') return;

    fetch('http://localhost:4300/backend/process.php', {
      method: 'POST',
      body: new FormData(form)
    }).then((res) => {
      if (!res.ok) {
        throw new Error(`Request error! Status: ${res.status}`);
      }
      return res;
    }).then(res => {
      console.log(res);
    });
  });
</script>
</body>
</html>
