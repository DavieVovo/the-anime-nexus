<?php 
function is_letters($value) {
    return preg_match("/^[a-zA-Z\s]*$/", $value); 
}

function generate_rating_stars($rating) {
    $max_rating = 5;

    $rating = max(0, min($max_rating, $rating));

    
    echo '<div class="d-flex">';
    
    if ($rating != NULL) {
        for ($i = 1; $i <= $max_rating; $i++) {
            $fill_color = ($i <= $rating) ? "#ffe234" : "#aaa";
            echo '<svg width="20px" height="20px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g><g id="SVGRepo_iconCarrier"> <path d="M9.15316 5.40838C10.4198 3.13613 11.0531 2 12 2C12.9469 2 13.5802 3.13612 14.8468 5.40837L15.1745 5.99623C15.5345 6.64193 15.7144 6.96479 15.9951 7.17781C16.2757 7.39083 16.6251 7.4699 17.3241 7.62805L17.9605 7.77203C20.4201 8.32856 21.65 8.60682 21.9426 9.54773C22.2352 10.4886 21.3968 11.4691 19.7199 13.4299L19.2861 13.9372C18.8096 14.4944 18.5713 14.773 18.4641 15.1177C18.357 15.4624 18.393 15.8341 18.465 16.5776L18.5306 17.2544C18.7841 19.8706 18.9109 21.1787 18.1449 21.7602C17.3788 22.3417 16.2273 21.8115 13.9243 20.7512L13.3285 20.4768C12.6741 20.1755 12.3469 20.0248 12 20.0248C11.6531 20.0248 11.3259 20.1755 10.6715 20.4768L10.0757 20.7512C7.77268 21.8115 6.62118 22.3417 5.85515 21.7602C5.08912 21.1787 5.21588 19.8706 5.4694 17.2544L5.53498 16.5776C5.60703 15.8341 5.64305 15.4624 5.53586 15.1177C5.42868 14.773 5.19043 14.4944 4.71392 13.9372L4.2801 13.4299C2.60325 11.4691 1.76482 10.4886 2.05742 9.54773C2.35002 8.60682 3.57986 8.32856 6.03954 7.77203L6.67589 7.62805C7.37485 7.4699 7.72433 7.39083 8.00494 7.17781C8.28555 6.96479 8.46553 6.64194 8.82547 5.99623L9.15316 5.40838Z" fill="'.$fill_color.'"></path> </g></svg>';
        }
    } else {
        echo '<p class="fw-light mb-0">Not yet rated</p>';
    }

    echo '</div>';
}

function count_records() {
    global $connection;
    $sql = "SELECT COUNT(*) as total FROM anime_catalogue;";
    $results = mysqli_query($connection, $sql);
    $count = mysqli_fetch_row($results);
    return $count[0];
}

function count_top_anime_records() {
    global $connection;
    $sql = "SELECT COUNT(*) as total FROM anime_catalogue WHERE rating = 5;";
    $results = mysqli_query($connection, $sql);
    $count = mysqli_fetch_row($results);
    return $count[0];
}

function count_fact_records() {
    global $connection;
    $sql = "SELECT COUNT(*) as total FROM anime_facts;";
    $results = mysqli_query($connection, $sql);
    $count = mysqli_fetch_row($results);
    return $count[0];
}

function find_records($limit = 0, $offset = 0, $sort_by = '', $order = "ASC") {
    global $connection;
    $sql = "SELECT * FROM anime_catalogue";

    // Required assitance with the premier date sorting
    if ($sort_by == 'premier_date') {
        $sql .= " ORDER BY CAST(SUBSTRING_INDEX(premier_date, ' ', -1) AS SIGNED) $order,
                  CASE
                    WHEN premier_date LIKE '%spring%' THEN 1
                    WHEN premier_date LIKE '%summer%' THEN 2
                    WHEN premier_date LIKE '%fall%' THEN 3
                    WHEN premier_date LIKE '%winter%' THEN 4
                    ELSE 5
                  END";
    } elseif ($sort_by != '' && $sort_by != 'premier_date') {
        $sql .= " ORDER BY $sort_by $order";
    }

    if ($limit > 0) {
        $sql .= " LIMIT " . $limit;
    }

    if ($offset > 0) {
        $sql .= " OFFSET " . $offset;
    }

    $result = $connection->query($sql);

    return $result;
}

