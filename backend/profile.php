<?php 
session_start();

if (isset($_SESSION['active']) && (time() - $_SESSION['active'] > 600)) {
  header('location: http://localhost:3000/openid.php?logout');
  die();
}
$_SESSION['active'] = time();

if (!isset($_SESSION['user_data'])) {
  header('location: http://localhost:3000');
  die();
} 
$data = json_decode($_SESSION['user_data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    div.flexer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 3rem;
    }
    div.flexer a {margin: 0;}
    table {
      border: 1px solid #444;
      border-collapse: collapse;
      margin: auto;
    }
    td {
      padding: 12px;
      border-bottom: 1px solid #444;
    }
    tr:last-child td {
      border: none;
    }
  </style>
</head>
<body>
  <div id="main">
    <div>
      <div class="flexer">
        <h4>Welcome <?= $data->username; ?>,</h4>
        <a href="http://localhost:3000/openid.php?logout">Sign out</a>
      </div>
      <table>
        <tbody>
          <tr>
            <td colspan="2" style="text-align: center; padding: 24px 12px;">USER DETAILS</td>
          </tr>
        <?php foreach($data as $key => $val) { ?>
          <tr>
            <td><?= ucwords(str_replace('_', ' ', $key)) ?></td>
            <td><?= $val; ?></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
