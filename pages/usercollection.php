<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Collection</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/userCollection.css">
    <?php require_once "../init.php";

        $DBConnection = new mysqli("localhost", "root", "", "melos");

        $name = "";
        $description = "";
        $privacy = "Private";
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = trim($_POST["playlist_name"] ?? "");
            $description = trim($_POST["playlist_desc"] ?? "");
            $privacy = $_POST["playlist_privacy"] ?? "";

            // Check an empty name then at least 3 characters
            if (empty($name)) {
                $errors["name"] = "Playlist name is required.";
            } elseif (!preg_match("/.{3,}/", $name)) {
                $errors["name"] = "Playlist name must be 3 characters or more.";
            }

            if (!in_array($privacy, ["Public", "Friends only", "Private"])) {
                $errors["privacy"] = "Please select a valid option.";
            }

            // Check for duplicate playlists per user
            $CheckDuplicate = $DBConnection->prepare("SELECT playlist_id FROM playlists WHERE user_id = ? AND playlist_name = ?");
            $CheckDuplicate->bind_param("is", $_SESSION['user_id'], $name);
            $CheckDuplicate->execute();
            $DuplicateResult = $CheckDuplicate->get_result();

            if ($DuplicateResult->num_rows > 0) {
                $errors["name"] = "Playlist already named '$name'.";
            }

            if (empty($errors)) {
                $InsertPlaylist = $DBConnection->query("INSERT INTO playlists (user_id, playlist_name, playlist_desc, privacy) VALUES ('" . $_SESSION['user_id'] . "', '$name', '$description', '$privacy')");

                if ($InsertPlaylist){
                    echo "$privacy playlist created!<br>";
                }
            }
        }
    ?>
</head>
<body>
    <?php 
        require "../components/navigation.php"
    ?>

    <?php if($_SESSION['isLoggedIn']): ?>
        <div id="content">
            <?php require "../components/playlistForm.php" ?>

            <h2>Your Playlists</h2>
            <div id="recent-playlists">
                <?php 
                    //This sets the user's specific playlists
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

                            echo "<form method='post' action='../pages/playlist-page.php'>
                                <div class'recent-playlist'>
                                    <input type='hidden' name='form_id' value='playlist_form'/>
                                    <input type='hidden' name='playlist_id' value='". htmlspecialchars($Row['playlist_id']) ."'/>
                                    <label font-size='20'>" . htmlspecialchars($Row['playlist_name']) . "</label>
                                    <label>". htmlspecialchars($Row['playlist_desc']) ."</label>
                                    <label>". htmlspecialchars($Row['privacy']) ."</label>
                                    <button type='submit'>Open</button>
                                </div>
                            </form>";

                            // echo "<div class='recent-playlist'><a href='../pages/playlist.php'>";
                            // echo "<input type='hidden' value='". htmlspecialchars($Row['playlist_id']) ."'/>";
                            // echo "<h3>" . htmlspecialchars($Row['playlist_name']) . "</h3>";
                            // echo "<p>" . htmlspecialchars($Row['playlist_desc']) . "</p>";
                            // echo "<p><strong>Privacy:</strong> " . htmlspecialchars($Row['privacy']) . "</p>";
                            // echo "</a></div>";
                        }
                    } else {
                        echo "<p>You don't have any playlists yet.</p>";
                    }
                ?>
            </div>
        </div>
    <?php else: ?>
        <div id="content">
            <h1>Login Required</h1>
            <h3>You're currently not logged in, please do so <a href="../pages/login.php">here!</a></h3>
        </div>
    <?php endif ?>
    <?php include "../components/footer.php" ?>
</body>
</html>