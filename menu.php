<?php

   include 'components/connect.php';

   session_start();

   if(isset($_SESSION['user_id'])){
      $user_id = $_SESSION['user_id'];
   } else {
      $user_id = '';
   };

   include 'components/add_cart.php';

?>

<!DOCTYPE html>

<html lang="en">

   <head>

      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>menu</title>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

      <link rel="stylesheet" href="css/style.css">

   </head>

   <body>
      
      <?php include 'components/user_header.php'; ?>

      <div class="heading">
         <h3>our menu</h3>
         <p><a href="home.php">Home</a> <span> / Menu</span></p>
      </div>

      <section class="products">

         <h1 class="title">latest dishes</h1>

         <div class="box-container">

            <?php
               $select_products = $conn->prepare("SELECT * FROM `menu`");
               $select_products->execute();
               if($select_products->rowCount() > 0){
                  while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
            ?>

            <form action="" method="post" class="box">
               <input type="hidden" name="pid" value="<?= $fetch_products['MenuID']; ?>">
               <input type="hidden" name="name" value="<?= $fetch_products['Name']; ?>">
               <input type="hidden" name="price" value="<?= $fetch_products['Price']; ?>">
               <input type="hidden" name="image" value="<?= $fetch_products['Image']; ?>">
               <a href="quick_view.php?pid=<?= $fetch_products['MenuID']; ?>" class="fas fa-eye"></a>
               <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
               <img src="uploaded_img/<?= $fetch_products['Image']; ?>" alt="">
               <a href="category.php?category=<?= $fetch_products['Category']; ?>" class="cat"><?= $fetch_products['Category']; ?></a>
               <div class="name"><?= $fetch_products['Name']; ?></div>
               <div class="flex">
                  <div class="price"><span>Rp </span><?= $fetch_products['Price']; ?></div>
                  <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2"">
               </div>
            </form>

            <?php
                  }
               }else{
                  echo '<p class="empty">no products added yet!</p>';
               }
            ?>

         </div>

      </section>

      <?php include 'components/footer.php'; ?>

      <script src="js/script.js"></script>

   </body>

</html>