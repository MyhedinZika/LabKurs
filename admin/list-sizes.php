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
  <title>Product Sizes List - iMenu Admin</title>

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

  <h1>iMenu Size Administration</h1>

  <div class="navbar">
    <ul class="nav">
      <li class="right"><a href="../" target="_blank">View Live Site</a></li>
      <li class="active"><a href="list-products.php">Products</a>
        <ul class="subnav">
          <li><a href="list-categories.php">Categories</a></li>
          <li><a href="list-ingredients.php">Ingredients</a></li>
          <li class="active"><a href="list-sizes.php">Sizes</a></li>
        </ul>
      </li>
      <!--    <li><a href="index.php?action=manageotherfood">Other Food</a></li>
         <li><a href="index.php?action=managedrinks">Drinks</a></li> -->
      <li><a href="list-gallery-images.php">Image Library</a></li>
      <!--  <li><a href="list-dailyoffers.php">Daily Offers</a></li> -->
      <li><a href="list-users.php">Users</a></li>
      <li class="right"><a href="../includes/logout.php">Log Out</a></li>
    </ul>
  </div>

</div><!-- /.page-header -->


<div class="page-body">

  <?php

  if (isset($_POST['action']) && $_POST['action'] === 'addSize') {

    $name = $_POST['size-name'];
    $category = $_POST['category'];

    if($name != null){
      $result = $session->addSize($name, $category);

   

    }
    else{
      $result = 'Emri i size nuk ben te jete i zbrazet';
    }

     echo $result;

    
  }

  if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $size = $session->getSize($_GET['id']);

    if (isset($_GET['confirmed']) && $_GET['confirmed'] === 'true') {
      $result = $session->deleteSize($_GET['id']);

      echo $result; // TODO styling for error/success
    } else {
      // ask for confirmation
      ?>
      <p>Are you sure you want to delete <?= $size['name'] ?>, <?= $size['CategoryIDFK'] ?>cm?</p>
      <p>
        <a href="list-sizes.php?action=delete&id=<?= $size['sizeId'] ?>&confirmed=true">Yes</a> -
        <a href="list-sizes.php">No</a>
      </p>
      <?php
    }
  }

  ?>

  <form class="admin-form" method="POST" style="width:849px; float:right;margin-top: -20px; ">
    Name: <input type="text" id="size-name" name="size-name" style="height: 36px;" required/>

    <?php
    $categories = $session->getCategory();

    ?>


    Category:
    <select name="category" id="category" style="height: 38px;" required>
      <?php
      foreach ($categories as $key => $value) {
        echo '<option value="' . $value['categoryId'] . '">' . $value['name'] . '</option>';
      }
      ?>
    </select>


    <button type="submit" class="button inline add" title="Add size" value="addSize" name="action">Add new Product
      size
    </button>
  </form>

  <h2 style="margin-left: 20px;">Pizza Sizes</h2>

  <ul class="items-list clearfix">
    <?php
    $sizes = $session->getSizes();

    foreach ($sizes as $size):
      ?>
      <li>
        <h3><?= $size['name'] ?></h3>

        <div class="actions">
          <a class="button icon delete" href="list-sizes.php?action=delete&id=<?= $size['sizeId'] ?>">Delete</a>
          <a class="button icon edit" href="edit-size.php?id=<?= $size['sizeId'] ?>">Edit</a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
  <br/>

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

