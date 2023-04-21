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
        $userResponses[] = "A review with that id does not exist.";
    }

    if (!empty($_POST)) {
        $stmt = $pdo->prepare('UPDATE `reviews` SET `name`= ?,`content`= ?,`rating`= ? WHERE `id` = ?');
        $stmt->execute([$_POST['name'], $_POST['content'], $_POST['rating'], $_GET['id']]);
        $userResponses[] = "Review successfully updated! <a href='blog-admin.php'>Return to Blog Admin</a>";
    }
} else {
    $userResponses[] = "No id was found.";
}
?>

<?= template_header('Review Edit') ?>
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
<h1 class="title">Review Edit</h1>
<form class="form" action="?id=<?= $review['id'] ?>" method="post">
    <div class="field">
        <input class="input" name="name" type="text" value="<?= $review['name'] ?>" required>
    </div>
    <div class="field">
        <input class="input" name="rating" type="number" min="1" max="5" value="<?= $review['rating'] ?>" required>
    </div>
    <div class="field">
        <textarea class="textarea" name="content" required><?= $review['content'] ?></textarea>
    </div>
    <button class="button is-info" type="submit">Update Review</button>
</form>
<?= template_footer() ?>