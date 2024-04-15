<?php
require_once('prepared.php');

if(isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check for username
    if($username && $password) {
        $result = get_username($username);
        // If user exists   
        if($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            // Check password
            if (password_verify($password, $row["hashed_pass"])) {
                // Regenerate session id
                session_regenerate_id();
                
                $_SESSION["username"] = $row["users"];
                $_SESSION['last_login'] = time();
                $_SESSION['login_expires'] = strtotime("+1 day midnight"); 
                // Redirect the user
                header('Location: public/index.php');
            } else {
                $message = "<p class=\"mb-0\">Invalid username or password</p>";
            }
        } else {
            $message = "<p class=\"mb-0\">Invalid username or password</p>";
        }
    } else {
        $message = "<p class=\"mb-0\">Both username and password are required.</p>";
    }
}
?>