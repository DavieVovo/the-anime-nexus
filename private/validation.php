<?php

require_once('functions.php');
require_once('prepared.php');

/*
    Anime validation & image processing
*/

if (isset($_POST['add']) || isset($_POST['update'])) {
    extract($_POST);

    $form_good = true;

    $message = '';
    $file_message = '';
    $name_message = '';
    $synopsis_message = '';
    $premier_message = '';
    $status_message = '';
    $genre_message = '';

    // Image file information
    $file = isset($_FILES['img-file']) ? $_FILES['img-file'] : '';
    $file_name = isset($_FILES['img-file']['name']) ? $_FILES['img-file']['name'] : '';
    $file_temp_name = isset($_FILES['img-file']['tmp_name']) ? $_FILES['img-file']['tmp_name'] : '';
    $file_size = isset($_FILES['img-file']['size']) ? $_FILES['img-file']['size'] : '';
    $file_error = isset($_FILES['img-file']['error']) ? $_FILES['img-file']['error'] : 0;

    // Image file extension
    $file_extension = $file_name ? strtolower(pathinfo($file_name, PATHINFO_EXTENSION)) : '';
    $allowed = array('jpg', 'jpeg', 'png', 'webp');

    if ((!empty($file_temp_name)) && !in_array($file_extension, $allowed)) {
        $form_good = false;
        $file_message = "Invalid file extension. Allowed extensions are JPG, JPEG, PNG, and WEBP.";
    }

    // Name Validation
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $name = mysqli_real_escape_string($connection, $name);

    if (strlen($name) < 2 || strlen($name) > 100) {
        $form_good = false;
        $name_message .= "<p>Please enter an anime name that is between 2-100 characters</p>";
    }

    // Synopsis Validation
    $synopsis = filter_var($synopsis, FILTER_SANITIZE_STRING);
    $synopsis = mysqli_real_escape_string($connection, $synopsis);

    if (strlen($synopsis) < 50) {
        $form_good = false;
        $synopsis_message .= "<p>Please enter a synopsis that is more than 50 characters</p>";
    }

    // Premier Date Validations
    if ($premier_date_season == '') {
        $form_good = false;
        $premier_season_message .= "<p class=\"mb-0\">Please select a premier date season.</p>";
    }

    if (empty($premier_date_year) || $premier_date_year < 1990 || $premier_date_year > date('Y') + 1) {
        $form_good = false;
        $premier_year_message .= "<p>Please enter a valid premier date year.</p>";
    }

    // Studio Validation
    $studio = filter_var($studio, FILTER_SANITIZE_STRING);
    $studio = mysqli_real_escape_string($connection, $studio);

    if (strlen($studio) < 2 || strlen($studio) > 50) {
        $form_good = false;
        $studio_message .= "<p>Please enter a studio that is between 2-50 characters</p>";
    }

    // Status Validation
    if ($completion_status == '') {
        $form_good = false;
        $status_message .= "<p>Please select a completion status</p>";
    }

    // Genre Validation
    if (!is_array($genre) || (is_array($genre) && empty($genre))) {
        $form_good = false;
        $genre_message .= "<p>Please select at least 1 genre.</p>";
    }

    // Check to see if the directory exists; if not, make it.
    if (!is_dir('images/full/')) {
        mkdir('images/full', 0777, TRUE);
    }

    if (!is_dir('images/thumbs/')) {
        mkdir('images/thumbs', 0777, TRUE);
    }
    
    // Proceed if validated
    if ($form_good) {
        // Image Validation and creation
        if (!empty($file['tmp_name'])) {

            if (in_array($file_extension, $allowed) && $file_error === 0 && $file_size < 2000000) {

                $file_name_new = uniqid('', true) . "." . $file_extension;
                $file_destination = 'images/full/' . $file_name_new;

                // Move the uploaded file to the directory.
                move_uploaded_file($file_temp_name, $file_destination);

                // Check the image dimensions.
                $image_info = getimagesize($file_destination);
                $width_original = $image_info[0];
                $height_original = $image_info[1];

                // Creates an empty canvas that is 256px x 256px.
                $thumb = imagecreatetruecolor(256, 256);

                // Calculate the shorter side / smaller size between width and height.
                $smaller_size = min($width_original, $height_original);

                // Calculate the starting point for cropping the image.
                $x_coordinate = ($width_original > $smaller_size) ? ($width_original - $smaller_size) / 2 : 0;
                $y_coordinate = ($height_original > $smaller_size) ? ($height_original - $smaller_size) / 2 : 0;

                // Create image based on the filetype we grabbed earlier.
                switch ($file_extension) {
                    case 'jpeg':
                    case 'jpg':
                        $src_image = imagecreatefromjpeg($file_destination);
                        break;
                    case 'png':
                        $src_image = imagecreatefrompng($file_destination);
                        break;
                    case 'webp':
                        $src_image = imagecreatefromwebp($file_destination);
                        break;
                    default:
                        // Invalid Type
                        $form_good = false;
                        $file_message .= "<p>This file type is not supported.</p>";
                        exit;
                }

                // Crop and resize the user-uploaded image.
                imagecopyresampled($thumb, $src_image, 0, 0, $x_coordinate, $y_coordinate, 256, 256, $smaller_size, $smaller_size);

                // Save the thumbnail to the server.
                imagejpeg($thumb, 'images/thumbs/' . $file_name_new, 100);

                // Free up some server resources by destroying the working object.
                imagedestroy($thumb);
                imagedestroy($src_image);

                $file_name_db = $file_name_new;

                $message .= "<p>Image uploaded successfully!</p>";
            } else {
                $form_good = false;
                if ($file_size >= 2000000) {
                    $file_message .= "<p>Your file is too big! Please upload an image under 2MB.</p>";
                } else {
                    $file_message .= "<p>There was an error with this file. Please upload a valid image.</p>";
                }
            }
        } else {
            $file_name_db = $existing_artwork;
        }

        $premier_date = $premier_date_season . ' ' . $premier_date_year;
    
        if (isset($_POST['update'])) {
            update_anime($name, $synopsis, $genre, $premier_date, $rating, $studio, $completion_status, $stream, $file_name_db, $username, $aid);
            $update_message = "<p>" . $name . " was updated successfully</p>";

            $aid = "";
            header("Location: $url");
            exit;
        }
    
        if (isset($_POST['add'])) {
            $inserted_id = insert_anime($name, $synopsis, $genre, $premier_date, $rating, $studio, $completion_status, $stream, $file_name_db, $username);
            $message .= "<p>$name was successfully added into the database!</p>";
            echo $inserted_id;
        
            header("Location: view.php?id=$inserted_id");
            exit;
        }
    }
}

