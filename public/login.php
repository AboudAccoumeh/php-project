<?php
if(!isset($_SESSION)){
    session_start();
}
if(isset($_SESSION["email"])){
    header("location:home.php");
}
include_once ('../private/conn.php');

$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if(!$email){
        $errors[] = 'Email is required';
    }
    if(!$password){
        $errors[] = 'Password is required';
    }
    if(empty($errors)){
        $sql = "SELECT * FROM `users` WHERE email='$email' AND password='$password' limit 1";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        $num_rows = mysqli_num_rows($result);
        if($num_rows != 0){
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['picture'] = $row['picture'];
            $_SESSION['isAdmin'] = $row['isAdmin'];
            header('location:login.php');
        }else{
            $errors[] = 'Wrong Password or Email';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <style type="text/css">
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
        }
        section{
            position: absolute;
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
            margin-bottom: 20px;
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
        section form span{
            display: block;
            text-align: center;
            font-size: 0.9em;
            color:#fff;
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
    <section id="container">
        <form action="" method="POST">
            <h2>Sign in</h2>
            <ul id="errors"><?php
                    if(isset($errors)){
                        foreach ($errors as $error) {
                            echo '<li>' . $error . '</li>';
                        }
                    }
                ?></ul>
            <div class="inputBox">
                <input id="email" type="text" name="email" required>
                <i>Email</i>
            </div>
            <div class="inputBox">
                <input id="password" type="password" name="password" required>
                <i>Password</i>
            </div>
            <input id="submit" type="submit" value="Login">
            <span>Dont have an account? Please contact the admin</span>
        </form>
    </section>
    <script>
        
        var container = document.getElementById("container");
        for(var i=0;i<500;i++){
            var span = document.createElement("SPAN");
            span.classList.add("squares");
            container.insertBefore(span, container.children[0]);
        }
    </script>
</body>
</html>

