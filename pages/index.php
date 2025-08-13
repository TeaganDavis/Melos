<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Updated 8/5/2025 by Teagan -->
    <!-- This page is a mock business/music page -->
    <!-- Melos is the shortened Greek word for 'melody' -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Melos</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <?php 
        require "../components/navigation.php"
    ?>

    <div id="content">
        <?php include "../components/welcome.php" ?>

        <!-- Once logged in, I want to show the user's recent
        listens and/or suggested music -->
        <h2>Your Recently Played</h2>
        <div id="recent-playlists">
            <?php 
                $DBConnection = new mysqli("localhost", "root", "", "melos");
                
                $UserPlaylists = $DBConnection->query("SELECT * FROM playlists WHERE user_id = ". $_SESSION['user_id']);

                $_SESSION['playlists'] = [];

                echo "<div id='recent-playlists>";

                if ($UserPlaylists && $UserPlaylists->num_rows > 0){
                    while ($Row = $UserPlaylists->fetch_assoc()) {
                        $_SESSION['playlists'][] = [
                            "playlist_id" => $Row['playlist_id'],
                            "user_id" => $Row['user_id'],
                            "playlist_name" => $Row['playlist_name'],
                            "playlist_desc" => $Row['playlist_desc'],
                            "privacy" => $Row['privacy']
                        ];

                        echo "
                        <form method='post' action='../pages/playlist-page.php'>
                            <a>
                            <div class='playlists'>
                                <input type='hidden' name='form_id' value='playlist_form'/>
                                <input type='hidden' name='playlist_id' value='". htmlspecialchars($Row['playlist_id']) ."'/>
                                <label font-size='20'>" . htmlspecialchars($Row['playlist_name']) . "</label>
                                <label>". htmlspecialchars($Row['playlist_desc']) ."</label>
                                <label>". htmlspecialchars($Row['privacy']) ."</label>
                                <button type='submit'>Open</button>
                            </div>
                            </a>
                        </form>
                        </div>";
                        
                    }
                } else {
                    echo "<p>You don't have any playlists yet.</p></div>";
                }
            ?>
        </div>
    </div>
    <?php include "../components/footer.php" ?>
</body>
</html>