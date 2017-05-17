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
  <title>Edit Product ingredient - iMenu Admin</title>

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
          <li class="active"><a href="list-ingredients.php">Ingredients</a></li>
          <li><a href="list-sizes.php">Sizes</a></li>
        </ul>
      </li>
      <!-- <li><a href="index.php?action=manageotherfood">Other Food</a></li>
      <li><a href="index.php?action=managedrinks">Drinks</a></li>-->
      <li><a href="imagelibrary.php?type=relprod">Image Library</a></li>
    <!--   <li><a href="list-dailyoffers.php">Daily Offers</a></li> -->
      <li><a href="list-users.php">Users</a></li>
      <li class="right"><a href="../includes/logout.php">Log Out</a></li>
    </ul>
  </div>

</div><!-- /.page-header -->


<div class="page-body">
  <?php
  if (isset($_GET['ingredientId'])) {
    if (isset($_POST['action']) && $_POST['action'] === 'update' && isset($_POST['ingredient-name'])) {
      $result = $session->updateIngredient($_GET['ingredientId'], $_POST['ingredient-name']);
      echo "<p>$result</p>";
    }

    $ingredient = $session->getOneIngredient($_GET['ingredientId']);

    ?>

    <h2>Edit Ingredient <?= $ingredient['i_name'] ?></h2>

    <form class="admin-form" method="post">
      <ul>
        <li>
          <label for="name">Ingredient:</label>
          <input type="text" id="ingredient-name" name="ingredient-name" value="<?= $ingredient['i_name'] ?>"/>
        </li>
      </ul>

      <div class="buttons">
        <button type="submit" class="button icon go" title="Update" name="action" value="update">Update</button>
        <a class="button icon cancel" title="Cancel" href="list-ingredients.php">Cancel</a>
      </div>

    </form>

    <?php
  }
  ?>
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

