<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/accueil.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script src="../javaScript/details.js"></script>
    <title>Détails du film</title>
</head>


<body>

    <div class="container my-5 py-5">
        <div class="row gx-3">
            <!-- La carte de film sera ajoutée ici par le JavaScript -->
        </div>

        <div id="divMereCommentaire" class="container mt-5">
            <?php
            $totalRating = 0;
            $totalReviews = 0;
            foreach ($lesCommentaires as $commentaire) {
                $rating = $commentaire['note'];
                $totalRating += $rating;
                $totalReviews++;
            }
            if ($totalReviews > 0) {
                $averageRating = $totalRating / $totalReviews;
            } else {
                $averageRating = 0; // ou toute autre valeur par défaut
            }
            ?>

            <div class="row justify-content-center">
                <div class="col-6">
                    <span>Note globale : </span>
                    <div id="averageRating" data-rating="<?php echo $averageRating; ?>" style="display: inline-block;"></div>
                    <span><?php echo "(".round($averageRating,2).")"; ?></span> <br> <br>

                    <label for="textCommentaire" class="form-label">Donner votre avis</label>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <textarea name="textCommentaire" id="textCommentaire" class="form-control" rows="3"></textarea>
                        <button id="btnPublier" class="btn btn-success">Publier</button>
                    </div>
                    <div id="rateYo"></div>
                    <p>Notez-le: <span id="rating">0</span></p>
                </div>
            </div>
            <?php

            foreach ($lesCommentaires as $commentaire) {
                $userRating = $commentaire['note']; // Assurez-vous que 'rating' est le bon nom de champ
                echo '<div class="row justify-content-center"><div class="col-6"><div class="card mt-2"><div class="card-body"><p class="card-text">' . htmlspecialchars($commentaire['coText']) . '
                </p><footer class="blockquote-footer">' . htmlspecialchars($commentaire['uPseudo']) . '</footer><div class="rate" data-rating="' . $userRating . '"></div></div></div></div></div>';
            }

            ?>

            <input id="idFilm" type="hidden" value=<?php echo $idFilm; ?>>
            <input id="idUser" type="hidden" value=<?php echo $user['uId']; ?>>
            <input id="uPseudo" type="hidden" value=<?php echo $user['uPseudo']; ?>>

        </div>

    </div>
</body>
</html>