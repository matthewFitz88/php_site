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
    $stmt = $pdo->prepare('SELECT * FROM `tickets` WHERE `id` = ?');
    $stmt->execute([$_GET['id']]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        $userResponses[] = "A ticket with that id does not exist.";
    }

    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            $stmt = $pdo->prepare('DELETE FROM `tickets` WHERE `id` = ?');
            $stmt->execute([$_GET['id']]);
            
            $stmt = $pdo->prepare('DELETE FROM `tickets_comments` WHERE `ticket_id` = ?');
            $stmt->execute([$_GET['id']]);
            
            $userResponses[] = "You have deleted the ticket! <a href='tickets.php'>Return to Tickets</a>";
        } else {
            header('Location: tickets.php');
        }
    }
}
?>

<?= template_header('Delete Ticket') ?>
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
<h1 class="title">Delete Ticket</h1>
<p class="subtitle">Are you sure you want to delete this poll: <?=$ticket['title']?></p>
<div class="buttons">
    <a href="?id=<?=$ticket['id']?>&confirm=yes" class="button is-success">Yes</a>
    <a href="?id=<?=$ticket['id']?>&confirm=no" class="button is-danger">No</a>
</div>
<?= template_footer() ?>