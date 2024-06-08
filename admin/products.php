<?php

   include '../components/connect.php';

   session_start();

   $admin_id = $_SESSION['admin_id'];

   if(!isset($admin_id)){
      header('location:admin_login.php');
   };

   if(isset($_POST['add_product'])){

      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $category = $_POST['category'];
      $category = filter_var($category, FILTER_SANITIZE_STRING);
      $Qty = $_POST['Qty'];
      $Qty = filter_var($Qty, FILTER_SANITIZE_STRING);
      $Status = $_POST['Status'];
      $Status = filter_var($Status, FILTER_SANITIZE_STRING);

      $image = $_FILES['image']['name'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = '../uploaded_img/'.$image;

      $select_products = $conn->prepare("SELECT * FROM `menu` WHERE Name = ?");
      $select_products->execute([$name]);

      if($select_products->rowCount() > 0){
         $message[] = 'product name already exists!';
      }else{
         if($image_size > 2000000){
            $message[] = 'image size is too large';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);

            $insert_product = $conn->prepare("INSERT INTO `menu`(Name, Qty, Category, Price, Status, Image) VALUES(?,?,?,?,?,?)");
            $insert_product->execute([$name, $Qty, $category, $price, $Status, $image]);

            $message[] = 'new product added!';
         }

      }

   }

   if(isset($_GET['delete'])){

      $delete_id = $_GET['delete'];
      $delete_product_image = $conn->prepare("SELECT * FROM `menu` WHERE MenuID = ?");
      $delete_product_image->execute([$delete_id]);
      $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded_img/'.$fetch_delete_image['Image']);
      $delete_product = $conn->prepare("DELETE FROM `menu` WHERE MenuID = ?");
      $delete_product->execute([$delete_id]);
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE MenuID = ?");
      $delete_cart->execute([$delete_id]);
      header('location:products.php');

   }

?>

<!DOCTYPE html>

<html lang="en">

   <head>

      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>products</title>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

      <link rel="stylesheet" href="../css/admin_style.css">

   </head>

   <body>

      <?php include '../components/admin_header.php' ?>

      <section class="add-products">

         <form action="" method="POST" enctype="multipart/form-data">
            <h3>add product</h3>
            <input type="text" required placeholder="Enter product name" name="name" maxlength="100" class="box">
            <input type="number" min="0" max="9999999999" required placeholder="Enter the Quantity" name="Qty" onkeypress="if(this.value.length == 10) return false;" class="box">
            <input type="number" min="0" max="9999999999" required placeholder="Enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
            <select name="category" class="box" required>
               <option value="" disabled selected>Select category --</option>
               <option value="main dish">Main dish</option>
               <option value="fast food">Fast food</option>
               <option value="drinks">Drinks</option>
               <option value="desserts">Desserts</option>
            </select>
            <select name="Status" class="box" required>
               <option value="" disabled selected>Select status --</option>
               <option value="Available">Available</option>
               <option value="Not Available">Not Available</option>
            </select>
            <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
            <input type="submit" value="add product" name="add_product" class="btn">
         </form>

      </section>

      <section class="show-products" style="padding-top: 0;">

         <div class="box-container">

            <?php
               $show_products = $conn->prepare("SELECT * FROM `menu`");
               $show_products->execute();
               if($show_products->rowCount() > 0){
                  while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
            ?>

            <div class="box">
               <img src="../uploaded_img/<?= $fetch_products['Image']; ?>" alt="">
               <div class="flex">
                  <div class="price"><span>Rp </span><?= $fetch_products['Price']; ?><span>/-</span></div>
                  <div class="category"><?= $fetch_products['Category']; ?></div>
               </div>
               <div class="name"><?= $fetch_products['Name']; ?></div>
               <div class="Qty"><?= $fetch_products['Qty']; ?></div>
               <div class="flex-btn">
                  <a href="update_product.php?update=<?= $fetch_products['MenuID']; ?>" class="option-btn">update</a>
                  <a href="products.php?delete=<?= $fetch_products['MenuID']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
               </div>
               <div class="Status"><?= $fetch_products['Status']; ?></div>
            </div>
            
            <?php
                  }
               }else{
                  echo '<p class="empty">no products added yet!</p>';
               }
            ?>

         </div>

      </section>

      <script src="../js/admin_script.js"></script>

   </body>

</html>