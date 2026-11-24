<?php
/* Fichier où seront placées les fonctions relatives aux requêtes en base de données */
function getConnexion(): PDO {
    static $connexion = null;
    if ( $connexion === null) {
        try {
            $connexion = new PDO('mysql:host=localhost;dbname=Be-Healthy', 'root', '',
                [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        } catch (Exception $e) {
            die("Erreur connexion à la base de données" . $e->getMessage());
        }
    }
    return $connexion;
}

/* Fonctions liées aux Calories */
function getMealsFromType(string $typeOfMeal): array {
    $query = "SELECT r.id_repas, r.nomRepas, 
       ri.quantite_g,
       i.nomIngredient, i.calories_100g, i.proteines_100g, i.lipides_100g, i.glucides_100g
                FROM repas r
                JOIN repas_ingredient ri ON ri.id_repas = r.id_repas 
                JOIN ingredient i ON i.id_ingredient = ri.id_ingredient 
                WHERE r.type_repas=:type
                ORDER BY r.id_repas, i.nomIngredient;
                    ";

    $stmt = getConnexion()->prepare($query);
    $stmt->bindValue(':type', $typeOfMeal);
    $stmt->execute();

    return $stmt->fetchAll();
}