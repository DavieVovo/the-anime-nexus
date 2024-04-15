<?php

define("DB_SERVER", "localhost");
define("DB_USER", "uqcps533");
define("DB_PASS", "7j8gde[OY53#MG");
define("DB_NAME", "uqcps533_anime_catalogue");

function db_connect() {
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_errno();
    } else {
        return $connection;
    }
}

function db_disconnect($connection) {
    if (isset($connection)) {
        mysqli_close($connection);
    }
}

?>