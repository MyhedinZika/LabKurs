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
  <title>Product List - iMenu Admin</title>

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

  <h1>Grandmas Pizza Administration</h1>

  <div class="navbar">
    <ul class="nav">
      <li class="right"><a href="../" target="_blank">View Live Site</a></li>
      <li class="active"><a href="list-products.php">Products</a>
        <ul class="subnav">
          <li><a href="list-categories.php">Categories</a></li>
          <li><a href="list-ingredients.php">Ingredients</a></li>
          <li><a href="list-sizes.php">Sizes</a></li>
        </ul>
      </li>
      <!--  <li><a href="index.php?action=manageotherfood">Other Food</a></li>
       <li><a href="index.php?action=managedrinks">Drinks</a></li> -->
      <li><a href="list-gallery-images.php">Image Library</a></li>
    <!--   <li><a href="list-dailyoffers.php">Daily Offers</a></li> -->
      <li><a href="list-users.php">Users</a></li>
      <li class="right"><a href="../includes/logout.php">Log Out</a></li>
    </ul>
  </div>

</div><!-- /.page-header -->


<div class="page-body">


  <h2>Products</h2>

  <?php

  if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['productId'])) {
    $product = $session->getProduct($_GET['productId']);

    if (isset($_GET['confirmed']) && $_GET['confirmed'] === 'true') {
      $result = $session->deleteProduct($_GET['productId']);

      echo $result; // TODO styling for error/success
    } else {
      // ask for confirmation
      ?>
      <p>Are you sure you want to delete <?= $product['name'] ?>?</p>
      <p>
        <a href="list-products.php?action=delete&productId=<?= $product['productId'] ?>&confirmed=true">Yes</a> -
        <a href="list-products.php">No</a>
      </p>
      <?php
    }
  }

  ?>


  <ul class="items-list clearfix">
    <?php
    $products = $session->getProducts();

    foreach ($products as $product):
      ?>

      <li>
        <h3><?= $product['name'] ?></h3>

        <div class="actions">
          <a class="button icon delete" href="list-products.php?action=delete&productId=<?= $product['productId'] ?>">Delete</a>
          <a class="button icon edit" href="edit-pizza.php?productId=<?= $product['productId'] ?>">Edit</a>
        </div>
      </li>

    <?php endforeach; ?>
  </ul>


  <p>
    <a class="button add" href="add-product.php">Add New Pizza</a>
  </p>

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