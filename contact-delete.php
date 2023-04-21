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
    $stmt = $pdo->prepare('SELECT * FROM `contacts` WHERE `id` = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$contact) {
        $userResponses[] = "A contact with that id does not exist.";
    }

    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            $stmt = $pdo->prepare('DELETE FROM `contacts` WHERE `id` = ?');
            $stmt->execute([$_GET['id']]);
            $userResponses[] = "You have deleted the contact! <a href='contacts.php'>Return to Contacts</a>";
        } else {
            header('Location: contacts.php');
        }
    }
} else {
    $userResponses[] = "No id was found.";
}

?>

<?= template_header('Contact Delete') ?>
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
<h1 class="title">Contact Delete</h1>
<p class="subtitle">Are you sure you want to delete this contact: <?=$contact['name']?></p>
<div class="buttons">
    <a href="?id=<?=$contact['id']?>&confirm=yes" class="button is-success">Yes</a>
    <a href="?id=<?=$contact['id']?>&confirm=no" class="button is-danger">No</a>
</div>
<?= template_footer() ?>