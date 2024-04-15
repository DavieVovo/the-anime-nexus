<?php
// Login was failing due regenerating session, was told to add ob_start() to start output buffering
ob_start();
session_start();

// x

require_once('../private/prepared.php');

// 1/2 of genres to generate first part of checkboxes
$genres_1 = [
    'Action',
    'Adventure',
    'Comedy',
    'Drama',
    'Ecchi',
    'Fantasy',
    'Gourmet',
    'Horror'
];
// 1/2 of genres to generate second part of checkboxes
$genres_2 = [
    'Isekai',
    'Mystery',
    'Romance',
    'Sci-Fi',
    'Slice of Life',
    'Sports',
    'Supernatural',
    'Suspense'
];

// Advanced search processing
if (isset($_POST['submit'])) {
    // Reset session variables
    $_SESSION['name'] = '';
    $_SESSION['studio'] = '';
    $_SESSION['completion_status'] = '';
    $_SESSION['genre'] = [];
    $_SESSION['rating'] = '';
    $_SESSION['order_by'] = 'name';
    $_SESSION['order'] = 'asc';
    $_SESSION['min_year'] = '1990';
    $_SESSION['max_year'] = date('Y') + 1;

    // Process advanced search form data and set session variables
    if (!empty($_POST['name'])) {
        $_SESSION['name'] = mysqli_real_escape_string($connection, $_POST['name']);
    }

    if (!empty($_POST['studio'])) {
        $_SESSION['studio'] = mysqli_real_escape_string($connection, $_POST['studio']);
    }

    if (!empty($_POST['completion_status'])) {
        $_SESSION['completion_status'] = $_POST['completion_status'];
    }

    if (!empty($_POST['genre']) && is_array($_POST['genre'])) {
        $_SESSION['genre'] = $_POST['genre'];
    }

    if (!empty($_POST['rating'])) {
        $_SESSION['rating'] = $_POST['rating'];
    }

    if (!empty($_POST['order_by'])) {
        $_SESSION['order_by'] = $_POST['order_by'];
    }

    if (!empty($_POST['order'])) {
        $_SESSION['order'] = $_POST['order'];
    }

    if (!empty($_POST['min_year']) && is_numeric($_POST['min_year'])) {
        $_SESSION['min_year'] = $_POST['min_year'];
    }

    if (!empty($_POST['max_year']) && is_numeric($_POST['max_year'])) {
        $_SESSION['max_year'] = $_POST['max_year'];
    }

    header('Location: search-results.php');
    exit();
}

// Get user information
$username = $_SESSION['username'];
$user_info = get_username($username);
$user = $user_info->fetch_assoc();
$profile_img = $user['profile_picture'];
$user_name = $user['user_name'];

