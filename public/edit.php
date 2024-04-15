<?php

require_once('../private/connect.php');
$connection = db_connect();


$title = "The Anime Nexus | Edit";
include("includes/header.php");

include('../private/functions.php');

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if (isset($_GET["aid"])) {
    $aid = $_GET['aid'];
} elseif (isset($_POST['aid'])) {
    $aid = $_POST['aid'];
} else {
    $aid = '';
}

// How many results per page
$per_page = 10;

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

//Sort variable
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'anime_id';

// Variable check
$user_name = isset($_POST['name']) ? trim($_POST['name']) : '';
$user_synopsis = isset($_POST['synopsis']) ? trim($_POST['synopsis']) : '';
$user_genre = isset($_POST['genre']) ? $_POST['genre'] : '';
$user_premier_date_season = isset($_POST['premier_date_season']) ? $_POST['premier_date_season'] : '';
$user_premier_date_year = isset($_POST['premier_date_year']) ? $_POST['premier_date_year'] : '';
$user_rating = isset($_POST['rating']) ? $_POST['rating'] : '';
$user_studio = isset($_POST['studio']) ? $_POST['studio'] : '';
$user_completion_status = isset($_POST['completion_status']) ? $_POST['completion_status'] : '';

if(isset($aid)) {
    if (is_numeric($aid) && $aid > 0) {
         $anime = select_anime_by_id($aid);

         if ($anime) {
             $existing_name = $anime['name'];
             $existing_synopsis = $anime['synopsis'];
             $existing_genre = explode(', ', $anime['genre']);
             $existing_premier_date = $anime['premier_date'];
             $existing_rating = $anime['rating'];
             $existing_studio = $anime['studio'];
             $existing_completion_status = $anime['completion_status'];

             $premier_date = explode(' ', $anime['premier_date']);
             $existing_premier_date_season = $premier_date[0];
             $existing_premier_date_year = $premier_date[1];
             $existing_artwork = $anime['artwork'];
             $existing_stream = explode(', ', $anime['stream']);

    } else {
        $message .= "Sorry, there are no records available that match your query.";
        }
    }
}

require_once('../private/validation.php');

$message = "";
$update_message = "";



