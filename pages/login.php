<html lang="en">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/login.css">

    <?php require_once "../init.php" ?>
</head>
<body>
        <div id="content">
            <?php require "../components/navigation.php" ?>
            <h1>Login</h1>
            <form method="post" action="">
                <input type="hidden" name="form_id" value="login_form">

                <label for="username">Username: </label>
                <input type="text" id="username" name="loginusername" value="" required>

                <label for="password">Password: </label>
                <input type="password" id="password" name="loginpassword" value="" required>

                <div>
                    <label><input type="checkbox" name="remember_me">Remember Me</label>
                </div>
                
                <button type="submit" name="submit_login">Login!</button>
            </form>
            
            <p>Don't have an account? <a href="./register.php">Create one</a>!</p>

            <?php

                $DBConnection = new mysqli("localhost", "root", "", "melos");
                $UserTable = $DBConnection->query("SELECT * FROM users");
                
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['form_id'])) {

                        $form_id = $_POST['form_id'];
                        if ($form_id == 'login_form') {
                            $username = trim($_POST['loginusername']);
                            $password = trim($_POST['loginpassword']);
                            checkUserArray($username, $password);
                        } else {
                            echo "<p>Unkown form recieved.</p>";
                        }
                    } else {
                        echo "";
                    }
                } else {
                    echo "";
                }

                // checks the accounts array to check for previous people now!
                function checkUserArray($username, $password) {
                    global $DBConnection;

                    foreach ($_SESSION['accountsArray'] as $account) {
                        // Admin login check
                        if ($username === 'nagaeT' && $password === 'Ii75xrT&SO6af') {
                            $_SESSION['user_id'] = 4;
                            $_SESSION['isLoggedIn'] = true;
                            $_SESSION['username'] = $username;
                            $_SESSION['admin'] = true;

                            // If I want auto-loggin
                            if (isset($_POST['remember_me'])) {
                                setcookie('username', $username, time() + (86400 * 30), "/");
                            } else {
                                setcookie('username', '', time() - 3600, "/");
                            }
                            header("Location: index.php");
                        }

                        // Normal user login check
                        if ($username === $account['username'] && $password === $account['password']) {
                            $stmt = $DBConnection->prepare("SELECT user_id FROM users WHERE username = ?");
                            $stmt->bind_param("s", $username);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();

                            if ($row) {
                                $_SESSION['user_id'] = $row['user_id'];
                                $_SESSION['isLoggedIn'] = true;
                                $_SESSION['username'] = $username;

                                // If they want auto-loggin
                                if (isset($_POST['remember_me'])) {
                                    setcookie('username', $username, time() + (86400 * 30), "/");
                                } else {
                                    setcookie('username', '', time() - 3600, "/");
                                }

                                header("Location: index.php");
                            } else {
                                echo "<p>Could not retrieve user ID.</p>";
                            }
                        }
                    }

                    // If no matches found at all
                    echo "<p>Invalid username or password. Please try again.</p>";
                }
                ?>

            <?php require "../components/footer.php" ?>
        </div>
</body>
</html>