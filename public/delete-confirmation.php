<?php

require_once('../private/connect.php');
$connection = db_connect();

$title = "The Anime Nexus | Deletion Confirmation";
include("includes/header.php");

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$anime_name = isset($_GET["anime_name"]) ? $_GET["anime_name"] :"";
$message = '';


if (isset($_GET['anime']) && is_numeric($_GET['anime']) && $_GET['anime'] > 0) {
    $anime_id = $_GET['anime'];
} else {
    $message = "<p>Please return to the delete page and select an option from the table.</p>";
    $anime_id = NULL;
}

if (isset($_POST["confirm"])) {
    $hidden_id = $_POST["hidden_id"];
    delete_anime($hidden_id);
    
    $message = "<p>Anime was deleted from the database</p>";
}

?>

<main>
    <section class="">
        <h1 class="fw-light text-center mb-5">Deletion Confirmation</h1>

        <?php if ($message): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; 
        
        if ($anime_id != NULL) :?>
        <p class="text-danger lead text-center">Are you sure you want to delete <?php echo $anime_name;?>?</p>
        
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
            <input type="hidden" id="hidden_id" name="hidden_id" value="<?php echo $anime_id?>">;
            <input type="submit" class="btn btn-danger d-block mx-auto" name="confirm" id="confirm" value="Yes, I'm sure">  
        </form>
        
        <?php endif; ?>
    </section>
</main>

<?php

include('includes/footer.php');

?>