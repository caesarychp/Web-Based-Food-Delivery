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
      <title>profile</title>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

      <link rel="stylesheet" href="css/style.css">

   </head>

   <body>
      
      <?php include 'components/user_header.php'; ?>

      <section class="user-details">

         <div class="user">

            <?php
               
            ?>

            <img src="images/user-icon.png" alt="">
            <p><i class="fas fa-user"></i><span><span><?= $fetch_profile['Name']; ?></span></span></p>
            <p><i class="fas fa-phone"></i><span><?= $fetch_profile['Number']; ?></span></p>
            <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['Email']; ?></span></p>
            <a href="update_profile.php" class="btn">Update Info</a>
            <p class="address"><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_profile['Address'] == ''){echo 'Please enter your address';}else{echo $fetch_profile['Address'];} ?></span></p>
            <a href="update_address.php" class="btn">Update Address</a>

         </div>

      </section>

      <?php include 'components/footer.php'; ?>

      <script src="js/script.js"></script>

   </body>

</html>