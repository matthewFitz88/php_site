<?php
//mysqli database connection
$servername = "localhost";
$username = "web3400";
$password = "password";
$dbname = "web3400";

//create connection
$mysqli = mysqli_connect($servername, $username, $password, $dbname);

//check connection
if(!$mysqli) {
    die("Connection Failed: " . mysqli_connect_error());
}

function pdo_connect_mysql() {
    $servername = "localhost";
    $username = "web3400";
    $password = "password";
    $dbname = "web3400";

    try {
        return new PDO(
            'mysql:host=' . $servername .
            ';dbname=' . $dbname . 
            ';charset=utf8', $username, $password
        );
    } catch (PDOException $exception){
        die("PDO failed to connect to the DB: $exception");
    }
}

//create output message array
$userResponses = [];

//get URL function
function getMyUrl() {
    $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $regx_pattern = '/(.*)\/.*\.php/';
    return 'https://' . preg_replace($regx_pattern, '$1', $url);
}

//header function
function template_header($title = "Page Title") {
    echo <<<EOT
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>$title</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
        <script defer src="js/main.js"></script>
    </head>
    <body>
    EOT;
}

//nav function
function template_nav() {
    //logic for login/logout button
    $logInOutLink = '';

    if (isset($_SESSION['loggedin'])) {
        $logInOutLink = 'out';
        $profileName = 'Profile';
    } else {
        $logInOutLink = 'in';
        $profileName = 'Admin';
    }

    echo <<<EOT
    <nav class="navbar" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <a class="navbar-item" href="index.php">
          <span class="icon is-large">
            <i class="fas fa-home"></i>
          </span>
          <span>FitzBlogging</span>
        </a>
    
        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>
    
      <div id="navbarBasicExample" class="navbar-menu">
    
        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons has-icons-left">
              <a href="profile.php" class="button">
                <span class="icon is-left">
                    <i class="fas fa-user-circle"></i>
                </span>
                <span>$profileName</span>
              </a>
              <a href="log$logInOutLink.php" class="button">
                <span class="icon"><i class="fas fa-sign-$logInOutLink-alt"></i></span>
                <span>Log$logInOutLink</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </nav>
    <section class="section">
        <div class="container">
    EOT;
}
 
//footer function
function template_footer() {
    echo <<<EOT
            </div>
        </section>
        <footer class="footer">
            <div class="container">
                <p>© Copyright 2022 All Rights Reserved</p>
            </div>
        </footer>
    </body>
    </html>
    EOT;
}
