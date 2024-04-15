<?php

require_once('../private/connect.php');
$connection = db_connect();

SESSION_START();

include('../private/functions.php');

$title = "The Anime Nexus | Anime Details";
include('includes/header.php');

// Variables for returning to home page button
$page = isset($_GET['page']) ? $_GET['page'] : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] :'';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] :'';


$anime_id = isset($_GET['id']) ? $_GET['id'] :'';

// Selecting anime based on id
$anime = select_anime_by_id($anime_id);

?>

<main class="container flex-column d-flex align-items-center">
    <!-- Generate anime card -->
    <?php if ($anime) {
        extract($anime);
        // Get last editor profile picture
        $edit_user_info = get_username($last_edited);
        $edit_user = $edit_user_info->fetch_assoc();
        $editor_profile_img = $edit_user['profile_picture'];
    ?>
        <div class="card col-md-12 col-lg-10 col-xxl-8 mt-3">
            <div class="card-body col-12 mx-auto row flex-wrap align-items-center py-5 shadow-lg <?php echo $dark_mode ? 'text-bg-dark' : '';?>">
                <img class="rounded col-lg-6 col-md-12" src="images/full/<?php echo $artwork;?>">
                <div class="col-lg-6 col-md-12 mb-5">
                    <h2 class="card-title text-center"><?php echo $name?></h2>
                    <div class="d-flex justify-content-center flex-wrap"><?php echo generate_genre_btns($genre);?></div>
                    <div class="d-flex justify-content-center my-3"><?php echo generate_rating_stars($rating);?></div>
                    <p class="card-text text-start"><?php echo $synopsis?></p>
                    <p class="card-text"><span class="fw-bold">Premier Date: </span><?php echo $premier_date?></p>
                    <p class="card-text"><span class="fw-bold">Studio: </span><?php echo $studio?></p>
                    <p class="card-text"><span class="fw-bold">Status: </span><?php echo $completion_status?></p>
                <?php if ($stream) : ?>
                    <p class="card-text fw-bold">Stream at</p>
                        <?php
                        $stream_arr = explode(", ", $stream);
                        foreach($stream_arr AS $str) {
                            $stream_link = "https://www.$str.com";
                            $img = '';
                            if ($str == "crunchyroll") {
                                $img = "crunchy-logo.svg";
                            } elseif ($str == "hidive") {
                                $img = "hidive-logo.webp";
                            }
                            echo "<a href=\"$stream_link\" target=\"_blank\" class=\"me-3\"><img src=\"img/$img\" alt=\"Crunchyroll Logo\" width=\"50px\" height=\"50px\"></a>";
                        }
                    endif;
                echo "</div>";
                 if ($last_edited != ''):?>
                <div class="mx-auto d-flex flex-wrap justify-content-center align-items-center col-7 my-3">
                    <p class="fs-5 m-0 p-0 fw-bold text-center w-100">Last Edited by:</p>
                    <p class="fs-5 m-0 p-0 fw-semibold w-100 text-center mb-1"><?php echo $last_edited;?></p>
                    <img src="profile/thumbs/<?php echo $editor_profile_img?>" alt="Profile image of <?php echo $username?>" class="w-25 rounded-circle px-3">
                </div>
                <?php endif; ?>
                <div>
                    <a href="<?php echo $_GET['genre'] ? 'search-results.php' : 'browse.php'?>?<?php echo $_SERVER['QUERY_STRING'];?>" class="d-block btn btn-info mt-3 col-3 mx-auto">Back to Browse</a>
                </div>
                <?php if ($_SESSION['username']) :?>
                    <a href="edit.php?aid=<?php echo $anime_id?>" class="d-block btn btn-warning mt-3 col-3 mx-auto">Edit Anime</a>
                    <?php endif;?>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="alert alert-info"><p class="mb-0">Apologies, we encountered a hiccup while trying to showcase the anime.</p></div>';
        echo '<a href="browse.php?' . $_SERVER['QUERY_STRING'] . '" class="d-block btn btn-info mt-3 col-3 mx-auto">Back to Browse</a>';
    }
    ?>
    
</main>

<?php

include('includes/footer.php');

?>