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

$stmt = $pdo->query('SELECT p.*, GROUP_CONCAT(pa.title ORDER BY pa.id) AS answers
                     FROM polls p LEFT JOIN poll_answers pa ON pa.poll_id = p.id
                     GROUP BY p.id');

$polls = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?= template_header('Polls') ?>
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
                <li class="has-background-info"><a href="polls.php" class="has-text-white"> Polls </a></li>
                <li><a href="contacts.php"> Contacts </a></li>
                <li><a href="tickets.php"> Tickets </a></li>
                <li><a href="blog-admin.php"> Blogs </a></li>
            </ul>
        </aside>
    </div>
    <!-- END LEFT NAV COLUMN-->
    <!-- START RIGHT CONTENT COLUMN-->
    <div class="column">
        <h1 class="title">Polls</h1>
        <p class="subtitle">Welcome, here is a list of polls</p>
        <a href="poll-create.php" class="button is-success">Create Poll</a>
        <table class="table is-striped is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Answers</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($polls as $poll) : ?>
                    <tr>
                        <td><?= $poll['id'] ?></td>
                        <td><?= $poll['title'] ?></td>
                        <td><?= $poll['answers'] ?></td>
                        <td>
                            <a href="poll-vote.php?id=<?= $poll['id'] ?>" class="button"><i class="fas fa-poll"></i></a>
                            <a href="poll-delete.php?id=<?= $poll['id'] ?>" class="button"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- END RIGHT CONTENT COLUMN-->
</div>
<?= template_footer() ?>