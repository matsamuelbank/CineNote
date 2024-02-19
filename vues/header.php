<?php
session_start();

    $user = $_SESSION['user'];
    if(!isset($_SESSION['user'])) {
        header("Location: ../index.php");
    exit;
}
?>
<header class="fixed-top">
        <div class="topnav ">
            <a class="active" href="../controleurs/c_cinenote.php?action=accueil">CineNote</a>
            <a href="#about">A propos</a>
            <a href="#contact">Contact</a>
            <a href="../controleurs/c_deconnexion.php">Déconnexion</a>

            <div class="search-container">
                <?php echo "Bonjour, " . $user['uPseudo']; ?>
                <input style="margin-left: 10px;"  type="text" id="inputRecherche" placeholder="recherche.." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
</header>