<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email = $_POST['email'];

?>

<?php require_once('header.php') ?>
<form method="POST" action="">
    <div>
        <label> Saisir votre Adresse-Mail :
            <input type="text" name="email" id="email" value="<?php htmlspecialchars(trim($_POST['email']))?>">
        </label>
        <button type="submit">Valider</button>
    </div>
</form>
