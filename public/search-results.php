<?php

require_once('../private/connect.php');
$connection = db_connect();

session_start();

include('../private/functions.php');

$title = "The Anime Nexus | Search Results";
include('includes/header.php');

// Creating array of user search parameters
$search_parameters = [
    'name' => isset($_SESSION['name']) ? $_SESSION['name'] : '',
    'studio' => isset($_SESSION['studio']) ? $_SESSION['studio'] : '',
    'genre' => $_SESSION['genre'] ?? array(),
    'completion_status' => isset($_SESSION['completion_status']) ? $_SESSION['completion_status'] : '',
    'min_year' => isset($_SESSION['min_year']) ? $_SESSION['min_year'] : '1990',
    'max_year' => isset($_SESSION['max_year']) ? $_SESSION['max_year'] : date('y') + 1,
    'rating' => isset($_SESSION['rating']) ? $_SESSION['rating'] : '',
    'order_by' => isset($_SESSION['order_by']) ? $_SESSION['order_by'] : '',
    'order' => isset($_SESSION['order']) ? $_SESSION['order'] : ''
];

?>

<main class="container">
    <section class="row justify-content-center mb-5">
        <div class="row col-md-12 col-lg-10 col-xxl-8">
            <?php if(isset($_GET['genre'])):?>
                <div class="col-lg-2 col-md-12 mb-5">
                <h2 class="mt-5 mb-4 fw-light">Genres</h2>
                <div class="row">
                    <?php
                    // Generate the genre filter buttons
                    echo '<div class="col-md-6 col-lg-12" role="group" aria-label="Genre Filter Group">';
                    foreach ($genres_1 as $genre) {
                        $is_active = ($_GET['genre'] == $genre);
                    
                        // Output button link
                        echo "<a href=\"search-results.php?genre=$genre\" class=\"mb-1 d-block btn " .
                            ($is_active ? ($genre == 'Ecchi' ? "btn-danger" :"btn-info") : ($genre == 'Ecchi' ? "btn-outline-danger" :"btn-outline-info")) .
                            '" ' . ($is_active ? "disabled" : "") . '>' .
                            htmlspecialchars($genre) .
                            "</a>";
                    }
                    echo '</div>';
                    echo '<div class="col-md-6 col-lg-12" role="group" aria-label="Genre Filter Group">';
                    foreach ($genres_2 as $genre) {
                        $is_active = ($_GET['genre'] == $genre);
                    
                        // Output button link
                        echo "<a href=\"search-results.php?genre=$genre\" class=\"mb-1 d-block btn " .
                            ($is_active ? "btn-info" : "btn-outline-info") .
                            '" ' . ($is_active ? "disabled" : "") . '>' .
                            htmlspecialchars($genre) .
                            "</a>";
                    }
                    echo "</div>";
                    ?>
                </div>
            </div>
            <?php endif;?>
            <div class="<?php echo isset($_GET['genre']) ? 'col-sm-12 col-lg-10' : 'col-12'?>">
            <?php
            // Show heading and call search function depending if advanced search of quick search
            if (isset($_GET['search'])) {
                $search = $_GET['search'];

                // Get total count from search results
                $total_count = count_search_records($search);

                // How many results per page
                $per_page = 5;

                // Total pages
                $total_pages = ceil($total_count / $per_page);
                // Make sure the page we're on exists.
                $current_page = (int) ($_GET['page'] ?? 1);

                $offset = $per_page * ($current_page - 1);
                $result = quick_search($search, $per_page, $offset);
                // How many records in total?
                ?>
                <h2 class="fw-light mt-5">Showing quick results for: <?php echo $search;?></h2>
                <?php
            } elseif (isset($_GET['genre'])) {
                $genre = $_GET['genre'];
                $result = search_genre($genre);
                ?>
                <h2 class="fw-light mt-5"><?php echo $genre;?></h2>
                <?php
            } else {
                $result = build_search_query($search_parameters);
                ?>
                <h2 class="fw-light">Advanced Search Results</h2>
                <?php
            }
            // Generate table using quick search partial or advanced search parameters
            if ($connection->error) {
                echo $connection->error;
            } elseif ($result->num_rows > 0) {
                ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th></th>
                        <th scope="col">Name</th>
                        <th scope="col">Premier Date</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Completion Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        extract($row);
                        ?>
                        <tr>
                            <td class="align-middle"><img class="rounded w-100"
                                                          src="images/thumbs/<?php echo $artwork; ?>"
                                                          alt="Key visual for the anime <?php echo $name; ?>"></td>
                            <td class="align-middle <?php echo $dark_mode ? 'text-bg-dark' : '';?>"><?php echo $name; ?></td>
                            <td class="align-middle <?php echo $dark_mode ? 'text-bg-dark' : '';?>"><?php echo $premier_date; ?></td>
                            <td class="align-middle <?php echo $dark_mode ? 'text-bg-dark' : '';?>"><?php echo generate_rating_stars($rating); ?></td>
                            <td class="align-middle <?php echo $dark_mode ? 'text-bg-dark' : '';?>"><?php echo $completion_status; ?></td>
                            <td class="align-middle <?php echo $dark_mode ? 'text-bg-dark' : '';?>"><a href="view.php?<?php echo $_SERVER['QUERY_STRING']. '&';?>id=<?php echo $anime_id; ?>"
                                                       class="text-info text-decoration-none">View</a></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <?php
            } else {
                echo "<p>Sorry! There are no records available.</p>";
            }
            ?>
        <ul class="pagination justify-content-center">
            <?php if ($current_page > 1) : ?>
                <li class="page-item">
                    <a class="page-link text-info"
                       href="search-results.php?search=<?php echo $search ?>&page=<?php echo $current_page - 1 ?><?php if (isset($_GET['sort_by'])) {
                           echo "&sort_by=" . $_GET['sort_by'] . "&order_by=" . $_GET['order_by'];
                       } ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif;

            $gap = FALSE;

            $window = 1;
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i > 1 + $window && $i < $total_pages - $window && abs($i - $current_page) > $window) {
                    if (!$gap) : ?>
                        <li class="page-item">
                            <span class="page-link text-info">...</span>
                        </li>
                    <?php endif;

                    $gap = TRUE;
                    continue;
                }

                $gap = FALSE;

                if ($current_page == $i) : ?>
                    <li class="page-item">
                        <a href="search-results.php?search=<?php echo $search ?>&page=<?php echo $i ?><?php if (isset($_GET['sort_by'])) {
                            echo "&sort_by=" . $_GET['sort_by'] . "&order_by=" . $_GET['order_by'];
                        } ?>" class="page-link text-bg-info"><?php echo $i ?></a>
                    </li>
                <?php else : ?>
                    <li class="page-item">
                        <a href="search-results.php?search=<?php echo $search ?>&page=<?php echo $i ?><?php if (isset($_GET['sort_by'])) {
                            echo "&sort_by=" . $_GET['sort_by'] . "&order_by=" . $_GET['order_by'];
                        } ?>" class="page-link text-info"><?php echo $i ?></a>
                    </li>
                <?php endif;
            }

            ?>

            <?php if ($current_page < $total_pages) : ?>
                <li class="page-item">
                    <a class="page-link text-info"
                       href="search-results.php?search=<?php echo $search ?>&page=<?php echo $current_page + 1 ?><?php if (isset($_GET['sort_by'])) {
                           echo "&sort_by=" . $_GET['sort_by'] . "&order_by=" . $_GET['order_by'];
                       } ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    </div>
    </section>
</main>

<?php

include('includes/footer.php');
db_disconnect($connection);

?>
