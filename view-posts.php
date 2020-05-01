<?php
include 'databaseConnection.php';
session_start();

if(!isset($_SESSION['id'])){
    header("location: login.php");
}

$sql = "SELECT * FROM posts WHERE 1";
$result = $pdo -> query($sql);

function get_post_topic($str){
    
    $topic_id = $str;
    $sql = "SELECT name FROM category WHERE id = '$topic_id'";
    global $pdo;
    $result = $pdo -> query($sql);
    $row = $result->fetch(PDO::FETCH_OBJ);

    return $row->name;
}

function get_author_name($str){
    $author = $str;
    $sql = "SELECT name FROM user WHERE id = '$author'";
    global $pdo;
    $result = $pdo -> query($sql);
    $row = $result->fetch(PDO::FETCH_OBJ);

    if($row != NULL){
        return $row->name;
    }

    else{
        $sql = "SELECT name FROM doctor WHERE id = '$author'";
        global $pdo;
        $result = $pdo -> query($sql);
        $row = $result->fetch(PDO::FETCH_OBJ);
        return $row->name;
    }
    
}

function like($the_id){
    global $pdo;
    $sql = "UPDATE posts
            SET likes = likes + 1 
            WHERE id = '$the_id'";
    $query = $pdo->prepare($sql);
    $query->execute();
    $current_page = $_SERVER['REQUEST_URI'];
    header("location: ".$current_page);
}

function dislike($the_id){
    global $pdo;
    $sql = "UPDATE posts
            SET dislikes = dislikes + 1 
            WHERE id = '$the_id'";
    $query = $pdo->prepare($sql);
    $query->execute();
    $current_page = $_SERVER['REQUEST_URI'];
    header("location: ".$current_page);
}

function fetch_comments($post_id){
    global $pdo;
    $match_id = $post_id;

    
    $sql = "SELECT reply_msg,user.name as username,doctor.name as doctorname,post_time,reply.user_id as userid,reply.doctor_id as doctorid
            FROM reply,user,doctor 
            WHERE post_id = '$match_id'";
    
   
    $result = $pdo -> query($sql);

    return $result;
}

function insert_comment($post_id, $user_id, $the_content){
    global $pdo;
    $date = date('Y-m-d H:i:s');
    if($_SESSION['role'] == 'user'){
        $sql = "INSERT INTO reply(post_id, reply_msg, user_id, post_time) VALUES (?,?,?,?)";
        $query = $pdo->prepare($sql);
        $query->execute(array($post_id, $the_content, $user_id, $date));
    }
    else{
        $sql = "INSERT INTO reply(post_id, reply_msg, doctor_id, post_time) VALUES (?,?,?,?)";
        $query = $pdo->prepare($sql);
        $query->execute(array($post_id, $the_content, $user_id, $date));
    }
    $current_page = $_SERVER['REQUEST_URI'];
    header("location: ".$current_page);
}

if(isset($_POST['like'])){
    like($_POST['the_id']);
}
if(isset($_POST['dislike'])){
    dislike($_POST['the_id']);
}

if(isset($_POST['post_comment'])){
    insert_comment($_POST['the_id'], $_SESSION['id'], $_POST['comment_box']);
}



?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Health Blog | posts</title>
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


<div class="post-page-logout">
    <a class="btn btn-primary" href="user-post.php"><b> <- Post Page</b></a>
    <a class="btn btn-primary logout" href="logout.php"><b>Logout</b></a>
</div>

<h1>All Posts</h1>

<div class="show-posts">
    
    <?php while($row = $result->fetch(PDO::FETCH_OBJ)) :?>
    <div class="post-card">
        <div class="post-topic">
            <?php 
                $topic = get_post_topic($row->cat_id);
                echo '<h5><b>Category: '.strtoupper($topic).'</b></h5>';
            ?>
        </div>
        <p class="posted-by">
            <?php 
                if($row->user_id != NULL){
                    $author = get_author_name($row->user_id);
                    
                }
                else{
                    $author = get_author_name($row->doctor_id);
                }
            ?>
            Posted By: <b><?php echo $author; ?></b>

            <div class="post-content">
                
                <h4>
                    <?php echo $row->content; ?>
                </h4>
                
            </div>


        </p>

        <form method="POST">
            
            <div class="like-box">
                <button type="submit" class="like-btn" name="like"><img src="./assets/like.png" alt=""></button>
                <div class="likes">Likes: <?php echo $row->likes; ?></div>
            </div>

            <div class="dislike-box">
                <button type="submit" class="dislike-btn" name="dislike"><img src="./assets/dislike.png" alt=""></button>
                <div class="dislikes">Dislikes: <?php echo $row->dislikes; ?></div>
            </div>

            <hr>

            <input type="hidden" name="the_id" value="<?php echo $row->id ?>">
        
            <textarea name="comment_box" class="comment-box" rows="5"></textarea>
            <div class="row">
                <!-- /.col -->
                <div class="col-xl-3 col-lg-3 col-xs-5 post-button">
                    <button type="submit" class="btn btn-primary btn-block btn-flat post-comment-btn" name="post_comment">Post</button>
                </div>
                    <!-- /.col -->
            </div>

        </form>        
        
        <div class="comment-container">
            <h4><b>Comments: </b></h4><br>
            <p>
                <?php 
                $comments = fetch_comments($row->id);
                
                    while($r = $comments -> fetch(PDO::FETCH_OBJ)):
                        $current_time = $r->post_time;
                    ?>
                        <div class="single-comment">
                            <p class="comment-message"><?php echo $r->reply_msg ?></p>
                            <div class="comment-info">
                                <?php if($r->userid != NULL): ?>
                                    <p class="posted-by">Posted By <b><?php echo $r->username ?></b> at <?php echo $current_time ?></p>
                                    <? else: ?>
                                    <p class="posted-by">Posted By <b><?php echo $r->doctorname ?></b> at <?php echo $current_time ?></p>
                                    <?php endif; ?>
                                <p class="posted-time"></p>
                            </div>
                        </div>
                    <?php
                    endwhile;
                
                ?>
            </p>
        </div>
    </div>

<?php endwhile; ?>
</div>




