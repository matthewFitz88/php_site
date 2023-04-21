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

$stmt = $pdo->query('SELECT *, DATE_FORMAT(created, "%M %D, %Y %h:%i %p") as created_formatted, `status` FROM `tickets` ORDER BY `created` DESC');
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?= template_header('Tickets') ?>
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
<div class="columns">
    <!-- START LEFT NAV COLUMN-->
    <div class="column is-one-quarter">
        <aside class="menu">
            <p class="menu-label"> Admin menu </p>
            <ul class="menu-list">
                <li><a href="profile.php"> Profile </a></li>
                <li><a href="polls.php"> Polls </a></li>
                <li><a href="contacts.php"> Contacts </a></li>
                <li class="has-background-info"><a href="tickets.php" class="has-text-white"> Tickets </a></li>
                <li><a href="blog-admin.php"> Blogs </a></li>
            </ul>
        </aside>
    </div>
    <!-- END LEFT NAV COLUMN-->
    <!-- START RIGHT CONTENT COLUMN-->
    <div class="column">
        <h1 class="title">Tickets</h1>
        <a href="ticket-create.php" class="button is-success">Create a new Ticket</a>
        <div class="row columns is-multiline">
            <?php foreach ($tickets as $ticket) : ?>
                <div class="column is-4 mt-4">
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title">
                                <?= $ticket['title'] ?>
                            </p>
                            <button class="card-header-icon" aria-label="more options">
                                <a href="ticket-detail.php?id=<?= $ticket['id'] ?>">
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
                            <a href="ticket-detail.php?id=<?= $ticket['id'] ?>" class="card-footer-item">View</a>
                            <a href="ticket-edit.php?id=<?= $ticket['id'] ?>" class="card-footer-item">Edit</a>
                            <a href="ticket-delete.php?id=<?= $ticket['id'] ?>" class="card-footer-item">Delete</a>
                        </footer>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- END RIGHT CONTENT COLUMN-->
</div>
<?= template_footer() ?>