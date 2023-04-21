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
    $stmt = $pdo->prepare('SELECT * FROM `polls` WHERE `id` = ?');
    $stmt->execute([$_GET['id']]);
    $poll = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$poll) {
        $userResponses[] = "A poll with that id does not exist.";
    }

    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            $stmt = $pdo->prepare('DELETE FROM `polls` WHERE `id` = ?');
            $stmt->execute([$_GET['id']]);
            
            $stmt = $pdo->prepare('DELETE FROM `poll_answers` WHERE `poll_id` = ?');
            $stmt->execute([$_GET['id']]);
            
            $userResponses[] = "You have deleted the poll! <a href='polls.php'>Return to Polls</a>";
        } else {
            header('Location: polls.php');
        }
    }
}
?>

<?= template_header('Delete Poll') ?>
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
<h1 class="title">Poll Delete</h1>
<p class="subtitle">Are you sure you want to delete this poll: <?=$poll['title']?></p>
<div class="buttons">
    <a href="?id=<?=$poll['id']?>&confirm=yes" class="button is-success">Yes</a>
    <a href="?id=<?=$poll['id']?>&confirm=no" class="button is-danger">No</a>
</div>
<?= template_footer() ?>