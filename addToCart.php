<?php
include('includes/session.php');
//session_start();
require_once 'includes/class.user.php';
$user_home = new USER();


if ($user_home->is_logged_in()) {
  $stmt = $user_home->runQuery("SELECT * FROM users WHERE userID=:uid");
  $stmt->execute(array(":uid" => $_SESSION['userSession']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $userId = $row['userID'];

  // get order id
  $order = $session->getOrderId($userId);
  //var_dump($order);

  if ($order == null) {
    // create new empty order
    try {
      $session->createCart($userId);
    } catch (Exception $e) {
      http_response_code(500);
      echo $e->getMessage();
      exit();
    }

    $order = $session->getOrderId($userId);
    //var_dump($order);
  }

  if (is_array($order)) {
    if (isset($_POST['Product_Id'])) {
      try {
        $productPrice = $session->getProductPrice($_POST['Product_Id'],$_POST['Size']);
        $totalPrice = $productPrice['price'] * $_POST['Quantity'];;
        $session->addProductToOrder($order['OrderId'], $_POST['Size'], $_POST['Product_Id'], $_POST['Quantity'], $totalPrice);
      } catch (Exception $e) {
        http_response_code(500);
        echo $e->getMessage();
      }

      echo 'Added Product to Cart';
    }
  }
}

?>

