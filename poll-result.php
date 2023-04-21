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
    $stmt = $pdo->prepare('SELECT * FROM `polls` WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $poll = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($poll) {
        $stmt = $pdo->prepare('SELECT * FROM `poll_answers` WHERE poll_id = ?');
        $stmt->execute([$_GET['id']]);
        $poll_answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total_votes = 0;

        foreach ($poll_answers as $poll_answer) {
            $total_votes += $poll_answer['votes'];
        }
    }
} else {
    $userResponses[] = "No poll with that id was found";
}
?>

<?= template_header('Poll Results') ?>
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
<h1 class="title">Poll Results</h1>
<p class="subtitle"><?=$poll['title']?> Totle Votes: <?=$total_votes?></p>
<?php foreach ($poll_answers as $poll_answer) : ?>
<p><?=$poll_answer['title']?> (<?=$poll_answer['votes']?>)</p>
<progress class="progress is-info is-large" value="<?=$poll_answer['votes']?>" max="<?=$total_votes?>"></progress>
<?php endforeach;?>
<?= template_footer() ?>