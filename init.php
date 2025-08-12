<!-- This is setting up a session to keep the user 
 logged in while keeping their username.
 This way I can call $_SESSION['username'] accross 
 different pages, instead of just in welcome.php-->
<?php
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // This section grabs all the data and stores it into the session's
    // multidimentional associative array for multipage use.
    $DBConnection = new mysqli("localhost", "root", "", "melos");
    if ($DBConnection->connect_error) {
        die("Connection failed: " . $DBConnection->connect_error);
    }
    $UserTable = $DBConnection->query("SELECT * FROM users");
    $PlaylistTable = $DBConnection->query("SELECT * FROM playlists");
    $PlaylistSongs = $DBConnection->query("SELECT * FROM playlist_songs");

    $_SESSION['accountsArray'] = [];

    // Setting each value in the array per the respective column
    if ($UserTable->num_rows > 0){
        while ($Row = $UserTable->fetch_assoc()) {
            if (isset($Row['user_id'], $Row['username'], $Row['email'], $Row['user_pass'])){
                $_SESSION['accountsArray'][] = [
                    "user_id" => $Row['user_id'],
                    "username" => $Row['username'],
                    "password" => $Row['user_pass'],
                    "email" => $Row['email']
                ];
            } else {
                echo "No user data found.<br>";
            }
        }
    }

    // Logged in toggle
    if(!isset($_SESSION['isLoggedIn'])){
        $_SESSION['isLoggedIn'] = false;
    }
    
    // Used for displaying, checks for a cookie username
    // otherwise just sets it to empty for when the login sets the username
    if(!isset($_SESSION['username']) && !empty($_COOKIE['username'])){
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['isLoggedIn'] = true;
    } elseif (!isset($_SESSION['username']) && empty($_COOKIE['username'])){
        $_SESSION['username'] = "";
    }
    // Used to grab playlists per user id
    if(!isset($_SESSION['user_id'])){
        $_SESSION['user_id'] = 0;
    }
    // Uh, dunno what to do with admin yet but it's here
    if(!isset($_SESSION['admin'])){
        $_SESSION['admin'] = false;
    }

    
    if(!isset($_SESSION['playlists'])){
        $_SESSION['playlists'] = [];
    }
    if(!isset($_SESSION['selectedPlaylist'])){
        $_SESSION['selectedPlaylist'] = 0;
    }

?>