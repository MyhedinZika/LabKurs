<?php
include('../includes/session.php');
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
  <title>Edit Product category - iMenu Admin</title>

  <link rel="stylesheet" type="text/css" href="ui/css/admin.css"/>

</head>


<body>
<?php


if ($user_login->is_logged_in())
{
$userId = $_SESSION['userSession'];
$user = $session->getUser($userId);
if ($user['userAdmin'] == 1)
{


?>
<div id="header" class="page-header">
  <a href="frontPage.php" class="logo-home">
    <img id="logo" src="../ui/images/logo.png" alt="Grandmas Pizza"/>
  </a>

  <h1>iMenu Category Administration</h1>


  <div class="navbar">
    <ul class="nav">
      <li class="right"><a href="../" target="_blank">View Live Site</a></li>

      <li class="active"><a href="list-products.php">Products</a>
        <ul class="subnav">
          <li class="active"><a href="list-categories.php">Categories</a></li>
          <li><a href="list-ingredients.php">Ingredients</a></li>
          <li><a href="list-sizes.php">Sizes</a></li>
        </ul>
      </li>
      <!--<li><a href="index.php?action=manageotherfood">Other Food</a></li>
      <li><a href="index.php?action=managedrinks">Drinks</a></li>-->
      <li><a href="imagelibrary.php?type=relprod">Image Library</a></li>
      <!-- <li><a href="list-dailyoffers.php">Daily Offers</a></li> -->
      <li><a href="list-users.php">Users</a></li>
      <li class="right"><a href="../includes/logout.php">Log Out</a></li>
    </ul>
  </div>

</div><!-- /.page-header -->


<div class="page-body" style="width:600px; margin:0 auto;">
  <?php
  if (isset($_GET['categoryId'])) {
    if (isset($_POST['action']) && $_POST['action'] === 'update' && isset($_POST['category-name'])) {
      $result = $session->updateCategory($_GET['categoryId'], $_POST['category-name']);
      echo "<p>$result</p>";
    }
  }


  $category = $session->getOneCategory($_GET['categoryId']);


  ?>
  <h2 style="margin-left: 150px;">Edit category: <?= $category['name'] ?> </h2>

  <form class="admin-form" method="post" enctype="multipart/form-data"
        style="background-color:rgba(0, 0, 0, 0.9); border-radius: 15px;">


    <ul>
      <li>
        <label for="name" style="color:#aaa;">Category:</label>
        <input style="width: 295px;" type="text" id="category-name" name="category-name"
               value="<?= $category['name'] ?> " required/>
      </li>
    </ul>


    <div class="buttons" style="margin-right: 150px; height: 50px;">
      <button style="width: 145px;" type="submit" class="button icon go" title="Update" name="action" value="update">
        Update
      </button>
      <a style="width: 145px;" class="button icon cancel" title="Cancel" href="list-categories.php">Cancel</a>
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

