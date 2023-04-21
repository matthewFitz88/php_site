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

//get data for table
$stmt = $pdo->prepare('SELECT * FROM `contacts`');
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?= template_header('Contacts') ?>
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
                <li class="has-background-info"><a href="contacts.php" class="has-text-white"> Contacts </a></li>
                <li><a href="tickets.php"> Tickets </a></li>
                <li><a href="blog-admin.php"> Blogs </a></li>
            </ul>
        </aside>
    </div>
    <!-- END LEFT NAV COLUMN-->
    <!-- START RIGHT CONTENT COLUMN-->
    <div class="column">
        <h1 class="title">Contacts</h1>
        <hr>
        <a href="contact-create.php" class="button is-success">Create Contact</a>
        <div class="table-container">
            <table class="table">
                <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Title</th>
                    <th>Created</th>
                    <th></th>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact) : ?>
                        <tr>
                            <td><?= $contact['id'] ?></td>
                            <td><?= $contact['name'] ?></td>
                            <td><?= $contact['email'] ?></td>
                            <td><?= $contact['phone'] ?></td>
                            <td><?= $contact['title'] ?></td>
                            <td><?= $contact['created'] ?></td>
                            <td>
                                <div class="buttons">
                                    <a href="contact-update.php?id=<?= $contact['id'] ?>" class="button is-small is-info"><i class="fas fa-pencil-alt"></i></a>
                                    <a href="contact-delete.php?id=<?= $contact['id'] ?>" class="button is-small is-danger"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- END RIGHT CONTENT COLUMN-->
</div>
<?= template_footer() ?>