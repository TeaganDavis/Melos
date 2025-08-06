<html>
    <head>
        <?php
        // Temporary passwords, most of which
        // are coming from a sample data file
        // I'll be using later for login stuff.
            $passwords = [
                "ayUf!R6n12l",
                "j_Wi3o0q!Kan",
                "thisIsAPassword",
                "HIAL372IE__EMS",
                "testingagain",
                "Ii75xrT&SO6af",
                "SGDn7!!20Ijan",
                "ValidP@ssword9"
            ];
        ?>
    </head>
    <body>
        <?php
            
            
            function checkPassword( $password){
                // Counter to tell if a password meets all 6 criteria
                $validCounter = 0;
                
                // Checks for the length of 12
                if (preg_match( "/.{12,}/", $password)){
                    $validCounter += 1;
                } else {
                    echo $password . " is too short.<br>";
                }

                // Goes through to find at least 1 lowercase letter
                if (preg_match( "/(?=.*[a-z])/", $password)){
                    $validCounter += 1;
                } else {
                    echo $password . " doesn't have a lowercase letter.<br>";
                }

                // Goes through to find at least 1 uppercase letter
                if (preg_match( "/(?=.*[A-Z])/", $password)){
                    $validCounter += 1;
                } else {
                    echo $password . " doesn't have an uppercase letter.<br>";
                }

                // Checks for at least 1 digit
                if (preg_match( "/(?=.*\d)/", $password)){
                    $validCounter += 1;
                } else {
                    echo $password . " doesn't have a number.<br>";
                }

                // Checks for at least 1 special char
                if (preg_match( "/(?=.*[\W_])/", $password)){
                    $validCounter += 1;
                } else {
                    echo $password . " doesn't have a special character.<br>";
                }

                // Makes sure that there isn't any spaces
                if (preg_match("/\s/", $password)) {
                    echo $password . " contains a space, no spaces in passwords.<br>";
                } else {
                    $validCounter += 1;
                }

                // Once all criteria is passed, displays valid password!
                if ($validCounter == 6){
                    echo $password . " is valid! <br>";
                }
            }
        ?>
        
        <div>
            <?php
                // Loops through the passwords array and checks each one
                foreach ($passwords as $password){
                    echo "Currently testing password: " . $password . "<br>";
                    checkPassword( $password );
                    echo "<br>";
                }
            ?>
        </div>
    </body>
</html>