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

<!--  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"
          type="text/javascript"></script>
  <script type="text/javascript"
          src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.js"></script>

  <style>
    label.error{
      float: none;
      margin-left: 131px;
      color: red;
      padding-left: .5em;
      vertical-align: top;
      display: block;
      width:300px;
    }
  </style>

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
    $(function () {

      $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
      }, "Username must contain only letters, numbers, or dashes.");


      $("#form-product").validate({
        rules: {
          name: {
            required: true,
            minlength: 3,
//            lettersonly: true
            pattern: /^[a-zA-Z_ -]*$/
          },
          newimage: {
            required: true,
            extension: "jpg|jpeg|png|gif"
         },
         category:{
            required: true
         },
         "ingredients[]":{
            required: true
         },
          price:{
            required: true
          }

        },
        messages: {
          name: {
            required: "Please write the name"
          },
          newimage:{
            required: "Please select a picture"
          },
          category:{
            required: "Please select a category"
          },
          ingredients: {
            required: "Please select an ingredient"
          },
          price: {
            required: "Please write price"
          }

        }
      });
    });



//    $("#form-product").validate({
//      rules: {
//        name:{
//          required: true,
//          minlength: 2/*,
//           alphanumericwithpunc: true*/
//        }
//      },
//      messages: {
//        name: {
//          required: "Escriba el titulo",
//          minlength: "Al menos 2 caracteres",
//          alphanumericwithpunc: "Sólo alfanumérico"
//        }
//      },
//
//    });


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


<div class="page-body" style="width:600px; margin:auto;">

  <h2 style="font-size:26px;margin-left:19px;margin-top: -46px; ">Add Product</h2>

  <form class="admin-form" id="form-product" method="post" action="../includes/process.php" enctype="multipart/form-data"
        style="border-left: solid 2px #cac0c0; border-bottom: solid 2px #cac0c0; background-color:rgba(0, 0, 0, 0.9);border-radius:10px;">
    <fieldset>
      <legend style="font-size:22px;text-decoration:underline;color:white;">Details</legend>

      <ul>
        <div class="form-group col-lg-1">
          <li>
            <label for="name" style="font-size: 16px;color:#aaa;">Name:</label>
            <input type="text" id="name" name="name" style="width:300px;" />

          </li>
        </div>
        <div class="form-group">
          <li>
            <label for="newimage" style="font-size: 16px;color:#aaa;">Photo:</label>
            <input type="file" id="newimage" name="newimage" style="width:300px;" />
          </li>
        </div>
        <li>
          <?php
          $categories = $session->getCategory();
          ?>

          <div class="form-group">
            <label for="category" style="font-size: 16px;color:#aaa;">Category:</label>
            <select name="category" id="category" class="js-example-basic-single" onchange="run()" style="width:300px;" >
              <?php
              foreach ($categories as $key => $value) {
                echo '<option value="' . $value['categoryId'] . '">' . $value['name'] . '</option>';
              }
              echo '</select>';
              ?>
          </div>
        </li>

        <li>
          <label for="ingredients" style="font-size: 16px;color:#aaa;">Ingredients:</label>
          <?php
          $ingredients = $session->getIngredients();

          echo '<select name="ingredients[]" id="ingredients" class="js-example-basic-multiple" multiple style=" min-width: 300px; " > ' ;
          foreach ($ingredients as $key => $value) {
            echo '<option value="' . $value['ingredientId'] . '">' . $value['i_name'] . '</option>';
          }
          echo '</select>';

          ?>

        </li>
        </br>

        <fieldset>
          <legend style="font-size:22px;text-decoration:underline; color:white;">Prices</legend>
          <div class="showMore" id="showMoreClear" style="color: #aaa;">

          </div>
        </fieldset>


      </ul>
    </fieldset>


    <div class="buttons" style="height:50px;margin-right:146px;margin-top:10px;margin-bottom:10px;">
      <button type="submit" class="button icon go" title="Submit" value="addPizza" name="action" style="width:145px;">
        Submit
      </button>
      <a class="button icon cancel" title="Cancel" href="../admin/add-pizza.php" style="width:145px;margin-left:10px;">Cancel</a>
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

