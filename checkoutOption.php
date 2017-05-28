<?php
$location = $_POST['locationId'];

if ($location == 1) {
  include('includes/session.php');
  //session_start();
  require_once 'includes/class.user.php';
  $user_home = new USER();

  if ($user_home->is_logged_in()) {
    $stmt = $user_home->runQuery("SELECT * FROM users WHERE userID=:uid");
    $stmt->execute(array(":uid" => $_SESSION['userSession']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

  }
  $userId = $row['userID'];
//    echo $userId;
  $order = $session->getOrderId($userId);
  $orderId = $order['OrderId'];
  $totalCosts = 0;
//      echo $orderId;
  // var_dump($orderId);

  $totalCosts = $session->getTotalCosts($orderId);

  $array = array_values($totalCosts);
  $totalCost = $array[0];

  ?>
  <form action="#" method="POST" enctype="multipart/form-data">
  <div class="row">
    <div class="col-md-8">

    <label>Please pick the area where you are:</label><br/>
      <select>

      </select><br/>
  <label>Please pick the table number where you are: </label>
    </div>
    <div class="col-md-4">
      <div class="order-summary">
        <h2>Your order</h2>
        <table>
          <thead>
          <tr style=" border-bottom: dashed 1px;">
            <th style="width:100px">Name</th>
            <th style="width: 85px;">Size</th>
            <th style="width: 84px;">Quantity</th>
            <th>Price</th>
          </tr>
          </thead>
          <tbody>
          <tr>

            <?php
            $suborders = $session->getOrderProducts($orderId);
            foreach ($suborders as $key => $value) {



            $product = $session->getProduct($value['ProductIdFK']);
            $productSizeInfo = $session->getSize($value['ProductSize']);

            $productSize = $productSizeInfo['name'];


            ?>


            <td><span class="item-name"><?= $product['name']; ?></span></td>
            <td> <span class="size"><?php echo $productSize; ?></span></td>
            <td style="text-align: center;"> <span class="qty"><?= $value['Quantity'] ?></span></td>
            <td> <span class="cost">$<?php echo $value['TotalPrice']  ?></span></td>




          </tr>


          <?php


          }


          ?>

          </tbody>
        </table>
        <ul class="cost-list">

          <li class="total">
            <span class="cost-item">Total</span>
            <span class="cost">$<?php  echo $totalCost; ?></span>
            <input type="hidden" name="TotalCost" value="" readonly>
          </li>
        </ul>


        <button type="submit" id="placeOrder" class="mu-send-btn">Place order</button>
      </div>
    </div>



  </div>
  </form>
  <?php
} elseif ($location == 2) { ?>
  <form action="payment.php" method="POST" enctype="multipart/form-data">
    <?php
    include('includes/session.php');
    //session_start();
    require_once 'includes/class.user.php';
    $user_home = new USER();

    if ($user_home->is_logged_in()) {
      $stmt = $user_home->runQuery("SELECT * FROM users WHERE userID=:uid");
      $stmt->execute(array(":uid" => $_SESSION['userSession']));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

    }
    $userId = $row['userID'];
//    echo $userId;
        $order = $session->getOrderId($userId);
        $orderId = $order['OrderId'];
    $totalCosts = 0;
//      echo $orderId;
    // var_dump($orderId);

    $totalCosts = $session->getTotalCosts($orderId);

    $array = array_values($totalCosts);
    $totalCost = $array[0];



    ?>
    <div class="row">


      <div class="col-md-8">
        <div class="addresses">
          <?php
               $addresses = $session->getUserAddresses($userId);

                  foreach ($addresses as $key => $value) {


          ?>
          <div class="radio">
            <label>
              <input type="radio" required name="delivery-addresses" value="$value['address_id'] ?>">
              Address line 1: $value['address_1'] ,<br/>
              Address line 2: $value['address_2'] ,<br/>
              City: $value['city'] ?>,
              Postal code: $value['postal_code']
            </label>
          </div>

          <?php

          }

          ?>

          <div class="radio">
            <label>
              <input type="radio" required name="delivery-addresses" value="new-address">
              Enter a new address below:
            </label>
          </div>

        </div>


        <h3>New address:</h3>

        <div class="form-group">
          <label for="address-1">Address line 1:</label>
          <input type="text" class="form-control" id="address-1" name="Address_1">
        </div>
        <div class="form-group">
          <label for="address-2">Address line 2:</label>
          <input type="text" class="form-control" id="address-2" name="Address_2">
        </div>
        <div class="form-group">
          <label for="city">City:</label>
          <input type="text" class="form-control" id="city" name="City">
        </div>
        <div class="form-group">
          <label for="postal-code">Postal code:</label>
          <input type="text" class="form-control" id="postal-code" name="Postal_Code">
        </div>

      </div>

      <div class="col-md-4">
        <div class="order-summary">
          <h2>Your order</h2>
          <table>
          <thead>
          <tr style=" border-bottom: dashed 1px;">
            <th style="width:100px">Name</th>
            <th style="width: 85px;">Size</th>
            <th style="width: 84px;">Quantity</th>
            <th>Price</th>
          </tr>
          </thead>
            <tbody>
            <tr>

            <?php
                $suborders = $session->getOrderProducts($orderId);
                foreach ($suborders as $key => $value) {



            $product = $session->getProduct($value['ProductIdFK']);
            $productSizeInfo = $session->getSize($value['ProductSize']);

            $productSize = $productSizeInfo['name'];


            ?>


              <td><span class="item-name"><?= $product['name']; ?></span></td>
              <td> <span class="size"><?php echo $productSize; ?></span></td>
              <td style="text-align: center;"> <span class="qty"><?= $value['Quantity'] ?></span></td>
              <td> <span class="cost">$<?php echo $value['TotalPrice']  ?></span></td>




          </tr>


            <?php


                }


            ?>

            </tbody>
          </table>
          <ul class="cost-list">

            <li class="total">
              <span class="cost-item">Total</span>
              <span class="cost">$<?php  echo $totalCost; ?></span>
              <input type="hidden" name="TotalCost" value="" readonly>
            </li>
          </ul>


          <button type="submit" id="placeOrder" class="mu-send-btn">Place order</button>
        </div>
      </div>
    </div>

  </form>

  <?php
} else {
  echo "No option was selected. Please pick one";
}

?>