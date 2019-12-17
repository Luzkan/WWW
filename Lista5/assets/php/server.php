<?php
function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}
?>

<?php
    // Connect to Database
    include 'dbconfig.php';
    error_reporting(E_ERROR | E_PARSE);

    session_start();

    // Initializing User Variables
    $username = "";
    $email    = "";
    $errors = array(); 

    // Prevents from infinite loop before reaching next if block
    // Without ever initializing timestamp
    if (!(isset($_SESSION['timestamp']))){
        $_SESSION['timestamp'] = time();
    }

    // Automatic Logout after 5 minutes (300s)
    // Subtract new timestamp from the old one
    console_log("Czasy:");
    console_log(time() - $_SESSION['timestamp']);
    if(time() - $_SESSION['timestamp'] > 300) { 
        echo"<script>alert('You were logged out due to being inactive for 5 minutes.');</script>";
        unset($_SESSION['username'], $_SESSION['password'], $_SESSION['timestamp']);
        $_SESSION['logged_in'] = false;
        header('Location: index.php');
        exit;
    } else {
        // Set New Timestamp
        $_SESSION['timestamp'] = time();
    }

    // Testing weird POST/REQUEST swap
    console_log("Post:");
    console_log($_POST);
    console_log("Request:");
    console_log($_REQUEST);
    console_log("Session:");
    console_log($_SESSION);

    // Register User Signal
    if (isset($_REQUEST['reg_user'])) {

        // Gather all Input Values
        $username = mysqli_real_escape_string($con, $_REQUEST['username']);
        $email = mysqli_real_escape_string($con, $_REQUEST['email']);
        $password_1 = mysqli_real_escape_string($con, $_REQUEST['password_1']);
        $password_2 = mysqli_real_escape_string($con, $_REQUEST['password_2']);
        
        // Form Validation: Ensure that the form is correctly filled.
        // Adding (array_push()) corresponding error unto $errors array.
        if (empty($username)) array_push($errors, "Username is required.");
        if (empty($email)) array_push($errors, "Email is required.");
        if (empty($password_1)) array_push($errors, "Password is required.");
        if ($password_1 != $password_2) array_push($errors, "The two passwords do not match.");

        // Check if user with same username/email not already exists in database
        $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
        $result = mysqli_query($con, $user_check_query);
        $user = mysqli_fetch_assoc($result);

        // If User Exists
        if ($user) { 
            if ($user['username'] === $username) array_push($errors, "Username already exists.");
            if ($user['email'] === $email) array_push($errors, "Email already exists.");
        }

        // Finally, register user if there are no errors in the form
        if (count($errors) == 0) {
            // Encrypt the password before saving in the database
            $password = md5($password_1);
            $query = "INSERT INTO users (username, email, password) 
            VALUES('$username', '$email', '$password')";
            mysqli_query($con, $query);
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            
            header('location: index.php');
        }
    }

    // Login User Signal
    if (isset($_REQUEST['login_user'])) {
        $username = mysqli_real_escape_string($con, $_REQUEST['username']);
        $password = mysqli_real_escape_string($con, $_REQUEST['password']);

        if (empty($username)) array_push($errors, "Username is required.");
        if (empty($password)) array_push($errors, "Password is required.");

        if (count($errors) == 0) {
            $password = md5($password);
            $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $results = mysqli_query($con, $query);
            if (mysqli_num_rows($results) == 1) {
                $_SESSION['username'] = $username;
                $_SESSION['success'] = "You are now logged in.";
                header('location: index.php');
            } else {
                array_push($errors, "Wrong username/password combination.");
            }
        }
    }

    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first.";
        // header('location: index.php');
    }

    if (isset($_REQUEST['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        // header("location: index.php");
    }


    // COOOOOOOOOOOOOOOOOOOOOMMMMMMMMMMMMMMMMMMMMMMMMMMEEEEEEEEEEEEENNNNNNNNNNNTTTTTTTTTTSSSSSSSSSSSSS

	// Get all comments f from database
	$comments0_query_result = mysqli_query($con, "SELECT * FROM comments WHERE post_id=0 ORDER BY created_at DESC");
    $comments0 = mysqli_fetch_all($comments0_query_result, MYSQLI_ASSOC);
	$comments1_query_result = mysqli_query($con, "SELECT * FROM comments WHERE post_id=1 ORDER BY created_at DESC");
    $comments1 = mysqli_fetch_all($comments1_query_result, MYSQLI_ASSOC);
	$comments2_query_result = mysqli_query($con, "SELECT * FROM comments WHERE post_id=2 ORDER BY created_at DESC");
    $comments2 = mysqli_fetch_all($comment2_query_result, MYSQLI_ASSOC);

    // Some useful functions
    function getCurrentComments($currentarticleid){
        $comments_query_result = mysqli_query($con, "SELECT * FROM comments WHERE post_id=0 ORDER BY created_at DESC");
        $comments = mysqli_fetch_all($comments_query_result, MYSQLI_ASSOC);
        return $comments;
    }

	function getUsernameById($id){
		global $con;
		$result = mysqli_query($con, "SELECT username FROM users WHERE id='$id' LIMIT 1");
		return mysqli_fetch_assoc($result)['username'];
    }

    function getIdByUsername($username){
		global $con;
		$result = mysqli_query($con, "SELECT id FROM users WHERE username='$username' LIMIT 1");
		return mysqli_fetch_assoc($result)['id'];
    }

	function getRepliesByCommentId($id)	{
		global $con;
		$result = mysqli_query($con, "SELECT * FROM replies WHERE comment_id=$id");
		$replies = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $replies;
    }

    // Add a comment
    if (isset($_REQUEST['submit_comment'])) {

        $user_id = getIdByUsername($_SESSION['username']);
        $comment_text = mysqli_real_escape_string($con, $_REQUEST['comment_text']);
        $post_id = mysqli_real_escape_string($con, $_REQUEST['articleid']);
        
        // Form Validation: Ensure that the form is correctly filled.
        // Adding (array_push()) corresponding error unto $errors array.
        if (empty($user_id)) array_push($errors, "Username is required.");
        if (empty($comment_text)) array_push($errors, "Comment can not be empty.");

        // Finally, register user if there are no errors in the form
        if (count($errors) == 0) {
            // Encrypt the password before saving in the database
            $query = "INSERT INTO comments (user_id, post_id, body) VALUES('$user_id', '$post_id', '$comment_text')";
            mysqli_query($con, $query);
            $_SESSION['username'] = $_SESSION['username'];
            $_SESSION['comment'] = "You have succesfully added a new comment!";
            header('location: index.php');
        }
    }

    // Add a reply
    if (isset($_REQUEST['submit_reply'])) {

        $user_id = getIdByUsername($_SESSION['username']);
        $reply_text = mysqli_real_escape_string($con, $_REQUEST['reply_text']);
        $comment_id = mysqli_real_escape_string($con, $_REQUEST['cmntid']);


        // Form Validation: Ensure that the form is correctly filled.
        // Adding (array_push()) corresponding error unto $errors array.
        if (empty($user_id)) array_push($errors, "Username is required.");
        if (empty($reply_text)) array_push($errors, "Comment can not be empty.");

        // Finally, register user if there are no errors in the form
        if (count($errors) == 0) {
            // Encrypt the password before saving in the database
            console_log("no kurde");
            $query = "INSERT INTO replies (user_id, comment_id, body) VALUES('$user_id', '$comment_id', '$reply_text')";
            mysqli_query($con, $query);
            $_SESSION['username'] = $_SESSION['username'];
            $_SESSION['reply'] = "Dodałeś nową odpowiedź do komentarza! :3";
            header('location: index.php');
        }
    }
?>