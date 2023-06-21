<?php 
if(!isset($_SESSION)){
    session_start();
}
include_once ('../private/conn.php');
if(!isset($_SESSION["email"])){
    header("location:home.php");
}
$id = $_SESSION['id'];
$errors = [];
if(isset($_POST['submit'])){
    $text = mysqli_real_escape_string($conn,$_POST['text']) ;

if(!$text){
    $errors[] = 'Text is required';
} 
if (empty( $_FILES["picture"]["name"])) {
    $errors[] = 'Picture is required';
} 

if(strlen($text) < 10){
    $errors[] = 'Text need to have a minimum of 10 letters';
}
if(empty($errors)){
    $image = $_FILES['picture']['name'];
    $image_size = $_FILES['picture']['size'];
    $image_error = $_FILES['picture']['error'];
    $file = explode('.',$image);
    $fileActual = strtolower(end($file));
    $allowed = array('png','jpg','jpge','svg');
    if(in_array($fileActual,$allowed)){
        if($image_error === 0){
            if($image_size < 4000000){
                    $image_new_name = uniqid('',true).'-'.$image;
                    $target = '../private/posts_picture/'.$image_new_name;
                    $create_at = date('Y-m-d H:i:s');
                    $sql = "INSERT INTO posts (text,picture,user_id,create_at) VALUES ('$text','$image_new_name','$id','$create_at')";
                    if(!empty($image)){
                        mysqli_query($conn,$sql);
                        if(move_uploaded_file($_FILES['picture']['tmp_name'],$target)){
                            header('location:home.php');
                        }
                    } 
                }else{
                $errors[] = 'Your picture is too Big';
            }
    
            }
    
        }else{
            $errors[] = 'png, jpg, jpeg, svg are the only supported extensions';
        }
    }
}
if(isset($_POST['get_id'])){
    $get_id = $_POST['get_id'];
}

