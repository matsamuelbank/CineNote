<?php session_start(); ?>
<!doctype html>
<html lang="fr">

<head>
    <title>Inscription</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="javaScript/popper.js"></script>
    <script src="javaScript/jquery.min.js"></script>
    <script src="javaScript/bootstrap.min.js"></script>
    <script src="javaScript/main.js"></script>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="icon" href="../images/cineNote_logo.jpeg">

</head>

<body>
    <section class="ftco-section">
        <div class="container">

            <div class="row justify-content-center">

                <div class="col-md-6 col-lg-5">

                    <div class="login-wrap p-4 p-md-5">

                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="fa fa-user-o"></span>
                        </div>

                        <h3 class="text-center mb-4">Entrez vos informations </h3>

                        <form action="../controleurs/c_connexion?action=inscription" class="login-form" method="POST">

                            <div class="form-group d-flex">
                                <input name="uNom" type="text" class="form-control rounded-left" placeholder="votre nom" required>
                            </div>

                            <div class="form-group d-flex">
                                <input name="uPrenom" type="text" class="form-control rounded-left" placeholder="votre prénom" required>
                            </div>

                            <div class="form-group d-flex">
                                <input name="uPseudo" type="text" class="form-control rounded-left" placeholder="votre pseudonyme" required>
                            </div>

                            <div class="form-group">
                                <input name="uLogin" type="text" class="form-control rounded-left" placeholder="login..." required>
                            </div>

                            <div class="form-group d-flex">
                                <input name="uMdp" type="password" class="form-control rounded-left" placeholder="mot de passe..." required>
                            </div>

                            <div class="form-group d-flex">
                                <input name="uCodePostal" type="numer" class="form-control rounded-left" placeholder="code postal" required>
                            </div>

                            <div class="form-group d-flex">
                                <input name="uAdresse" type="text" class="form-control rounded-left" placeholder="adresse" required>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary rounded submit p-3 px-5">Inscription</button>
                                <p style="color: red">
                                    <?php
                                    if (isset($_SESSION['error'])) {
                                        echo $_SESSION['error'];
                                        unset($_SESSION['error']);
                                    }
                                    ?>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>