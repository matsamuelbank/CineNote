<?php
require_once('../models/class.pdogsb.php');

class FilmAction
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = PdoGsb::getPdoGsb();
    }

    public function filterData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function accueil()
    {
        include('../vues/accueil.php');
    }

    public function details($idFilm)
    {
        include('../vues/header.php');
        $lesCommentaires = $this->pdo->getCommentaires($idFilm);
        include('../vues/details.php');
    }

    public function ajoutCommentaire($valueCommentaire, $coIdUtilisateur, $coIdFilm)
    {
        $coDateDePublication = date('Y-m-d H:i:s');
        $this->pdo->addCommentaire($valueCommentaire, $coDateDePublication, $coIdFilm, $coIdUtilisateur);
    }
}

$action = new FilmAction();

if ($_REQUEST['action'] == 'accueil') {
    $action->accueil();
}

if ($_REQUEST['action'] == 'details') {
    if (isset($_GET["idFilm"])) {
        $idFilm = $_GET["idFilm"];
        $action->details($idFilm);
    }
}

if ($_REQUEST['action'] == 'ajoutCommentaire') {
    if (isset($_POST['valueCommentaire'], $_POST['idUser'], $_POST['idFilm'])) {
        $valueCommentaire = $action->filterData($_POST['valueCommentaire']);
        $coIdUtilisateur = $action->filterData($_POST['idUser']);
        $coIdFilm = $action->filterData($_POST['idFilm']);
        $action->ajoutCommentaire($valueCommentaire, $coIdUtilisateur, $coIdFilm);
    }
}
