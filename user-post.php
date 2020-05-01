<?php
include 'databaseConnection.php';
session_start();

if(!isset($_SESSION['id'])){
    header("location: login.php");
}

if(isset($_POST['submit'])){


    $catName = $_POST['selection'];

    $sql = "SELECT id FROM category WHERE name = :catName";
                
    $query = $pdo->prepare($sql);
    $query->bindParam(':catName',$catName);
    $query -> setFetchMode(PDO::FETCH_OBJ);    
    $query -> execute();
    $row = $query->fetch();
    $cat_id = $row->id;

    $user_comment = $_POST['comment'];

    if($_SESSION['role']=='user'){
        $sql = "INSERT INTO posts(user_id, cat_id, content) VALUES (?,?,?)";
        $query = $pdo->prepare($sql);
        $query->execute(array($_SESSION['id'], $cat_id, $user_comment));
    }

    else{
        
        $sql = "INSERT INTO posts(doctor_id, cat_id, content) VALUES (?,?,?)";
        $query = $pdo->prepare($sql);
        $query->execute(array($_SESSION['id'],$cat_id, $user_comment));
        
    }


}

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Health Blog | user</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <script src="https://use.fontawesome.com/7c91c9d530.js"></script>
    <link rel="stylesheet" href="style/style.css">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>


<div class="user-post-form">
    <a class="btn btn-primary logout" href="logout.php"><b>Logout</b></a>
    <h1>Hello, <?php echo $_SESSION['userName']; ?></h1>
    <form action="user-post.php" method="post">
        <div class="form-group">
            <label for="sel">Select Category</label>
                <select class="form-control" id="sel" name="selection">
                    <?php 
                        $sql = "SELECT * FROM category WHERE 1";
                        $result = $pdo -> query($sql);
                        while($row = $result->fetch(PDO::FETCH_OBJ)){
                            echo '<option> '.strtoupper($row->name).' </option>';
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="comment">Write Post:</label>
                <textarea class="form-control" rows="5" id="comment" name="comment"></textarea>
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" name="submit">Submit</button>
                </div>
                <!-- /.col -->
            </div>
    </form>
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                    <a href="view-posts.php" type="submit" class="btn btn-primary btn-block btn-flat">View All Posts</a>
                </div>
                <!-- /.col -->
            </div>
</div>