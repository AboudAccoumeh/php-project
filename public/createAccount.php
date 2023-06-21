<?php
    include_once ('../private/conn.php');
    if(!isset($_SESSION)){
        session_start();
    }
    if(!isset($_SESSION["email"]) || $_SESSION["isAdmin"] == 0){
        header("location:home.php");
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = mysqli_real_escape_string($conn,$_POST['username']) ;
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $isAdmin = mysqli_real_escape_string($conn, $_POST['isAdmin']);
        if($isAdmin == "on"){
            $isAdmin = 1;
        }
        else{
            $isAdmin = 0;
        }
        $error = [];

        $check_user = mysqli_query($conn,"SELECT * FROM `users` WHERE email='$email'");
        if($check_user &&mysqli_num_rows($check_user) != 0){
            $errors[] = 'This email has already been used. use another email';
        }

        if(!$email){
            $errors[] = 'Email is required';
        } 


        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $errors[] = 'Enter validate email'; 
        }


        if (empty( $_FILES["picture"]["name"])) {
            $errors[] = 'Picture is required';
        }


        if(strlen($username) < 8){
            $errors[] = 'Username need to have a minimum of 8 letters';
        }

        if(strlen($password) < 8){
            $errors[] = 'Password need to have a minimum of 8 letters';
        }

        if(!isset($errors)){
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
                        $target = '../private/profile_picture/'.$image_new_name;
                        $sql = "INSERT INTO users (username,email,password,picture,isAdmin) VALUES ('$username','$email','$password','$image_new_name','$isAdmin')";
                        if(!empty($image)){
                            mysqli_query($conn,$sql);
                            if(move_uploaded_file($_FILES['picture']['tmp_name'],$target)){
                                header('location:home.php');
                            }
                        } 
                    }
                    else{
                        $errors[] = 'Your picture is to Big';
                    }
                }
            }
            else{
                $errors[] = 'png, jpg, jpeg, svg are the only supported extensions';
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account</title>
    <style type="text/css">
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }
        header{
            z-index:10000;
        }
        section{
            position: absolute;
            top:0;
            left: 0;
            display: flex;
            align-items:center;
            justify-content: center;
            width: 100vw;
            height: 100vh;
            background: #111;
            overflow: hidden;
            flex-wrap: wrap;
            gap:2px;
        }
        section:before,
        section::before{
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom,#000,orange,#000);
            animation: animate 5s linear infinite;
        }
        section span.squares{
            position: relative;
            display: block;
            width: calc(6.25vw - 2px);
            height: calc(6.25vw - 2px);
            background: #181818;
            z-index: 2;
            transition: background 1.5s linear,box-shadow 1.5s linear;
        }
        section span.squares:hover{
            background: orange;
            box-shadow: 0 0 20px orange,
                        0 0 40px orange;
            z-index:3;
            transition-duration: 0s;
        }
        section form{
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 40px;
            z-index: 1000;
            width:400px;
            background: #333;
            border-radius: 4px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
            gap:20px;
            padding: 25px;
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
        }
        section form ul:empty{
            padding: 0;
        }
        section form h2{
            color:orange;
            margin-bottom: 5px;
            font-size:2em;
            text-transform: uppercase;
        }
        section form .inputBox{
            position: relative;
            width: 100%;
        }
        section form .inputBox input{
            position: relative;
            width: 100%;
            color:#fff;
            background-color: #444  !important;
            border:none;
            outline: none;
            padding:25px 10px 7.5px;
            border-radius:4px;
            font-weight: 500;
            font-size: 1em;
        }
        input:-webkit-autofill{
            -webkit-text-fill-color: #444 !important;
        }
        section form .inputBox i{
            position: absolute;
            left:0;
            padding:15px 10px;
            font-style: normal;
            color:#aaa;
            transition: 0.5s;
            pointer-events: none;
        }
        section form .inputBox input:focus ~ i,
        section form .inputBox input:valid ~ i{
            transform: translateY(-7.5px);
            font-size: 0.8em;
            color:#fff;
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
        section form .inputBox input[type="checkbox"]{
            display: none;
        }
        section form .inputBox .customCheckbox{
            display: inline-block;
            width:40px;
            height:20px;
            background: #222;
            border-radius: 20px;
            margin-right:20px;
            box-shadow: 0 0 5px rgba(255,255,255,0.2);
            transition: 0.5s;
        }
        section form .inputBox input[type="checkbox"] + label .customCheckbox:before{
            content:'';
            width: 20px;
            height:20px;
            border-radius: 50%;
            position: absolute;
            top: 0;
            left: 0;
            transform: scale(1.1);
            box-shadow: 0 1px 2px rgba(255,255,255.2);
            background:#fff;
            transition: 0.5s;
        }
        section form .inputBox input[type="checkbox"]:checked + label .customCheckbox{
            background: orange;
        }
        section form .inputBox input[type="checkbox"]:checked + label .customCheckbox:before{
            left: 20px;
        }
        section form input[type="submit"]{
            width:100%;
            padding:10px;
            color:#222;
            background: orange;
            font-size: 1.25em;
            letter-spacing: 0.05em;
            cursor: pointer;
            border-radius: 4px;
            border: none;
            outline: none;
        }
        @keyframes animate{
            0%{
                transform: translateY(-100%);
            }
            100%{
                transform: translateY(100%);
            }
        }
        @media (max-width:900px){
            section span.squares{
                width:calc(10vw - 2px);
                height:calc(10vw - 2px);
            }
        }
        @media (max-width:600px){
            section span.squares{
                width:calc(20vw - 2px);
                height:calc(20vw - 2px);
            }
        }
    </style>
</head>
<body>
    <?php include ("header.php");?>
    <section id="container">
        <form action="" method="POST" enctype="multipart/form-data">
            <h2>Create Account</h2>
            <ul id="errors"><?php
                    if(isset($errors)){
                        foreach ($errors as $error) {
                            echo '<li>' . $error . '</li>';
                        }
                    }
                ?></ul>
            <div class="inputBox">
                <input id="username" type="text" name="username" required>
                <i>Username</i>
            </div>
            <div class="inputBox">
                <input id="email" type="text" name="email" required>
                <i>Email</i>
            </div>
            <div class="inputBox">
                <input id="password" type="password" name="password" required>
                <i>Password</i>
            </div>
            <div class="inputBox">
                <input id="file" type="file" name="picture" accept="image/png, image/jpeg, image/jpg, image/svg+xml">
                <label for="file"><p id="preview">Click here to upload an image or drop it anywhere.</p></label>
            </div>
            <div class="inputBox">
                <input id="checkbox" type="checkbox" name="isAdmin">
                <label for="checkbox" style="display: flex;align-items: center;color:#fff;cursor: pointer;">
                    <div class="customCheckbox"></div>
                    <span>Admin Privileges</span>
                </label>
            </div>
            <input id="submit" type="submit" value="Create Account">
        </form>
    </section>
    <script>
            
        var container = document.getElementById("container");
        var username = document.getElementById("username");
        var email = document.getElementById("email");
        var password = document.getElementById("password");
        var submit = document.getElementById("submit");
        var errors = document.getElementById("errors");
        var preview = document.getElementById("preview");
        var file = document.getElementById("file");

        for(var i=0;i<500;i++){
            var span = document.createElement("SPAN");
            span.classList.add("squares");
            container.insertBefore(span, container.children[0]);
        }
        submit.addEventListener('click', function(event){
            var usernameRegex = /^.{8,}$/;
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            var passwordRegex = /^.{8,}$/;
            errors.innerHTML = '';
            if(!usernameRegex.test(username.value)){
                // Prevent the default form submission behavior
                event.preventDefault();
                errors.innerHTML += '<li>The username should be at least 8 characters.</li>';
            }
            if (!emailRegex.test(email.value)) {
                // Prevent the default form submission behavior
                event.preventDefault();
                errors.innerHTML += '<li>Please write a valid email.</li>';
            }
            if(!passwordRegex.test(password.value)){
                // Prevent the default form submission behavior
                event.preventDefault();
                errors.innerHTML += '<li>The password should be at least 8 characters.</li>';
            }
            if (!file.files.length){
                event.preventDefault();
                errors.innerHTML += '<li>Please select an image (required).</li>';
            }
        });
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