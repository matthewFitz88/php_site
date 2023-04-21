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

//add contact once form is submitted
if (!empty($_POST)) {
    $stmt = $pdo->prepare('INSERT INTO `contacts`(`name`, `email`, `phone`, `title`) VALUES (?, ?, ?, ?)');
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['phone'], $_POST['title']]);
    header('Location: contacts.php');
}
?>

<?= template_header('Create Contact') ?>
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
<h1 class="title">Create Contact</h1>
<form method="POST">
    <div class="field">
        <label class="label">Name</label>
        <div class="control has-icons-left">
            <input class="input" name="name" type="text" placeholder="Bob Smith" required>
            <span class="icon is-left">
                <i class="fas fa-user"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <label class="label">Email</label>
        <div class="control has-icons-left">
            <input class="input" type="email" name="email"  placeholder="example@example.com" required>
            <span class="icon is-left">
                <i class="fas fa-at"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <label class="label">Phone</label>
        <div class="control has-icons-left">
            <input class="input" type="tel" name="phone" placeholder="##########" required>
            <span class="icon is-left">
                <i class="fas fa-phone"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <label class="label">Title</label>
        <div class="control has-icons-left">
            <input class="input" name="title" type="text" placeholder="Manager" required>
            <span class="icon is-left">
                <i class="fas fa-poo"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <button type="submit" class="button is-success">Create Contact</button>
        </div>
    </div>
</form>
<?= template_footer() ?>