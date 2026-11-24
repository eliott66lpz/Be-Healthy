<?php
    if ( session_status() === PHP_SESSION_NONE ) session_start();

    if ( isset($_POST['logout']) ) {
        logoutUser();
        header("Location: /Be-Healthy/index.php");
        exit;
    }

    function isConnected(): bool {
        return isset($_SESSION['idUser']);
    }

    function isAdmin(): bool {
        return isConnected() && $_SESSION["isAdmin"] === true;
    }

    function requireLogin(): void {
        if( !isConnected() ){
            header("Location: /Be-Healthy/index.php");
            exit;
        }
    }

    function requireAdmin(): void {
        if( !isAdmin() ) {
            header("Location: /Be-Healthy/index.php");
            exit;
        }
    }

    function logoutUser(): void {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

?>