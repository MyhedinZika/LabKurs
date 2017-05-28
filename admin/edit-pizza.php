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
  <title>Edit product - iMenu Admin</title>

  <link rel="stylesheet" type="text/css" href="ui/css/admin.css"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      $("#category").select2();
      $("#ingredients").select2();
    });
  </script>

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

  <h1>iMenu Pizza Administration</h1>


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

      <li><a href="imagelibrary.php?type=relprod">Image Library</a></li>
      <!-- <li><a href="list-dailyoffers.php">Daily Offers</a></li> -->
      <li><a href="list-users.php">Users</a></li>
      <li class="right"><a href="../includes/logout.php">Log Out</a></li>
    </ul>
  </div>

</div><!-- /.page-header -->


<div class="page-body" style="width: 600px; margin: 0 auto;">
  <?php

  $productId = $_GET['productId'];

  $product = $session->getProduct($productId);
  if (isset($_POST['action']) && $_POST['action'] == 'update') {
    try {


      $name = $_POST['name'];// name of pizza

      $imgFile = $_FILES['new-image']['name'];
      $tmp_dir = $_FILES['new-image']['tmp_name'];
      $imgSize = $_FILES['new-image']['size'];

      $category = $_POST['category'];
      $ingredients = $_POST['ingredients'];

      //$prices = $_POST['price'];

      if (empty($name)) {
        $errMSG = "Please enter pizza name.";
      }

      if (!empty($imgFile)) {
        $upload_dir = __DIR__ . '/../includes/pizza_images/'; // upload directory

        $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

        // valid image extensions
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions

        // rename uploading image
        $userpic = rand(1000, 1000000) . "." . $imgExt;

        // allow valid image file formats
        if (in_array($imgExt, $valid_extensions)) {
          // Check file size '5MB'
          if ($imgSize < 5000000) {
            unlink($upload_dir . $product['photo']);
            move_uploaded_file($tmp_dir, $upload_dir . $userpic);
          } else {
            $errMSG = "Sorry, your file is too large.";
          }
        } else {
          $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
      } else { // photo not updated
        $userpic = $product['photo'];
      }


      //update Pizza
      if (!isset($errMSG)) {

        $sql = "UPDATE product SET name=:name,photo=:photo,categoryIdFK=:category where productId=:productId";
        $stmt = $database->connection->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':photo', $userpic);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();
      }

      if (!isset($errMSG)) {
        $stmt = $database->connection->prepare('DELETE FROM product_ingredients WHERE productIdFK = :product');
        $stmt->bindParam(':product', $productId);

        if (!$stmt->execute()) {
          $errMSG = "error while deleting all pizza ingredients from pizza_ingredients";
        }

        foreach ($ingredients as $ingredient) {
          $stmt = $database->connection->prepare('INSERT INTO product_ingredients(productIdFK,ingredientIdFK) VALUES(:product,:ingredient)');
          $stmt->bindParam(':ingredient', $ingredient);
          $stmt->bindParam(':product', $productId);

          if (!$stmt->execute()) {
            $errMSG = "error while inserting into pizza_ingredients";
          }
        }
      }


      //insert prices good :P so same with prices now, yup
      // if (!isset($errMSG)) {
      //   $stmt = $database->connection->prepare('DELETE FROM product_sz WHERE pizza = :pizza');
      //   $stmt->bindParam(':pizza', $productId);

      //   if (!$stmt->execute()) {
      //     $errMSG = "error while deleting all pizza sizes from pizza_size";
      //   }


      //   foreach ($prices as $sizeId => $price) {
      //     $stmt = $database->connection->prepare('INSERT INTO pizza_size(pizza,size,price) VALUES(:pizza, :size, :price)');
      //     $stmt->bindParam(':pizza', $productId);
      //     $stmt->bindParam(':size', $sizeId);
      //     $stmt->bindParam(':price', $price);

      //     if (!$stmt->execute()) {
      //       $errMSG = "error while inserting into pizza_size";
      //     }
      //   }
      // }


    } catch (Exception $e) {
      return $e->getMessage();
    }

    echo '<p>Product successfully updated!</p>';

  }


  $name = $product['name'];
  $product = $session->getProduct($productId);

  ?>
  <h2 style="margin-left: 150px;">Edit <?= $product['name'] ?></h2>


  <form class="admin-form" method="post" enctype="multipart/form-data"
        style="background-color:rgba(0, 0, 0, 0.9); border-radius: 15px;">


    <fieldset>
      <legend style="font-size:22px;text-decoration:underline;color:white;">Details</legend>

      <ul>
        <li>
          <label for="name" style="color: #aaa;">Name:</label>
          <input type="text" id="name" name="name" value="<?= $product['name'] ?>" style="width:295px;"/>
        </li>
        <li>
          <label for="image" style="color: #aaa;">Photo:</label>
          <img class="product-photo" src="../includes/pizza_images/<?= $product['photo'] ?>"/>
        </li>
        <li>
          <label for="image" style="color: #aaa;">Update photo:</label>
          <input type="file" id="new-image" name="new-image"/>
        </li>
        <li>

          <?php
          $categories = $session->getCategory();
          echo '<label for="category" style="color: #aaa;">Category:</label>';
          echo '<select style="width:295px;" name="category" id="category">';
          foreach ($categories as $key => $value) {
            if ($value['categoryId'] == $product['categoryIdFK']) {
              echo '<option value="' . $value['categoryId'] . '" selected="selected">' . $value['name'] . '</option>';
            } else {
              echo '<option value="' . $value['categoryId'] . '">' . $value['name'] . '</option>';
            }
          }
          echo '</select>';
          ?>
        </li>
        <li>
          <label for="ingredients" style="color: #aaa;">Ingredients</label>


          <?php
          $ingredientsPizza = $session->getPizzaIngredients($productId);


          function pizzaIngredientCheck($productIngredients, $ingredientId)
          {
            foreach ($productIngredients as $productIngredient) {

              //var_dump($productIngredient);
              if ($productIngredient['ingredientId'] == $ingredientId) {
                return true;
              }
            }

            return false;
          }


          // var_dump($ingredientsPizza);


          $ingredients = $session->getIngredients();
          //var_dump($ingredients);
          echo '<select style="width:295px;" name="ingredients[]" id="ingredients" multiple>';
          foreach ($ingredients as $key => $value) {
            if (pizzaIngredientCheck($ingredientsPizza, $value['ingredientId']) === true) {
              echo '<option value="' . $value['ingredientId'] . '" selected="selected">' . $value['i_name'] . ' </option>';
            } else {
              echo '<option value="' . $value['ingredientId'] . '">' . $value['i_name'] . '</option>';
            }
          }
          echo '</select>';

          ?>


        </li>
      </ul>
    </fieldset>


    <!--   <fieldset>
      <legend>Prices</legend>
      <ul>
        <?php
    // $sizes = $session->getPizzaPrices($productId);

    // var_dump($sizes);

    // $productIdSize = 1;
    // foreach ($sizes as $key => $value) {
    //   echo '<li>';
    //   echo '<label for="price-' . $value['id'] . '">' . $value['name'] . ':</label>';
    //   echo '<input type="text" id="price-' . $value['id'] . '" name="price[' . $value['id'] . ']" value=' . $value['price'] . ' />';
    //   echo '</li>';
    // }
    ?>
      </ul>
    </fieldset> -->


    <div class="buttons" style="margin-right: 150px; height: 50px;">
      <button style="width:145px;" type="submit" class="button icon go" title="Update" name="action" value="update">
        Update
      </button>
      <a style="width:145px;" class="button icon cancel" title="Cancel" href="list-products.php">Cancel</a>
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

<!--     <?php
// function pizzaIngredientCheck(){

//   foreach($productIngredients as $ingr) {
//     echo $ingr;}
// }

?> -->
</body>
</html>

