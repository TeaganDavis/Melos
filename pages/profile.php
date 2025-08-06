<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <?php 
        require "../init.php";

        if (isset($_POST['logout'])) {
            session_start();
            setcookie('username', '', time() - 3600, "/");
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit;
        }
    ?>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <?php require "../components/navigation.php" ?>

    <?php if($_SESSION['isLoggedIn']): ?>
        <div id="content">
            <h1><?php echo $_SESSION['username'] ?></h1>
            <form method="post">
                <button type="submit" name="logout" value="logout">Log Out</button>
            </form>
        </div>
    <?php else: ?>
        <div id="content">
            <h1><a href="../pages/login.php">Login</a></h1>
        </div>
    <?php endif ?>
    <?php require "../components/footer.php" ?>
</body>
</html>