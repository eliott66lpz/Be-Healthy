<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../database/functions_database.php');

$msgErreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom           = trim($_POST['nom'] ?? '');
    $prenom        = trim($_POST['prenom'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $dateNaissance = $_POST['dateNaissance'] ?? '';
    $sexe          = $_POST['sexe'] ?? '';
    $mdp           = $_POST['mdp'] ?? '';

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($dateNaissance) && !empty($sexe) && !empty($mdp)) {
        try {
            $dateObj = new DateTime($dateNaissance);
            $reussite = creerUser($nom, $prenom, $email, $mdp ,$dateObj, $sexe);

            if ($reussite === null) {
                header('Location: index.php');
                exit();
            } else {
                $msgErreur = $reussite;
            }
        } catch (Exception $e) {
            $msgErreur = "Le format de date de naissance est invalide";
        }
    } else {
        $msgErreur = "Veuillez remplir tous les champs";
    }
}
?>

<?php require_once('header.php'); ?>

<?php if ($msgErreur): ?>
    <p style="color: red; font-weight: bold;"><?= htmlspecialchars($msgErreur) ?></p>
<?php endif; ?>

    <h1>Page Inscription</h1>

    <form method="POST">
        <label>Nom :
            <input type="text" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required/>
        </label>

        <label> Prénom :
            <input type="text" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" required/>
        </label>

        <label> Adresse-mail :
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required/>
        </label>

        <label> Date de Naissance:
            <input type="date" name="dateNaissance" value="<?= htmlspecialchars($_POST['dateNaissance'] ?? '') ?>" required/>
        </label>

        <label> Sexe :
            <select id="sexe" name="sexe" required>
                <option value="homme" <?= (($_POST['sexe'] ?? '') === 'homme') ? 'selected' : '' ?>>Homme</option>
                <option value="femme" <?= (($_POST['sexe'] ?? '') === 'femme') ? 'selected' : '' ?>>Femme</option>
            </select>
        </label>

        <label> Mot de Passe :
            <input type="password" name="mdp" required/>
        </label>

        <button type="submit">S'INSCRIRE</button>
    </form>

<?php require_once('footer.php'); ?>