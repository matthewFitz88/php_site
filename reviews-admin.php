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

if (isset($_GET['page_id'])) {
    // Get all reviews by the Page ID ordered by the submit date
    $stmt = $pdo->prepare('SELECT * FROM reviews WHERE page_id = ? ORDER BY submit_date DESC');
    $stmt->execute([$_GET['page_id']]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    exit('Please provide the page ID.');
}

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array('y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second');
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>

<?= template_header('Reviews Admin') ?>
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
<h1 class="title">Reviews Admin</h1>
<?php foreach ($reviews as $review) : ?>
    <div class="box">
        <h3><?= htmlspecialchars($review['name'], ENT_QUOTES) ?></h3>
        <div>
            <span><?= str_repeat('&#9733;', $review['rating']) ?></span>
            <span><?= time_elapsed_string($review['submit_date']) ?></span>
        </div>
        <p><?= htmlspecialchars($review['content'], ENT_QUOTES) ?></p>
    </div>
    <div class="buttons">
        <a href="reviews-update.php?id=<?= $review['id'] ?>" class="button is-info">Edit</a>
        <a href="reviews-delete.php?id=<?= $review['id'] ?>" class="button is-danger">Delete</a>
    </div>
<?php endforeach ?>
<?= template_footer() ?>