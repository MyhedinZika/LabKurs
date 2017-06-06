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
  <title>Add gallery image - iMenu Admin</title>

  <link rel="stylesheet" type="text/css" href="ui/css/admin.css"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

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

  <script type="text/javascript">
    $(function () {

      $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
      }, "Username must contain only letters, numbers, or dashes.");


      $("#form-gallery").validate({
        rules: {
          newimage: {
            required: true,
            extension: "jpg|jpeg|png|gif"
          },
          description:{
            required: true
          }

        },
        messages: {
          newimage:{
            required: "Please select a picture"
          },
          description:{
            required: "Please fill out some info"
          }

        }
      });
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

  <h1>iMenu Gallery Administration</h1>


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
      <!--  <li><a href="index.php?action=manageotherfood">Other Food</a></li>
       <li><a href="index.php?action=managedrinks">Drinks</a></li> -->
      <li class="active"><a href="list-gallery-images.php">Image Library</a></li>
      <li><a href="list-users.php">Users</a></li>
      <li class="right"><a href="../includes/logout.php">Log Out</a></li>
    </ul>
  </div>

</div><!-- /.page-header -->


<div class="page-body" style="width:600px; margin:0 auto;">

  <h2 style="margin-top: -30px; margin-left: 150px;">Add Gallery Image</h2>

  <form class="admin-form" id="form-gallery" method="post" action="../includes/process.php" enctype="multipart/form-data"
        style=" background-color:rgba(0, 0, 0, 0.9); border-radius:15px;">
    <input type="hidden" name="action" value="doaddimage"/>
    <!-- you might not want this - I used it to specify what the next action would be after submitting the form -->


    <ul>
      <li>
        <label for="title" style="color:#aaa;">Gallery item title:</label>
        <input type="text" id="title" name="title" style="width:295px;" />
      </li>
      <li>
        <label for="description" style="color:#aaa;">Description:<br/></label>
        <textarea id="description" name="description" rows="4" style="width:295px"></textarea>
      </li>
      <li>
        <label for="newimage" style="color:#aaa;">Photo:</label>
        <input type="file" id="newimage" name="newimage" />
      </li>
    </ul>


    <div class="buttons" style="margin-right: 150px;">
      <button type="submit" class="button icon go" title="Submit" value="addGallery" name="action" style="width:145px;">
        Submit
      </button>
      <a class="button icon cancel" title="Cancel" href="list-gallery-images.php" style="width:145px;">Cancel</a>
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

