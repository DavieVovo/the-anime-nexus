<?php
// Initialise our SQL query and parameters.
$sql = "SELECT * FROM anime_catalogue WHERE 1=1";
$sql_count = "SELECT COUNT(*) AS filter_count FROM anime_catalogue WHERE 1=1";

$types = "";

$parameters = [];

// Filter Anime Name
if (isset($active_filters['anime_name'])) {
    $anime_name_filters = $active_filters['anime_name'];

    $name_condition = [];
    foreach ($anime_name_filters as $filter) {
        list($start_letter, $end_letter) = explode("-", $filter);

        $name_condition[] = "name BETWEEN ? AND ?";
        $types .= "ss";
        $parameters[] = $start_letter;
        $parameters[] = $end_letter;
    }

    $sql .= " AND (" . implode(" OR ", $name_condition) . ")";
    $sql_count .= " AND (" . implode(" OR ", $name_condition) . ")";
}

// Filter Premier Date
if (isset($active_filters['premier_date'])) {
    $premier_date_filters = $active_filters['premier_date'];

    $premier_date_conditions = [];
    foreach ($premier_date_filters as $filter) {
        // Extract the numeric part (year) from the premier_date
        $premier_date_conditions[] = "(SUBSTRING_INDEX(SUBSTRING_INDEX(premier_date, ' ', -1), ' ', -1) BETWEEN ? AND ?)";
        $types .= "ii";
        
        list($min, $max) = explode("-", $filter);
        $parameters[] = $min;
        $parameters[] = $max;
    }

    $sql .= " AND (" . implode(" OR ", $premier_date_conditions) . ")";
    $sql_count .= " AND (" . implode(" OR ", $premier_date_conditions) . ")";
}

// Filter Rating
if (isset($active_filters['rating'])) {
    $rating_filters = $active_filters['rating'];
    $in = str_repeat("?,", count($rating_filters) - 1) . "?";
    $sql .= " AND rating IN ($in)";
    $sql_count .= " AND rating IN ($in)";
    $types .= str_repeat("i", count($rating_filters));
    $parameters = array_merge($parameters, $rating_filters);
}

// Filter Completion Status
if (isset($active_filters['completion_status'])) {
    $completion_status_filters = $active_filters['completion_status'];
    $in = str_repeat("?,", count($completion_status_filters) - 1) . "?";
    $sql .= " AND completion_status IN ($in)";
    $sql_count .= " AND completion_status IN ($in)";
    $types .= str_repeat("s", count($completion_status_filters));
    $parameters = array_merge($parameters, $completion_status_filters);
}

// Determine sorting parameters
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'name';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'ASC';

$sql .= " ORDER BY $sort_by $order_by LIMIT $per_page OFFSET $offset;";

// Filter statement
$filter_statement = $connection->prepare($sql);
if ($filter_statement === FALSE) {
    echo "Failed to prepare the statement: (" . $connection->errno . ") " . $connection->error;
    exit();
}

$filter_statement->bind_param($types, ...$parameters);

if (!$filter_statement->execute()) {
    echo "Execute failed: (" . $filter_statement->errno . ") " . $filter_statement->error;
} 

$filter_result = $filter_statement->get_result(); 

// Count statement
$count_statement = $connection->prepare($sql_count);
if ($filter_statement === FALSE) {
    echo "Failed to prepare the statement: (" . $connection->errno . ") " . $connection->error;
    exit();
}

$count_statement->bind_param($types, ...$parameters);

if (!$count_statement->execute()) {
    echo "Execute failed: (" . $count_statement->errno . ") " . $count_statement->error;
} 

$count_result = $count_statement->get_result(); 
$count = $count_result->fetch_assoc();
$total_count = $count['filter_count'];
$total_pages = ceil($total_count / $per_page);

// echo $total_pages;


if ($filter_result->num_rows > 0) {
    ?>
    <?php
    while ($row = $filter_result->fetch_assoc()) { 
        extract($row);
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
    <?php } ?>
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

<?php } else {
    echo "<p class=\"text-center fs-5\">No results found.</p>";
}

?>