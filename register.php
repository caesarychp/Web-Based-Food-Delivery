<?php

   include 'components/connect.php';

   session_start();

   if(isset($_SESSION['user_id'])){
      $user_id = $_SESSION['user_id'];
   } else {
      $user_id = '';
   };

   if(isset($_POST['submit'])){

      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $email = $_POST['email'];
      $email = filter_var($email, FILTER_SANITIZE_STRING);
      $number = $_POST['number'];
      $number = filter_var($number, FILTER_SANITIZE_STRING);
      $address = $_POST['address'];
      $address = filter_var($address, FILTER_SANITIZE_STRING);
      $pass = sha1($_POST['pass']);
      $pass = filter_var($pass, FILTER_SANITIZE_STRING);
      $cpass = sha1($_POST['cpass']);
      $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

      $select_user = $conn->prepare("SELECT * FROM `customer` WHERE Email = ? OR Number = ?");
      $select_user->execute([$email, $number]);
      $row = $select_user->fetch(PDO::FETCH_ASSOC);

      if($select_user->rowCount() > 0){
         $message[] = 'email or number already exists!';
      } else {
         if($pass != $cpass){
            $message[] = 'confirm password not matched!';
         } else {
            $insert_user = $conn->prepare("INSERT INTO `customer`(Name, Email, Number, Address, Password) VALUES(?,?,?,?,?)");
            $insert_user->execute([$name, $email, $number, $address, $cpass]);
            $select_user = $conn->prepare("SELECT * FROM `customer` WHERE Email = ? AND Password = ?");
            $select_user->execute([$email, $pass]);
            $row = $select_user->fetch(PDO::FETCH_ASSOC);
            if($select_user->rowCount() > 0){
               $_SESSION['user_id'] = $row['CustomerID'];
               header('location:home.php');
            }
         }
      }

   }

?>

<!DOCTYPE html>

<html lang="en">

   <head>

      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>register</title>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

      <link rel="stylesheet" href="css/style.css">

   </head>

   <body>
      
      <?php include 'components/user_header.php'; ?>

      <section class="form-container">

         <form action="" method="post">
            <h3>Register now</h3>
            <input type="text" name="name" required placeholder="Enter your name" class="box" maxlength="50">
            <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="number" name="number" required placeholder="Enter your number" class="box" min="0" max="9999999999" maxlength="10">
            <input type="text" name="address" required placeholder="Enter your address" class="box" maxlength="1000" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="pass" required placeholder="Enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="cpass" required placeholder="Confirm your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="register now" name="submit" class="btn">
            <p>Already have an account? <a href="login.php">Login now</a></p>
         </form>

      </section>

      <?php include 'components/footer.php'; ?>

      <script src="js/script.js"></script>

   </body>

</html>