<?PHP
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
  <title>Add product - iMenu Admin</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js
"></script>
  <link rel="stylesheet" type="text/css" href="ui/css/admin.css"/>
  <link rel="stylesheet" type="../assets/css/bootstrap.css"/>
  <link rel="stylesheet" href="bootstrap-select/dist/css/bootstrap-select.min.css">
  <!---->
  <!--  <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">-->
  <!--  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css"/>-->
  <!---->
  <!--  <!-- Latest compiled and minified JavaScript -->
  <!--  <script src="bootstrap-select/js/bootstrap-select.js"></script>-->
  <!---->
  <!--  <!-- (Optional) Latest compiled and minified JavaScript translation files -->
  <!--  <script src="bootstrap-select/js/i18n/defaults-en_US.js"></script>-->

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      $("#category").select2();
      $("#ingredients").select2();
      run();
    });
    function run() {
      var cat = document.getElementById("category").value;

      //console.log(cat);
      showSizes(cat);
    }
    function showSizes(valueID) {

      var categoryIdData = {
        categoryId: valueID
      };

      $.ajax({
        type: 'POST',
        url: 'categorySizes.php',
        data: categoryIdData
      }).then(function (data) {
        $("#showMoreClear").html("");
        $('.showMore').append(data);

        //console.log(data);
      }, function (err, x, y) {
        console.log(err, x, y);
        alert('Item couldn\'t be added to cart.');
      });
    }
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

  <h1>iMenu Product Administration</h1>


  <div class="navbar">
    <ul class="nav">
      <li class="right"><a href="../" target="_blank">View Live Site</a></li>

      <li class="active"><a href="list-products.php">Products</a></li>
      <!-- <li><a href="index.php?action=manageotherfood">Other Food</a></li>
      <li><a href="index.php?action=managedrinks">Drinks</a></li>
      <li><a href="imagelibrary.php?type=relprod">Image Library</a></li> -->
      <li><a href="list-gallery-images.php">Image Library</a></li>
      <li><a href="list-users.php">Users</a></li>
      <li class="right"><a href="../includes/logout.php">Log Out</a></li>
    </ul>
  </div>

</div><!-- /.page-header -->


<div class="page-body">

  <h2>Add Pizza</h2>

  <form class="admin-form" method="post" action="../includes/process.php" enctype="multipart/form-data">
    <fieldset>
      <legend>Details</legend>

      <ul>
        <div class="form-group col-lg-1">
          <li>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name"/>
          </li>
        </div>
        <div class="form-group">
          <li>
            <label for="new-image">Photo:</label>
            <input type="file" id="new-image" name="new-image"/>
          </li>
        </div>
        <li>
          <?php
          $categories = $session->getCategory();
          ?>

          <div class="form-group">
            <label for="category">Category:</label>
            <select name="category" id="category" class="js-example-basic-single" onchange="run()">
              <?php
              foreach ($categories as $key => $value) {
                echo '<option value="' . $value['categoryId'] . '">' . $value['name'] . '</option>';
              }
              echo '</select>';
              ?>
          </div>
        </li>

        <li>
          <label for="ingredients">Ingredients</label>
          <?php
          $ingredients = $session->getIngredients();

          echo '<select name="ingredients[]" id="ingredients" class="js-example-basic-multiple" multiple style=" min-width: 400px;">';
          foreach ($ingredients as $key => $value) {
            echo '<option value="' . $value['ingredientId'] . '">' . $value['i_name'] . '</option>';
          }
          echo '</select>';

          ?>

        </li>
        <fieldset>
          <legend>Prices</legend>
          <div class="showMore" id="showMoreClear">

          </div>
        </fieldset>


      </ul>
    </fieldset>


    <div class="buttons">
      <button type="submit" class="button icon go" title="Submit" value="addPizza" name="action">Submit</button>
      <a class="button icon cancel" title="Cancel" href="../admin/add-pizza.php">Cancel</a>
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

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

</body>

</html>
