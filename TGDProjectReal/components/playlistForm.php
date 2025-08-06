<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div id="content">
        <div id="form_content">
                <form method="post" action="">
                    <h3>New Playlist</h3>

                    <!-- Playlist name -->
                    <label>Playlist Name: <input type="text" id="playlist_name" name="playlist_name" value="<?= htmlspecialchars($name) ?>" required></label>
                    <?php if (isset($errors["name"])): ?>
                        <span class="error"><?= $errors["name"] ?></span>
                    <?php endif; ?>

                    <!-- Description -->
                    <label>Description: <input type="text" id="playist_desc" name="playlist_desc" value="<?= htmlspecialchars($description) ?>" > </label>

                    <!-- Privacy -->
                    <div id="playlist_privacy_options">
                        <h4>Privacy:</h4>
                        <label>
                            <input type="radio" name="playlist_privacy" value="Public" <?= $privacy === "Public" ? "checked" : "" ?>>
                            Public
                        </label>
                        <label>
                            <input type="radio" name="playlist_privacy" value="Friends only" <?= $privacy === "Friends only" ? "checked" : "" ?>>
                            Friends only
                        </label>
                        <label>
                            <input type="radio" name="playlist_privacy" value="Private" <?= $privacy === "Private" || empty($privacy) ? "checked" : "" ?>>
                            Private
                        </label>
                    </div>
                    <?php if (isset($errors["privacy"])): ?>
                        <span class="error"><?= $errors["privacy"] ?></span>
                    <?php endif; ?>

                    <div>
                        <button type="submit" name="submit_new_playlist">Create!</button>
                    </div>
                </form>
            </div>
    </div>
</body>
</html>