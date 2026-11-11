<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

require_once ('database/functions_database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    if (!empty($email) && !empty($mdp)) {
        authentifierUser($email, $mdp);
            } else {
                $erreur = "Veuillez remplir les champs";
            }
        }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <body>
        <p>page connexion</p>

        <form method="POST">
            <label>
                Adresse-Mail :
                <input type="text" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"/>
            </label>

            <label>
                Mot de Passe :
                <input type="password" name="mdp" value="<?= htmlspecialchars($_POST['mdp'] ?? '') ?>"/>
            </label>

            <button>CONNEXION</button>

        </form>

        <a href="filesPHP/motDePasseOublie.php" type="button">MOT DE PASSE OUBLIÉ</a>

        <a href="filesPHP/p_inscription.php">S'INSCRIRE</a>

        <script src="https://accounts.google.com/gsi/client" async defer></script>

        <div id="g_id_onload"
             data-client_id="315466710562-hji77ere2gg2ntbpopangpo4utgkpnvk.apps.googleusercontent.com"             data-login_uri="http://localhost/sae/Code/be_health/google_callback.php"
             data-login_uri="http://localhost/sae/Code/be_health/filesPHP/google_callback.php"
             data-auto_prompt="false">
        </div>

        <div class="g_id_signin"
             data-type="standard"
             data-size="large"
             data-theme="outline"
             data-text="sign_in_with"
             data-shape="rectangular"
             data-logo_alignment="left">
        </div>
    </body>
</html>