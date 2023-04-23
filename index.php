<?php
require 'config.php';

session_start();

$pdo = pdo_connect_mysql();

$stmt = $pdo->query('SELECT *, DATE_FORMAT(created, "%M %D, %Y %h:%i %p") as created_formatted FROM `blog_post` WHERE `published` = 1 ORDER BY `created` DESC LIMIT 5');
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?= template_header('Home') ?>
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
<?php endforeach; ?>
<?= template_footer() ?>