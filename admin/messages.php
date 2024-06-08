<?php

   include '../components/connect.php';

   session_start();

   $admin_id = $_SESSION['admin_id'];

   if(!isset($admin_id)){
      header('location:admin_login.php');
   }

   if(isset($_GET['delete'])){
      $delete_id = $_GET['delete'];
      $delete_message = $conn->prepare("DELETE FROM `messages` WHERE MessagesID = ?");
      $delete_message->execute([$delete_id]);
      header('location:messages.php');
   }

?>

<!DOCTYPE html>

<html lang="en">

   <head>
      
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>messages</title>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

      <link rel="stylesheet" href="../css/admin_style.css">

   </head>

   <body>

      <?php include '../components/admin_header.php' ?>

      <section class="messages">

         <h1 class="heading">messages</h1>

         <div class="box-container">

            <?php
               $select_messages = $conn->prepare("SELECT * FROM `messages`");
               $select_messages->execute();
               if($select_messages->rowCount() > 0){
                  while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
            ?>

            <div class="box">
               <p> Name : <span><?= $fetch_messages['Name']; ?></span> </p>
               <p> Number : <span><?= $fetch_messages['Number']; ?></span> </p>
               <p> Email : <span><?= $fetch_messages['Email']; ?></span> </p>
               <p> Message : <span><?= $fetch_messages['Message']; ?></span> </p>
               <a href="messages.php?delete=<?= $fetch_messages['MessagesID']; ?>" class="delete-btn" onclick="return confirm('delete this message?');">Delete</a>
            </div>

            <?php
                  }
               }else{
                  echo '<p class="empty">you have no messages</p>';
               }
            ?>

         </div>

      </section>

      <script src="../js/admin_script.js"></script>

   </body>

</html>