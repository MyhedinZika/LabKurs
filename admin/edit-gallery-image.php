<?php
include('../includes/session.php');
error_reporting(~E_NOTICE);
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
  <title>Edit gallery image - iMenu Admin</title>

  <link rel="stylesheet" type="text/css" href="ui/css/admin.css"/>
  <style>
    .galleryUpdated p{
      text-align: right;
      margin-top: -20px;
    }
  </style>

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
      <!-- <li><a href="index.php?action=manageotherfood">Other Food</a></li>
      <li><a href="index.php?action=managedrinks">Drinks</a></li> -->
      <li class="active"><a href="list-gallery-images.php">Image Library</a></li>
      <!--       <li><a href="list-dailyoffers.php">Daily Offers</a></li> -->
      <li><a href="list-users.php">Users</a></li>
      <li class="right"><a href="../includes/logout.php">Log Out</a></li>
    </ul>
  </div>

</div><!-- /.page-header -->


<div class="page-body" style="width: 600px; margin:0 auto;">
  <?php
  $gallery = $session->getGallery($_GET['g_id']);

  if (isset($_POST['action']) && $_POST['action'] == 'update') {
    try {
      $title = $_POST['title'];
      $imgFile = $_FILES['newimage']['name'];
      $tmp_dir = $_FILES['newimage']['tmp_name'];
      $imgSize = $_FILES['newimage']['size'];

      $description = $_POST['description'];
      if (empty($title)) {
        $errMSG = "Please Enter Title.";
      }


      if (!empty($imgFile)) {
        $upload_dir = __DIR__ . '/../includes/gallery_images/'; // upload directory 

        $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

        // valid image extensions
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions

        // rename uploading image
        $userpic = rand(1000, 1000000) . "." . $imgExt;

        if (in_array($imgExt, $valid_extensions)) {
          // Check file size '5MB'
          if ($imgSize < 5000000) {
            var_dump($upload_dir . $gallery['g_photo']);
            unlink($upload_dir . $gallery['g_photo']);
            var_dump($tmp_dir, $upload_dir . $userpic);
            move_uploaded_file($tmp_dir, $upload_dir . $userpic);
          } else {
            $errMSG = "Sorry, your file is too large.";
          }
        } else {
          $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
      } else {
        $userpic = $gallery['g_photo'];
      }

      $sql = "UPDATE gallery SET g_title = :g_title, g_description = :g_description, g_photo = :g_photo WHERE g_id = :g_id";
      $stmt = $database->connection->prepare($sql);
      $stmt->bindParam('g_id', $_GET['g_id']);
      $stmt->bindParam('g_title', $title);
      $stmt->bindParam('g_description', $description);
      $stmt->bindParam('g_photo', $userpic);
      $stmt->execute();


    } catch (Exception $e) {
      return $e->getMessage();
    }

    echo '<div class="galleryUpdated" style="margin-top: -5px"><p>Gallery image successfully updated!</p></div>';

  }

  // if(isset($_GET['g_id'])){
  //    if(isset($_POST['action']) && $_POST['action'] === 'update' && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['newimage'])){

  //         $result = $session->updateGallery($_GET['g_id']); 
  //         echo "<p>$result</p>";
  //     }
  // }

  $gallery = $session->getGallery($_GET['g_id']);
  ?>
  <h2 style="margin-top:-20px; margin-left:150px; ">Edit Gallery image: <?= $gallery['g_title'] ?></h2>

  <form class="admin-form" method="post" enctype="multipart/form-data"
        style="background-color:rgba(0, 0, 0, 0.9); border-radius: 15px;">
    <ul>
      <li>
        <label for="title" style="color: #aaa;">Gallery item title:</label>
        <input type="text" id="title" name="title" value="<?= $gallery['g_title'] ?>" style="width:295px;"/>
      </li>
      <li>
        <label for="description" style="color: #aaa;">Description:<br/></label>
        <textarea style="width:295px;" id="description" name="description" rows="4"
                  value="<?= $gallery['g_description'] ?>"><?= $gallery['g_description'] ?>></textarea>
      </li>
      <li>
        <label for="image" style="color: #aaa;">Photo:</label>
        <img class="gallery-photo" src="../includes/gallery_images/<?= $gallery['g_photo'] ?> "
             style=" max-width: 200px; min-width: 200px; " alt="<?= $gallery['g_title'] ?>"/>
      </li>
      <li>
        <label for="image" style="color: #aaa;">Update photo:</label>
        <input type="file" id="newimage" name="newimage"/>
      </li>
    </ul>


    <div class="buttons" style="margin-right: 150px;">
      <button type="submit" class="button icon go" title="Update" name="action" value="update" style="width:145px;">
        Update
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

