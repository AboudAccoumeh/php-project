<?php
include_once('../private/conn.php');
  if(!isset($_SESSION)){ 
        session_start(); 
  }
  $currentFileName = basename($_SERVER['PHP_SELF'],".php");
  $user_info = mysqli_query($conn, "SELECT * FROM `users` WHERE email='" . $_SESSION['email'] . "'");
  $data = mysqli_fetch_array($user_info)
?>
<style type="text/css">
  *{
    font-family:sans-serif;
  }
  body{
    background: #222;
  }
  header{
    position:fixed;
    top:5px;
    left:5px;
    width:calc( 100% - 10px );
    box-sizing: border-box;
    padding: 10px;
    background-color: #333333;
    z-index:1000000000;
    box-shadow: 0 0 3px #fff;
  }
  .logo{
    float:left;
    font-size:1.8rem;
    font-weight: 700;
    color:orange;
    text-decoration: none;
    z-index: 101;
  }
  nav,
  .toggle{
    float:right;
  }
  .clear{
    clear:both;
  }
  nav{
    margin-right:30px;
  }
  nav ul{
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
  }
  nav ul li{
    margin:0 20px;
  }
  nav ul li a{
    text-decoration: none;
    font-weight: bold;
    font-size:1.8rem;
    color:#ddd;
    cursor: pointer;
    transition: all 300ms linear;
  }
  nav ul li a.active,
  nav ul li a:hover{
    color:orange;
    text-shadow: 0 0 5px orange;
  }
  .toggle{
    display: none;
    flex-direction: column;
    align-items:center;
    justify-content: space-between;
    width:40px;
    height:25px;
    margin-top:4px;
    cursor: pointer;
  }
  .toggle .bar{
    display: block;
    width:100%;
    height:4px;
    background: #ddd;
    border-radius:20px;
    transition:all 300ms linear;
  }
  .toggle:hover .bar{
    background: orange;
    box-shadow: 0 0 10px orange;
  }
  .toggle.active .bar1{
    transform:translateY(-3px) rotate(45deg);
    transform-origin: left center;
  }
  .toggle.active .bar2{
    transform: translate(20px);
    opacity: 0;
  }
  .toggle.active .bar3{
    transform:translateY(3px) rotate(-45deg);
    transform-origin: left center;
  }

  header .profileImg{
    width:30px;
    height:30px;
    border-radius: 50%;
    margin:0 10px;
    transition: 500ms;
  }
  header .userProfile{
    display: flex;
    align-items: center;
    color:#fff;
    text-decoration: none;
  }
  header .userProfile:hover{
    color:orange;
    text-shadow: 0 0 5px orange;
  }
  header .userProfile:hover img{
    transform: scale(1.2);
  }
  @media(max-width:1100px){
    header{
      padding: 10px 5px;
    }
    header nav{
      margin: 0;
    }
    header nav li{
      margin: 0 12px;
    }
  }
    @media(max-width:900px){
    header{
      padding: 20px;
    }
    header nav{
      z-index:-1;
      opacity: 0;
      margin-top:-20px;
      position: absolute;
      top:-500px;
    }
    .toggle{
      display: flex;
    }
    .toggle.active + nav ul,  
    .toggle.active + nav{
      margin: 0;
      display: block;
      width: 100%;
    }
    .toggle.active + nav{
      position: static;
      margin-top:20px;
      opacity: 1;
      z-index: 100;
      border-top:1px solid #222;
      transition: margin-top 600ms ease-in-out, opacity 600ms ease-in-out;
    }
    .toggle.active + nav ul li {
      margin:20px 0;
    }
    header .profileImg{
      margin: 0;
      margin-right:20px;
    }
  }
</style>
<header>
  <a href="#" class="logo">Logo</a>
  <div class="toggle">
    <span class="bar bar1"></span>
    <span class="bar bar2"></span>
    <span class="bar bar3"></span>
  </div>
  <nav>
    <ul>
      <li><a href="home.php" class="<?php if($currentFileName === 'home')echo 'active';?>">Home</a></li>
      <li><a href="profile.php" class="<?php if($currentFileName === 'profile')echo 'active';?>">Profile</a></li>
      <?php if(!isset($_SESSION['email'])): ?><li><a href="login.php" class="<?php if($currentFileName === 'login')echo 'active';?>">Login</a></li><?php endif?>
      <?php if(isset($_SESSION['email']) && $_SESSION['isAdmin']==1):?><li><a href="createAccount.php" class="<?php if($currentFileName === 'createAccount')echo 'active';?>">Create Account</a></li><?php endif?>
      <?php if(isset($_SESSION['email'])):?><li><a href="logout.php" class="<?php if($currentFileName === 'logout')echo 'active';?>">Logout</a></li><?php endif ?>
      <a href="profile.php" class="userProfile">
        <?php echo "<img class='profileImg' src='../private/profile_picture/" . $data['picture'] . "'>"; ?>
        <?php echo $data['username']; ?>
      </a>
    </ul>
  </nav>
  <div class="clear"></div>
</header>
<script type="text/javascript">
  var toggle = document.getElementsByClassName("toggle")[0];
  toggle.addEventListener("click",function(){
    this.classList.toggle("active");
  });
</script>