/*
    Profile validation & image processing
*/

if (isset($_POST['update-profile'])) {
    extract($_POST);

    $form_good = true;

    $message = '';

    $file = isset($_FILES['profile-img']) ? $_FILES['profile-img'] : '';
    $file_name = isset($_FILES['profile-img']['name']) ? $_FILES['profile-img']['name'] : '';
    $file_temp_name = isset($_FILES['profile-img']['tmp_name']) ? $_FILES['profile-img']['tmp_name'] : '';
    $file_size = isset($_FILES['profile-img']['size']) ? $_FILES['profile-img']['size'] : '';
    $file_error = isset($_FILES['profile-img']['error']) ? $_FILES['profile-img']['error'] : 0;

    // Image file extension
    $file_extension = $file_name ? strtolower(pathinfo($file_name, PATHINFO_EXTENSION)) : '';
    $allowed = array('jpg', 'jpeg', 'png', 'webp');

    // Name Validation
    $profile_name = filter_var($profile_name, FILTER_SANITIZE_STRING);
    $profile_name = mysqli_real_escape_string($connection, $profile_name);

    if ($profile_name != '') {
        if (!is_letters($profile_name)) {
            $message = "<p>Your name can only container letters and spaces.</p>";
            $form_good = FALSE;
        } elseif ($profile_name == null || $profile_name == FALSE) {
            $message = "<p>Please enter your name.</p>";
            $form_good = FALSE;
        }
    }

    if ((!empty($file_temp_name)) && !in_array($file_extension, $allowed)) {
        $form_good = false;
        $message = "Invalid file extension. Allowed extensions are JPG, JPEG, PNG, and WEBP.";
    }

    // Check to see if the directory exists; if not, make it.
    if (!is_dir('profile/full/')) {
        mkdir('profile/full', 0777, TRUE);
    }

    if (!is_dir('profile/thumbs/')) {
        mkdir('profile/thumbs', 0777, TRUE);
    }
    // Proceed if validated
    if ($form_good) {
        // Image Validation and creation
        if (!empty($file_temp_name)) {

            if (in_array($file_extension, $allowed) && $file_error === 0 && $file_size < 2000000) {

                $file_name_new = uniqid('', true) . "." . $file_extension;
                $file_destination = 'profile/full/' . $file_name_new;

                // Move the uploaded file to the directory.
                move_uploaded_file($file_temp_name, $file_destination);

                // Check the image dimensions.
                $image_info = getimagesize($file_destination);
                $width_original = $image_info[0];
                $height_original = $image_info[1];

                // Creates an empty canvas that is 256px x 256px.
                $thumb = imagecreatetruecolor(256, 256);

                // Calculate the shorter side / smaller size between width and height.
                $smaller_size = min($width_original, $height_original);

                // Calculate the starting point for cropping the image.
                $x_coordinate = ($width_original > $smaller_size) ? ($width_original - $smaller_size) / 2 : 0;
                $y_coordinate = ($height_original > $smaller_size) ? ($height_original - $smaller_size) / 2 : 0;

                // Create image based on the filetype we grabbed earlier.
                switch ($file_extension) {
                    case 'jpeg':
                    case 'jpg':
                        $src_image = imagecreatefromjpeg($file_destination);
                        break;
                    case 'png':
                        $src_image = imagecreatefrompng($file_destination);
                        break;
                    case 'webp':
                        $src_image = imagecreatefromwebp($file_destination);
                        break;
                    default:
                        // Invalid Type
                        $form_good = false;
                        $file_message .= "<p>This file type is not supported.</p>";
                        exit;
                }

                // Crop and resize the user-uploaded image.
                imagecopyresampled($thumb, $src_image, 0, 0, $x_coordinate, $y_coordinate, 256, 256, $smaller_size, $smaller_size);

                // Save the thumbnail to the server.
                imagejpeg($thumb, 'profile/thumbs/' . $file_name_new, 100);

                // Free up some server resources by destroying the working object.
                imagedestroy($thumb);
                imagedestroy($src_image);

                $file_name_db = $file_name_new;

                $message .= "<p>Image uploaded successfully!</p>";
            } else {
                $form_good = false;
                if ($file_size >= 2000000) {
                    $file_message .= "<p>Your file is too big! Please upload an image under 2MB.</p>";
                } else {
                    $file_message .= "<p>There was an error with this file. Please upload a valid image.</p>";
                }
            }
        } else {
            $file_name_db = '';
        }
        $profile_parameters = [
            'name' => isset($_POST['profile_name']) ? $_POST['profile_name'] : '',
            'profile-img' => $file_name_db ? $file_name_db : '',
        ];
            update_profile($username, $profile_parameters);
            $message = "<p>Profile updated successfully</p>";
    }
}

