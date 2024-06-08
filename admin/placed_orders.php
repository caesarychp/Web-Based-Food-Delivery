<?php

   include '../components/connect.php';

   session_start();

   $admin_id = $_SESSION['admin_id'];

   if(!isset($admin_id)){
      header('location:admin_login.php');
   };

   if(isset($_POST['update'])){

      $order_id = $_POST['order_id'];
      $payment_status = $_POST['payment_status'];
      $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
      $update_status = $conn->prepare("UPDATE `orders` SET PaymentStatus = ?  WHERE OrderID = ?");
      $update_status->execute([$payment_status, $order_id]);
      $message[] = 'Status updated!';

   }

   else if(isset($_POST['update'])){

      $order_id = $_POST['order_id'];
      $order_status = $_POST['order_status'];
      $order_status = filter_var($order_status, FILTER_SANITIZE_STRING);
      $update_status = $conn->prepare("UPDATE `orders` SET OrderStatus = ?  WHERE OrderID = ?");
      $update_status->execute([$order_status, $order_id]);
      $message[] = 'Status updated!';

   }

   if(isset($_GET['delete'])){
      $delete_id = $_GET['delete'];
      $delete_order = $conn->prepare("DELETE FROM `orders` WHERE OrderID = ?");
      $delete_order->execute([$delete_id]);
      header('location:placed_orders.php');
   }

?>

<!DOCTYPE html>

<html lang="en">

   <head>

      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>placed orders</title>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

      <link rel="stylesheet" href="../css/admin_style.css">

   </head>

   <body>

      <?php include '../components/admin_header.php' ?>

      <section class="placed-orders">

         <h1 class="heading">placed orders</h1>

         <div class="box-container">

            <?php
               $select_orders = $conn->prepare("SELECT * FROM `orders`");
               $select_orders->execute();
               if($select_orders->rowCount() > 0){
                  while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
            ?>

            <div class="box">
               <p> User id : <span><?= $fetch_orders['CustomerID']; ?></span> </p>
               <p> Placed on : <span><?= $fetch_orders['Date']; ?></span> </p>
               <p> Name : <span><?= $fetch_orders['Name']; ?></span> </p>
               <p> Email : <span><?= $fetch_orders['Email']; ?></span> </p>
               <p> Number : <span><?= $fetch_orders['Number']; ?></span> </p>
               <p> Address : <span><?= $fetch_orders['Address']; ?></span> </p>
               <p> Total products : <span><?= $fetch_orders['TotalProducts']; ?></span> </p>
               <p> Total price : <span>$<?= $fetch_orders['TotalPrice']; ?>/-</span> </p>
               <p> Payment method : <span><?= $fetch_orders['PaymentMethod']; ?></span> </p>
               <form action="" method="POST">
                  <input type="hidden" name="order_id" value="<?= $fetch_orders['OrderID']; ?>">
                  <select name="payment_status" class="drop-down">
                     <option value="" selected disabled><?= $fetch_orders['PaymentStatus']; ?></option>
                     <option value="Pending">Pending</option>
                     <option value="Completed">Completed</option>
                  </select>
                  <select name="order_status" class="drop-down">
                     <option value="" selected disabled><?= $fetch_orders['OrderStatus']; ?></option>
                     <option value="Ordered">Ordered</option>
                     <option value="Cooked">Cooked</option>
                     <option value="Find Delivery">Find Delivery</option>
                     <option value="Delivering">Delivering</option>
                     <option value="Thank You">Thank You</option>
                  </select>
                  <div class="flex-btn">
                     <input type="submit" value="update" class="btn" name="update">
                     <a href="placed_orders.php?delete=<?= $fetch_orders['OrderID']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
                  </div>
               </form>
            </div>

            <?php
                  }
               }else{
                  echo '<p class="empty">no orders placed yet!</p>';
               }
            ?>

         </div>

      </section>

      <script src="../js/admin_script.js"></script>

   </body>

</html>