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

$stmt = $pdo->query('SELECT *, DATE_FORMAT(created, "%M %D, %Y %h:%i %p") as created_formatted FROM `blog_post` ORDER BY `created` DESC');
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?= template_header('Blog Admin') ?>
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
                <li><a href="tickets.php"> Tickets </a></li>
                <li class="has-background-info"><a href="blog-admin.php" class="has-text-white"> Blogs </a></li>
            </ul>
        </aside>
    </div>
    <!-- END LEFT NAV COLUMN-->
    <!-- START RIGHT CONTENT COLUMN-->
    <div class="column">
        <h1 class="title">Blog Posts</h1>
        <hr>
        <a href="blog-create.php" class="button is-success mb-4">Create Blog Post</a>
        <?php foreach ($blogs as $blog) : ?>
            <div class="box content">
                <h1 class="title"><?= $blog['title'] ?></h1>
                <p class="subtitle">
                    <?= $blog['author_name'] ?> -
                    <time datetime="<?= $blog['created'] ?>"><?= $blog['created_formatted'] ?></time>
                </p>
                <p>
                    <?= substr($blog['content'], 0, 100) ?>...
                    <a href="blog-post.php?id=<?= $blog['id'] ?>">Read More</a>
                </p>
            </div>
            <div class="buttons">
                <a href="blog-update.php?id=<?= $blog['id'] ?>" class="button is-info">Edit</a>
                <a href="blog-delete.php?id=<?= $blog['id'] ?>" class="button is-danger">Delete</a>
                <a href="reviews-admin.php?id=<?= $blog['id'] ?>&page_id=<?= $blog['id'] ?>" class="button is-link">View Reviews</a>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- END RIGHT CONTENT COLUMN-->
</div>
<?= template_footer() ?>