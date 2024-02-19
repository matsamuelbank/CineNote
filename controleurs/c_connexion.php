<?php
require_once('../models/class.pdogsb.php');
$pdo = PdoGsb::getPdoGsb();

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'connexion';

function filterData($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


if ($action == 'connexion') {
    if (isset($_POST['uLogin']) && isset($_POST['uMdp'])) {
        $uLogin = filterData($_POST['uLogin']);
        $uMdp = filterData($_POST['uMdp']);

        $infosUser = $pdo->rechercherUtilisateur($uLogin);
        if ($infosUser !== false) {
            $hashed_password = $infosUser['uMdp'];

            if (password_verify($uMdp, $hashed_password)) {
                session_start();
                $_SESSION['user'] = $infosUser;
                header('Location: c_cinenote.php?action=accueil');
            } else {
                // Le mot de passe est incorrect
                $_SESSION['error'] = "Mot de passe incorrect. Veuillez réessayer.";
                header('Location: ../index.php');
            }
        } else {
            // L'utilisateur n'a pas été trouvé
            $_SESSION['error'] = "Utilisateur non trouvé. Veuillez réessayer.";
            header('Location: ../index.php');
        }
    }
}

if ($action == 'inscription') {

    if (
        isset($_POST['uNom']) && isset($_POST['uPrenom']) && isset($_POST['uPseudo']) && isset($_POST['uLogin'])
        && isset($_POST['uMdp']) && isset($_POST['uCodePostal']) && isset($_POST['uAdresse'])
    ) {

        $uNom = filterData($_POST['uNom']);
        $uPrenom = filterData($_POST['uPrenom']);
        $uPseudo = filterData($_POST['uPseudo']);
        $uLogin = filterData($_POST['uLogin']);
        $uMdp = filterData($_POST['uMdp']);
        $uMdp = password_hash($uMdp, PASSWORD_DEFAULT);
        $uCodePostal = filterData($_POST['uCodePostal']);
        $uAdresse = filterData($_POST['uAdresse']);

        $data =  $pdo->addUser($uNom, $uPrenom, $uPseudo, $uLogin, $uMdp, $uCodePostal, $uAdresse);
        if ($data) {
            header('Location: ../index.php');
        } else {
            $_SESSION['error'] = "Il y a eu une erreur lors de l'inscription. Veuillez réessayer.";
            header('Location: ../vues/inscription.php');
        }
    }
    exit;
}
