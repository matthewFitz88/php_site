<?php
require 'config.php';

//start the session
session_start();

//password protect this page
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$pdo = pdo_connect_mysql();

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM `reviews` WHERE `id` = ?');
    $stmt->execute([$_GET['id']]);
    $review = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$review) {
        $userResponses[] = "A reivew with that id does not exist.";
    }

    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            $stmt = $pdo->prepare('DELETE FROM `reviews` WHERE `id` = ?');
            $stmt->execute([$_GET['id']]);
            $userResponses[] = "You have deleted the review! <a href='blog-admin.php'>Return to Blog Admin</a>";
        } else {
            header('Location: blog-admin.php');
        }
    }
} else {
    $userResponses[] = "No id was found.";
}
?>

<?= template_header('Delete Review') ?>
<?= template_nav('My Site') ?>
<?php if ($userResponses) : ?>
    <p class="notification is-success is-light">
        <?php
        echo implode('<br>', $userResponses);
        // echo '<br>';
        // var_dump($_POST);
        ?>
    </p>
<?php endif; ?>
<h1 class="title">Delete Review</h1>
<p class="subtitle">Are you sure you want to delete this review by: <?=$review['name']?></p>
<div class="buttons">
    <a href="?id=<?=$review['id']?>&confirm=yes" class="button is-success">Yes</a>
    <a href="?id=<?=$review['id']?>&confirm=no" class="button is-danger">No</a>
</div>
<?= template_footer() ?>