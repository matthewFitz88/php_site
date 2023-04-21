<?php
require 'config.php';

//start user session
session_start();

//check to see if the form was sent
if (isset($_POST['username'], $_POST['password'])) {
    if ($stmt = $mysqli->prepare('SELECT `id`, `password`, `email`, `activation_code` FROM `accounts` WHERE `username` = ?')) {
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows() > 0) {
            $stmt->bind_result($id, $hash, $email, $code);
            $stmt->fetch();

            //check activation code
            if ($code === 'activated') {

                if (password_verify($_POST['password'], $hash)) {
                    session_regenerate_id();
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['username'] = $_POST['username'];
                    $_SESSION['id'] = $id;
                    header('Location: profile.php');
                } else {
                    $userResponses[] = 'Incorrect password.';
                }

            } else {
                //Redisplay activation code
                $stmt = $mysqli->prepare('UPDATE `accounts` SET `activation_code` = ? WHERE `id` = ? AND `activation_code` = ?');

                $newActivationCode = uniqid();
                $stmt->bind_param('sis', $newActivationCode, $id, $newActivationCode);
                $stmt->execute();

                $activationLink = getMyUrl() . '/activate.php?email=' . $email . '&code=' . $code;
                $userResponses[] = 'Your account is not yet active. Please click this link to activate your account: <a href="'. $activationLink .'">'. $activationLink .'</a>';
            }
        } else {
            $userResponses[] = 'Incorrect username.';
        }

    } else {
        $userResponses[] = 'Could not prepare the sql statement.';
    }
}
?>

<?= template_header('Login') ?>
<?= template_nav('My Website') ?>
<?php if ($userResponses) : ?>
    <p class="notification is-success is-light">
        <?php
        echo implode('<br>', $userResponses);
        // echo '<br>';
        // var_dump($_POST);
        ?>
    </p>
<?php endif; ?>
<h1 class="title">Login</h1>
<form action="" method="post">
    <div class="field">
        <label class="label">Username</label>
        <div class="control has-icons-left">
            <input class="input" name="username" type="text" placeholder="bsmith" required>
            <span class="icon is-left">
                <i class="fas fa-user"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <label class="label">Password</label>
        <div class="control has-icons-left">
            <input class="input" type="password" name="password" required>
            <span class="icon is-left">
                <i class="fas fa-lock"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <button type="submit" class="button is-success">Login</button>
        </div>
    </div>
</form>
<hr>
<a href="register.php" class="button is-small">
    <span class="icon">
        <i class="fas fa-user-plus"></i>
    </span>
    <span>Create User Account</span>
</a>
<?= template_footer() ?>