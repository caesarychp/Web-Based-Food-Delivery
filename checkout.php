<?php

   include 'components/connect.php';

   session_start();

   if(isset($_SESSION['user_id'])){
      $user_id = $_SESSION['user_id'];
   } else {
      $user_id = '';
      header('location:home.php');
   };

   if(isset($_POST['submit'])){

      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $number = $_POST['number'];
      $number = filter_var($number, FILTER_SANITIZE_STRING);
      $email = $_POST['email'];
      $email = filter_var($email, FILTER_SANITIZE_STRING);
      $method = $_POST['method'];
      $method = filter_var($method, FILTER_SANITIZE_STRING);
      $address = $_POST['address'];
      $address = filter_var($address, FILTER_SANITIZE_STRING);
      $total_products = $_POST['total_products'];
      $total_price = $_POST['total_price'];

      $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE CustomerID = ?");
      $check_cart->execute([$user_id]);

      if($check_cart->rowCount() > 0){

         if($address == ''){
            $message[] = 'please add your address!';
         }else{
            
            $insert_order = $conn->prepare("INSERT INTO `orders`(CustomerID, Name, Number, Email, PaymentMethod, Address, TotalProducts, TotalPrice) VALUES(?,?,?,?,?,?,?,?)");
            $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE CustomerID = ?");
            $delete_cart->execute([$user_id]);

            $message[] = 'order placed successfully!';
         }
         
      } else {
         $message[] = 'your cart is empty';
      }

   }

?>

<!DOCTYPE html>

<html lang="en">

   <head>

      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>checkout</title>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

      <link rel="stylesheet" href="css/style.css">

   </head>

   <body>
      
      <?php include 'components/user_header.php'; ?>

      <div class="heading">
         <h3>checkout</h3>
         <p><a href="home.php">Home</a> <span> / Checkout</span></p>
      </div>

      <section class="checkout">

         <h1 class="title">order summary</h1>

      <form action="" method="post">

         <div class="cart-items">

            <h3>cart items</h3>

            <?php
               $grand_total = 0;
               $cart_items[] = '';
               $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE CustomerID = ?");
               $select_cart->execute([$user_id]);
               if($select_cart->rowCount() > 0){
                  while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                     $cart_items[] = $fetch_cart['Name'].' ('.$fetch_cart['Price'].' x '. $fetch_cart['Quantity'].') - ';
                     $total_products = implode($cart_items);
                     $grand_total += ($fetch_cart['Price'] * $fetch_cart['Quantity']);
            ?>

            <p><span class="name"><?= $fetch_cart['Name']; ?></span><span class="price">$<?= $fetch_cart['Price']; ?> x <?= $fetch_cart['Quantity']; ?></span></p>
            
            <?php
                  }
               }else{
                  echo '<p class="empty">your cart is empty!</p>';
               }
            ?>

            <p class="grand-total"><span class="name">grand total :</span><span class="price">$<?= $grand_total; ?></span></p>
            
            <a href="cart.php" class="btn">veiw cart</a>

         </div>

         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
         <input type="hidden" name="name" value="<?= $fetch_profile['Name'] ?>">
         <input type="hidden" name="number" value="<?= $fetch_profile['Number'] ?>">
         <input type="hidden" name="email" value="<?= $fetch_profile['Email'] ?>">
         <input type="hidden" name="address" value="<?= $fetch_profile['Address'] ?>">

         <div class="user-info">

            <h3>your info</h3>

            <p><i class="fas fa-user"></i><span><?= $fetch_profile['Name'] ?></span></p>
            <p><i class="fas fa-phone"></i><span><?= $fetch_profile['Number'] ?></span></p>
            <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['Email'] ?></span></p>
            
            <a href="update_profile.php" class="btn">update info</a>
            
            <h3>delivery address</h3>
            
            <p><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_profile['Address'] == ''){echo 'please enter your address';}else{echo $fetch_profile['Address'];} ?></span></p>
            
            <a href="update_address.php" class="btn">update address</a>
            
            <select name="method" class="box" required>
               <option value="" disabled selected>select payment method --</option>
               <option value="Cash on delivery">cash on delivery</option>
               <option value="Credit card">Credit card</option>
               <option value="OVO">OVO</option>
               <option value="Dana">Dana</option>
               <option value="Gopay">Gopay</option>
            </select>

            
            <input type="submit" value="place order" class="btn <?php if($fetch_profile['Address'] == ''){echo 'disabled';} ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit">
         </div>

      </form>
         
      </section>

      <?php include 'components/footer.php'; ?>

      <script src="js/script.js"></script>

   </body>

</html>