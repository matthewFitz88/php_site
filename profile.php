<?php
require 'config.php';

//start the session
session_start();

//password protect this page
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

//get the user data to display
$stmt = $mysqli->prepare('SELECT `password`, `email` FROM accounts WHERE id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($hash, $email);
$stmt->fetch();
$stmt->close();
?>

<?= template_header('Profile') ?>
<?= template_nav('My Website') ?>
<?php if ($userResponses) : ?>
    <p class="notification is-success is-light">
        <?php
        echo implode('<br>', $userResponses);
        ?>
    </p>
<?php endif; ?>
<div class="columns">
    <!-- START LEFT NAV COLUMN-->
    <div class="column is-one-quarter">
        <aside class="menu">
            <p class="menu-label"> Admin menu </p>
            <ul class="menu-list">
                <li class="has-background-info"><a href="profile.php" class="has-text-white"> Profile </a></li>
                <li><a href="polls.php"> Polls </a></li>
                <li><a href="contacts.php"> Contacts </a></li>
                <li><a href="tickets.php"> Tickets </a></li>
                <li><a href="blog-admin.php"> Blogs </a></li>
            </ul>
        </aside>
    </div>
    <!-- END LEFT NAV COLUMN-->
    <!-- START RIGHT CONTENT COLUMN-->
    <div class="column">
        <h1 class="title">Profile</h1>
        <table class="table">
            <tr>
                <td>Username:</td>
                <td><?= $_SESSION['username'] ?></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><?= $hash ?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?= $email ?></td>
            </tr>
        </table>
    </div>
    <!-- END RIGHT CONTENT COLUMN-->
</div>
<?= template_footer() ?>