<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    <?php 
        include_once "../init.php";
    ?>
</head>
<body>
    <?php 
        if (!empty($_COOKIE['username'])) {
            echo "
                <div class='header'>
                    <h1>Welcome Back, <a href='../pages/profile.php'>" . htmlspecialchars($_COOKIE['username']) . "</a></h1>
                    <h3>The best collection of melodies</h3>
                </div>
            ";
        } elseif (!empty($_SESSION['username'])) {
            echo "
                <div class='header'>
                    <h1>Welcome Back, <a href='../pages/profile.php'>" . htmlspecialchars($_SESSION['username']) . "</a></h1>
                    <h3>The best collection of melodies</h3>
                </div>
            ";
        } else {
            echo "
                <div class='header'>
                    <h1>Welcome to <a>Melos</a></h1>
                    <h3>The best collection of melodies</h3>
                </div>
                
                <div id='login'>
                    <h4>Be sure to <a href='../pages/login.php'>login</a></h4>
                    <h4>or <a href='../pages/register.php'>create</a> an account!</h4>
                </div>";
        }
    ?>
</body>
</html>