<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <link rel="stylesheet" href="../Css/main.css">
    <link rel="stylesheet" href="../Css/login.css">

    <?php
        require_once "../init.php";
    ?>
</head>
<body>
<?php require "../Components/navigation.php" ?>
<div id="content">
    <h1>Register</h1>
    <form method="POST">
        <input type="hidden"  name="form_id" value="register_form">

        <label for="username">Username: </label>
        <input type="text" id="username" name="registerusername" required>

        <label for="password">Password: </label>
        <input type="password" id="password" name="registerpassword" required>

        <label for="email">Email: </label>
        <input type="email" id="email" name="registeremail" required>

        <button type="submit" name="create_account">Create!</button>
    </form>
    <p>Already have an account? <a href="./login.php">Login in</a>!</p>

    <?php
        // This is the form handler
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if (isset($_POST['form_id'])) {
                $form_id = $_POST['form_id'];

                if ($form_id === 'register_form') {
                    $username = trim($_POST['registerusername']);
                    $password = trim($_POST['registerpassword']);
                    $email = trim($_POST['registeremail']);
                    $errors = checkPassword($username, $password, $email);

                    if (empty($errors)) {
                        createUser($username, $password, $email);
                    }
                }
            }
        }

        function checkPassword($username, $password, $email)
        {
            $validCounter = 0;
            $errors = [];

            // Checks for 12 length
            if (preg_match('/.{12,}/', $password)) {
                $validCounter++;
            } else {
                $errors[] = "Password must contain at least 12 characters.";
            }

            // Checks for at least 1 lowercase
            if (preg_match('/[a-z]/', $password)) {
                $validCounter++;
            } else {
                $errors[] = "Password must contain at least one lowercase letter.";
            }

            // Checks for at least 1 uppercase
            if (preg_match('/[A-Z]/', $password)) {
                $validCounter++;
            } else {
                $errors[] = "Password must contain at least one uppercase letter.";
            }

            // Checks for at least 1 number
            if (preg_match('/\d/', $password)) {
                $validCounter++;
            } else {
                $errors[] = "Password must contain at least one number.";
            }

            // Checks for at least 1 special character
            if (preg_match('/[\W_]/', $password)) {
                $validCounter++;
            } else {
                $errors[] = "Password must contain at least one special character.";
            }

            // Checks for no spaces
            if (!preg_match('/\s/', $password)) {
                $validCounter++;
            } else {
                $errors[] = "Password must not contain spaces.";
            }

            // Checks for accounts with the username and/or email
            // then combines all the errors together (if any)
            if ($validCounter === 6) {
                $duplicateErrors = checkPasswordFile($username, $password, $email);
                $errors = array_merge($errors, $duplicateErrors);
            }

            return $errors;
        }


        function checkPasswordFile($username, $password, $email)
        {
            $errors = [];

            $usernameTaken = false;
            $emailTaken = false;

            // This will be checking for duplicates of usernames and passwords
            // now using the multidimentional associative array!
            foreach ($_SESSION['accountsArray'] as $account){
                if (!$usernameTaken && $account['username'] === $username){
                    $errors[] = "Username is already taken.";
                    $usernameTaken = true;
                }
                if (!$emailTaken && $account['email'] === $email){
                    $errors[] = "Email is already taken.";
                    $emailTaken = true;
                }

                if ($usernameTaken && $emailTaken){
                    break;
                }
            }

            return $errors;
        }

        // Creating the user (making a new row)
        function createUser($username, $password, $email) {
            $DBConnection = new mysqli("localhost", "root", "", "melos");
            if ($DBConnection->connect_error) {
                die ("Connection failed: " . $DBConnection->connect_error);
            }

            $InsertUser = $DBConnection->query("INSERT INTO users(username, email, user_pass) VALUES ('$username', '$email', '$password')");

            if ($InsertUser) {
                // If they want auto-loggin
                if (isset($_POST['remember_me'])) {
                    setcookie('username', $username, time() + (86400 * 30), "/");
                } else {
                    setcookie('username', '', time() - 3600, "/");
                }
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['username'] = $username;

                // This checks the most recent insertion for it's id avoiding
                // any problems if multiple people make one at the same time.
                $_SESSION['user_id'] = $DBConnection->insert_id;
                header("Location: index.php");
            } else {
                echo "Insert failed: " . $DBConnection->error;
            }
            
        }
    ?>

    <!-- This displays any invalid requirements with the password -->
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <?php foreach ($errors as $error): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


</div>
<?php require "../Components/footer.php" ?>
</body>
</html>