if(isset($_POST['comment'])){
    $comment_text = $_POST['comment_text'];

if(!isset($comment_text) || $comment_text == ""){
    exit();
}

$create_at = date('Y-m-d H:i:s');
$sql = "INSERT INTO comments (text,created_at,user_id,post_id) 
VALUES ('$comment_text','$create_at','$id','$get_id')";
mysqli_query($conn,$sql);
echo '<meta http-equiv="Refresh" content="0; url=home.php">';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <style type="text/css">
        body{
            box-sizing: border-box;
            padding: 20px;
            padding-top: 120px;
        }
        .app{
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }
        .posts{
            position: absolute;
            left: 5%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .upload{
            position: fixed;
            right:5%;
            width: 30%;
        }
        .post {
          background-color: #333;
          border: 1px solid #222;
          border-radius: 10px;
          padding: 10px;
          margin-bottom: 20px;
          width: 400px;
          box-sizing: border-box;
          padding: 20px;
        }

        .post-header {
          display: flex;
          align-items: center;
        }

        .post-avatar {
          width: 40px;
          height: 40px;
          border-radius: 50%;
        }

        .post-info {
          margin-left: 10px;
        }

        .post-username {
          font-weight: bold;
          margin: 0;
          color: orange;
        }

        .post-date {
          font-size: 12px;
          color: grey;
          margin: 0;
        }

        .post-description {
          margin-bottom: 10px;
          color:#fff;
        }

        .post-image {
          display: block;
          width: 100%;
          height: auto;
          margin-bottom: 10px;
        }

        .comment-toggle {
          background-color: #333;
          color: #fff;
          border: none;
          border-radius: 4px;
          margin-bottom: 10px;
          cursor: pointer;
          margin-bottom: 10px;
        }
        .comment-toggle:focus,
        .comment-toggle:focus{
            outline: none;
        }
        .comment-toggle .arrow{
            display: inline-block;
            transform: rotate(-90deg);
            margin-right: 5px;
            transition: 0.5s;
            font-weight: 900;
        }
        .comment-toggle.active .arrow{
            transform: rotate(0deg);
        }

        .comment-toggle + .comment-section {
          width: 100%;
          background-color: #222;
          overflow: hidden;
          margin-bottom: 15px;
          border-radius: 8px;
          box-sizing: border-box;
          max-height: 0;
          transition:all 0.15s ease-in;
        }
        .comment-toggle.active + .comment-section {
          height:auto;
          padding: 10px;
          max-height: 300px;
          transition:all 0.25s ease-in;
          overflow: auto;
        }
        .comment-section .comment{
            background: orange;
            box-sizing: border-box  !important;
            border-radius: 10px;
            padding: 5px;
            margin: 10px 0;
            width: 100%;
            color:#fff;
        }
        .comment-textarea {
          width: 100%;
          padding: 5px;
          margin-bottom: 5px;
          border:none;
          background-color: #222;
          resize: none;
          height:60px;
          outline: none;
          box-sizing: border-box;
          padding: 10px;
          color:#fff;
          border-radius: 8px;
        }
        .send-comment{
            padding:5px 10px;
            font-size: 1.1em;
            background-color: orange;
            color:#fff;
            border: none;
            outline: none;
            margin-top: 8px;
            border-radius:4px;
        }
        section form{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        section form .inputBox input[type="file"]{
            display: none;
        }
        section form .inputBox p{
            display: flex;
            align-items: center;
            color:#fff;
            padding: 8px;
            outline:2px grey dashed;
            border-radius: 4px;
            cursor: pointer;
            user-select: none;
            font-size:0.9em;
            overflow: hidden;
        }
        section form .inputBox p img{
            width:30px;
            height: 30px;
            border-radius: 50%;
            margin-right:20px;
        }
        section form .inputBox p span{
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        section form ul{
            display: block;
            margin: 0;
            background: #c00;
            color: #fff;
            width: 100%;
            border-radius:4px;
            list-style-position: inside;
            list-style-type: square;
            padding: 10px;
            font-size:0.9em;
            margin-bottom: 10px;
        }
        section form ul:empty{
            padding: 0;
        }
        h1,h2,h3,span{
            color:crimson;
        }
        @media(max-width:1000px){
            body{
                padding: 10px;
                padding-top: 120px;
            }
        }
        .user-posts h3{
            color:#fff;
            text-align: center;
            text-align-last: center;
        }
        @media(max-width:800px){
            .app{
                flex-direction: column;
                align-items: center;
            }
            .posts,.upload{
                position: static;
            }
            .upload{
                width: 80%;
                max-width: 350px;
                order: 1;
                margin-bottom: 20px;
            }
            .posts{
                width: 100%;
                order:2;
            }
        }
    </style>
</head>
<body>
<div class="app">
<?php include("header.php")?>
<section class="posts">
<?php
// Fetch posts from the database
$sql = "SELECT * FROM posts";
$result = $conn->query($sql);
// Check if there are any posts
if ($result->num_rows > 0) {
    // Loop through each post and display them
    while ($row = $result->fetch_assoc()) {
        $postId = $row['post_id'];
        $userId = $row['user_id'];
        $result2 = mysqli_query($conn,"SELECT * FROM users WHERE id=$userId");
        $row2 = $result2->fetch_assoc();

        echo '<div class="post">';
        echo    '<div class="post-header">';
        echo        '<img class="post-avatar" src="../private/profile_picture/' . $row2["picture"] . '" alt="User Avatar">';
        echo        '<div class="post-info">';
        echo            '<p class="post-username">' . $row2['username'] . '</p>';
        echo            '<p class="post-date">' . $row['create_at'] . '</p>';
        echo        '</div>';
        echo    '</div>';
        echo    '<div class="post-description">';
        echo        '<p>' . $row['text'] . '</p>';
        echo      '</div>';
        echo      '<img class="post-image" src="../private/posts_picture/' . $row['picture'] . '" alt="Post Image">';
        echo      '<button class="comment-toggle"><span class="arrow">▼</span> <span>Show Comments</span></button>';
        echo       '<div class="comment-section">';
        
        // Fetch comments for the current post
        $commentSql = "SELECT * FROM comments WHERE post_id = $postId";
        $commentResult = $conn->query($commentSql);

        // Check if there are any comments
        if ($commentResult->num_rows > 0) {
            // Loop through each comment and display them
            while ($commentRow = $commentResult->fetch_assoc()) {
                $commentUserId = $commentRow["user_id"];
                $result3 = mysqli_query($conn,"SELECT * FROM users WHERE id=$commentUserId");
                $row3 = $result3->fetch_assoc();
                echo '<div class="comment">';
                echo '<div class="post-header" style="margin-bottom:10px">';
                echo '<img class="post-avatar" src="../private/profile_picture/' . $row3["picture"] . '" alt="User Avatar">';
                echo '<div class="post-info">';
                echo '<p class="post-username" style="color:#fff;">' . $row3['username'] . '</p>';
                echo '<p class="post-date">' . $commentRow['created_at'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '<div>' . $commentRow['text'] . '</div>';
                echo '</div>';
            }
        }else{
            echo '<h3>No comments found.</h3>';
        }

        echo    '</div>';
        echo    '<form action="" method="POST">';
        echo    '<input type="hidden" name="get_id" value="'. $postId .'">';
        echo    '<textarea name="comment_text" class="comment-textarea" placeholder="Write a comment"></textarea>';
        echo    '<input name="comment" type="submit" class="send-comment" value="Send Comment">';
        echo    '</form>';
        echo    '</div>';
    }
} else {
    echo '<h3>No posts found.</h3>';
}
?>
</section>
<section class="upload">
    <form action="" method="POST" enctype="multipart/form-data">
        <h1>Add a post</h1>
        <ul id="errors"><?php
            if(isset($errors)){
                foreach ($errors as $error) {
                    echo '<li>' . $error . '</li>';
                }
            }
        ?></ul>
        <textarea style="background-color:#333;" name="text" class="comment-textarea" placeholder="Write your post description"></textarea>
        <div class="inputBox">
            <input id="file" type="file" name="picture" accept="image/png, image/jpeg, image/jpg, image/svg+xml">
            <label for="file"><p id="preview">Click here to upload an image or drop it anywhere.</p></label>
        </div>
        <input name="submit" type="submit" class="send-comment" value="Post">
    </form>
</section>
</div>
<script type="text/javascript">
    var commentToggle = document.getElementsByClassName("comment-toggle");
    var file = document.getElementById("file");
    for(let i=0;i<commentToggle.length;i++){
        commentToggle[i].addEventListener("click",function(){
            commentToggle[i].classList.toggle("active");
            commentToggle[i].children[1].innerText = ["Show Comments", "Hide Comments"][commentToggle[i].classList.contains("active") ? 1 : 0];

        });
    }
    file.addEventListener("change", function(event) {
        var selectedFile = event.target.files[0];
        if (selectedFile) {
            displayImage(selectedFile);
        }
    });
    function displayImage(selectedFile){
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = "<img src='" + e.target.result + "'><span>" + selectedFile.name + "</span>";
        }
        reader.readAsDataURL(selectedFile);
    }

    // Prevent default drag behaviors
    window.addEventListener('dragenter', preventDefault, false);
    window.addEventListener('dragover', preventDefault, false);
    window.addEventListener('drop', preventDefault, false);

    // Handle dropped files
    window.addEventListener('drop', handleDrop, false);

    function preventDefault(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        var dt = e.dataTransfer;
        var theFile = dt.files[0];
        var allowedExtensions = ['png', 'jpg', 'jpeg', 'svg'];
        var fileExtension = theFile.name.split('.').pop().toLowerCase();
        if(allowedExtensions.includes(fileExtension)){
            var dataTransfer = new DataTransfer();
            dataTransfer.items.add(theFile);
            file.files = dataTransfer.files;
            displayImage(file.files[0]);
        }
    }

</script>
</body>
</html>