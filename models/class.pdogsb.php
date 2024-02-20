<?php

/** Classe d'acces aux donnees. 
 */

class PdoGsb
{
    private static $serveur = 'mysql:host=localhost';
    private static $bdd = 'dbname=cinenote';
    private static $user = 'root';
    private static $mdp = 'root';
    private static $monPdo;
    private static $monPdoGsb = null;

    private function __construct()
    {
        PdoGsb::$monPdo = new PDO(PdoGsb::$serveur . ';' . PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
        //PdoGsb::$monPdo->query("SET CHARACTER SET utf8");SET client_encoding = 'UTF8'; 
    }

    public function _destruct()
    {
        PdoGsb::$monPdo = null;
    }

    public  static function getPdoGsb()
    {
        if (PdoGsb::$monPdoGsb == null) {
            PdoGsb::$monPdoGsb = new PdoGsb();
        }
        return PdoGsb::$monPdoGsb;
    }


    public function rechercherUtilisateur($login)
    {
        $req = "SELECT * FROM utilisateur WHERE uLogin = :login;";

        $stmt = PdoGsb::$monPdo->prepare($req);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);

        if (!$stmt->execute()) {
            afficherErreurSQL("Problème lors de la recherche de l'utilisateur.", $req, PdoGsb::$monPdo->errorInfo());
        }
        $infoUser = $stmt->fetch();
        return $infoUser;
    }


    public function addUser($uNom, $uPrenom, $uPseudo, $uLogin, $uMdp, $uCodePostal, $uAdresse)
    {
        $req = 'INSERT INTO utilisateur (uNom, uPrenom,uPseudo,uLogin, uMdp,uCodePostal,uAdresse) 
        VALUE(:uNom,:uPrenom,:uPseudo,:uLogin,:uMdp,:uCodePostal,:uAdresse)';

        $stmt = PdoGsb::$monPdo->prepare($req);
        $stmt->bindParam(':uNom', $uNom);
        $stmt->bindParam(':uPrenom', $uPrenom);
        $stmt->bindParam(':uPseudo', $uPseudo);
        $stmt->bindParam(':uLogin', $uLogin);
        $stmt->bindParam(':uMdp', $uMdp);
        $stmt->bindParam(':uCodePostal', $uCodePostal);
        $stmt->bindParam(':uAdresse', $uAdresse);

        if (!$stmt->execute()) {
            afficherErreurSQL("Problème lors de l'insertion de l'utilisateur.", $req, PdoGsb::$monPdo->errorInfo());
        } else {
            return true;
        }
    }

    public function getCommentaires($idFilm)
    {
        $req = "SELECT commentaire.coText, utilisateur.uPseudo, notation.note, commentaire.coIdFilm
        FROM commentaire 
        INNER JOIN utilisateur ON utilisateur.uId = commentaire.coIdUtilisateur
        INNER JOIN notation ON utilisateur.uId = notation.nIdUtilisateur AND commentaire.coIdFilm = notation.nIdFilm
        WHERE commentaire.coIdFilm =:idFilm";

        $stmt = PdoGsb::$monPdo->prepare($req);
        $stmt->bindParam(':idFilm', $idFilm);

        if (!$stmt->execute()) {
            afficherErreurSQL("Problème lors de la récupération des commentaires.", $req, PdoGsb::$monPdo->errorInfo());
        }

        $lesCommentaires = $stmt->fetchAll();
        return $lesCommentaires;
    }


    public function addCommentaire($valueCommentaire, $coDateDePublication, $coIdFilm, $coIdUtilisateur)
    {
        $req = 'INSERT INTO commentaire (coText, coDateDePublication,coIdFilm,coIdUtilisateur) 
        VALUE(:coText, :coDateDePublication,:coIdFilm,:coIdUtilisateur)';

        $stmt = PdoGsb::$monPdo->prepare($req);
        $stmt->bindParam(':coText', $valueCommentaire);
        $stmt->bindParam(':coDateDePublication', $coDateDePublication);
        $stmt->bindParam(':coIdFilm', $coIdFilm);
        $stmt->bindParam(':coIdUtilisateur', $coIdUtilisateur);

        if (!$stmt->execute()) {
            afficherErreurSQL("Problème lors de l'insertion du commentaire.", $req, PdoGsb::$monPdo->errorInfo());
        } else {
            return true;
        }
    }

    public function addNote($nIdFilm, $nIdUtilisateur, $note, $dateNotation)
    {
        $req = 'INSERT INTO notation (nIdFilm, nIdUtilisateur,note,dateNotation)
        VALUES (:nIdFilm,:nIdUtilisateur,:note,:dateNotation )';

        $stmt = PdoGsb::$monPdo->prepare($req);
        $stmt->bindParam(':nIdFilm', $nIdFilm);
        $stmt->bindParam(':nIdUtilisateur', $nIdUtilisateur);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':dateNotation', $dateNotation);

        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            afficherErreurSQL("Problème lors de l'insertion de la note. Erreur : ", $req, $errorInfo);
        } else {
            return true;
        }
    }
}
