<?php

   include 'components/connect.php';

   session_start();

   if(isset($_SESSION['user_id'])){
      $user_id = $_SESSION['user_id'];
   } else {
      $user_id = '';
      header('location:home.php');
   };

?>

<!DOCTYPE html>

<html lang="en">

   <head>

      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>orders</title>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

      <link rel="stylesheet" href="css/style.css">

   </head>

   <body>
      
      <?php include 'components/user_header.php'; ?>

      <div class="heading">
         <h3>orders</h3>
         <p><a href="html.php">home</a> <span> / orders</span></p>
      </div>

      <section class="orders">

         <h1 class="title">your orders</h1>

         <div class="box-container">

         <?php
            if($user_id == ''){
               echo '<p class="empty">please login to see your orders</p>';
            }else{
               $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE CustomerID = ?");
               $select_orders->execute([$user_id]);
               if($select_orders->rowCount() > 0){
                  while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
         ?>

         <div class="box">
            <p>Placed on : <span><?= $fetch_orders['Date']; ?></span></p>
            <p>Name : <span><?= $fetch_orders['Name']; ?></span></p>
            <p>Email : <span><?= $fetch_orders['Email']; ?></span></p>
            <p>Number : <span><?= $fetch_orders['Number']; ?></span></p>
            <p>Address : <span><?= $fetch_orders['Address']; ?></span></p>
            <p>Payment method : <span><?= $fetch_orders['PaymentMethod']; ?></span></p>
            <p>Your orders : <span><?= $fetch_orders['TotalProducts']; ?></span></p>
            <p>Total price : <span>$<?= $fetch_orders['TotalPrice']; ?>/-</span></p>
            <p>Payment status : <span style="color:<?php if($fetch_orders['PaymentStatus'] == 'Pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['PaymentStatus']; ?></span> </p>
            <p>Order status : <span style="color:<?php if($fetch_orders['OrderStatus'] == 'Ordered'){ echo 'blue'; }else if($fetch_orders['OrderStatus'] == 'Cooked'){ echo 'red'; }else if($fetch_orders['OrderStatus'] == 'Find Delivery'){ echo 'yellow'; }else if($fetch_orders['OrderStatus'] == 'Delivering'){ echo 'gray'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['OrderStatus']; ?></span> </p>
         </div>

         <?php
            }
            } else {
               echo '<p class="empty">no orders placed yet!</p>';
            }
            }
         ?>

         </div>

      </section>

      <?php include 'components/footer.php'; ?>

      <script src="js/script.js"></script>

   </body>

</html>