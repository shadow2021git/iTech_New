<?php
require_once('../config.php');
require_once('googleClientSetup.php');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (!isset($token["error"])) {
        $client->setAccessToken($token['access_token']);

        // Get profile info
        $google_oauth = new Google\Service\Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        $email = $google_account_info->email;
        $name = $google_account_info->name;

        // Check user in DB or register new
        $qry = $conn->query("SELECT * FROM users WHERE email = '{$email}'");
        if ($qry->num_rows > 0) {
            $user = $qry->fetch_assoc();
            foreach ($user as $k => $v) {
                if (!is_numeric($k))
                    $_SESSION['userdata'][$k] = $v;
            }

            $_SESSION['login_id'] = $user['id'];
            header('Location: index.php');
            exit;
        } else {
            //
            $conn->query("INSERT INTO users (fullname, email, username, password) 
                VALUES ('{$name}', '{$email}', '{$email}', md5('default_password'))");

            $id = $conn->insert_id;
            $_SESSION['userdata'] = [
                'id' => $id,
                'fullname' => $name,
                'email' => $email,
                'username' => $email
            ];
            $_SESSION['login_id'] = $id;

            header('Location: index.php');
            exit;
        }
    } else {
        echo "Error while authenticating with Google.";
    }
} else {
    echo "No code returned from Google.";
}