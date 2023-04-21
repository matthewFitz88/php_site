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
    //get the poll and poll answers for the given id
    $stmt = $pdo->prepare('SELECT * FROM `polls` WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $poll = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($poll) {
        $stmt = $pdo->prepare('SELECT * FROM `poll_answers` WHERE poll_id = ?');
        $stmt->execute([$_GET['id']]);
        $poll_answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if (isset($_POST['poll_answer'])) {
        $stmt = $pdo->prepare('UPDATE `poll_answers` SET `votes` = votes + 1 WHERE id = ?');
        $stmt->execute([$_POST['poll_answer']]);
        header('Location: poll-result.php?id=' . $_GET['id']);
    }
} else {
    $userResponses[] = "No poll with that id was found";
}
?>

<?= template_header('Poll Vote') ?>
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
<h1 class="title">Poll Vote</h1>
<p class="subtitle"><?=$poll['title']?></p>
<form action="?id=<?=$_GET['id']?>" method="post">
    <div class="field">
        <div class="control">
            <?php foreach ($poll_answers as $poll_answer) : ?>
                <label class="radio">
                    <input type="radio" name="poll_answer" value="<?=$poll_answer['id']?>" required>
                    <?=$poll_answer['title']?>
                </label><br>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <button class="button is-success">Submit Vote</button>
        </div>
    </div>
</form>
<?= template_footer() ?>