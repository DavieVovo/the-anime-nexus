<?php

require_once('../private/connect.php');
$connection = db_connect();

$title = "The Anime Nexus - Update Profile";

include('includes/header.php');

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Initialize all our variables
$message  = $message ?? '';

// Get user information
$username = $_SESSION['username'];
$user_info = get_username($username);
$user = $user_info->fetch_assoc();
$existing_profile_img = $user['profile_picture'];

$profile_name = isset($_POST['profile_name']) ? $_POST['profile_name'] : $user['user_name'];

require_once('../private/validation.php');

?>
    <main class="container">
        <section class="row justify-content-center py-5 my-5">
            <div class="col-6">
                <h1 class="fw-light mb-5">Update your Profile</h1>
                <!-- Error Message -->
                <?php if ($message != '') : ?>
                <div class="alert-secondary alert my-3">
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>
                <div class="d-flex justify-content-center">
                    <img src="profile/thumbs/<?php echo $profile_img?>" alt="Profile image of <?php echo $username?>" class="rounded-circle">
                </div>
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST" enctype="multipart/form-data">
                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input class="form-control" id="disabledInput" type="text" value="<?php echo $_SESSION['username'];?>" disabled>
                    </div>
                    <!-- Profile Name -->
                    <div class="mb-3">
                        <label for="profile_name" class="form-label">Name</label>
                        <input type="text" id="profile_name" name="profile_name" maxlength="50" class="form-control" value="<?php if(isset($profile_name)) echo $profile_name; ?>">
                    </div>
                    <!-- Profile Image -->
                    <div class="mb-3">
                        <label for="profile-img" class="form-label">Profile Picture</label>
                        <input type="file" id="profile-img" name="profile-img" class="form-control" accept=".jpg, .jpeg, .webp, .png">
                    </div>
                    <!-- Submit -->
                    <div class="mb-3">
                        <input type="submit" name="update-profile" id="update-profile" value="Update" class="btn btn-info">
                    </div>
                </form>
                <div class="my-5 d-flex justify-content-center">
                    <a href="index.php" class="btn btn-info">Home</a>
                </div>
            </div>
        </section>
    </main>
<?php
include("includes/footer.php");
?>