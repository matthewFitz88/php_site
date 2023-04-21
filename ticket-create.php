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
    $stmt = $pdo->prepare('INSERT INTO `tickets` (`title`, `email`, `msg`) VALUES(?, ?, ?)');
    $stmt->execute([$_POST['title'], $_POST['email'], $_POST['msg']]);
    $id = $pdo->lastInsertId();
    header('Location: ticket-detail.php?id=' . $id);
}
?>

<?= template_header('Create Ticket') ?>
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
<h1 class="title">Create Ticket</h1>
<form action="" method="post">
    <div class="field">
        <label class="label">Title</label>
        <div class="control">
            <input type="text" name="title" class="input" placeholder="Ticket Title" required>
        </div>
    </div>
    <div class="field">
        <label class="label">Email</label>
        <div class="control has-icons-left">
            <input type="email" name="email" class="input" placeholder="example@example.com" required>
            <span class="icon is-left">
                <i class="fas fa-envelope"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <label class="label">Message</label>
        <div class="control">
            <textarea name="msg" class="textarea" placeholder="Description of issue for ticket..." required></textarea>
        </div>
    </div>
    <div class="field is-grouped">
        <div class="control">
            <button class="button is-success">Create Ticket</button>
        </div>
        <div class="control">
            <a href="tickets.php" class="button is-warning">Cancel</a>
        </div>
    </div>
</form>
<?= template_footer() ?>