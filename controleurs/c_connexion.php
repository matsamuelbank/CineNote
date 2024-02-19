<?php
require_once('../models/class.pdogsb.php');

class UserAction {
    private $pdo;

    public function __construct() {
        $this->pdo = PdoGsb::getPdoGsb();
    }

    public function filterData($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function connexion($uLogin, $uMdp) {
        $infosUser = $this->pdo->rechercherUtilisateur($uLogin);
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

    public function inscription($uNom, $uPrenom, $uPseudo, $uLogin, $uMdp, $uCodePostal, $uAdresse) {
        $uMdp = password_hash($uMdp, PASSWORD_DEFAULT);

        $data =  $this->pdo->addUser($uNom, $uPrenom, $uPseudo, $uLogin, $uMdp, $uCodePostal, $uAdresse);
        if ($data) {
            header('Location: ../index.php');
        } else {
            $_SESSION['error'] = "Il y a eu une erreur lors de l'inscription. Veuillez réessayer.";
            header('Location: ../vues/inscription.php');
        }
    }
}

$action = new UserAction();

if ($_REQUEST['action'] == 'connexion') {
    if (isset($_POST['uLogin']) && isset($_POST['uMdp'])) {
        $uLogin = $action->filterData($_POST['uLogin']);
        $uMdp = $action->filterData($_POST['uMdp']);
        $action->connexion($uLogin, $uMdp);
    }
}

if ($_REQUEST['action'] == 'inscription') {
    if (
        isset($_POST['uNom']) && isset($_POST['uPrenom']) && isset($_POST['uPseudo']) && isset($_POST['uLogin'])
        && isset($_POST['uMdp']) && isset($_POST['uCodePostal']) && isset($_POST['uAdresse'])
    ) {
        $uNom = $action->filterData($_POST['uNom']);
        $uPrenom = $action->filterData($_POST['uPrenom']);
        $uPseudo = $action->filterData($_POST['uPseudo']);
        $uLogin = $action->filterData($_POST['uLogin']);
        $uMdp = $action->filterData($_POST['uMdp']);
        $uCodePostal = $action->filterData($_POST['uCodePostal']);
        $uAdresse = $action->filterData($_POST['uAdresse']);

        $action->inscription($uNom, $uPrenom, $uPseudo, $uLogin, $uMdp, $uCodePostal, $uAdresse);
    }
}
