<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/main.css">
    <?php 
        $DBConnection = new mysqli("localhost", "root", "", "melos");
    ?>
</head>
<body>
    <?php require "../components/navigation.php" ?>

    <div id="content">
        <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                if (isset($_POST['form_id'])) {
                    $form_id = $_POST['form_id'];
                    if ($form_id == 'playlist_form') {
                        $playlistId = trim($_POST['playlist_id']);

                        retrieveData($playlistId);
                    } else {
                        echo "<p>Unkown form recieved.</p>";
                    }
                } else {
                    echo "Form ID is empty. Please retry the selection.";
                }
            } else {
                echo "Invalid request method. Please retry the selection.";
            }

            function retrieveData($playlistId) {
                global $DBConnection;
                // Started with this... did NOT go well.
                // $PlaylistInfo = $DBConnection->query("SELECT * FROM playlists WHERE playlist_id = $playlistId");
                // $SongsInPlaylist = $DBConnection->query("SELECT * FROM songs WHERE playlist_songs.song_id = songs.song_id AND playlist_songs.playlist_id = $playlistId");

                
                // This is grabing all the data from the songs collection,
                // if the playlist_songs (table with playlist_id, song_id, 
                // and user_rating to pair songs to the playlists) 
                // matches the song id with the playlist id (from regular playlists table)
                // Example: melos_collection <-> playlist_songs <-> playlists
                $PlaylistWithSongs = $DBConnection->query("
                    SELECT 
                        p.playlist_id, p.user_id, p.playlist_name, p.playlist_desc, p.privacy,
                        m.song_id, m.title, m.artist, m.album, m.release_date, m.song_length,
                        ps.user_rating
                    FROM playlists p
                    LEFT JOIN playlist_songs ps ON p.playlist_id = ps.playlist_id
                    LEFT JOIN melos_collection m ON ps.song_id = m.song_id
                    WHERE p.playlist_id = $playlistId
                    ORDER BY ps.song_id
                ");

                if ($PlaylistWithSongs->num_rows > 0){
                    $playlistInfo = null;
                    $songs = [];

                    while ($row = $PlaylistWithSongs->fetch_assoc()) {
                        // Get playlist info from first row (will be the same for all rows)
                        if ($playlistInfo === null) {
                            $playlistInfo = [
                                'playlist_id' => $row['playlist_id'],
                                'user_id' => $row['user_id'],
                                'playlist_name' => $row['playlist_name'],
                                'playlist_desc' => $row['playlist_desc'],
                                'privacy' => $row['privacy']
                            ];
                        }
                        
                        // Only add songs if they exist (song_id won't be null)
                        if ($row['song_id'] !== null) {
                            $songs[] = [
                                'song_id' => $row['song_id'],
                                'title' => $row['title'],
                                'artist' => $row['artist'],
                                'album' => $row['album'],
                                'release_date' => $row['release_date'],
                                'song_length' => $row['song_length'],
                                'user_rating' => $row['user_rating']
                            ];
                        }
                    }

                    displayPlaylistInfo($playlistInfo, $songs);
                } else {
                    echo "<h2>Playlist is empty.</h2>";
                }
            }

            function displayPlaylistInfo($playlistInfo, $songs){
                
            }
        ?>
    </div>

    <?php include "../components/footer.php" ?>
</body>
</html>