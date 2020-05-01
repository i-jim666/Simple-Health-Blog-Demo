<?php
    include 'databaseConnection.php';   
    session_start();
   
    if(isset($_POST['registerButton']))
    {
        $name = $_POST['name']; 
        $email = $_POST['email'];
        $password = $_POST['password'];
        $retypePass = $_POST['retypePass'];
        
        if($password == $retypePass){       
            //Inserting data into attn table and fetching the id
            $sql = "INSERT INTO user(name, email, password) VALUES (?,?,?)";
            $query = $pdo->prepare($sql);
            $query->execute(array($name,$email,$password));
            header("location:login.php");
        }
        else{
            echo "Password and re-type password don't match";
        }

    }
?>
 
<!DOCTYPE html>
<html>
 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Health Blog | Signup</title>
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
 
<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <h2><b>Health Blog!</b></h2>
        </div>
 
        <div class="register-box-body">
            <p class="login-box-msg">Register as a user</p>
 
            <form action="register-user.php" method="post">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Full name" name="name">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" name="email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Retype password" name="retypePass">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat" name="registerButton">Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <a href="login.php" class="text-center">I already have a membership</a><br>
            <a href="register-doctor.php" class="text-center">Register as a Doctor</a>
        </div>
        <!-- /.form-box -->
    </div>

    <!-- jQuery 3 -->
    <script src="assets/js/library/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="assets/js/library/bootstrap.min.js"></script>

</body>
 
</html>