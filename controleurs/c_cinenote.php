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
        
    }