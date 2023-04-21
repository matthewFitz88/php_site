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
    $stmt = $pdo->prepare('SELECT `id`, `title`,`email`, `msg` FROM `tickets` WHERE `id` = ?');
    $stmt->execute([$_GET['id']]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($_POST)) {
        $stmt = $pdo->prepare('UPDATE `tickets` SET `title`= ?, `email`= ?, `msg`= ? WHERE id = ?');
        $stmt->execute([$_POST['title'], $_POST['email'], $_POST['msg'], $_GET['id']]);
        header('Location: tickets.php');
    }
} else {
    $userResponses[] = "No id was found.";
}
?>

<?= template_header('Edit Ticket') ?>
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
<h1 class="title">Edit Ticket</h1>
<form action="?id=<?=$ticket['id']?>" method="post">
    <div class="field">
        <label class="label">Title</label>
        <div class="control">
            <input type="text" name="title" class="input" value="<?=$ticket['title']?>" required>
        </div>
    </div>
    <div class="field">
        <label class="label">Email</label>
        <div class="control has-icons-left">
            <input type="email" name="email" class="input" value="<?=$ticket['email']?>" placeholder="example@example.com" required>
            <span class="icon is-left">
                <i class="fas fa-envelope"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <label class="label">Message</label>
        <div class="control">
            <textarea name="msg" class="textarea" required><?= $ticket['msg'] ?></textarea>
        </div>
    </div>
    <div class="field is-grouped">
        <div class="control">
            <button class="button is-success">Update Ticket</button>
        </div>
        <div class="control">
            <a href="tickets.php" class="button is-warning">Cancel</a>
        </div>
    </div>
</form>
<?= template_footer() ?>