// Toggle dark mode, set it in session and check if dark mode is set
if (isset($_POST['toggle-dark-mode'])) {
    $_SESSION['dark-mode'] = !empty($_SESSION['dark-mode']) ? false : true;
}
$dark_mode = !empty($_SESSION['dark-mode']);

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            <?php echo $title; ?>
        </title>
        <!-- BS Styles -->
        <link rel="icon" href="img/svg/">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <!-- BS Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>

    <body class="d-flex flex-column justify-content-between min-vh-100 <?php echo $dark_mode ? 'text-bg-dark' : '';?>">
        <header class="">
            <nav class="py-2 border-bottom <?php echo $dark_mode ? 'text-bg-dark border-secondary' : 'bg-secondary';?>">
                <div class="container d-flex flex-wrap align-items-center justify-content-md-between justify-content-center">
                    <ul class="nav">
                    <form method="post" class="d-flex align-items-center">
                        <button type="submit" name="toggle-dark-mode" class="btn"><?php echo $dark_mode == 1 ? '<svg height="32px" width="32px" version="1.1" id="_x34_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <g> <path style="fill:#BFB61E;" d="M258.373,448.122c-11.783,0-21.337,1.395-21.337,18.136c0,8.131,9.553,45.742,21.337,45.742 c11.784,0,21.336-37.611,21.336-45.742C279.709,449.518,270.156,448.122,258.373,448.122z"></path> <path style="fill:#BFB61E;" d="M352.653,422.86c-10.205,5.891-17.78,11.876-9.41,26.374c4.065,7.041,31.144,34.837,41.349,28.945 c10.205-5.892-0.328-43.241-4.393-50.282C371.829,413.4,362.858,416.968,352.653,422.86z"></path> <path style="fill:#BFB61E;" d="M448.046,344.432c-14.498-8.37-20.483-0.795-26.375,9.41c-5.892,10.205-9.46,19.176,5.038,27.546 c7.041,4.065,44.39,14.598,50.282,4.393C482.883,375.576,455.087,348.497,448.046,344.432z"></path> <path style="fill:#BFB61E;" d="M465.07,238.225c-16.741,0-18.136,9.553-18.136,21.337c0,11.784,1.396,21.336,18.136,21.336 c8.13,0,45.742-9.553,45.742-21.336C510.812,247.777,473.2,238.225,465.07,238.225z"></path> <path style="fill:#BFB61E;" d="M426.71,137.735c-14.498,8.37-10.93,17.341-5.038,27.546c5.892,10.204,11.877,17.78,26.375,9.41 c7.041-4.065,34.837-31.144,28.945-41.349C471.099,123.137,433.75,133.67,426.71,137.735z"></path> <path style="fill:#BFB61E;" d="M164.092,422.86c-10.205-5.892-19.176-9.46-27.546,5.038c-4.065,7.041-14.598,44.39-4.393,50.282 c10.205,5.892,37.283-21.904,41.349-28.945C181.872,434.737,174.297,428.752,164.092,422.86z"></path> <path style="fill:#BFB61E;" d="M424.226,259.561c0-45.799-18.564-87.263-48.577-117.276L141.097,376.837 c30.013,30.013,71.477,48.578,117.276,48.578C349.971,425.415,424.226,351.159,424.226,259.561z"></path> </g> <g> <path style="fill:#C6BA56;" d="M164.11,96.239c-10.143,5.855-19.05,9.401-27.297-4.618c-0.082-0.083-0.165-0.247-0.248-0.412 c-4.123-7.009-14.596-44.367-4.453-50.305c7.669-4.454,25.07,10.308,34.719,20.781c3.298,3.464,5.69,6.433,6.68,8.164 C181.84,84.364,174.336,90.384,164.11,96.239z"></path> <g> <path style="fill:#C6BA56;" d="M279.729,52.861v0.577c-0.248,16.164-9.732,17.566-21.359,17.566 c-9.319,0-17.236-0.907-20.122-9.483c-0.824-2.227-1.237-5.113-1.237-8.66c0-5.03,3.629-21.276,9.154-32.987 c3.546-7.257,7.752-12.782,12.205-12.782c1.319,0,2.639,0.495,3.876,1.402C272.225,15.174,279.729,45.604,279.729,52.861z"></path> <path style="fill:#C6BA56;" d="M95.085,165.264c-5.938,10.226-11.875,17.813-26.39,9.401 c-3.958-2.227-14.432-11.793-21.854-21.524c-0.082-0.083-0.165-0.165-0.165-0.248c-5.69-7.504-9.484-15.091-6.928-19.545 c5.938-10.226,43.213,0.33,50.305,4.371c1.237,0.742,2.391,1.484,3.381,2.226C103.909,147.699,100.445,155.945,95.085,165.264z"></path> <path style="fill:#C6BA56;" d="M69.85,259.524c0,11.546-1.32,21.03-17.236,21.359h-0.907c-7.834,0-43.13-8.907-45.605-20.122 c-0.082,0-0.082,0-0.082,0c0-0.412-0.083-0.824-0.083-1.237c0-4.536,5.69-8.824,13.112-12.205 c11.711-5.525,27.709-9.071,32.657-9.071c4.701,0,8.164,0.742,10.721,2.062C69.108,243.773,69.85,251.113,69.85,259.524z"></path> <path style="fill:#C6BA56;" d="M68.7,344.432c-7.041,4.065-34.837,31.144-28.945,41.349c5.892,10.205,43.241-0.328,50.281-4.393 c14.498-8.37,10.93-17.341,5.038-27.546C89.183,343.637,83.197,336.062,68.7,344.432z"></path> <path style="fill:#C6BA56;" d="M352.653,96.263c10.205,5.892,19.176,9.46,27.546-5.038c4.065-7.041,14.598-44.39,4.393-50.282 c-10.205-5.892-37.284,21.904-41.349,28.945C334.873,84.386,342.448,90.371,352.653,96.263z"></path> <path style="fill:#C6BA56;" d="M258.373,93.708c-91.598,0-165.853,74.255-165.853,165.853 c0,45.799,18.563,87.262,48.577,117.276l234.552-234.552C345.635,112.271,304.172,93.708,258.373,93.708z"></path> </g> </g> </g> <g> <path style="fill:#EFE748;" d="M252.408,440.964c-11.783,0-21.337,1.395-21.337,18.136c0,8.131,9.553,45.742,21.337,45.742 s21.336-37.611,21.336-45.742C273.744,442.36,264.191,440.964,252.408,440.964z"></path> <path style="fill:#EFE748;" d="M346.688,415.702c-10.205,5.892-17.78,11.877-9.41,26.375c4.065,7.041,31.144,34.837,41.349,28.945 c10.205-5.892-0.328-43.241-4.393-50.282C365.864,406.242,356.893,409.81,346.688,415.702z"></path> <path style="fill:#EFE748;" d="M442.081,337.274c-14.498-8.37-20.483-0.795-26.375,9.41c-5.892,10.205-9.46,19.176,5.038,27.546 c7.041,4.065,44.39,14.598,50.282,4.393C476.918,368.418,449.122,341.339,442.081,337.274z"></path> <path style="fill:#EFE748;" d="M459.105,231.066c-16.741,0-18.136,9.553-18.136,21.337c0,11.784,1.395,21.336,18.136,21.336 c8.13,0,45.742-9.553,45.742-21.336C504.846,240.619,467.235,231.066,459.105,231.066z"></path> <path style="fill:#EFE748;" d="M420.744,130.577c-14.497,8.37-10.93,17.341-5.038,27.546c5.892,10.205,11.877,17.78,26.375,9.41 c7.041-4.065,34.837-31.144,28.945-41.349C465.134,115.979,427.785,126.511,420.744,130.577z"></path> <path style="fill:#EFE748;" d="M158.127,415.702c-10.205-5.892-19.176-9.46-27.546,5.038c-4.065,7.041-14.598,44.39-4.392,50.282 c10.205,5.892,37.283-21.904,41.349-28.945C175.907,427.578,168.332,421.594,158.127,415.702z"></path> <path style="fill:#EFE748;" d="M418.261,252.403c0-45.799-18.564-87.263-48.577-117.276L135.132,369.679 c30.014,30.013,71.477,48.578,117.276,48.578C344.006,418.257,418.261,344.001,418.261,252.403z"></path> </g> <g> <path style="fill:#FAF2AF;" d="M158.09,89.065c-7.67,4.453-14.679,7.587-21.277,2.557c-2.144-1.567-4.206-4.041-6.268-7.587 c-4.041-7.01-14.597-44.367-4.371-50.223c9.814-5.69,34.967,19.545,40.657,27.874c0.33,0.412,0.577,0.742,0.742,1.072 C175.903,77.189,168.316,83.209,158.09,89.065z"></path> <g> <path style="fill:#FAF2AF;" d="M273.709,45.687c0,0.577,0,1.155-0.083,1.65c-0.577,15.174-9.814,16.493-21.194,16.493 c-4.288,0-8.247-0.165-11.628-1.237c-0.907-0.247-1.732-0.659-2.556-1.072c-2.722-1.402-4.866-3.711-6.02-7.422 c-0.083-0.083,0-0.083,0-0.083c-0.742-2.227-1.155-5.03-1.155-8.329c0-4.865,3.464-20.452,8.824-32.08 c3.216-7.01,7.175-12.617,11.381-13.442C251.69,0.083,252.02,0,252.432,0c3.547,0,6.927,3.463,9.814,8.494 c0.99,1.649,1.897,3.464,2.804,5.443C270.328,25.482,273.709,40.904,273.709,45.687z"></path> <path style="fill:#FAF2AF;" d="M89.147,158.09c-5.937,10.226-11.875,17.813-26.39,9.484c-2.969-1.732-9.648-7.505-15.916-14.432 c-0.082-0.083-0.165-0.165-0.165-0.248c-8.577-9.401-16.246-20.864-12.865-26.719c5.855-10.226,43.213,0.33,50.222,4.371 c5.195,3.051,8.164,6.185,9.401,9.401C95.662,145.637,92.858,151.658,89.147,158.09z"></path> <path style="fill:#FAF2AF;" d="M63.83,252.432c0,11.793-1.402,21.277-18.142,21.277H45.44c-5.03-0.083-20.122-3.382-31.503-8.577 c-2.886-1.402-5.608-2.804-7.835-4.371c-0.082,0-0.082,0-0.082,0C2.309,258.205,0,255.401,0,252.432c0-0.33,0-0.742,0.165-1.072 c0.742-4.041,5.69-7.669,12.04-10.886c11.793-5.608,28.451-9.401,33.482-9.401c1.897,0,3.629,0.165,5.196,0.412 c6.762,0.99,9.978,4.288,11.545,8.824c0.412,1.072,0.66,2.309,0.825,3.546C63.748,246.412,63.83,249.381,63.83,252.432z"></path> <path style="fill:#FAF2AF;" d="M84.034,374.237c-5.196,3.051-27.379,9.649-40.739,8.576c-1.567-0.083-2.969-0.33-4.288-0.742 c-2.392-0.66-4.206-1.815-5.195-3.464c-0.908-1.567-1.072-3.629-0.577-5.855c2.804-12.206,23.503-32.08,29.523-35.461 c2.969-1.732,5.608-2.804,7.917-3.216c7.917-1.732,12.453,2.969,16.659,9.566c0.577,0.99,1.237,1.979,1.814,3.051 c2.062,3.711,3.959,7.175,4.701,10.556C95.25,363.268,93.353,368.876,84.034,374.237z"></path> <path style="fill:#FAF2AF;" d="M346.688,89.104c10.205,5.892,19.176,9.46,27.546-5.038c4.065-7.041,14.598-44.39,4.393-50.282 c-10.205-5.892-37.284,21.904-41.349,28.945C328.908,77.228,336.483,83.213,346.688,89.104z"></path> <path style="fill:#FAF2AF;" d="M369.701,135.164l-0.743,0.742l-3.381,3.381L135.164,369.701 c-2.474-2.474-4.783-4.948-7.092-7.587c-6.185-7.009-11.793-14.514-16.741-22.513c-15.668-25.318-24.74-55.171-24.74-87.168 c0-91.621,74.221-165.842,165.842-165.842c29.936,0,58.057,7.917,82.385,21.936C347.6,115.784,359.31,124.773,369.701,135.164z"></path> </g> </g> </g> </g></svg>' : '<svg width="32px" height="32px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M22 12.0004C22 17.5232 17.5228 22.0004 12 22.0004C10.8358 22.0004 9.71801 21.8014 8.67887 21.4357C8.24138 20.3772 8 19.217 8 18.0004C8 15.7792 8.80467 13.7459 10.1384 12.1762C11.31 13.8818 13.2744 15.0004 15.5 15.0004C17.8615 15.0004 19.9289 13.741 21.0672 11.8572C21.3065 11.4612 22 11.5377 22 12.0004Z" fill="#1C274C"></path> <path d="M2 12C2 16.3586 4.78852 20.0659 8.67887 21.4353C8.24138 20.3768 8 19.2166 8 18C8 15.7788 8.80467 13.7455 10.1384 12.1758C9.42027 11.1303 9 9.86422 9 8.5C9 6.13845 10.2594 4.07105 12.1432 2.93276C12.5392 2.69347 12.4627 2 12 2C6.47715 2 2 6.47715 2 12Z" fill="#1C274C"></path> </g></svg>'?></button>
                    </form>
                        <li class="nav-item"><a href="index.php" class="nav-link link-light link-body-emphasis px-3">Home</a></li>
                        <li class="nav-item"><a href="browse.php?sort_by=name&order_by=ASC" class="nav-link link-light link-body-emphasis px-3">Browse Anime</a></li>
                        <li class="nav-item"><a href="search-results.php?genre" class="nav-link link-light link-body-emphasis px-3">Browse Genres</a></li>
                        <li class="nav-item"><button type="button" class="nav-link link-light link-body-emphasis px-3 btn btn-link" data-bs-toggle="modal" data-bs-target="#search-modal">Advanced Search</button></li>
                    </ul>
                    <form method="post">

                    <div class="align-items-center d-flex">
                        <?php if(isset($_SESSION["username"])):?>
                            <div class="nav-item dropdown d-flex gap-3 align-items-center">
                                <p class="mb-0 text-light">Welcome, <?php echo $user_name != '' ? $user_name : $username;?></p>
                                <a class="nav-link dropdown-toggle text-light" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><img src="profile/thumbs/<?php echo $profile_img?>" alt="Profile image of <?php echo $username?>" class="rounded-circle"></a>
                                <ul class="dropdown-menu bg-secondary mt-1">
                                    <li class="nav-item"><a href="add.php" class="nav-link link-light link-body-emphasis px-3 mb-1">Add Anime</a>
                                    <li class="nav-item"><a href="edit.php" class="nav-link link-light link-body-emphasis px-3 mb-1">Edit Anime</a>
                                    <li class="nav-item"><a href="facts.php" class="nav-link link-light link-body-emphasis px-3 mb-1">Edit Facts</a>
                                    <li class="nav-item"><a href="profile-update.php" class="nav-link link-light link-body-emphasis px-3 mb-1">Update Profile</a>
                                    <li class="nav-item"><a href="logout.php" class="nav-link link-light link-body-emphasis px-3">Log Out</a>
                                </ul>
                        </div>
                        <?php else :?>
                            <a href="login.php" class="nav-link link-light link-body-emphasis px-3">Log In</a>
                        <?php endif ?>
                    </div>
                </div>
            </nav>
            <!-- Modal for advanced search -->
            <div class="modal fade" id="search-modal" tabindex="-1" aria-labelledby="modal-search" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header text-bg-info">
                            <h3 class="modal-title fs-5" id="modal-title-search">Advanced Search</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body <?php echo $dark_mode ? 'text-bg-dark' : '';?>">
                        <!-- Message if search does not pass -->
                        <?php if ($message != '') : ?>
                        <div class="alert alert-info" role="alert">
                            <?php echo $message; ?>
                        </div>
                        <?php endif;?>
                        <!-- Advances search form -->
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                            <!-- Name search -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo !empty($_SESSION['name']) ? $_SESSION['name'] : '';?>">
                            </div>
                            <!-- Studio search -->
                            <div class="mb-3">
                                <label for="studio" class="form-label">Studio:</label>
                                <input type="text" class="form-control" id="studio" name="studio" value="<?php echo !empty($_SESSION['studio']) ? $_SESSION['studio'] : '';?>">
                            </div>
                            <!-- Completion Status search -->
                            <div class="mb-3">
                        <label for="completion_status">Completion Status</label>
                        <select name="completion_status" id="completion_status" class="form-select">
                            <option value="">Select</option>
                            <option value="Completed" <?php if ($_SESSION['completion_status'] == 'Completed') echo 'selected';?>>Completed</option>
                            <option value="Watching" <?php if ($_SESSION['completion_status'] == 'Watching') echo 'selected';?>>Watching</option>
                            <option value="Plan to watch" <?php if ($_SESSION['completion_status'] == 'Plan to watch') echo 'selected';?>>Plan to watch</option>
                        </select>
                        <?php if(isset($status_message)) :?>
                            <div class="message text-danger">
                                <?php echo $status_message; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                            <!-- Genre checkbox search -->
                            <div class="mb-3">
                                <label for="genre" class="form-label">Genre:</label>
                                <div class="d-flex gap-3">
                                    <!-- First half of genres -->
                                    <div>
                                        <?php
                                        foreach ($genres_1 as $genre) {
                                            $is_checked = '';

                                            if (isset($_POST['genre']) && is_array($_POST['genre'])) {
                                                $is_checked = (in_array($genre, $_POST['genre'])) ? 'checked' : '';
                                            } elseif (isset($_SESSION['genre']) && is_array($_SESSION['genre'])) {
                                                $is_checked = (in_array($genre, $_SESSION['genre'])) ? 'checked' : '';
                                            }

                                            echo '<div class="form-check mb-1">';
                                            echo '<input class="form-check-input" type="checkbox" id="' . strtolower(str_replace(' ', '-', $genre)) . '" name="genre[]" value="' . $genre . '" ' . $is_checked . '>';
                                            echo '<label class="form-check-label" for="' . strtolower(str_replace(' ', '-', $genre)) . '">' . $genre . '</label>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                    <!-- Second half of genres -->
                                    <div>
                                        <?php
                                        foreach ($genres_2 as $genre) {
                                            $is_checked = '';

                                            if (isset($_POST['genre']) && is_array($_POST['genre'])) {
                                                $is_checked = (in_array($genre, $_POST['genre'])) ? 'checked' : '';
                                            } elseif (isset($_SESSION['genre']) && is_array($_SESSION['genre'])) {
                                                $is_checked = (in_array($genre, $_SESSION['genre'])) ? 'checked' : '';
                                            }

                                            echo '<div class="form-check mb-1">';
                                            echo '<input class="form-check-input" type="checkbox" id="' . strtolower(str_replace(' ', '-', $genre)) . '" name="genre[]" value="' . $genre . '" ' . $is_checked . '>';
                                            echo '<label class="form-check-label" for="' . strtolower(str_replace(' ', '-', $genre)) . '">' . $genre . '</label>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Rating Minimum Search-->
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label for="rating" class="form-label">Minimum Rating:</label>
                                    <select class="form-select" id="rating" name="rating">
                                    <?php
                                $rating_arr = [
                                    '' => '',
                                    '1' => '⭐',
                                    '2' => '⭐⭐',
                                    '3' => '⭐⭐⭐',
                                    '4' => '⭐⭐⭐⭐',
                                    '5' => '⭐⭐⭐⭐⭐',
                                ];
                                foreach($rating_arr as $key => $value) {
                                    if ($_SESSION['rating'] == $key) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option value=\"$key\" $selected>$value</option>";
                                }
                            ?>
                                    </select>
                                </div>
                            </div>
                            <!-- Year search between min and max -->
                            <div class="mb-3">
                                <label for="year" class="form-label">Year:</label>
                                <div class="d-flex align-items-center gap-3">
                                    <p class="mb-0">Between</p>
                                    <input type="number" class="form-control" id="min_year" name="min_year" value="<?php echo !empty($_SESSION['min_year']) ? $_SESSION['min_year'] : '1990';?>" style="width: 20%;">
                                    <p class="mb-0">and</p>
                                    <input type="number" class="form-control" id="max_year" name="max_year" value="<?php echo !empty($_SESSION['max_year']) ? $_SESSION['max_year'] : (int)date('Y') + 1; ?>" style="width: 20%;">
                                </div>
                            </div>
                            <!-- Order the search by-->
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label for="order_by" class="form-label">Order By:</label>
                                    <select class="form-select" id="order_by" name="order_by">
                                        <option value="name" <?php echo $_SESSION['order_by'] == 'name' ? 'selected' : '';?>>Name</option>
                                        <option value="rating" <?php echo $_SESSION['order_by'] == 'rating' ? 'selected' : '';?>>Rating</option>
                                    </select>
                                </div>
                               <!-- Order by ascending or descending order -->
                                <div class="form-check">
                                    <input type="radio" id="asc" name="order" value="asc" class="form-check-input" 
                                    <?php echo $_SESSION['order'] == 'desc' || empty($_SESSION['order']) ? '' : 'checked';?>>
                                    <label for="asc" class="form-check-label">Ascending</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="desc" name="order" value="desc" class="form-check-input" 
                                    <?php echo $_SESSION['order'] == 'desc' ? 'checked' : '';?>>
                                    <label for="order" class="form-check-label">Descending</label>
                                </div>
                            </div>
                            <!-- Submit button -->
                            <button type="submit" name="submit" id="submit" class="btn btn-info">Submit</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
            <section class="py-3 border-bottom shadow <?php echo $dark_mode ? 'text-bg-secondary border-secondary' : '';?>">
                <div class="container d-flex flex-wrap justify-content-center">
                    <a href="index.php"
                        class="d-flex align-items-center mb-3 mb-lg-0 me-lg-auto link-body-emphasis text-decoration-none gap-3">
                        <img src="img/svg/icon.svg" alt="anime eyes logo">
                        <h1 class="fs-4 mb-0 fw-light text-info">The Anime Nexus</h1>
                    </a>
                    <form class="col-12 col-lg-auto mb-3 mb-lg-0" role="search" method="GET" action="search-results.php">
                    <input type="search" class="form-control" aria-label="Search" placeholder="search" id="search" name="search">
                    </form>
                </div>
            </section>
        </header>