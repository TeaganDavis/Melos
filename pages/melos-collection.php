<html>
<head>
    <title>Melos Collection</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" href="../Css/main.css">
    <link rel="stylesheet" href="../Css/songsPage.css">

    <?php
        session_start();
        $DBConnection = new mysqli("localhost", "root", "", "melos");
        
        // Get ALL songs from the collection (this should show all songs)
        $MelosCollection = $DBConnection->query("SELECT * FROM melos_collection");

        $userId = $_SESSION['user_id'];
        
        // Get user's playlists for the dropdown options
        $UserPlaylists = $DBConnection->query("SELECT playlist_id, playlist_name FROM playlists WHERE user_id = $userId ORDER BY playlist_name");
        $playlists = [];
        while ($playlist = $UserPlaylists->fetch_assoc()) {
            $playlists[] = $playlist;  // Fixed: Use [] to add to array
        }

        $message = "";
        $messageType = "";

        // Handle adding song to playlist
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_playlist'])) {
            $songId = intval($_POST['song_id']);
            $playlistId = intval($_POST['playlist_id']);
            $userRating = floatval($_POST['user_rating']);
            
            // Validate rating range
            if ($userRating < 0.1 || $userRating > 10.0) {
                $message = "Rating must be between 0.1 and 10.0";
                $messageType = "error";
            } elseif ($songId > 0 && $playlistId > 0) {
                // Check if song already exists in playlist
                $CheckDuplicate = $DBConnection->prepare("SELECT * FROM playlist_songs WHERE playlist_id = ? AND song_id = ?");
                $CheckDuplicate->bind_param("ii", $playlistId, $songId);
                $CheckDuplicate->execute();
                $DuplicateResult = $CheckDuplicate->get_result();
                
                if ($DuplicateResult->num_rows > 0) {
                    $message = "Song is already in this playlist!";
                    $messageType = "error";
                } else {
                    // Add song to playlist with user's rating
                    $InsertSong = $DBConnection->prepare("INSERT INTO playlist_songs (playlist_id, song_id, user_rating) VALUES (?, ?, ?)");
                    $InsertSong->bind_param("iid", $playlistId, $songId, $userRating);
                    
                    if ($InsertSong->execute()) {
                        // Get playlist name for success message
                        $PlaylistName = $DBConnection->prepare("SELECT playlist_name FROM playlists WHERE playlist_id = ?");
                        $PlaylistName->bind_param("i", $playlistId);
                        $PlaylistName->execute();
                        $PlaylistResult = $PlaylistName->get_result();
                        $playlistData = $PlaylistResult->fetch_assoc();
                        
                        $message = "Song added to '" . $playlistData['playlist_name'] . "' with rating " . $userRating . "!";
                        $messageType = "success";
                    } else {
                        $message = "Error adding song to playlist.";
                        $messageType = "error";
                    }
                }
            } else {
                $message = "Please select a valid playlist.";
                $messageType = "error";
            }
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
            
            <?php if (!empty($message)): ?>
                <div class="<?php echo $messageType; ?>-message" style="text-align: center; padding: 10px; margin: 10px 0;">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
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
                                echo "<tr>
                                    <td>" . htmlspecialchars($Row['title']) . "</td>
                                    <td>" . htmlspecialchars($Row['artist']) . "</td>
                                    <td>" . htmlspecialchars($Row['album']) . "</td>
                                    <td>" . htmlspecialchars($Row['song_length']) . "</td>
                                    <td>" . htmlspecialchars($Row['release_date']) . "</td>
                                    <td>";
                                
                                // Only show form if user has playlists
                                if (!empty($playlists)) {
                                    echo "<form method='post' class='playlist-form'>
                                            <input type='hidden' name='song_id' value='" . $Row['song_id'] . "'/>
                                            <select name='playlist_id' required>
                                                <option value=''>Select playlist...</option>";
                                    
                                    // Add playlist options
                                    foreach ($playlists as $playlist) {
                                        echo "<option value='" . $playlist['playlist_id'] . "'>" . 
                                             htmlspecialchars($playlist['playlist_name']) . "</option>";
                                    }
                                    
                                    echo "      </select>
                                            <input type='number' name='user_rating' min='0.1' max='10.0' step='0.1' value='7.0' 
                                                   style='width: 50px; padding: 4px; border: 1px solid #ccc; border-radius: 4px; font-size: 12px;' 
                                                   title='Rating (0.1 to 10.0)' required>
                                            <button type='submit' name='add_to_playlist'>Add</button>
                                        </form>";
                                } else {
                                    echo "<span style='font-size: 12px; color: #666;'>No playlists</span>";
                                }
                                
                                echo "</td>
                                </tr>";
                            } else {
                                echo "<tr><td colspan='6'>Row data incomplete</td></tr>";
                            }
                        }
                    } else {
                        echo "<tr><td colspan='6'>Collection empty</td></tr>";
                    }
                ?>
            </table>

            <?php if (empty($playlists)): ?>
                <div style="text-align: center; margin: 20px; padding: 20px; background-color: #f0f0f0; border-radius: 5px;">
                    <p>You don't have any playlists yet!</p>
                    <p><a href="../pages/user-collection.php">Create your first playlist here</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php include "../components/footer.php" ?>
</body>
</html>