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

                if ($UserPlaylists && $UserPlaylists->num_rows > 0){
                    while ($Row = $UserPlaylists->fetch_assoc()) {
                        $_SESSION['playlists'][] = [
                            "playlist_id" => $Row['playlist_id'],
                            "user_id" => $Row['user_id'],
                            "playlist_name" => $Row['playlist_name'],
                            "playlist_desc" => $Row['playlist_desc'],
                            "privacy" => $Row['privacy']
                        ];

                        echo "<a class='playlist-links'>
                            <div class='playlists' id='". $Row['playlist_id'] ."'>";
                        echo "<h3>" . htmlspecialchars($Row['playlist_name']) . "</h3>";
                        echo "<p>" . htmlspecialchars($Row['playlist_desc']) . "</p>";
                        echo "<p><strong>Privacy:</strong> " . htmlspecialchars($Row['privacy']) . "</p>";
                        echo "</div>
                            </a>";
                    }
                } else {
                    echo "<p>You don't have any playlists yet.</p>";
                }
            ?>
        </div>
    </div>
    <?php include "../components/footer.php" ?>
</body>
</html>