<html>
<head>
    <title>Melos Collection</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" href="../Css/main.css">
    <link rel="stylesheet" href="../Css/songsPage.css">

    <?php
        $DBConnection = new mysqli("localhost", "root", "", "melos");
        $MelosCollection = $DBConnection->query("SELECT * FROM melos_collection");
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
                                </tr>";
                            } else {
                                echo "Row empty <br>";
                            }
                        }
                    } else {
                        echo "Collection empty <br>";
                    }
                ?>
            </table>
        </div>
    </div>
    <?php include "../components/footer.php" ?>
</body>
</html>