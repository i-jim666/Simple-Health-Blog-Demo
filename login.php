<?php
include 'databaseConnection.php';
  session_start();
   
    if(isset($_POST["submit"]))
    {

        $email = $_POST["email"];
        $password = $_POST["password"];
         

        if(empty($_POST["email"]) || empty($_POST["password"]))
        {
            echo '<script>alert("Empty fields!!!")</script>';
        }
        else
        {

            $sql = "SELECT * FROM user WHERE email = :email AND password = :password";
            
            $query = $pdo->prepare($sql);
            $query->bindParam(':email',$email);
            $query->bindParam(':password',$password);
            $query -> setFetchMode(PDO::FETCH_ASSOC);    
            $query -> execute();
     
            $row = $query->fetch();

            if(is_array($row)){
                $_SESSION['userName'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['role'] = 'user';
                header('location:home.php');
            }
            else{
                $sql = "SELECT * FROM doctor WHERE email = :email AND password = :password";
            
                $query = $pdo->prepare($sql);
                $query->bindParam(':email',$email);
                $query->bindParam(':password',$password);
                $query -> setFetchMode(PDO::FETCH_ASSOC);    
                $query -> execute();
        
                $row = $query->fetch();

                if(is_array($row)){
                    $_SESSION['userName'] = $row['name'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['role'] = 'doctor';
                    header('location:home.php');
                }

                else{
                    echo '<script>alert("Wrong Email or Password!!!")</script>';
                }

            }
                                  
        }   
    }
 
?>
<!DOCTYPE html>
<html>
 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Health Blog ! | Login</title>
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
 
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <h2><b>Health Blog!</b></h2>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>
 
            <form action="login.php" method="post">
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" name="email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8"></div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <a href="Home.php"><button type="submit" class="btn btn-primary btn-block btn-flat" name="submit">Log In</button></a>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <a href="register-user.php" class="text-center">Register as a User</a><br>
            <a href="register-doctor.php" class="text-center">Register as a Doctor</a><br>
 
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
 
    <!-- jQuery 3 -->
    <script src="assets/js/library/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="assets/js/library/bootstrap.min.js"></script>
</body>
 
</html>