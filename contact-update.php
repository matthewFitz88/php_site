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

    //update record if form is sent
    if (!empty($_POST)) {
        $stmt = $pdo->prepare('UPDATE `contacts` SET `name`= ?,`email`= ?,`phone`= ?,`title`= ? WHERE `id` = ?');
        $stmt->execute([$_POST['name'], $_POST['email'], $_POST['phone'], $_POST['title'], $_GET['id']]);
        header('Location: contacts.php');
    }

} else {
    $userResponses[] = "No id was found.";
}
?>

<?= template_header('Update Contact') ?>
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
<h1 class="title">Update Contact</h1>
<form action="?id=<?= $contact['id'] ?>" method="POST">
    <div class="field">
        <label class="label">Name</label>
        <div class="control has-icons-left">
            <input class="input" name="name" type="text" value="<?= $contact['name'] ?>" required>
            <span class="icon is-left">
                <i class="fas fa-user"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <label class="label">Email</label>
        <div class="control has-icons-left">
            <input class="input" type="email" name="email" value="<?= $contact['email'] ?>" required>
            <span class="icon is-left">
                <i class="fas fa-at"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <label class="label">Phone</label>
        <div class="control has-icons-left">
            <input class="input" type="tel" name="phone" value="<?= $contact['phone'] ?>" required>
            <span class="icon is-left">
                <i class="fas fa-phone"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <label class="label">Title</label>
        <div class="control has-icons-left">
            <input class="input" name="title" type="text" value="<?= $contact['title'] ?>" required>
            <span class="icon is-left">
                <i class="fas fa-poo"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <button type="submit" class="button is-success">Update Contact</button>
        </div>
    </div>
</form>
<?= template_footer() ?>