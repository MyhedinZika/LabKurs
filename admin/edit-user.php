<?php
include("../includes/session.php");
require_once '../includes/class.user.php';


$user_login = new USER();
ob_start();
?>
<!doctype html>
<html class="no-js" lang="">
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit user - iMenu Admin</title>

  <link rel="stylesheet" type="text/css" href="ui/css/admin.css"/>

</head>


<body>
<?php


if ($user_login->is_logged_in())
{
$userId = $_SESSION['userSession'];
$user = $session->getUser($userId);
if ($user['userAdmin'] == 1 | $user['userAdmin'] == 3)
{


?>
<div id="header" class="page-header">
  <a href="frontPage.php" class="logo-home">
    <img id="logo" src="../ui/images/logo.png" alt="Grandmas Pizza"/>
  </a>

  <h1>iMenu User Administration</h1>

  <?php if ($user['userAdmin'] == 1) { ?>
    <div class="navbar">
      <ul class="nav">
        <li class="right"><a href="../" target="_blank">View Live Site</a></li>

        <li><a href="list-products.php">Products</a>
          <ul class="subnav">
            <li><a href="list-categories.php">Categories</a></li>
            <li><a href="list-ingredients.php">Ingredients</a></li>
            <li><a href="list-sizes.php">Sizes</a></li>
          </ul>
        </li>
        <li><a href="index.php?action=manageotherfood">Other Food</a></li>
        <!--   <li><a href="index.php?action=managedrinks">Drinks</a></li> -->
        <li><a href="list-gallery-images.php">Image Library</a></li>
        <li class="active"><a href="list-users.php">Users</a></li>
        <li class="right"><a href="../includes/logout.php">Log Out</a></li>
      </ul>
    </div>
  <?php }
  if ($user['userAdmin'] == 3) {
    ?>
    <div class="navbar">
      <ul class="nav">
        <li class="right"><a href="../" target="_blank">View Live Site</a></li>
        <li><a href="owner.php">Reports</a>
          <!--<ul class="subnav">
              <li><a href="list-categories.php">Daily</a></li>
              <li><a href="list-ingredients.php">Monthly</a></li>
              <li><a href="list-sizes.php">Yearly</a></li>
          </ul>
        </li>
        <!--  <li><a href="index.php?action=manageotherfood">Other Food</a></li>
         <li><a href="index.php?action=managedrinks">Drinks</a></li> -->
          <!--<li><a href="list-gallery-images.php">Image Library</a></li>
          <li><a href="list-dailyoffers.php">Daily Offers</a></li>-->
        <li class="active"><a href="list-users.php">Users</a></li>
        <li class="right"><a href="../includes/logout.php">Log Out</a></li>
      </ul>
    </div>
  <?php } ?>


</div><!-- /.page-header -->


<div class="page-body" style="width: 600px;margin: 0 auto;">
  <?php
  $userId = $_GET['userID'];

  $user = $session->getUser($userId);

  if (isset($_POST['action']) && $_POST['action'] == 'update') {
    try {

      $name = $_POST['name'];
      $surname = $_POST['surname'];
      $email = $_POST['email'];
      $gender = $_POST['gender'];
      $phone = $_POST['phone'];

      if (isset($_POST['role'])) {
        if ($_POST['role'] === 'Client') {
          $role = 0;
        } elseif ($_POST['role'] === 'Admin') {
          $role = 1;
        } elseif ($_POST['role'] === 'Seller') {
          $role = 2;
        } elseif ($_POST['role'] === 'Owner') {
          $role = 3;
        }
      } else {
        $role = 0;
      }
      //$test = $_POST['is-admin'];
      //var_dump($test);
      $sql = "UPDATE users SET userName = :user_name,userSurname = :user_surname,
												  userEmail = :user_mail ,userGender = :user_gender,userPhone = :user_phone,
												  userAdmin = :user_admin WHERE userID = :userID";
      $stmt = $database->connection->prepare($sql);
      $stmt->bindparam(":user_name", $name);
      $stmt->bindparam(":user_surname", $surname);
      $stmt->bindparam(":user_mail", $email);
      $stmt->bindparam(":user_gender", $gender);
      $stmt->bindparam(":user_phone", $phone);
      $stmt->bindparam(":user_admin", $role);
      $stmt->bindparam(":userID", $userId);
      $stmt->execute();
    } catch (Exception $e) {
      return $e->getMessage();
    }
    echo '<p>User successfully updated!</p>';
  }


  $user = $session->getUser($userId);
  ?>

  <h2 style="margin-left: 150px; margin-top: -20px;">
    Edit <?= $user['userName'] ?><?php echo '&nbsp'; ?><?= $user['userSurname'] ?></h2>

  <form class="admin-form" method="post" style="background-color:rgba(0, 0, 0, 0.9); border-radius: 15px;">


    <fieldset>
      <legend style="color: white; text-decoration: underline; font-size: 16px;">Details</legend>

      <ul>
        <li>
          <label style="color: #aaa;" for="name">Name:</label>
          <input style="width:295px;" type="text" id="name" name="name" value="<?= $user['userName'] ?>"/>
        </li>
        <li>
          <label style="color: #aaa;" for="surname">Surname:</label>
          <input style="width:295px;" type="text" id="surname" name="surname" value="<?= $user['userSurname'] ?>"/>
        </li>
        <li>
          <label style="color: #aaa;" for="email">Email:</label>
          <input style="width:295px;" type="text" id="email" name="email" value="<?= $user['userEmail'] ?>"/>
        </li>
        <li>
          <label style="color: #aaa;" for="gender">Gender:</label>
          <select style="width:295px;" name="gender" id="gender">
            <?php
            if ($user['userGender'] === 'M') {
              echo '<option value="M" selected="selected">Male</option>';
              echo '<option value="F">Female</option>';
            } else {
              echo '<option value="M" >Male</option>';
              echo '<option value="F" selected="selected">Female</option>';
            }
            ?>
          </select>
        </li>
        <li>
          <label style="color: #aaa;" for="phone">Phone:</label>
          <input style="width:295px;" type="text" id="phone" name="phone" value="<?= $user['userPhone'] ?>"/>
        </li>
        <li>
          <label style="color: #aaa;" for="status">Status:</label>
          <select style="width:295px;" name="status" id="status" disabled>
            <?php
            if ($user['userStatus'] == 0) {
              echo '<option value="0" selected="selected">Unverified</option>';
              echo '<option value="1" >Verified</option>';
            } else {
              echo '<option value="0" >Unverified</option>';
              echo '<option value="1" selected="selected" >Verified</option>';
            }
            ?>
          </select>
        </li>
        <li>
          <div class="checkbox" style="color: #aaa;">
            <label style="color: #aaa;" for="is-admin">Role:</label>
            <?php
            if ($user['userAdmin'] == 0) {
              echo '<input type="radio" name="role" value="Client" checked="checked"/> Client';
              echo '<input type="radio" name="role" value="Admin" />Admin';
              echo '<input type="radio" name="role" value="Seller"/>Seller';
              echo '<input type="radio" name="role" value="Owner"/>Owner';
            } elseif ($user['userAdmin'] == 1) {
              echo '<input type="radio" name="role" value="Client"/>Client';
              echo '<input type="radio" name="role" value="Admin" checked="checked"/>Admin';
              echo '<input type="radio" name="role" value="Seller"/>Seller';
              echo '<input type="radio" name="role" value="Owner"/>Owner';
            } elseif ($user['userAdmin'] == 2) {
              echo '<input type="radio" name="role" value="Client" /> Client';
              echo '<input type="radio" name="role" value="Admin" />Admin';
              echo '<input type="radio" name="role" value="Seller" checked="checked"/>Seller';
              echo '<input type="radio" name="role" value="Owner"/>Owner';
            } elseif ($user['userAdmin'] == 3) {
              echo '<input type="radio" name="role" value="Client" /> Client';
              echo '<input type="radio" name="role" value="Admin" />Admin';
              echo '<input type="radio" name="role" value="Seller" />Seller';
              echo '<input type="radio" name="role" value="Owner" checked="checked"/>Owner';
            } else {

            }
            ?>
          </div>
        </li>
      </ul>
    </fieldset>


    <div class="buttons" style="margin-right: 150px; height: 50px;">
      <button style="width: 145px;" type="submit" class="button icon go" title="Update" name="action" value="update">
        Update
      </button>
      <a style="width: 145px;" class="button icon cancel" title="Cancel" href="list-users.php">Cancel</a>
    </div>

  </form>

  <?php }
  else {
    $user_login->redirect('../index.php');
  }
  }
  else {
    //ob_end();
    $user_login->redirect('../index.php');
    echo "test";

    //header("Location: ../../index.php" );
    //exit();


  }
  ?>

</div><!-- /.page-body -->

</body>
</html>

