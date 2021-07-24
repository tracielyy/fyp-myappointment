<!DOCTYPE html>
<html lang="en">
<?php
require_once '../resources/config.php';
require '../vendor/autoload.php';
require_once SECURE_MOD. '/Security.php';
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body> 
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") :
            // collect value of input field
            $text = $_POST['textToDecrpyt'];
            $password = $_POST['passwordtoHash'];
            $verify = $_POST['verifypassword'];

            $sec = new Security();

            $hash_password = $sec->hash($password);
            echo $text.'<br><br>';
            $encrypted_text = $sec->encrypt($text);
            echo 'encrypted<br>';
            echo $encrypted_text;

            echo '<br><br>decryption<br>';
            $decrypted_text = $sec->decrypt($encrypted_text);
            echo $decrypted_text;
            echo '<br><br>hashed password<br>';
            echo $hash_password;

            $password_verify = $sec->compareHash($verify,'$2y$10$ynGkLfsLWRaSX5YxfQvDveWy8tiY0lYVaRQHyyrVG4sxmyTfWamEi');

            echo '<br><br>';
            echo "verification : " . $password_verify;

            if (true) :
                ?><script> testing = false; </script> <?php
            else:
                ?><script> testing = true; </script> <?php
            endif;

        endif; 
        ?>

<p id="demo"></p>
    <script> var testing; 
    document.getElementById("demo").innerHTML = testing;
</script> 
    <form method="post">

    <input type="text" name="textToDecrpyt"placeholder="some text" >
    <input type="password" name="passwordtoHash" placeholder="password" >
    <input type="password" name="verifypassword" placeholder="verify password" >
    <button type="submit" value=""> Submit </button>

    </form>

</body>
</html>