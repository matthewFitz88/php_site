<?php
require 'config.php';

//php scripts will go here
?>

<?= template_header('Test Page') ?>
<?= template_nav('FitzBlogging') ?>
<?php if ($userResponses) : ?>
    <p class="notification is-success is-light">
        <?php
        echo implode('<br>', $userResponses);
        // echo '<br>';
        // var_dump($_POST);
        ?>
    </p>
<?php endif; ?>
<h1 class="title">Hello World</h1>
<p>A paragraph</p>
<?= template_footer() ?>