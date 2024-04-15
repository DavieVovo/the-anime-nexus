<?php

require_once('../private/connect.php');
$connection = db_connect();

$title = "The Anime Nexus | Home";

include('includes/header.php');

include('../private/functions.php');

/*
    Fact variables
*/
// Get total anime facts
$total_facts = count_fact_records();

// Random number generated for facts.
$random_fact_id = rand(1, $total_facts);

// Get Fact
$fact = select_fact_by_id($random_fact_id);

/*
    Top anime variables
*/

// Get total top animes
$total_top_animes = count_top_anime_records();

// Random number generated for facts.
$random_anime_id_1 = rand(1, $total_top_animes);
$random_anime_id_2 = rand(1, $total_top_animes);
$random_anime_id_3 = rand(1, $total_top_animes);

// Check to see if the numbers are different
while ($random_anime_id_2 == $random_anime_id_1) {
    $random_anime_id_2 = rand(1, $total_top_animes);
}
while ($random_anime_id_3 == $random_anime_id_1 || $random_anime_id_3 == $random_anime_id_2) {
    $random_anime_id_3 = rand(1, $total_top_animes);
}

// Get top animes
$top_anime = get_top_animes();

?>

<main class="container">
    <section class="row gap-5 justify-content-sm-center my-5">

        <!-- Introduction -->
        <div class="col-md-10 col-lg-8 col-xxl-6 mb-4 mb-md-2">
            <h2 class="display-4">Welcome to <span class="d-block text-info">The Anime Nexus</span></h2>
            <p>Discover captivating anime stories and characters at Anime Nexus. Dive into vibrant adventures filled with action, romance, and mystery. Immerse yourself in the rich universe of Japanese animation and experience the unique artistry of anime. Every frame tells a special story at Anime Nexus—start browsing now to explore the magic!</p>
            <a href="browse.php" class="btn btn-info btn-lg">Browse Now</a>
        </div>
        <!-- Fact box -->
        <div class="col-md-10 col-lg-4 card mb-4 mb-md-2 py-3 align-items-center border-info <?php echo $dark_mode ? 'text-bg-dark' : '';?>">
            <img src="fact/thumbs/<?php echo $fact['fact_img'];?>" alt="Fact image" width="150px" height="150px" class="rounded-circle border-solid">
            <h3 class="fw-light text-center mb-4">Did you Know?</h3>
            <p class="text-center"><?php echo $fact['facts'];?></p>
        </div>
        <div class="border rounded p-3 col-10 row justify-content-lg-between justify-content-sm-center">
            <h2 class="fw-light mb-3 text-center">Recently Added Anime</h2>
            <?php
            $latest_anime = get_latest_anime();
            if (count($latest_anime) > 0) {
                foreach ($latest_anime as $anime) {
                extract($anime);
                $short_synopsis = strlen($synopsis) > 200 ? substr($synopsis, 0, 200) . "..." : $synopsis;
                ?>
                
                <div class="col-lg-4 col-md-8 mb-3 row align-items-between">
                    <div class="">
                        <div>
                            <img class="w-100 rounded mb-3" src="images/thumbs/<?php echo $artwork;?>" alt="Key visual for the anime <?php echo $name;?>">
                            <h5 class="card-title"><?php echo $name;?></h5>
                            <p class=" fw-light"><?php echo $genre;?></p>
                            <hr>
                            <p class="mb-2">Premier Date: <?php echo $premier_date?></p>
                            <p class="mb-2">Studio: <?php echo $studio;?></p>
                            <p class=""><?php echo $short_synopsis?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="view.php?id=<?php echo $anime_id?>" class="btn btn-info">View</a>
                    </div>
                </div>
                <?php
                }
            }?>
        </div>
        <div class="border rounded p-3 col-10 row justify-content-lg-between justify-content-sm-center">
            <h2 class="fw-light mb-3 text-center">⭐⭐⭐⭐⭐ Anime</h2>
            <?php
            if (count($top_anime) > 0) {
                foreach ($top_anime as $anime) {
                extract($anime);
                if ($row_number == $random_anime_id_1 || $row_number == $random_anime_id_2 || $row_number == $random_anime_id_3) :
                $short_synopsis = strlen($synopsis) > 200 ? substr($synopsis, 0, 200) . "..." : $synopsis;
                ?>
                
                <div class="col-lg-4 col-md-8 mb-3 row align-items-between">
                    <div class="">
                        <div>
                            <img class="w-100 rounded mb-3" src="images/thumbs/<?php echo $artwork;?>" alt="Key visual for the anime <?php echo $name;?>">
                            <h5 class="card-title"><?php echo $name;?></h5>
                            <p class=" fw-light"><?php echo $genre;?></p>
                            <hr>
                            <p class="mb-2">Premier Date: <?php echo $premier_date?></p>
                            <p class="mb-2">Studio: <?php echo $studio;?></p>
                            <p class=""><?php echo $short_synopsis?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="view.php?id=<?php echo $anime_id?>" class="btn btn-info">View</a>
                    </div>
                </div>
                <?php
                endif;
                }
            }?>
        </div>
</main>

<?php

include('includes/footer.php');
db_disconnect($connection);

?>