?>  

    <main class="container mt-5">
        <section class="row justify-content-center col-10 mx-auto">
        <h1 class="fw-light text-center">Edit an Anime Record</h1>
        <p class="text-muted mb-5 text-center">To edit an anime entry in our catalogue, click on the 'Edit' button within the anime card you want to modify. Then, enter your revised information into the provided form. If you wish to remove the anime from our catalogue, you can do so by clicking the 'Delete' button. Confirm any changes or deletions directly. Your updates, including deletions, will be seamlessly integrated into our anime catalogue.</p>
        <?php

            if ($message != '') : ?>

            <div class="alert <?php echo $form_good ? "alert-info" : "alert-danger";?> text-center" role="alert">
                <?php echo $message; ?>
            </div>

        <?php endif;?>
            <div class="">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <!-- Name table heading -->
                    <th scope="col"><a href="<?php 
                        $sort_by_link = 'name';
                        $order_by = 'ASC';

                        if (isset($_GET['order_by']) && $_GET['sort_by'] == 'name') {
                            $order_by = ($_GET['order_by'] == 'ASC') ? 'DESC' : 'ASC';
                        }

                        echo build_link("edit.php", $current_page, "", $sort_by_link, $order_by);
                    ?>" class="text-info text-decoration-none">Name <?php if($_GET['sort_by'] == 'name' && $_GET['order_by'] == 'ASC') echo "&#8679;"; if($_GET['sort_by'] == 'name' && $_GET['order_by'] == 'DESC') echo "&#8681;";?></a></th>

                    <!-- Premier Date table heading -->
                    <th scope="col"><a href="<?php 
                        $sort_by_link = 'premier_date';
                        $order_by = 'ASC';

                        if (isset($_GET['order_by']) && $_GET['sort_by'] == 'premier_date') {
                            $order_by = ($_GET['order_by'] == 'ASC') ? 'DESC' : 'ASC';
                        }

                        echo build_link("edit.php", $current_page, "", $sort_by_link, $order_by);
                    ?>" class="text-info text-decoration-none">Premier Date <?php if($_GET['sort_by'] == 'premier_date' && $_GET['order_by'] == 'ASC') echo "&#8679;"; if($_GET['sort_by'] == 'premier_date' && $_GET['order_by'] == 'DESC') echo "&#8681;";?></a></th>

                    <!-- Status table heading -->
                    <th scope="col"><a href="<?php 
                        $sort_by_link = 'completion_status';
                        $order_by = 'ASC';

                        if (isset($_GET['order_by']) && $_GET['sort_by'] == 'completion_status') {
                            $order_by = ($_GET['order_by'] == 'ASC') ? 'DESC' : 'ASC';
                        }

                        echo build_link("edit.php", $current_page, "", $sort_by_link, $order_by);
                    ?>" class="text-info text-decoration-none">Completion Status <?php if($_GET['sort_by'] == 'completion_status' && $_GET['order_by'] == 'ASC') echo "&#8679;"; if($_GET['sort_by'] == 'completion_status' && $_GET['order_by'] == 'DESC') echo "&#8681;";?></a></th>

                    <!-- Rating table heading -->
                    <th scope="col"><a href="<?php 
                        $sort_by_link = 'rating';
                        $order_by = 'DESC';

                        if (isset($_GET['order_by']) && $_GET['sort_by'] == 'rating') {
                            $order_by = ($_GET['order_by'] == 'DESC') ? 'ASC' : 'DESC';
                        }

                        echo build_link("edit.php", $current_page, "", $sort_by_link, $order_by);
                    ?>" class="text-info text-decoration-none">Rating <?php if($_GET['sort_by'] == 'rating' && $_GET['order_by'] == 'DESC') echo "&#8679;"; if($_GET['sort_by'] == 'rating' && $_GET['order_by'] == 'ASC') echo "&#8681;";?></a></th>
                    <th scope="col">Action</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <?php

            //Order by variable
            $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'DESC';

            $result = find_records($per_page, $offset, $sort_by, $order_by);
            // Generate records table
            if ($connection->error) {
                echo $connection->error;
            } elseif ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                extract($row);
                ?>
                <tbody>
                    <tr>
                    <td class="align-middle"><img class="rounded w-50" src="images/thumbs/<?php echo $artwork;?>" alt="Key visual for the anime <?php echo $name;?>"></td>
                    <td class="align-middle fw-bold fs-5 <?php echo $dark_mode ? 'text-bg-dark' : '';?>"><?php echo $name;?></td>
                    <td class="align-middle <?php echo $dark_mode ? 'text-bg-dark' : '';?>"><?php echo $premier_date;?></td>
                    <td class="align-middle <?php echo $dark_mode ? 'text-bg-dark' : '';?>"><?php echo $completion_status;?></td>
                    <td class="align-middle <?php echo $dark_mode ? 'text-bg-dark' : '';?>"><?php echo generate_rating_stars($rating);?></td>
                    <td class="align-middle <?php echo $dark_mode ? 'text-bg-dark' : '';?>">
                        <a href="<?php echo build_link('edit.php', $current_page, "", $sort_by, $order_by);?>&aid=<?php echo $anime_id?>" class="text-warning text-decoration-none">Edit</a>
                    </td>
                    <td class="align-middle">                        
                        <?php
                        echo "<a href=\"delete-confirmation.php?anime=" . urlencode($anime_id) . "&anime_name=" . urlencode($name) . "\" class=\"text-danger text-decoration-none\">Delete</a>"
                        ?>  
                    </td>
                    </tr>
                </tbody>
                
                <?php
                }
            } else {
                echo "<p>Sorry! There are no records available.</p>";
            }
            ?>
        </table>
        <!-- Table pagination -->
        <nav aria-label="Page Number">
            <ul class="pagination justify-content-center">
                <?php if ($current_page > 1) : ?>
                    <li class="page-item">
                        <a class="page-link <?php echo $dark_mode ? 'bg-dark text-info' : 'text-info';?>" href="<?php echo build_link("edit.php", $current_page - 1, "") ?>" aria-label="Previous">
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
                            <a href="<?php echo build_link("edit.php", $i, "") ?>" class="page-link text-bg-info"><?php echo $i ?></a>
                        </li>
                    <?php else : ?>
                        <li class="page-item">
                            <a href="<?php echo build_link("edit.php", $i, "") ?>" class="page-link <?php echo $dark_mode ? 'bg-dark text-info' : 'text-info';?>"><?php echo $i ?></a>
                        </li>
                    <?php endif;
                }

                ?>

                <?php if ($current_page < $total_pages) : ?>
                    <li class="page-item">
                        <a class="page-link <?php echo $dark_mode ? 'bg-dark text-info' : 'text-info';?>" href="<?php echo build_link("edit.php", $current_page + 1, "") ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    <div class="modal-header text-bg-info">
        <h5 class="modal-title" id="editModalTitle">Edit <?php echo $existing_name;?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="d-flex justify-content-center <?php echo $dark_mode ? 'text-bg-dark' : '';?>">
        <img class="rounded w-50 my-3 <?php echo $dark_mode ? 'text-bg-dark' : '';?>" src="images/thumbs/<?php echo $existing_artwork;?>" alt="Key visual for the anime <?php echo $name;?>">
    </div>
        <div class="modal-body <?php echo $dark_mode ? 'text-bg-dark' : '';?>">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" enctype="multipart/form-data">
                    <!-- Update Message -->
                    <?php if(isset($update_message)) :?>
                        <div class="message text-danger">
                            <?php echo $update_message; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name">Anime Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?php 
                            if($user_name != '') {
                                echo $user_name;
                            } else {
                                echo $existing_name;
                            }
                        ?>" required>
                        <?php if(isset($name_message)) :?>
                            <div class="message text-danger">
                                <?php echo $name_message; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Synopsis -->
                    <div class="mb-3">
                        <label for="synopsis">Synopsis <span class="fw-light">(100 characters max)</span></label>
                        <textarea rows="10" name="synopsis" id="synopsis" class="form-control"><?php 
                            if($user_synopsis != '') {
                                echo $user_synopsis;
                            } else {
                                echo $existing_synopsis;
                            }
                        ?></textarea>
                        <?php if(isset($synopsis_message)) :?>
                            <div class="message text-danger">
                                <?php echo $synopsis_message; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Premier Date -->
                    <div class="mb-3">
                        <label for="premier_date">Premier Date <span class="fw-light">(Between 1990-present)</span></label>
                        <div class="d-flex gap-3">
                            <select name="premier_date_season" id="premier_date_season" class="form-select">
                                <option value="">Select</option>
                                <option value="Spring" <?php 
                                    if ($user_premier_date_season != '' && $user_premier_date_season == "Spring") {
                                        echo "selected";
                                    } elseif ($user_premier_date_season == '' && $existing_premier_date_season == "Spring") {
                                        echo "selected";
                                    }
                                    ?>
                                >Spring</option>
                                <option value="Summer" <?php 
                                    if ($user_premier_date_season != '' && $user_premier_date_season == "Summer") {
                                        echo "selected";
                                    } elseif ($user_premier_date_season == '' && $existing_premier_date_season == "Summer") {
                                        echo "selected";
                                    }
                                    ?>
                                    >Summer</option>
                                <option value="Fall" <?php 
                                    if ($user_premier_date_season != '' && $user_premier_date_season == "Fall") {
                                        echo "selected";
                                    } elseif ($user_premier_date_season == '' && $existing_premier_date_season == "Fall") {
                                        echo "selected";
                                    }
                                    ?>
                                    >Fall</option>
                                <option value="Winter" <?php 
                                    if ($user_premier_date_season != '' && $user_premier_date_season == "Winter") {
                                        echo "selected";
                                    } elseif ($user_premier_date_season == '' && $existing_premier_date_season == "Winter") {
                                        echo "selected";
                                    }
                                    ?>
                                    >Winter</option>
                            </select>
                            <input type="number" name="premier_date_year" id="premier_date_year" class="form-control" value="<?php 
                                if($user_premier_date_year != '') {
                                    echo $user_premier_date_year;
                                } else {
                                    echo $existing_premier_date_year;
                                }
                            ?>" required>
                        </div>
                        <?php if(isset($premier_season_message)) :?>
                            <div class="message text-danger">
                                <?php echo $premier_season_message; ?>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($premier_year_message)) :?>
                            <div class="message text-danger">
                                <?php echo $premier_year_message; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Studio -->
                    <div class="mb-3">
                        <label for="studio">Studio</label>
                        <input type="text" name="studio" id="studio" class="form-control" value="<?php 
                            if($user_studio != '') {
                                echo $user_studio;
                            } else {
                                echo $existing_studio;
                            }
                        ?>" required>
                        <?php if(isset($studio_message)) :?>
                            <div class="message text-danger">
                                <?php echo $studio_message; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Rating -->
                    <div class="mb-3">
                        <label for="rating">Rating</label>
                        <select name="rating" id="rating" class="form-select">
                            <?php
                                $rating_arr = [
                                    NULL => '',
                                    '1' => '⭐',
                                    '2' => '⭐⭐',
                                    '3' => '⭐⭐⭐',
                                    '4' => '⭐⭐⭐⭐',
                                    '5' => '⭐⭐⭐⭐⭐',
                                ];
                                foreach($rating_arr as $key => $value) {
                                    if (isset($user_rating) && $user_rating == $key) {
                                        $selected = 'selected';
                                    }
                                    if ($existing_rating == $key) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option value=\"$key\" $selected>$value</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="completion_status">Completion Status</label>
                        <select name="completion_status" id="completion_status" class="form-select">
                            <option value="">Select</option>
                            <option value="Completed" <?php if ($user_completion_status == 'Completed' || $existing_completion_status == 'Completed') echo 'selected';?>>Completed</option>
                            <option value="Watching" <?php if ($user_completion_status == 'Watching' || $existing_completion_status == 'Watching') echo 'selected';?>>Watching</option>
                            <option value="Plan to watch" <?php if ($user_completion_status == 'Plan to watch' || $existing_completion_status == 'Plan to watch') echo 'selected';?>>Plan to watch</option>
                        </select>
                        <?php if(isset($status_message)) :?>
                            <div class="message text-danger">
                                <?php echo $status_message; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Genre -->
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre:</label>
                        <div class="d-flex gap-3">
                            <!-- First half of genres -->
                            <div>
                                <?php
                                foreach ($genres_1 as $genre) {
                                    $is_checked = '';

                                    if (isset($user_genre) && is_array($user_genre)) {
                                        $is_checked = (in_array($genre, $user_genre)) ? 'checked' : '';
                                    } elseif (isset($existing_genre) && is_array($existing_genre)) {
                                        $is_checked = (in_array($genre, $existing_genre)) ? 'checked' : '';
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
                                    } elseif (isset($existing_genre) && is_array($existing_genre)) {
                                        $is_checked = (in_array($genre, $existing_genre)) ? 'checked' : '';
                                    }

                                    echo '<div class="form-check mb-1">';
                                    echo '<input class="form-check-input" type="checkbox" id="' . strtolower(str_replace(' ', '-', $genre)) . '" name="genre[]" value="' . $genre . '" ' . $is_checked . '>';
                                    echo '<label class="form-check-label" for="' . strtolower(str_replace(' ', '-', $genre)) . '">' . $genre . '</label>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                        <?php if(isset($genre_message)) :?>
                            <div class="message text-danger">
                                <?php echo $genre_message; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Streaming -->
                    <div class="mb-3">
                        <label class="form-check-label" for="stream">Stream at <span class="fw-light">(Optional)</span>:</label>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="crunchyroll" name="stream[]" value="crunchyroll" <?php $checked = (is_array($existing_stream) && in_array("crunchyroll", $existing_stream)) ? "checked" : $checked = ''; echo $checked;?>>
                            <label class="form-check-label" for="crunchyroll">Crunchy Roll</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="hidive" name="stream[]" value="hidive" <?php $checked = (is_array($existing_stream) && in_array("hidive", $existing_stream)) ? "checked" : $checked = ''; echo $checked;?>>
                            <label class="form-check-label" for="hidive">HIDIVE</label>
                        </div>
                    </div>

                    <!-- Image upload -->
                    <div class="mb-3">
                        <label for="img-file" class="form-label">Image File</label>
                        <input type="file" id="img-file" name="img-file" class="form-control" accept=".jpg, .jpeg, .webp, .png">
                        <?php if(isset($file_message)) :?>
                        <div class="message text-danger">
                            <?php echo $file_message; ?>
                        </div>
                        <?php endif; ?>
                        </div>

                    <!-- Hidden Value -->
                    <input type="hidden" name="aid" value="<?php echo $aid?>">
                    <input type="hidden" name="url" value="<?php echo build_link('edit.php', $current_page, "", $sort_by = NULL, $order_by = NULL);?>">
                    <input type="submit" name="update" value="Save" class="btn btn-info">
                </form>
            </div>
            </div>
        </div>
        </div>
    </section>
</main>
<?php if ($aid > 0) : ?>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      let myModal = new bootstrap.Modal(document.getElementById("editModal"), {});
      myModal.show();

      document.querySelector("#closeModalButton").addEventListener("click", function () {
        myModal.hide();
      });
    });
  </script>
<?php endif;

include('includes/footer.php');
db_disconnect($connection);

?>