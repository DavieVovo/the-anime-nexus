<?php

require_once('../private/connect.php');
$connection = db_connect();

$title = "The Anime Nexus | Browse";
include('includes/header.php');

include('../private/functions.php');

// How many results per page
$per_page = 9;

// How many records in total?
$total_count = count_records();

// Total pages
$total_pages = ceil($total_count / $per_page);

// Make sure the page we're on exists.
$current_page = (int) ($_GET['page'] ?? 1);


// default to page 1
if ($current_page < 1 || $current_page > $total_pages || !is_int($current_page)) {
    $current_page = 1;
}

// Offset
$offset = $per_page * ($current_page - 1);

//Order by variable
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'ASC';

//Sort variable
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'name';

// Browse by variable
$browse_by = isset($_GET['browse_by']) ? $_GET['browse_by'] : '';

// Filters Array
$filters = [
    "anime_name" => [
        "A-E" => "A-D",
        "E-I" => "E-H",
        "I-M" => "I-L",
        "M-Q" => "M-P",
        "Q-U" => "Q-T",
        "U-Z" => "U-Z",
    ],
    "premier_date" => [
        "1990-1999" => "1990s",
        "2000-2009" => "2000s",
        "2010-2019" => "2010s",
        "2020-2029" => "2020s",
    ],
    "rating" => [
        1 => "1",
        2 => "2",
        3 => "3",
        4 => "4",
        5 => "5",
    ],
    "completion_status"=> [
        "plan to watch" => "Plan to Watch",
        "watching" => "Watching",
        "completed" => "Completed",
    ]
];

// State filters to check $_GET
$known_filters = ["anime_name", "premier_date", "rating", "completion_status"];
$active_filters = [];

foreach ($_GET as $filter => $values) {
    // Check $_GET if filters are present
    if (in_array($filter, $known_filters)) {
        if (!is_array($values)) {
            $values = [$values];
        }
        $active_filters[$filter] = array_map("htmlspecialchars", $values);
    }
}
?>