/*
    Fact validation & image processing
*/

if (isset($_POST['edit-fact']) || isset($_POST['add-fact'])) {
    extract($_POST);
    $form_good = true;

    $message = '';

    $file = isset($_FILES['img-file']) ? $_FILES['img-file'] : '';
    $file_name = isset($_FILES['img-file']['name']) ? $_FILES['img-file']['name'] : '';
    $file_temp_name = isset($_FILES['img-file']['tmp_name']) ? $_FILES['img-file']['tmp_name'] : '';
    $file_size = isset($_FILES['img-file']['size']) ? $_FILES['img-file']['size'] : '';
    $file_error = isset($_FILES['img-file']['error']) ? $_FILES['img-file']['error'] : 0;


    // Image file extension
    $file_extension = $file_name ? strtolower(pathinfo($file_name, PATHINFO_EXTENSION)) : '';
    $allowed = array('jpg', 'jpeg', 'png', 'webp');

    // Name Validation
    $fact = filter_var($fact, FILTER_SANITIZE_STRING);
    $fact = mysqli_real_escape_string($connection, $fact);

    if (strlen($fact) < 10 || strlen($fact) > 500) {
        $form_good = false;
        $message .= "<p>Please enter a fact that is more than 10 characters and less than 500 characters</p>";
    }

    if ((!empty($file_temp_name)) && !in_array($file_extension, $allowed)) {
        $form_good = false;
        $message =  "Invalid file extension. Allowed extensions are JPG, JPEG, PNG, and WEBP.";
    }

    if (!is_dir('fact/thumbs/')) {
        mkdir('fact/thumbs', 0777, TRUE);
    }
    if (!is_dir('fact/full/')) {
        mkdir('fact/full', 0777, TRUE);
    }
    // Proceed if validated
    if ($form_good) {
        // Image Validation and creation
        if (!empty($file_temp_name)) {

            if (in_array($file_extension, $allowed) && $file_error === 0 && $file_size < 2000000) {

                $file_name_new = uniqid('', true) . "." . $file_extension;
                $file_destination = 'fact/full/' . $file_name_new;

                // Move the uploaded file to the directory.
                move_uploaded_file($file_temp_name, $file_destination);

                // Check the image dimensions.
                $image_info = getimagesize($file_destination);
                $width_original = $image_info[0];
                $height_original = $image_info[1];

                // Creates an empty canvas that is 256px x 256px.
                $thumb = imagecreatetruecolor(256, 256);

                // Calculate the shorter side / smaller size between width and height.
                $smaller_size = min($width_original, $height_original);

                // Calculate the starting point for cropping the image.
                $x_coordinate = ($width_original > $smaller_size) ? ($width_original - $smaller_size) / 2 : 0;
                $y_coordinate = ($height_original > $smaller_size) ? ($height_original - $smaller_size) / 2 : 0;

                // Create image based on the filetype we grabbed earlier.
                switch ($file_extension) {
                    case 'jpeg':
                    case 'jpg':
                        $src_image = imagecreatefromjpeg($file_destination);
                        break;
                    case 'png':
                        $src_image = imagecreatefrompng($file_destination);
                        break;
                    case 'webp':
                        $src_image = imagecreatefromwebp($file_destination);
                        break;
                    default:
                        // Invalid Type
                        $form_good = false;
                        $file_message .= "<p>This file type is not supported.</p>";
                        exit;
                }

                // Crop and resize the user-uploaded image.
                imagecopyresampled($thumb, $src_image, 0, 0, $x_coordinate, $y_coordinate, 256, 256, $smaller_size, $smaller_size);

                // Save the thumbnail to the server.
                imagejpeg($thumb, 'fact/thumbs/' . $file_name_new, 100);

                // Free up some server resources by destroying the working object.
                imagedestroy($thumb);
                imagedestroy($src_image);

                $file_name_db = $file_name_new;

                $message .= "<p>Image uploaded successfully!</p>";
            } else {
                $form_good = false;
                if ($file_size >= 2000000) {
                    $file_message .= "<p>Your file is too big! Please upload an image under 2MB.</p>";
                } else {
                    $file_message .= "<p>There was an error with this file. Please upload a valid image.</p>";
                }
            }
        } else {
            $file_name_db = '';
        }
        $fact_parameters = [
            'fact' => isset($_POST['fact']) ? $fact : '',
            'img' => $file_name_db ? $file_name_db : '',
            'id' => isset($_POST['edit']) ? $_POST['edit'] : '',
        ];

        if (isset($_POST['edit-fact'])) {
            update_fact($fact_parameters);
            $message = "<p>Fact was updated successfully</p>";

            $aid = "";
        }
    
        if (isset($_POST['add-fact'])) {
            $inserted_id = insert_fact($fact_parameters);
            $message = "<p>Fact was successfully added into the database!</p>";
        }
    }
}

?>