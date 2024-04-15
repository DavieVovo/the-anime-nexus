<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once('../private/connect.php');
$connection = db_connect();

$title = "The Anime Nexus - Login";
include("includes/header.php");
include("../private/login-process.php");

// If the user is already logged in, kick them out! They shouldn't be logging in again.
if (isset($_SESSION["username"])) {
    header('Location: index.php');
    exit();
}

?>

<main class="container mt-5">
    <section class="row justify-content-center">
        <div class="col-md-8 col-xl-6">
            <h1>Login</h1>
            <p class="lead">To access administrative features, please log in below.</p>

            <?php if ($message != NULL) : ?>
                <div class="alert alert-info" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <!-- Submit -->
                <input type="submit" class="btn btn-info mt-4" id="login" name="login" value="Login">
            </form>
        </div>
    </section>
</main>

<?php
include("includes/footer.php");
?>