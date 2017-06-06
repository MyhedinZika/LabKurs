<?php
include('includes/session.php');
//session_start();
require_once 'includes/class.user.php';
$user_home = new USER();

if ($user_home->is_logged_in()) {
  $products = $session->getOrderProducts($_POST['orderId']);

  ?>
  <tr>
    <th style="color: red;">Product Name</th>
    <th style="color: red;">Size</th>
    <th style="color: red;">Total Price</th>
    <th style="color: red;">Quantity</th>

  </tr>

  <?php
  foreach ($products as $key => $value) { ?>
      <tr>
        <?php

        $pizzaProduct = $session->getProduct($value['ProductIdFK']);
        //$pizzaPrices = $session->getProductPrice($value['Pizza_IdFK'], $value['Size']);
        $size = $session->getSize($value['ProductSize']);
        ?>
        <td class="item-desc"><?= $pizzaProduct['name']; ?></td>
        <td class="item-size"><?php echo $size['name'];?></td>
        <td><?= $value['TotalPrice']; ?></td>
        <td><input class="qty-num" name="productQuantity" value="<?= $value['Quantity'] ?>">
      </tr>
      <?php

  }
}
?>