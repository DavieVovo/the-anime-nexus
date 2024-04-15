<?php


$insert_statement = $connection->prepare("INSERT INTO anime_catalogue (name, synopsis, genre, premier_date, rating, studio, completion_status, stream, artwork, last_edited) VALUES (? ,? ,? ,? ,? ,? ,? ,?, ?, ?);");

$update_statement = $connection->prepare("UPDATE anime_catalogue SET name = ?, synopsis = ?, genre = ?, premier_date =?, rating = ?, studio = ?, completion_status = ?, stream = ?, artwork = ?, last_edited = ? WHERE anime_id = ?;");

$delete_statement = $connection->prepare("DELETE FROM anime_catalogue WHERE anime_id = ?;");

$select_statement = $connection->prepare("SELECT * FROM anime_catalogue ORDER BY anime_id DESC LIMIT 3;");

$select_top_anime_statement = $connection->prepare("SELECT *, ROW_NUMBER() OVER (ORDER BY name) AS row_number FROM anime_catalogue WHERE rating = 5;");

$select_genre_statement = $connection->prepare("SELECT * FROM anime_catalogue WHERE genre LIKE CONCAT('%', ?, '%') ORDER BY name ASC;");

$select_fact_statement = $connection->prepare("SELECT * FROM anime_facts WHERE id = ?;");

$delete_fact_statement = $connection->prepare("DELETE FROM anime_facts WHERE id = ?;");

$insert_fact_statement = $connection->prepare("INSERT INTO anime_facts (facts, fact_img) VALUES (?, ?);");

$specific_select_statement = $connection->prepare("SELECT * FROM anime_catalogue WHERE anime_id = ?;");

$username_select_statement = $connection->prepare("SELECT * FROM catalogue_admin WHERE users = ?;");

$quick_search_count_select_statement = $connection->prepare("SELECT COUNT(*) as search_count FROM anime_catalogue WHERE name LIKE CONCAT('%', ?, '%') OR synopsis LIKE CONCAT('%', ?, '%') OR studio LIKE CONCAT('%', ?, '%') OR completion_status LIKE CONCAT('%', ?, '%') OR genre LIKE CONCAT('%', ?, '%') OR premier_date LIKE CONCAT('%', ?, '%');");

function handle_database_errors($statement) {
    global $connection;
    die("Error in: " . $statement . ". Error details: " . $connection->error);
}

function get_username($username) {
    global $connection;
    global $username_select_statement;

    $username_select_statement->bind_param("s", $username);

    if(!$username_select_statement->execute()) {
        handle_database_errors("fetching quick search results");
    } else {
       $result = $username_select_statement->get_result();
        return $result;
    }
}

// Function for select all statement
function get_latest_anime() {
    global $connection;
    global $select_statement;

    if(!$select_statement->execute()) {
        handle_database_errors("fetching anime.");
    } else {
        $result = $select_statement->get_result();
        $anime = [];
        while ($row = $result->fetch_assoc()) {
            $anime[] = $row;
        }
        return $anime;
    }
}

// Function to get 5 star animes
function get_top_animes() {
    global $connection;
    global $select_top_anime_statement;

    if(!$select_top_anime_statement->execute()) {
        handle_database_errors("fetching anime.");
    } else {
        $result = $select_top_anime_statement->get_result();
        $anime = [];
        while ($row = $result->fetch_assoc()) {
            $anime[] = $row;
        }
        return $anime;
    }
}

// Function to insert anime
function insert_anime($name, $synopsis, $genre, $premier_date, $rating, $studio, $completion_status, $stream, $artwork, $username) {
    global $connection;
    global $insert_statement;

    $genre_string = implode(", ", $genre);

    $stream_string = implode(", ", $stream);

    $insert_statement->bind_param("ssssisssss", $name, $synopsis, $genre_string, $premier_date, $rating, $studio, $completion_status, $stream_string, $artwork, $username);



    if(!$insert_statement->execute()) {
        handle_database_errors("inserting anime");
    }
    $id = $connection->insert_id;
    return $id;
}

function update_anime($name, $synopsis, $genre, $premier_date, $rating, $studio, $completion_status, $stream, $artwork, $username, $id) {
    global $connection;
    global $update_statement;

    $genre_string = implode(", ", $genre);
    if ($stream != "") {
        $stream_string = implode(", ", $stream);
    }

    $update_statement->bind_param("ssssisssssi", $name, $synopsis, $genre_string, $premier_date, $rating, $studio, $completion_status, $stream_string, $artwork, $username, $id);

    if(!$update_statement->execute()) {
        handle_database_errors("updating anime");
    }
}

// Function to bind parameters for select anime by id statement
function select_anime_by_id($id) {
    global $connection;
    global $specific_select_statement;

    $specific_select_statement->bind_param("i", $id);

    if(!$specific_select_statement->execute()) {
        handle_database_errors("fetching anime by ID.");
    } else {
        $result = $specific_select_statement->get_result();
        $specific_anime = $result->fetch_assoc();
        return $specific_anime;
    }
}

