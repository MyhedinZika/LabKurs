<?php
include('../includes/session.php');
//session_start();
require_once '../includes/class.user.php';
$user_home = new Session();

$categoryId = $_POST['categoryId'];


$sizes = $session->getCategorySizes($categoryId);

foreach ($sizes as $key => $value) { ?>

  <li>
    <label for="price-<?= $value['sizeId']; ?>"><?= $value['name']; ?>:</label>
    <input type="number" id="price-<?= $value['sizeId']; ?>" name="price[<?= $value['sizeId']; ?>]"
           style="min-width: 300px;" step="any" min="0" required >
  </li>

  <?php
}


?>