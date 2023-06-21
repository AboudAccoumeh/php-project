<?php
    $errors = [];
    if(!isset($_SESSION)){ 
        session_start(); 
    }
    if(!isset($_SESSION["email"])){
        header("location:home.php");
    }
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $id = $_SESSION['id'];
        $isAdmin = $_SESSION['isAdmin'];
        include_once('../private/conn.php');
        if (isset($_POST['submit'])) {
            if (empty($_FILES["picture"]["name"])) {
                $errors[] = 'Picture is required';
            }
            if (empty($errors)) {
                $image = $_FILES['picture']['name'];
                $image_size = $_FILES['picture']['size'];
                $image_error = $_FILES['picture']['error'];
                $file = explode('.', $image);
                $fileActual = strtolower(end($file));
                $allowed = array('png', 'jpg', 'jpge', 'svg');
                if (in_array($fileActual, $allowed)) {
                    if ($image_error === 0) {
                        if ($image_size < 4000000) {
                            $image_new_name = uniqid('', true) . '-' . $image;
                            $target = '../private/profile_picture/' . $image_new_name;
                            $sql = "UPDATE users SET picture='$image_new_name' WHERE email='$email'";
                            if (!empty($image)) {
                                mysqli_query($conn, $sql);
                                move_uploaded_file($_FILES['picture']['tmp_name'], $target);
                            }
                        }
                        else{
                            $errors[] = 'Your picture is too Big';
                        }
                    }
                }
                else{
                    $errors[] = 'png, jpg, jpeg, svg are the only supported extensions';
                }
            }
        }
    }
        $something = "";
        foreach($errors as $error){
            $something = $something . "-" . $error . "<br>";
        }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Profile</title>
  <style>
        body{
            box-sizing: border-box;
            padding: 70px;
            padding-top: 120px;
        }
        .profile-info {
          display: flex;
          align-items: center;
          justify-content: center;
          margin-bottom: 20px;
          width:100%;
        }
        .profile-image {
          width: 300px;
          height: 300px;
          border-radius: 50%;
          object-fit: cover;
          margin-right: 100px;
          border: 1px solid orange;
        }
        .profile-details {
            color:#fff;
            font-size: 1.25em;
        }
        form input[type="file"]{
            display: none;
        }
        form p{
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
            max-width: 300px;
        }
        form p img{
            width:30px;
            height: 30px;
            border-radius: 50%;
            margin-right:20px;
        }
        form p span{
            text-overflow: ellipsis;
            white-space: nowrap;
            color:orange;
        }
        form input[type="submit"]{
            font-size: 1.2em;
            font-weight: 500;
            color:#fff;
            background: orange;
            border-radius: 4px;
            padding: 10px 30px;
            outline: none;
            border:none;
            letter-spacing: 1px;
            cursor: pointer;
            transition:0.5s;
        }
        form input[type="submit"]:hover{
            box-shadow: 0 0 10px orange;
        }

        table {
          width: 100%;
          border-collapse: collapse;
        }

        table th, table td {
          padding: 10px;
          border: 1px solid #111;
        }
        table td{
            text-align: center;
        }

        table th {
          background-color: #222;
          color: orange;
        }

        table tr:nth-child(even) {
          background-color: #333;
          color: #fff;
        }

        table tr:hover {
          background-color: orange;
          color: #fff;
        }
        h1{
            color:#fff;
            text-align: center;
        }
        table img{
            transition:all 0.5s ease-in-out;
        }
        table img:hover{
            transform: scale(2);
            cursor: zoom-in;
        }
        svg path{
            fill:#fff;
        }

        @media(max-width:1000px){
            .profile-image{
                margin-right:30px;
            }
            body{
                padding: 50px;
                padding-top: 120px;
            }
        }
        .user-posts h3{
            color:#fff;
            text-align: center;
            text-align-last: center;
        }
        @media(max-width:700px){
            body{
                padding: 10px;
                padding-top: 120px;
            }
            .profile-info{
                flex-direction: column;
            }
            .profile-details,
            .profile-details form{
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
            h2{
                text-align: center;
            }
        }
  </style>
</head>
<body>
    <?php include ("header.php");?>
    <div class="profile-info">
        <?php echo "<img class='profile-image' src='../private/profile_picture/" . $data['picture'] . "'>"; ?>
        <div class="profile-details">
            <h2>Welcome, <span style="color:orange"><?php echo $data['username'];?></span></h2>
            <p>Email: <span style="color:orange;"><?php echo $data["email"]; ?></span></p>
            <form action="" method="post" enctype="multipart/form-data">
            <input id="file" type="file" name="picture" accept="image/png, image/jpeg, image/jpg, image/svg+xml">
            <label for="file">
                <p id="preview">
                    <?php 
                    if(empty($errors)){echo "Click here to upload an image or drop it anywhere to update your profile image.";}
                    else{echo $something;}
                    ?>
                </p>
            </label>
            <input id="submit" type="submit" name="submit" value="Upload">
            </form>
        </div>
    </div>
    <div class="user-posts">
  <h1>Your Posts</h1>
  <?php
// Assuming you have a database connection established

// Fetch posts from the database
$userid = $_SESSION['id'];
$sql = "SELECT * FROM posts WHERE user_id = '$userid'";
$result = mysqli_query($conn, $sql);

// Check if there are any posts
if (mysqli_num_rows($result) > 0) {
    ?>
    <table style="background-color: #222; color: orange; width: 100%;">
        <thead>
            <tr>
                <th>Image</th>
                <th>Description</th>
                <th>Date</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Loop through the posts
        while ($row = mysqli_fetch_assoc($result)) {
            $postId = $row['post_id'];
            $postImage = $row['picture'];
            $postDescription = $row['text'];
            $postDate = $row['create_at'];
            ?>
            <tr>
                <td><img class="post-image" src="../private/posts_picture/<?php echo $postImage; ?>" alt="Post Image" style="width: 50px; height: 50px;"></td>
                <td><?php echo $postDescription; ?></td>
                <td><?php echo $postDate; ?></td>
                <td><a class="post-delete" href="delete.php?id=<?php echo $postId; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 16 16">
                        <path d="M 6.496094 1 C 5.675781 1 5 1.675781 5 2.496094 L 5 3 L 2 3 L 2 4 L 3 4 L 3 12.5 C 3 13.328125 3.671875 14 4.5 14 L 10.5 14 C 11.328125 14 12 13.328125 12 12.5 L 12 4 L 13 4 L 13 3 L 10 3 L 10 2.496094 C 10 1.675781 9.324219 1 8.503906 1 Z M 6.496094 2 L 8.503906 2 C 8.785156 2 9 2.214844 9 2.496094 L 9 3 L 6 3 L 6 2.496094 C 6 2.214844 6.214844 2 6.496094 2 Z M 5 5 L 6 5 L 6 12 L 5 12 Z M 7 5 L 8 5 L 8 12 L 7 12 Z M 9 5 L 10 5 L 10 12 L 9 12 Z"></path>
                    </svg>
                </a></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
    <?php
} else {
    echo "<h3>You do not have any posts yet.</h3>";
}

?>

  <script type="text/javascript">
      var file = document.getElementById("file");
      var preview = document.getElementById("preview");
      var submit = document.getElementById("submit");
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