<main class="container">
    <section class="row my-5 justify-content-sm-center">
    <div class="col-lg-4 col-md-10 mb-5 d-sm-flex d-lg-block flex-wrap justify-content-sm-center justify-content-lg-start">
        <h2>Filters</h2>
        <?php
        // Generate the filter buttons
        foreach ($filters as $filter => $options) {
            // Replace underscores or dashes with spaces and capitalise the words for the heading
            $heading = ucwords(str_replace(["_", "-"], " ", $filter));
            // Add a heading before each button group
            echo "<h4 class=\"fw-light w-100 text-sm-center text-lg-start\">" .
                htmlspecialchars($heading) .
                "</h4>";

            echo '<div class="btn-group mb-3" role="group" aria-label="' .
                htmlspecialchars($filter) .
                ' Filter Group">';
            foreach ($options as $value => $label) {
                $is_active = in_array(
                    $value,
                    $active_filters[$filter] ?? []
                );
                $updated_filters = $active_filters;

                if ($is_active) {
                    $updated_filters[$filter] = array_diff(
                        $updated_filters[$filter],
                        [$value]
                    );
                    if (empty($updated_filters[$filter])) {
                        unset($updated_filters[$filter]);
                    }
                } else {
                    $updated_filters[$filter][] = $value;
                }

                // Build the link
                $link = build_link("browse.php", 1, $updated_filters);

                // Output button link
                echo '<a href="' .
                    htmlspecialchars($link) .
                    '" class="btn ' .
                    ($is_active ? "btn-info" : "btn-outline-info") .
                    '">' .
                    htmlspecialchars($label) .
                    "</a>";
            }
            echo "</div>";
        }?>
        <h4 class="w-100 text-sm-center text-lg-start">Order by</h4>

        <div class="btn-group mb-3">
            <a href="<?php 
                $sort_by_link = 'name';
                $order_by = 'ASC';

                if (isset($_GET['order_by']) && $_GET['sort_by'] == 'name') {
                    $order_by = ($_GET['order_by'] == 'ASC') ? 'DESC' : 'ASC';
                }
                $btn_status = ($_GET['sort_by'] == $sort_by_link) ? 'btn-info' : 'btn-outline-info';

                echo build_link("browse.php", $current_page, $active_filters, $sort_by_link, $order_by);
            ?>" class="btn <?php echo $btn_status ?>">Name <?php if($_GET['sort_by'] == 'name' && $_GET['order_by'] == 'ASC') echo "&#8679;"; if($_GET['sort_by'] == 'name' && $_GET['order_by'] == 'DESC') echo "&#8681;";?></a>
            
            <a href="<?php 
                $sort_by_link = 'premier_date';
                $order_by = 'ASC';

                if (isset($_GET['order_by']) && $_GET['sort_by'] == 'premier_date') {
                    $order_by = ($_GET['order_by'] == 'ASC') ? 'DESC' : 'ASC';
                }
                $btn_status = ($_GET['sort_by'] == $sort_by_link) ? 'btn-info' : 'btn-outline-info';

                echo build_link("browse.php", $current_page, $active_filters, $sort_by_link, $order_by);
            ?>" class="btn <?php echo $btn_status ?>">Premier Date <?php if($_GET['sort_by'] == 'premier_date' && $_GET['order_by'] == 'ASC') echo "&#8679;"; if($_GET['sort_by'] == 'premier_date' && $_GET['order_by'] == 'DESC') echo "&#8681;";?></a>
            
            <a href="<?php 
                $sort_by_link = 'completion_status';
                $order_by = 'ASC';

                if (isset($_GET['order_by']) && $_GET['sort_by'] == 'completion_status') {
                    $order_by = ($_GET['order_by'] == 'ASC') ? 'DESC' : 'ASC';
                }
                $btn_status = ($_GET['sort_by'] == $sort_by_link) ? 'btn-info' : 'btn-outline-info';

                echo build_link("browse.php", $current_page, $active_filters, $sort_by_link, $order_by);
            ?>" class="btn <?php echo $btn_status ?>">Status <?php if($_GET['sort_by'] == 'completion_status' && $_GET['order_by'] == 'ASC') echo "&#8679;"; if($_GET['sort_by'] == 'completion_status' && $_GET['order_by'] == 'DESC') echo "&#8681;";?></a>
            
            <a href="<?php 
                $sort_by_link = 'rating';
                $order_by = 'DESC';

                if (isset($_GET['order_by']) && $_GET['sort_by'] == 'rating') {
                    $order_by = ($_GET['order_by'] == 'DESC') ? 'ASC' : 'DESC';
                }
                $btn_status = ($_GET['sort_by'] == $sort_by_link) ? 'btn-info' : 'btn-outline-info';

                echo build_link("browse.php", $current_page, $active_filters, $sort_by_link, $order_by);
            ?>" class="btn <?php echo $btn_status ?>">Rating <?php if($_GET['sort_by'] == 'rating' && $_GET['order_by'] == 'DESC') echo "&#8679;"; if($_GET['sort_by'] == 'rating' && $_GET['order_by'] == 'ASC') echo "&#8681;";?></a>
        </div>
    </div>

    <!-- If filters are active -->
    <?php if (!empty($active_filters)) : ?>
        <div class="col-lg-8 col-md-12 my-5 row justify-content-center">
            <?php include("includes/filter_results.php"); ?>
        </div>
    <?php else: ?>
    <!-- Default browse list -->
    <div class="col-lg-8 col-md-12 my-5 row justify-content-center">

            <?php
            $result = find_records($per_page, $offset, $sort_by, $_GET['order_by']);
            // Generate records table
            if ($connection->error) {
                echo $connection->error;
            } elseif ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                extract($row);
                $short_synopsis = strlen($synopsis) > 200 ? substr($synopsis, 0, 200) . "..." : $synopsis;
                ?>
                                <div class="p-2 col-lg-4 col-md-3 col-sm-8">
                    <div class="card p-2 h-100 shadow-sm <?php echo $dark_mode ? 'text-bg-dark border-secondary' : '';?>">
                        <div class="card-body p-0">
                            <img class="big-border w-100 rounded mb-3" src="images/thumbs/<?php echo $artwork;?>" alt="Key visual for the anime <?php echo $name;?>">
                            <h5 class="card-title"><?php echo $name;?></h5>
                            <p class=" fw-light"><?php echo $genre;?></p>
                            <hr>
                            <!-- <p class="mb-2">Premier Date: <?php echo $premier_date?></p>
                            <p class="mb-2">Studio: <?php echo $studio;?></p> -->
                            <p class="mb-2">Rating: <?php generate_rating_stars($rating)?></p>
                            <p class="mb-2">Status: <?php echo $completion_status?></p>
                            <!-- <p class=""><?php echo $short_synopsis?></p> -->
                        </div>
                        <a href="<?php echo build_link("view.php", $current_page, $active_filters, $sort_by, $_GET['order_by'])?>&id=<?php echo $anime_id;?>" class="btn btn-info">More Info</a>
                    </div>
                </div>
                
                <?php
                }
            } else {
                echo "<p>Sorry! There are no records available.</p>";
            }
            ?>
        <!-- Table pagination -->
        <nav aria-label="Page Number">
            <ul class="pagination justify-content-center border-primary">
                <?php if ($current_page > 1) : ?>
                    <li class="page-item">
                        <a class="page-link <?php echo $dark_mode ? 'bg-dark text-info' : 'text-info';?>" href="<?php echo build_link("browse.php", $current_page - 1, $active_filters) ?>" aria-label="Previous">
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
                                <span class="page-link <?php echo $dark_mode ? 'bg-dark text-info' : 'text-info';?>">...</span>
                            </li>
                        <?php endif;

                        $gap = TRUE;
                        continue;
                    }

                    $gap = FALSE;

                    if ($current_page == $i) : ?>
                        <li class="page-item">
                            <a href="<?php echo build_link("browse.php", $i, $active_filters) ?>" class="page-link text-bg-info "><?php echo $i ?></a>
                        </li>
                    <?php else : ?>
                        <li class="page-item">
                            <a href="<?php echo build_link("browse.php", $i, $active_filters) ?>" class="page-link <?php echo $dark_mode ? 'bg-dark text-info' : 'text-info';?>"><?php echo $i ?></a>
                        </li>
                    <?php endif;
                }

                ?>

                <?php if ($current_page < $total_pages) : ?>
                    <li class="page-item">
                        <a class="page-link <?php echo $dark_mode ? 'bg-dark text-info' : 'text-info';?>" href="<?php echo build_link("browse.php", $current_page + 1, $active_filters) ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <?php endif;?>
    </section>
</main>

<?php

include('includes/footer.php');

?>