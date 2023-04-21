<?php
require 'config.php';

//check to see if account exists
if (isset($_GET['email'], $_GET['code'])) {
    if ($stmt = $mysqli->prepare('SELECT * FROM `accounts` WHERE `email` = ? AND `activation_code` = ?')) {
        $stmt->bind_param('ss', $_GET['email'], $_GET['code']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows() > 0) {
            if ($stmt = $mysqli->prepare('UPDATE `accounts` SET `activation_code` = ? WHERE `email` = ? AND `activation_code` = ?')) {
                $newcode = 'activated';
                $stmt->bind_param('sss', $newcode, $_GET['email'], $_GET['code']);
                $stmt->execute();
                echo 'Your account had been verified and activated!';
            }
        }
    }
}