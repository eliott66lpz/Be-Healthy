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
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if (!$user) {
        return "Aucun compte associé à cette adresse e-mail.";
    }

    if(empty($user['mdp'])){
        return "Ce compte est associé à un compte Google, veuillez utiliser le bouton associé";
    }

    if (!password_verify($mdp, $user['mdp'])) {
        return "Mot de passe incorrect.";
    }

    $_SESSION['user'] = [
        'id'            => (int)$user['id'],
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

function creerUser(string $nom, string $prenom, string $email, string $mdp, DateTime $dateNaissance, string $sexe): ?string{

    $connexion = Connexion();

    $stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
    $stmt->closeCursor();

    if($user){
        return "Cette adresse-mail est déja utilisée";
    }

    $hashMdp    = password_hash($mdp, PASSWORD_DEFAULT);

    $requete = $connexion->prepare(
        "INSERT INTO utilisateur (nom, prenom ,email, mdp, dateNaissance, sexe) 
                    VALUES (:nom, :prenom, :email, :mdp, :dateNaissance, :sexe)"
    );
    $reussite = $requete->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email'  => $email,
        'mdp'    => $hashMdp,
        'dateNaissance' => $dateNaissance->format('Y-m-d'),
        'sexe'  => $sexe
    ]);

    if(!$reussite){
        return "Problème lors de la création du compte";
    }

    $_SESSION['user'] = [
        'id'     => (int)$connexion->lastInsertId(),
        'nom' => $nom,
        'prenom' => $prenom,
        'email'  => $email,
        'mdp'    => $hashMdp,
        'dateNaissance' => $dateNaissance->format('d/m/Y'),
        'sexe'  => $sexe
    ];

    return null;
}

function creerToken(string $email): ?string{
    $connexion = Connexion();

    $stmt = $connexion->prepare("SELECT id_user FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = null;
    $user->fetch();

    if(!$user){
        return null;
    }

    $delete = $connexion->prepare("DELETE FROM motdepasseoublie WHERE id_user := id_user");
    $delete->execute(['user_id' => $user['id']]);

    $token = bin2hex(random_bytes(64));
    $dateExpiration = (new DateTime())->modify('+15 minutes')->format('Y-m-d H:i:s');

    $insert = $connexion->prepare(
        "INSERT INTO motDePasseOublie (id_user, token ,dateExpiration) 
                    VALUES (:user_id, :token, :dateExpiration)"
    );

    $reussite = $insert->execute([
        'user_id' => $user[id],
        'token' => $token,
        'dateExpiration' => $dateExpiration

    ]);
    return $token;
}

function verifierTokenMdp(string $token): ?int
{
    $connexion = Connexion();

    $tokenHash = hash('sha256', $token);

    $stmt = $connexion->prepare(
        "SELECT id_user FROM motdepasseoublie WHERE token = :token AND dateExpiration > NOW()"
    );
    $stmt->execute(['token' => $tokenHash]);
    $reset = $stmt->fetch();

    return $reset ? (int)$reset['user_id'] : null;
}

function reinitisialiserMdp(string $userId, string $mdp) : bool{
    $connexion = Connexion();
    $hashMdp = password_hash($mdp, PASSWORD_DEFAULT);

    $stmt = $connexion->prepare("UPDATE utilisateur SET mdp = :mdp WHERE id_ligue =:id");
    $succes = $stmt->execute([
        'mdp' => $hashMdp,
        'id' => $userId
    ]);

    if ($succes) {
        $del = $connexion->prepare("DELETE FROM motdepasseoublie WHERE id_user = :user_id");
        $del->execute(['user_id' => $userId]);
    }

    return $succes;

}

function connecterOuCreerUserGoogle(string $googleId, string $email, string $prenom, string $nom): void
{
    $connexion = Connexion();

    // Vérifier si l'utilisateur existe déjà via son compte Google ou son email
    $stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE google_id = :gid OR email = :email");
    $stmt->execute(['gid' => $googleId, 'email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // Si l'utilisateur existait déjà par formulaire classique, on associe son compte Google
        if (empty($user['google_id'])) {
            $update = $connexion->prepare("UPDATE utilisateur SET google_id = :gid WHERE id_user = :id");
            $update->execute(['gid' => $googleId, 'id' => $user['id']]);
        }
        $userId = (int)$user['id'];
        $nom    = $user['nom'];
        $prenom = $user['prenom'];
    } else {
        // Premier enregistrement de l'utilisateur Google
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

    // Initialisation de la session
    $_SESSION['user'] = [
        'id'     => $userId,
        'nom'    => $nom,
        'prenom' => $prenom,
        'email'  => $email
    ];
}


