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
    $stmt = $pdo->prepare('SELECT * FROM `blog_post` WHERE `id` = ?');
    $stmt->execute([$_GET['id']]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$blog) {
        $userResponses[] = "A blog with that id does not exist.";
    }

    if (!empty($_POST)) {
        $stmt = $pdo->prepare('UPDATE `blog_post` SET `author_name`= ?,`title`= ?,`content`= ?, `published` = `published` + 1 WHERE `id` = ?');
        $stmt->execute([$_POST['author_name'], $_POST['title'], $_POST['content'], $_GET['id']]);
        header('Location: blog-admin.php');
    }
} else {
    $userResponses[] = "No id was found.";
}
?>

<?= template_header('Blog Edit') ?>
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
<h1 class="title">Blog Edit</h1>
<form action="?id=<?= $blog['id'] ?>" method="post">
    <div class="field">
        <label class="label">Blog Title</label>
        <div class="control">
            <input class="input" name="title" type="text" value="<?= $blog['title'] ?>" required>
        </div>
    </div>
    <div class="field">
        <label class="label">Author Name</label>
        <div class="control has-icons-left">
            <input class="input" name="author_name" type="text" value="<?= $blog['author_name'] ?>" required>
            <span class="icon is-left">
                <i class="fas fa-user"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <label class="label">Content</label>
        <div class="control">
            <textarea name="content" class="textarea" required><?= $blog['content'] ?></textarea>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <div class="buttons">
                <button type="submit" class="button is-info">Update Blog Post</button>
                <a href="blog-admin.php" class="button is-warning">Cancel</a>
            </div>
        </div>
    </div>
</form>
<?= template_footer() ?>