function delete_anime($id) {
    global $connection;
    global $delete_statement;

    $delete_statement->bind_param("i", $id);

    if(!$delete_statement->execute()) {
        handle_database_errors("deleting anime");
    }
}

function quick_search($part, $limit = 0, $offset = 0) {
    global $connection;

    $sql = "SELECT * FROM anime_catalogue 
            WHERE name LIKE CONCAT('%', ?, '%') 
               OR synopsis LIKE CONCAT('%', ?, '%') 
               OR studio LIKE CONCAT('%', ?, '%') 
               OR completion_status LIKE CONCAT('%', ?, '%') 
               OR genre LIKE CONCAT('%', ?, '%') 
               OR premier_date LIKE CONCAT('%', ?, '%')";

    if ($limit > 0) {
        $sql .= " LIMIT ?";
    }
    if ($offset > 0) {
        $sql .= " OFFSET ?";
    }

    $quick_search_select_statement = $connection->prepare($sql);

    if ($limit > 0 && $offset > 0) {
        $quick_search_select_statement->bind_param("ssssssii", $part, $part, $part, $part, $part, $part, $limit, $offset);
    } elseif ($limit > 0) {
        $quick_search_select_statement->bind_param("ssssssi", $part, $part, $part, $part, $part, $part, $limit);
    } else {
        $quick_search_select_statement->bind_param("ssssss", $part, $part, $part, $part, $part, $part);
    }

    if (!$quick_search_select_statement->execute()) {
        handle_database_errors("fetching quick search results");
    } else {
        $result = $quick_search_select_statement->get_result();
        return $result;
    }
}

function count_search_records($part) {
    global $connection;
    global $quick_search_count_select_statement;

    $quick_search_count_select_statement->bind_param("ssssss", $part, $part, $part, $part, $part, $part);
    if(!$quick_search_count_select_statement->execute()) {
        handle_database_errors("fetching quick search result count");
    } else {
       $count_result = $quick_search_count_select_statement->get_result();
       $count = $count_result->fetch_assoc();
       return $count['search_count'];
    }
}

function search_genre($genre) {
    global $connection;
    global $select_genre_statement;

    $select_genre_statement->bind_param("s", $genre);
    if(!$select_genre_statement->execute()) {
        handle_database_errors("fetching search genre");
    } else {
       $result = $select_genre_statement->get_result();
       return $result;
    }
}

function update_profile($username, $profile_parameters) {
    global $connection;
    
    $fields = [];
    $types = "";
    $parameters = [];

    if (!empty($profile_parameters['name'])) {
        $fields[] = "user_name = ?";
        $types .= "s";
        $parameters[] = $profile_parameters['name'];
    }

    if (!empty($profile_parameters['profile-img'])) {
        $fields[] = "profile_picture = ?";
        $types .= "s";
        $parameters[] = $profile_parameters['profile-img'];
    }

    // Construct the SQL statement
    $sql = "UPDATE catalogue_admin SET " . implode(", ", $fields) . " WHERE users = ?;";
    $types .= "s";
    $parameters[] = $username;

    // Prepare and execute the statement
    $statement = $connection->prepare($sql);
    $statement->bind_param($types, ...$parameters);
    
    if(!$statement->execute()) {
        handle_database_errors("updating profile");
    }
}

function select_fact_by_id($id) {
    global $connection;
    global $select_fact_statement;

    $select_fact_statement->bind_param("i", $id);

    if(!$select_fact_statement->execute()) {
        handle_database_errors("fetching fact by ID.");
    } else {
        $result = $select_fact_statement->get_result();
        $fact = $result->fetch_assoc();
        return $fact;
    }
}

function delete_fact($id) {
    global $connection;
    global $delete_fact_statement;

    $delete_fact_statement->bind_param("i", $id);

    if(!$delete_fact_statement->execute()) {
        handle_database_errors("deleting fact");
    }
}

function insert_fact($fact_parameters) {
    global $connection;
    global $insert_fact_statement;

    $insert_fact_statement->bind_param("ss", $fact_parameters['fact'], $fact_parameters['img']);

    if(!$insert_fact_statement->execute()) {
        handle_database_errors("inserting fact");
    }
}

function update_fact($fact_parameters) {
    global $connection;
    
    $fields = [];
    $types = "";
    $parameters = [];

    if (!empty($fact_parameters['fact'])) {
        $fields[] = "facts = ?";
        $types .= "s";
        $parameters[] = $fact_parameters['fact'];
    }

    if (!empty($fact_parameters['img'])) {
        $fields[] = "fact_img = ?";
        $types .= "s";
        $parameters[] = $fact_parameters['img'];
    }

    $sql = "UPDATE anime_facts SET " . implode(", ", $fields) . " WHERE id = ?;";
    $parameters[] = $fact_parameters['id'];
    $types .= "i";

    $statement = $connection->prepare($sql);
    $statement->bind_param($types, ...$parameters);
    
    if(!$statement->execute()) {
        handle_database_errors("updating Fact");
    }
}
