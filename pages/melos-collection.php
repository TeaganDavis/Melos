<html>
<head>
    <title>Melos Collection</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" href="../Css/main.css">
    <link rel="stylesheet" href="../Css/songsPage.css">

    <?php
        $DBConnection = new mysqli("localhost", "root", "", "melos");
        $MelosCollection = $DBConnection->query("SELECT * FROM melos_collection");
        
        $AllPlaylists = $DBConnection->query("SELECT playlist_id, playlist_name, privacy FROM playlists ORDER BY playlist_name");
        $playlists = [];
        while ($playlist = $AllPlaylists->fetch_assoc()) {
            $playlists[] = $playlist;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_to_playlists') {
            $songId = intval($_POST['song_id']);
            $playlistIds = json_decode($_POST['playlist_ids'], true);
            $userRating = 5; // Default rating, you can make this configurable
            
            $successCount = 0;
            $errors = [];
            
            foreach ($playlistIds as $playlistId) {
                $playlistId = intval($playlistId);
                
                // Check if song is already in playlist
                $checkQuery = $DBConnection->prepare("SELECT * FROM playlist_songs WHERE playlist_id = ? AND song_id = ?");
                $checkQuery->bind_param("ii", $playlistId, $songId);
                $checkQuery->execute();
                $result = $checkQuery->get_result();
                
                if ($result->num_rows == 0) {
                    // Add song to playlist
                    $insertQuery = $DBConnection->prepare("INSERT INTO playlist_songs (playlist_id, song_id, user_rating) VALUES (?, ?, ?)");
                    $insertQuery->bind_param("iii", $playlistId, $songId, $userRating);
                    
                    if ($insertQuery->execute()) {
                        $successCount++;
                    } else {
                        $errors[] = "Failed to add to playlist ID: $playlistId";
                    }
                } else {
                    $errors[] = "Song already exists in playlist ID: $playlistId";
                }
            }
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $successCount > 0,
                'successCount' => $successCount,
                'errors' => $errors,
                'message' => $successCount > 0 ? "Successfully added to $successCount playlist(s)" : "No playlists were updated"
            ]);
            exit;
        }
    ?>
</head>
<body>
    <?php
        require "../components/navigation.php"
    ?>

    <div id="content">
        <div class="header">
            <h2>Our Song Collection</h2>
            
            <!-- I do plan on having a search option and filter options in the future
            <input type="text" id="song-searchBox" placeholder="Search Songs"> -->
        </div>
        <div id="song-collection">
            
            <table id="song-table">
                <tr>
                    <th>Title</th>
                    <th>Artist</th>
                    <th>Album</th>
                    <th>Length</th>
                    <th>Release Date</th>
                    <th>Add to Playlist</th>
                </tr>
                <!-- I would like to change this to an API call Spotify's api eventually. -->
                <?php
                    if ($MelosCollection->num_rows > 0) {
                        while ($Row = $MelosCollection->fetch_assoc()){
                            if (isset($Row['song_id'], $Row['title'], $Row['artist'], $Row['album'], $Row['release_date'], $Row['song_length'])){
                                echo 
                                "<tr>
                                    <td>" . $Row['title'] . "</td>
                                    <td>" . $Row['artist'] . "</td>
                                    <td>" . $Row['album'] . "</td>
                                    <td>" . $Row['song_length'] . "</td>
                                    <td>" . $Row['release_date'] . "</td>
                                    <td>
                                        <button onclick='openPlaylistModal(" . $Row['song_id'] . ", \"" . htmlspecialchars($Row['title']) . "\")'>
                                            Add to Playlist
                                        </button>
                                    </td>
                                </tr>";
                            } else {
                                echo "<tr><td colspan='6'>Row empty</td></tr>";
                            }
                        }
                    } else {
                        echo "<tr><td colspan='6'>Collection empty</td></tr>";
                    }
                ?>
            </table>
        </div>
    </div>
    <?php include "../components/footer.php" ?>
</body>
</html>