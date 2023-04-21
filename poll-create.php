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

if (!empty($_POST)) {
    $stmt = $pdo->prepare('INSERT INTO `polls`(`title`, `desc`) VALUES(?, ?)');
    $stmt->execute([$_POST['title'], $_POST['description']]);

    //get the new poll id
    $poll_id = $pdo->lastInsertId();

    $answers = isset($_POST['answers']) ? explode(PHP_EOL, $_POST['answers']) : '';

    foreach ($answers as $answer) {
        $stmt = $pdo->prepare('INSERT INTO `poll_answers`(`poll_id`, `title`) VALUES(?, ?)');
        $stmt->execute([$poll_id, $answer]);
    }

    $userResponses[] = "Your poll was created successfully! <a href='polls.php'>Return to Polls page</a>";
}
?>

<?= template_header('Create Poll') ?>
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
<h1 class="title">Create Poll</h1>
<form action="" method="post">
    <div class="field">
        <label class="label">Title</label>
        <div class="control">
            <input type="text" name="title" class="input" placeholder="Poll Title" required>
        </div>
    </div>
    <div class="field">
        <label class="label">Description</label>
        <div class="control">
            <input type="text" name="description" class="input" placeholder="Poll Description">
        </div>
    </div>
    <div class="field">
        <label class="label">Answers (one answer per line)</label>
        <div class="control">
            <textarea name="answers" class="textarea" required></textarea>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <button class="button is-success">Create a Poll</button>
        </div>
    </div>
</form>
<?= template_footer() ?>