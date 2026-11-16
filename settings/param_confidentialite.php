<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once ('../database/functions_database.php');

$erreur = null;
$succes =null;


$userId = $_SESSION['user']['id'] ?? null;

if (!$userId) {
    header('Location: ../login.php');
    exit();
}

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        switch ($action) {
            case 'modifier_email':
                $email = trim($_POST['nv_email'] ?? '');

                if(!empty($email)){
                    $erreur = modifierEmail($userId, $email);
                    if(!$erreur){
                        $_SESSION['user']['email'] = $email;
                        $succes = "Adresse email mise à jour avec succès";
                    }
                }else{
                    $erreur = "Veuillez remplir tous les champs";
                }

                break;

            case 'modifier_mdp':
                $mdp = $_POST['mdp'] ?? '';
                $nvMdp = $_POST['nv_mdp'] ?? '';

                if(!empty($mdp) && !empty($nvMdp)){
                    $erreur = modifierMdp($userId, $mdp, $nvMdp);
                    if(!$erreur){
                        $succes = "Mot de passe mis à jour avec succès";
                    }
                }else{
                    $erreur = "Veuillez remplir tous les champs";
                }

                break;

            case 'supp_compte':
                suppUtilisateur($userId);
                header('Location: ../login.php');
                exit();
        }
    }


?>

<?php require_once ('header.php'); ?>

<?php if (!empty($erreur)): ?>
    <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<?php if (!empty($succes)): ?>
    <p style="color: green;"><?= htmlspecialchars($succes) ?></p>
<?php endif; ?>

<button type="button">Modifier mon adresse mail</button>
<form method="POST">
    <div>
        <input type="hidden" name="action" value="modifier_email">
        <label for="nv_email">Saisir la nouvelle adresse mail</label>
        <input type="text" name="nv_email" value="">
        <button type="submit">Valider</button>
    </div>
</form>

<button type="button">Modifier le mot de passe</button>
<form method="POST">
    <div>
        <input type="hidden" name="action" value="modifier_mdp"  >
        <label for="mdp">Mot de passe actuel : </label>
        <input type="password" name="mdp">
    </div>
    <div>
        <label for="nv_mdp">Nouveau Mot de passe : </label>
        <input type="password" name="nv_mdp">
    </div>
    <button type="submit">Valider</button>
</form>

<form method="POST">
    <div>
        <input type="hidden" name="action" value="supp_compte" >
        <button type="submit">Supprimer le compte</button>
    </div>
</form>


<?php require_once ('footer.php'); ?>
