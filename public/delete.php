<?php 
session_start();
include_once ('../private/conn.php');
if(isset($_SESSION['email']) && isset($_GET['id'])){
    $id_post = $_GET['id'];
    $sql = "DELETE FROM `comments` WHERE `comments`.post_id='$id_post'";
    mysqli_query($conn,$sql);
    $sql = "DELETE FROM `posts` WHERE `posts`.post_id='$id_post'";
    mysqli_query($conn,$sql);
    header('location:profile.php');
}else{
    header('location:index.php');
}


