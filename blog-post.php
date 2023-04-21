<?php
require 'config.php';

session_start();

$pdo = pdo_connect_mysql();

// Your MySQL query that selects the blog post goes here
if (isset($_GET['id'])) {

    // MySQL query that selects the poll records by the GET request "id"
    $stmt = $pdo->prepare('SELECT * FROM blog_post WHERE id = ?');
    $stmt->execute([$_GET['id']]);

    // Fetch the record
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    die('No ID specified.');
}
?>

<?= template_header('Blog Post') ?>
<?= template_nav() ?>

<!-- START PAGE CONTENT -->
<h1 class="title"><?= $post['title'] ?></h1>
<h2 class="subtitle"><?= $post['author_name'] ?> - <time datetime="2016-1-1">Created: <?= date('F dS, G:ia', strtotime($post['created'])) ?></time></h2>
<p class="content"><?= $post['content'] ?></p>

<div class="reviews"></div>
<script>
    const reviews_page_id = <?= $post['id'] ?>;
    fetch("reviews.php?page_id=" + reviews_page_id).then(response => response.text()).then(data => {
        document.querySelector(".reviews").innerHTML = data;
        document.querySelector(".reviews .write_review_btn").onclick = event => {
            event.preventDefault();
            document.querySelector(".reviews .write_review").style.display = 'block';
            document.querySelector(".reviews .write_review input[name='name']").focus();
        };
        document.querySelector(".reviews .write_review form").onsubmit = event => {
            event.preventDefault();
            fetch("reviews.php?page_id=" + reviews_page_id, {
                method: 'POST',
                body: new FormData(document.querySelector(".reviews .write_review form"))
            }).then(response => response.text()).then(data => {
                document.querySelector(".reviews .write_review").innerHTML = data;
            });
        };
    });
</script>
<!-- END PAGE CONTENT -->

<?= template_footer() ?>