<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('database/functions_database.php');

$jwt = $_POST['credential'] ?? null;

if (!$jwt) {
    die("Aucune donnée reçue de Google.");
}

// Un JWT est composé de 3 parties séparées par des points : Header.Payload.Signature
$parties = explode('.', $jwt);

if (count($parties) === 3) {
    // La 2ème partie contient les informations de l'utilisateur en clair (Base64)
    $payloadJson = base64_decode(strtr($parties[1], '-_', '+/'));
    $googleUser  = json_decode($payloadJson, true);

    $googleId = $googleUser['sub']        ?? null;
    $email    = $googleUser['email']      ?? null;
    $prenom   = $googleUser['given_name'] ?? 'Utilisateur';
    $nom      = $googleUser['family_name'] ?? '';

    if ($googleId && $email) {
        connecterOuCreerUserGoogle($googleId, $email, $prenom, $nom);
        header('Location: filesPHP/index.php');
        exit();
    }
}

die("Erreur lors de la lecture des données Google.");