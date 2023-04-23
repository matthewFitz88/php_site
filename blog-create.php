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

if (!empty($_POST)) {
    $published = isset($_POST['published']) && $_POST['published'] == 'on' ? 1 : 0;

    $stmt = $pdo->prepare('INSERT INTO `blog_post`(`author_name`, `title`, `content`, `published`) VALUES (?, ?, ?, ?)');
    $stmt->execute([$_POST['author_name'], $_POST['title'], $_POST['content'], $published]);
    header('Location: blog-admin.php');
}
?>

<?= template_header('Create Blog Post') ?>
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
<h1 class="title">Create Blog Post</h1>
<form method="post">
    <div class="field">
        <label class="label">Blog Title</label>
        <div class="control">
            <input class="input" name="title" type="text" placeholder="Blog Title" required>
        </div>
    </div>
    <div class="field">
        <label class="label">Author Name</label>
        <div class="control has-icons-left">
            <input class="input" name="author_name" type="text" placeholder="Bob Smith" required>
            <span class="icon is-left">
                <i class="fas fa-user"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <label class="label">Content</label>
        <div class="control">
            <textarea name="content" class="textarea" placeholder="Content for your new blog post..." required></textarea>
        </div>
    </div>
    <div class="field">
        <label class="label checkbox">Publish</label>
        <div class="control">
            <input type="checkbox" name="published">
            Publish
        </div>
    </div>
    <div class="field">
        <div class="control">
            <button type="submit" class="button is-success">Create Blog Post</button>
        </div>
    </div>
</form>
<?= template_footer() ?>