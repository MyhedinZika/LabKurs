<?php
  $location =  $_POST['locationId'];

  if($location == 1){ ?>


  <?php
  }
  elseif($location == 2){ ?>
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
          echo $userId;
      //    $order = $session->getOrderId($userId);
      //    $orderId = $order['Order_Id'];
      $totalCosts = 0;
      // var_dump($orderId);

      ?>
      <div class="row">


        <div class="col-md-8">
          <div class="addresses">
            <?php
            //        $addresses = $session->getUserAddresses($userId);

            //        foreach ($addresses as $key => $value) {


            ?>
            <div class="radio">
              <label>
                <input type="radio" required name="delivery-addresses" value="// $value['address_id'] ?>">
                Address line 1:  //$value['address_1'] ,<br/>
                Address line 2:  //$value['address_2'] ,<br/>
                City:// $value['city'] ?>,
                Postal code:  //$value['postal_code']
              </label>
            </div>

            <?php

            //}

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
            <ul class="items-list">
              <?php
              //    $suborders = $session->getOrderProducts($orderId);
              //    foreach ($suborders as $key => $value) {
              $subOrderId = $value['OrderProductsId'];//Mos e ndrysho
              // var_dump($value);
              //   var_dump($subOrderId);

              $pizzaProduct = $session->getPizzaProduct($subOrderId);
              //      if ($value['Pizza_IdFK'] == null) {
              $dailyOfferProduct = $session->getDailyOffer($value['DailyIdFK']);

              $totalCosts += $dailyOfferProduct['DO_Price'] * $value['Quantity'];
              $itemPrice = $dailyOfferProduct['DO_Price'] * $value['Quantity'];

              ?>

              <li>
                <span class="item-name"><?= $dailyOfferProduct['DO_Name']; ?></span>
                <span class="qty"><?= $value['Quantity'] ?></span>
                <span class="cost">$<?php echo $itemPrice; ?></span>
              </li>

              <?php
              //      } else {
              $pizzaProduct = $session->getPizza($value['Pizza_IdFK']);
              $pizzaPrices = $session->getProductPrice($value['Pizza_IdFK'], $value['Size']);
              $totalCosts += $value['Quantity'] * $pizzaPrices['price'];
              $itemPrice = $value['Quantity'] * $pizzaPrices['price'];
              ?>
              <li>
                <span class="item-name"><?= $pizzaProduct['p_name']; ?></span>
                <span class="qty"><?= $value['Quantity'] ?></span>
                <span class="cost">$<?php echo $itemPrice; ?></span>
              </li>


              <?php

              //      }
              //    }

              //  }
              ?>
            </ul>
            <ul class="cost-list">

              <li class="total">
                <span class="cost-item">Total</span>
                <span class="cost">$</span>
                <input type="hidden" name="TotalCost" value="" readonly>
              </li>
            </ul>


            <button type="submit" id="placeOrder" class="mu-send-btn">Place order</button>
          </div>
        </div>
      </div>

    </form>

<?php    echo "Location 2";
  }
  else{
    echo "No option was selected. Please pick one";
  }

?>