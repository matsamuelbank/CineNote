<?php

    require_once('../models/class.pdogsb.php');
    $pdo = PdoGsb::getPdoGsb();
    
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'connexion';

    if($action =='accueil')
    {
        include('../vues/accueil.php');
    }

    if($action == 'details')
    {
        $idFilm = 0;

        if(isset($_GET["idFilm"]))
        {
            $idFilm = $_GET["idFilm"];
            include('../vues/header.php');

            $lesCommentaires = $pdo->getCommentaires($idFilm);
            include('../vues/details.php');
            exit;
        }
    }

    if($action == 'ajoutCommentaire') {
        if(isset($_POST['valueCommentaire'], $_POST['idUser'], $_POST['idFilm'])) {
            $valueCommentaire = $_POST['valueCommentaire'];
            $coIdUtilisateur = $_POST['idUser'];
            $coIdFilm = $_POST['idFilm'];
            $coDateDePublication = date('Y-m-d H:i:s');
            
            $pdo->addCommentaire($valueCommentaire, $coDateDePublication, $coIdFilm,$coIdUtilisateur);
        } 
    }
    
    