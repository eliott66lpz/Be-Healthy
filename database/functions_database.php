<?php

function Connexion() : PDO {
    try {
        $connexion = new PDO('mysql:host=localhost;dbname=be_healthy;charset=utf8', 'root', '');
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $connexion;
    } catch (PDOException $e) {
        die('Erreur PDO : ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur Générale : ' . $e->getMessage());
    }
}

function authentifierUser(string $email, string $mdp): ?string {
    $connexion = Connexion();

    $stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
    $stmt->closeCursor();

    if (!$user) {
        return "Aucun compte associé à cette adresse e-mail.";
    }

    if (empty($user['mdp'])) {
        return "Ce compte est associé à un compte Google, veuillez utiliser le bouton associé.";
    }

    if (!password_verify($mdp, $user['mdp'])) {
        return "Mot de passe incorrect.";
    }

    $_SESSION['user'] = [
        'id'            => (int)$user['id_user'], // id_user pour correspondre à votre BDD
        'nom'           => $user['nom'],
        'prenom'        => $user['prenom'],
        'email'         => $user['email'],
        'dateNaissance' => $user['dateNaissance'],
        'sexe'          => $user['sexe'],
        'taille'        => $user['taille'],
        'poids'         => $user['poids'],
        'pointXP'       => (int)$user['pointXP']
    ];

    header('Location: filesPHP/index.php');
    exit();
}

function creerUser(string $nom, string $prenom, string $email, string $mdp, ?DateTime $dateNaissance, string $sexe): ?string {
    $connexion = Connexion();

    $stmt = $connexion->prepare("SELECT id_user FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        return "Cette adresse email est déjà utilisée.";
    }

    $hashMdp = password_hash($mdp, PASSWORD_DEFAULT);
    $dateSql = $dateNaissance ? $dateNaissance->format('Y-m-d') : null;

    $requete = $connexion->prepare(
        "INSERT INTO utilisateur (nom, prenom, email, mdp, dateNaissance, sexe) 
         VALUES (:nom, :prenom, :email, :mdp, :dateNaissance, :sexe)"
    );

    $reussite = $requete->execute([
        'nom'           => $nom,
        'prenom'        => $prenom,
        'email'         => $email,
        'mdp'           => $hashMdp,
        'dateNaissance' => $dateSql,
        'sexe'          => $sexe
    ]);

    if (!$reussite) {
        return "Problème lors de la création du compte.";
    }

    $_SESSION['user'] = [
        'id'            => (int)$connexion->lastInsertId(),
        'nom'           => $nom,
        'prenom'        => $prenom,
        'email'         => $email,
        'dateNaissance' => $dateNaissance ? $dateNaissance->format('d/m/Y') : null,
        'sexe'          => $sexe
    ];

    return null;
}

function connecterOuCreerUserGoogle(string $googleId, string $email, string $prenom, string $nom): void
{
    $connexion = Connexion();

    $stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE google_id = :gid OR email = :email");
    $stmt->execute(['gid' => $googleId, 'email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        if (empty($user['google_id'])) {
            $update = $connexion->prepare("UPDATE utilisateur SET google_id = :gid WHERE id_user = :id");
            $update->execute(['gid' => $googleId, 'id' => $user['id_user']]);
        }
        $userId = (int)$user['id_user'];
        $nom    = $user['nom'];
        $prenom = $user['prenom'];
    } else {
        $sql = "INSERT INTO utilisateur (nom, prenom, email, google_id, mdp, dateNaissance, sexe) 
                VALUES (:nom, :prenom, :email, :gid, NULL, NULL, 'non-precise')";
        $insert = $connexion->prepare($sql);
        $insert->execute([
            'nom'    => $nom,
            'prenom' => $prenom,
            'email'  => $email,
            'gid'    => $googleId
        ]);
        $userId = (int)$connexion->lastInsertId();
    }

    $_SESSION['user'] = [
        'id'     => $userId,
        'nom'    => $nom,
        'prenom' => $prenom,
        'email'  => $email
    ];
}

function suppUtilisateur(int $userId): bool {
    $connexion = Connexion();

    $stmt = $connexion->prepare("DELETE FROM utilisateur WHERE id_user = :userId");
    $succes = $stmt->execute(['userId' => $userId]);

    if ($succes) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
    }

    return $succes;
}

function modifierMdp(int $userId, string $mdp, string $nvMdp): ?string {
    $connexion = Connexion();

    $stmt = $connexion->prepare("SELECT mdp FROM utilisateur WHERE id_user = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch();

    if (!$user || empty($user['mdp'])) {
        return "Ce compte est géré par Google et ne possède pas de mot de passe.";
    }

    if (!password_verify($mdp, $user['mdp'])) {
        return "Le mot de passe actuel est incorrect.";
    }

    $nvMdpHash = password_hash($nvMdp, PASSWORD_DEFAULT);
    $update = $connexion->prepare("UPDATE utilisateur SET mdp = :mdp WHERE id_user = :id");
    $update->execute(['mdp' => $nvMdpHash, 'id' => $userId]);

    return null; // Succès (aucune erreur)
}

function modifierEmail(int $userId, string $nvEmail): ?string {
    $connexion = Connexion();

    $check = $connexion->prepare("SELECT id_user FROM utilisateur WHERE email = :email AND id_user != :id");
    $check->execute(['email' => $nvEmail, 'id' => $userId]);
    if ($check->fetch()) {
        return "Cette adresse email est déjà utilisée par un autre compte.";
    }

    $update = $connexion->prepare("UPDATE utilisateur SET email = :email WHERE id_user = :id");
    $update->execute(['email' => $nvEmail, 'id' => $userId]);

    return null; // Succès (aucune erreur)
}