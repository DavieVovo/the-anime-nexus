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

if (isset($_GET["fid"])) {
    $fid = $_GET['fid'];
} else {
    $fid = '';
}

$message = '';

require_once('../private/validation.php');

if (isset($_POST['delete-fact'])) {
    extract($_POST);
    delete_fact($fid);
    $message = "<p>Fact deleted successfully.</p>";
    $fid = '';
}

$fact = isset($_POST['fact']) ? $_POST['fact'] : '';

?>

<main class="container mt-5">
    <section class="row justify-content-center">
        <h1 class="fw-light text-center">Edit a Fact Record</h1>
        <p class="text-muted text-center">Within this interface, you have the option to include or remove facts. To delete a fact, click on "delete" adjacent to the specific fact, and to add a new one, insert it at the bottom of the list.</p>
        <div class="mb-3 d-flex justify-content-center">
            <a href="facts.php?add=true" class="btn btn-info btn-lg">Add a Fact</a>
        </div>
        <div class="col-lg-8">
        <?php
            if ($message != '') : ?>
            <div class="alert <?php echo $form_good ? "alert-info" : "alert-danger";?> text-center" role="alert">
                <?php echo $message; ?>
            </div>
            <?php endif;
            $all_facts = get_all_facts();
            if (empty($all_facts)) : ?>
                <p>There are no facts on record.</p>
            <?php else :?>
                <table class="table">
                    <thead>
                        <th scope="col"></th>
                        <th scope="col">Fact</th>
                        <th scope="col">Action</th>
                        <th scope="col"></th>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($all_facts as $facts) {
                            echo "<tr>
                                    <td class=\"align-middle\"><img class=\"rounded-circle w-50\" src=\"fact/thumbs/".$facts['fact_img']."\" alt=\"Image for". $facts['id']."\"></td>
                                    <td class=\"align-middle " . ($dark_mode ? 'text-bg-dark' : '') . "\">".$facts['facts']."</td>
                                    <td class=\"align-middle\"><a href=\"facts.php?edit=" . $facts['id'] . "\" class=\"text-decoration-none text-warning\">Edit</a></td>
                                    <td class=\"align-middle\"><a href=\"facts.php?fid=" . $facts['id'] . "\" class=\"text-decoration-none text-danger\">Delete</a></td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php endif?>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header text-bg-info">
                        <h5 class="modal-title" id="deleteModalTitle">Delete Fact?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body <?php echo $dark_mode ? 'text-bg-dark' : '';?>">
                        <?php
                        if ($fid != '') {
                            $deleting_fact = select_fact_by_id($fid);
                            extract($deleting_fact);
                        } 
                        ?>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
                            <p><?php echo $facts; ?></p>
                            <!-- Hidden Value -->
                            <input type="hidden" name="fid" value="<?php echo $fid?>">
                            <div class="d-flex justify-content-center">
                                <input type="submit" name="delete-fact" value="Delete" class="btn btn-danger">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Modal -->
        <div class="modal fade" id="addEditModal" tabindex="-1" role="dialog" aria-labelledby="addEditModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header text-bg-info">
                        <h5 class="modal-title" id="addEditModalTitle"><?php if (isset($_GET['add'])) {echo "Add";} elseif (isset($_GET['edit'])){echo "Edit";}?> Fact</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body <?php echo $dark_mode ? 'text-bg-dark' : '';?>">
                        <?php
                        if ($_GET['edit'] != '') {
                            $edit_fact = select_fact_by_id($_GET['edit']);
                            extract($edit_fact);
                        } 
                        ?>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" enctype="multipart/form-data">
                            <?php if ($_GET['edit'] != '') {
                                echo "<div class=\"d-flex justify-content-center\">";
                                echo "<img class=\"rounded-circle w-50\" src=\"fact/thumbs/$fact_img\" alt=\"Image for $id\">";
                                echo "</div";
                            }?>
                            <!-- Fact -->
                            <div class="mb-3">
                                <label for="fact">Fact</label>
                                <textarea rows="5" name="fact" id="fact" class="form-control"><?php 
                                    if (isset($_GET['edit'])) {
                                        echo $facts;
                                    }
                                    echo isset($_POST['fact']) ? ($_POST['fact']) : ''; 
                                ?></textarea>
                            </div>
                            <!-- Img -->
                            <div class="mb-3">
                                <label for="img-file" class="form-label">Image File</label>
                                <input type="file" id="img-file" name="img-file" class="form-control" accept=".jpg, .jpeg, .webp, .png" <?php echo isset($_GET['add']) ? 'required' : '' ?>>
                            </div>
                            <!-- Hidden Value -->
                            <input type="hidden" name="edit" value="<?php echo $_GET['edit']?>">
                            <!-- Submit -->
                            <div class="d-flex justify-content-center">
                                <?php if(isset($_GET['add']) == TRUE):?>
                                <input type="submit" name="add-fact" value="Add" class="btn btn-info">
                                <?php elseif(isset($_GET['edit'])) : ?>
                                <input type="submit" name="edit-fact" value="Update" class="btn btn-info">
                                <?php endif;?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </section>
</main>
<?php if ($fid > 0) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let myModal = new bootstrap.Modal(document.getElementById("deleteModal"), {});
            myModal.show();

            document.querySelector("#closeDeleteModalButton").addEventListener("click", function () {
                myModal.hide();
            });
        });
    </script>
<?php endif; ?>

<?php if (isset($_GET['add']) == true || ($_GET['edit'] ?? false)) : ?>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        let myModal = new bootstrap.Modal(document.getElementById("addEditModal"), {});
        myModal.show();
  
        document.querySelector("#closeAddModalButton").addEventListener("click", function () {
          myModal.hide();
        });
      });
    </script>
<?php endif;

include('includes/footer.php');
db_disconnect($connection);

?>