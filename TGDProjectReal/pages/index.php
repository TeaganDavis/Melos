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
            <?php for ($x = 1; $x <= 8; $x+=1) {
                echo "<a href='#playlist-id". $x . "' class='playlist-links'>
                    <div class='playlists' id='playist-id" . $x . "'>
                    <!--<img src='../data/musical-note.png' width=20%>-->
                    <div class='playlist-details'>
                        <h3>Playlist Name " . $x . "</h3>
                        <!-- Song Count -->
                        <h4>## songs</h4>
                        <!-- Playlist play length -->
                        <h4># hr. ## min.</h4> 
                    </div> </a>
                </div>";}
            ?>
        </div>
    </div>
    <?php include "../components/footer.php" ?>
</body>
</html>