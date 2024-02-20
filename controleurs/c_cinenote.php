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

    public function ajoutCommentaire($valueCommentaire, $coIdUtilisateur, $coIdFilm,$note)
    {
        $coDateDePublication = date('Y-m-d');
        $this->pdo->addCommentaire($valueCommentaire, $coDateDePublication, $coIdFilm, $coIdUtilisateur);
        $this->pdo->addNote($coIdFilm, $coIdUtilisateur,$note,$coDateDePublication);
    }

    public function ajoutNote($nIdFilm, $nIdUtilisateur,$note)
    {
        $dateNotation = date('Y-m-d');
        $this->pdo->addNote($nIdFilm, $nIdUtilisateur,$note,$dateNotation);
    }
}

$action = new FilmAction();

try {
    if ($_REQUEST['action'] == 'accueil') {
        $action->accueil();
    }

    if ($_REQUEST['action'] == 'details') {
        if (isset($_GET["idFilm"])) {
            $idFilm = $_GET["idFilm"];
            $action->details($idFilm);
        } else {
            throw new Exception("ID du film non fourni");
        }
    }

    if ($_REQUEST['action'] == 'ajoutCommentaire') {
        
        if (isset($_POST['valueCommentaire'], $_POST['idUser'], $_POST['idFilm'], $_POST['valueNote'])) {

            $valueCommentaire = $action->filterData($_POST['valueCommentaire']);
            $coIdUtilisateur = $action->filterData($_POST['idUser']);
            $coIdFilm = $action->filterData($_POST['idFilm']);
            $note = $action->filterData($_POST['valueNote']);
            $action->ajoutCommentaire($valueCommentaire, $coIdUtilisateur, $coIdFilm,$note);
        } else {
            throw new Exception("Données de commentaire non fournies");
        }
    }
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>
