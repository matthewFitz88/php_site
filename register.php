<?php
require 'config.php';

//php scripts will go here
if (isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    //check if database conn is valid & our sql statement is prepared
    if ($stmt = $mysqli -> prepare('SELECT `id`, `password` FROM `accounts` WHERE `username` = ?')) {
        $stmt->bind_param('s', $_POST['username']);
        //Execute the query
        $stmt->execute();
        //store the result
        $stmt->store_result();
        //check to see if the username already exists
        if ($stmt->num_rows > 0) {
            $userResponses[] = 'Username already exists, please choose another.';
        } else {
            //That username is availble so inert the record
            if ($stmt = $mysqli->prepare('INSERT INTO `accounts`(`username`, `password`, `email`, `activation_code`) VALUES (?,?,?,?)')) {
                $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                //create a unique id for the activation code
                $activationCode = uniqid();
                //bind data
                $stmt->bind_param('ssss', $_POST['username'], $hash, $_POST['email'], $activationCode);
                $stmt->execute();

                //Send confirmation email using a link to display on page instead
                $activationLink = getMyUrl() . '/activate.php?email=' . $_POST['email'] . '&code=' . $activationCode;
                $userResponses[] = 'Please click this link to activate your account: <a href="'. $activationLink .'">'. $activationLink .'</a>';
            }
        }
    } else {
        $userResponses[] = 'Could not prepare SELECT statement';
    }
}
?>

<?= template_header('Register') ?>
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
<h1 class="title">Register</h1>
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
        <label class="label">Email</label>
        <div class="control has-icons-left">
            <input class="input" type="email" name="email" placeholder="example@email.com" required>
            <span class="icon is-left">
                <i class="fas fa-at"></i>
            </span>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <button type="submit" class="button is-success">Register</button>
        </div>
    </div>
</form>
<?= template_footer() ?>