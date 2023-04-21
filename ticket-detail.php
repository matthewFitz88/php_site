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

$stmt = $pdo->prepare('SELECT `id`, `title`, `msg`, DATE_FORMAT(created, "%M %D, %Y %h:%i %p") as created_formatted, `status` FROM `tickets` WHERE `id` = ?');
$stmt->execute([$_GET['id']]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!empty($_POST)) {
    $stmt = $pdo->prepare('INSERT INTO `tickets_comments` (`ticket_id`, `msg`) VALUES(?, ?)');

    if (isset($_POST['ticket_id'], $_POST['msg'])) {
        $stmt->execute([$_POST['ticket_id'], $_POST['msg']]);
    }
}

$stmt = $pdo->prepare('SELECT *, DATE_FORMAT(created, "%M %D, %Y %h:%i %p") as created_formatted FROM `tickets_comments` WHERE ticket_id = ? ORDER BY `created` DESC');
$stmt->execute([$_GET['id']]);
$ticket_comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id'], $_GET['status'])) {
    $stmt = $pdo->prepare('UPDATE `tickets` SET `status` = ? WHERE `id` = ?');
    $stmt->execute([$_GET['status'], $_GET['id']]);

    $ticket['status'] = $_GET['status'];
}
?>

<?= template_header('Ticket Detail') ?>
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
<h1 class="title">Ticket Detail</h1>
<p class="subtitle">
    <a href="tickets.php">View all Tickets</a>
</p>
<div class="card">
    <header class="card-header">
        <p class="card-header-title">
            <?= $ticket['title'] ?>
        </p>
        <button class="card-header-icon" aria-label="more options">
            <a href="ticket-detail.php">
                <?php if ($ticket['status'] == 'open') : ?>
                    <span class="icon">
                        <i class="far fa-clock fa-2x"></i>
                    </span>
                <?php elseif ($ticket['status'] == 'closed') : ?>
                    <span class="icon">
                        <i class="fas fa-times fa-2x"></i>
                    </span>
                <?php else : ?>
                    <span class="icon">
                        <i class="fas fa-check fa-2x"></i>
                    </span>
                <?php endif ?>
            </a>
        </button>
    </header>
    <div class="card-content">
        <div class="content">
            <time datetime="<?= $ticket['created'] ?>">Created: <?= $ticket['created_formatted'] ?></time>
            <br>
            <p><?= $ticket['msg'] ?></p>
        </div>
    </div>
    <footer class="card-footer">
        <a href="ticket-detail.php?id=<?= $ticket['id'] ?>&status=closed" class="card-footer-item">
            <span class="icon">
                <i class="fas fa-times fa-2x"></i>
            </span>
            <span>Close</span>
        </a>
        <a href="ticket-detail.php?id=<?= $ticket['id'] ?>&status=resolved" class="card-footer-item">
            <span class="icon">
                <i class="fas fa-check fa-2x"></i>
            </span>
            <span>Resolve</span>
        </a>
        <a href="ticket-detail.php?id=<?= $ticket['id'] ?>&status=open" class="card-footer-item">
            <span class="icon">
                <i class="far fa-clock fa-2x"></i>
            </span>
            <span>Re-Open</span>
        </a>
    </footer>
</div>
<div class="block mt-4">
    <form action="" method="post">
        <div class="field">
            <div class="control">
                <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                <textarea name="msg" class="textarea" placeholder="Enter Comment here..." required></textarea>
            </div>
        </div>
        <div class="field">
            <div class="control">
                <button class="button is-info">Post Comment</button>
            </div>
        </div>
    </form>
</div>
<hr>
<?php foreach ($ticket_comments as $ticket_comment) : ?>
    <div class="content">
        <p class="box">
            <span><i class="fas fa-comment"></i></span>
            <time datetime="<?= $ticket_comment['created'] ?>"><?= $ticket_comment['created_formatted'] ?></time>
            <br>
            <?= $ticket_comment['msg'] ?>
        </p>
    </div>
<?php endforeach; ?>
<?= template_footer() ?>