<?php

require_once('../private/connect.php');
$connection = db_connect();

$title = "The Anime Nexus | Add an Anime";
include("includes/header.php");
require_once('../private/validation.php');

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$name = isset($_POST['name']) ? TRIM($_POST['name']) : '';
$synopsis = isset($_POST['synopsis']) ? TRIM($_POST['synopsis']) : '';
$genre = isset($_POST['genre']) ? $_POST['genre'] : '';
$premier_date_season = isset($_POST['premier_date_season']) ? $_POST['premier_date_season'] : '';
$premier_date_year = isset($_POST['premier_date_year']) ? $_POST['premier_date_year'] : '';
$rating = isset($_POST['rating']) ? $_POST['rating'] : '';
$studio = isset($_POST['studio']) ? $_POST['studio'] : '';
$completion_status = isset($_POST['completion_status']) ? $_POST['completion_status'] : '';
$stream = isset($_POST['stream']) ? $_POST['stream'] : '';

    ?>

<main class="container mt-5">
    <section class="col-md-8 col-xl-6 mx-auto">
        <h1 class="fw-light text-center">Adding Anime to the Database</h1>
        <p class="text-muted mb-5 text-center">To add an anime in our database, simply fill out the form below and hit 'save'.</p>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" enctype="multipart/form-data">
                    <!-- Update Message -->
                    <?php if(isset($message)) :?>
                        <div class="message text-danger">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name">Anime Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?php 
                            if($name != '') {
                                echo $name;
                            } else {
                                echo '';
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
                        <label for="synopsis">Synopsis</label>
                        <textarea rows="10" name="synopsis" id="synopsis" class="form-control"><?php 
                            if($synopsis != '') {
                                echo $synopsis;
                            } else {
                                echo '';
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
                            <option value="Spring" <?php if ($premier_date_season == 'Spring') echo 'selected';?>>Spring</option>
                            <option value="Summer" <?php if ($premier_date_season == 'Summer') echo 'selected';?>>Summer</option>
                            <option value="Fall" <?php if ($premier_date_season == 'Fall') echo 'selected';?>>Fall</option>
                            <option value="Winter" <?php if ($premier_date_season == 'Winter') echo 'selected';?>>Winter</option>
                        </select>
                        <input type="number" name="premier_date_year" id="premier_date_year" class="form-control" value="<?php 
                            if($premier_date_year != '') {
                                echo $premier_date_year;
                            } else {
                                echo '';
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
                            if($studio != '') {
                                echo $studio;
                            } else {
                                echo '';
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
                                    '' => '',
                                    '1' => '⭐',
                                    '2' => '⭐⭐',
                                    '3' => '⭐⭐⭐',
                                    '4' => '⭐⭐⭐⭐',
                                    '5' => '⭐⭐⭐⭐⭐',
                                ];
                                foreach($rating_arr as $key => $value) {
                                    if ($rating == $key) {
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
                            <option value="Completed" <?php if ($completion_status == 'Completed') echo 'selected';?>>Completed</option>
                            <option value="Watching" <?php if ($completion_status == 'Watching') echo 'selected';?>>Watching</option>
                            <option value="Plan to watch" <?php if ($completion_status == 'Plan to watch') echo 'selected';?>>Plan to watch</option>
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

                                    if (isset($genre) && is_array($_POST['genre'])) {
                                        $is_checked = (in_array($genre, $_POST['genre'])) ? 'checked' : '';
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
                            <input class="form-check-input" type="checkbox" id="crunchyroll" name="stream[]" value="crunchyroll" <?php $checked = (is_array($stream) && in_array("crunchyroll", $stream)) ? "checked" : $checked = ''; echo $checked;?>>
                            <label class="form-check-label" for="crunchyroll">Crunchy Roll</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="hidive" name="stream[]" value="hidive" <?php $checked = (is_array($stream) && in_array("hidive", $stream)) ? "checked" : $checked = ''; echo $checked;?>>
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
                    <input type="submit" name="add" value="Save" class="btn btn-info">
                </form>

        
    </section>
</main>

<?php
include('includes/footer.php');
?>