function build_link($url, $page, $filters, $sort_by = NULL, $order_by = NULL)
{
    $link = "$url?page={$page}";

    if ($sort_by !== NULL) {
        $link .= "&sort_by=$sort_by";
    } elseif ($sort_by == NULL && isset($_GET['sort_by'])) {
        $link .= "&sort_by=".$_GET['sort_by'];
    }

    if ($order_by !== NULL) {
        $link .= "&order_by=$order_by";
    } elseif ($order_by == NULL && isset($_GET['order_by'])) {
        $link .= "&order_by=".$_GET['order_by'];
    }


    foreach ($filters as $filter => $values) {
        $values = is_array($values) ? $values : [$values];
        foreach ($values as $value) {
            $link .= "&{$filter}[]=" . urlencode($value);
        }
    }

    return $link;
}

function generate_genre_btns($genres) {
    $genres = explode(', ', $genres);
    foreach ($genres as $genre) {
        $mode = $_SESSION['dark-mode'] ? 'text-bg-dark' : '';
        $ecchi_btn = ($genre != 'Ecchi') ? 'btn-light' : 'btn-danger';
        echo '<a href="search-results.php?genre=' . urlencode($genre) . '" class="btn ' . $ecchi_btn . ' ' . $mode . ' btn-sm border m-1">' . $genre . '</a>';
    }
}

function build_search_query($search_parameters) {
    global $connection;

    $types = '';
    $parameters = [];
    extract($search_parameters);

    $sql = "SELECT * FROM anime_catalogue WHERE 1=1";

    if (!empty($name)) {
        $sql .= " AND name LIKE CONCAT('%',?,'%')";
        $types .= 's';
        $parameters[] = $name;
    }

    if (!empty($studio)) {
        $sql .= " AND studio LIKE CONCAT('%',?,'%')";
        $types .= "s";
        $parameters[] = $studio;
    }

    if (!empty($genre)) {
        foreach ($genre as $genre_item) {
            $sql .= " AND genre LIKE CONCAT('%', ?, '%')";
            $types .= "s";
            $parameters[] = $genre_item;
        }
    }

    if (!empty($completion_status)) {
        $sql .= " AND completion_status = ?";
        $types .= "s";
        $parameters[] = $completion_status;
    }

    if (!empty($rating)) {
        $sql .= " AND rating >= ?";
        $types .= "i"; // Fixed the typo here from $tpes to $types
        $parameters[] = $rating;
    }

    if (!empty($min_year) && !empty($max_year)) {
        $sql .= " AND SUBSTRING_INDEX(SUBSTRING_INDEX(premier_date, ' ', -1), ' ', -1) BETWEEN ? AND ?";
        $types .= 'ii';
        $parameters[] = $min_year;
        $parameters[] = $max_year;
    }

    if (!empty($order_by) && !empty($order)) {
        $sql .= " ORDER BY $order_by $order"; // Removed placeholders for ORDER BY and ASC/DESC
    }

    $statement = $connection->prepare($sql);
    $statement->bind_param($types, ...$parameters);

    if (!$statement->execute()) {
        handle_database_errors("fetching advanced search results");
    } else {
        $results = $statement->get_result();
        return $results;
    }
}

function get_all_facts() {
    global $connection;

    $sql = "SELECT * FROM anime_facts";
    $statement = $connection->prepare($sql);
    $all_facts = [];

    if ($statement->execute()) {
        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
            $all_facts[] = $row;
        }

        return $all_facts;
    } else {
        handle_database_errors("fetching all facts");
    }
}
?>