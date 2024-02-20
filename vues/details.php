<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <link rel="stylesheet" href="../css/accueil.css">
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

    <script>
        $(document).ready(function() {

            let idFilm = $('#idFilm').val();
            let btnPublier = $("#btnPublier");
            let divMereCommentaire = $("#divMereCommentaire");
            let idUser = $("#idUser").val();
            let uPseudo = $("#uPseudo").val();
            let nbNote = $("#nbNote").val();
            let moyenneNote = $("#moyenneNote").val();
            const options = {
                method: 'GET',
                headers: {
                    accept: 'application/json',
                    Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI0NzBlNTJmY2M3YWIxYzlmNTQwYWVjYWZhMmY4MmE0OCIsInN1YiI6IjY1Y2U3OTk1NDU3NjVkMDE3Y2RiMzU2ZSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.GYEVwIHcb20wAq8mikJ8RTtABgcdGryCzTXmmnj59yw'
                }
            };

            function updateAverageRating() {
                let averageRating = $("#averageRating").data("rating");
                $("#averageRating").rateYo({
                    fullStar: false,
                    starWidth: "30px",
                    spacing: "5px",
                    rating: averageRating,
                    readOnly: true
                });
            }

            updateAverageRating();
            // requette HTTP
            $.ajax({
                url: `https://api.themoviedb.org/3/movie/${idFilm}?api_key=470e52fcc7ab1c9f540aecafa2f82a48`,
                type: 'GET',
                headers: options.headers,
                success: function(data) { // Cette fonction est appelée lorsque la requête réussit
                    const movie = data;
                    const container = $('.row.gx-3'); // On sélectionne l'élément

                    const imageUrl = movie.poster_path ? `https://image.tmdb.org/t/p/w500${movie.poster_path}` : '../images/nature.jpeg';
                    const card = `
                    <div class="col-3 d-flex align-items-center justify-content-center  w-30 mx-auto">
                        <div class="card" style="width: 100%;">
                            <img src="${imageUrl}" class="card-img-top" alt="${movie.title} poster">
                            <div class="card-body">
                                <h5 class="card-title">Nom du film: ${movie.title}</h5>
                                <p class="card-text">Synthèse: ${movie.overview}</p>
                                <p>Date de sortie: ${movie.release_date}</p>
                            </div>
                        </div>
                    </div>
                    `;
                    container.append(card); // On ajoute la carte à l'élément

                },
                error: function(err) { // Cette fonction est appelée lorsque la requête échoue
                    console.error(err);
                }
            });

            $("#rateYo").rateYo({
                fullStar: false, // cette option permet de selectioner les nombres à virgules exp: 4.5
                starWidth: "30px", // taille des étoiles 
                spacing: "5px",
                onSet: function(rating, rateYoInstance) { //Cette fonction est appelée chaque fois qu’une note est sélectionnée
                    $('#rating').text(rating);
                }
            });

            $(".rate").each(function() {
                let rating = $(this).data("rating"); // on recupere la valeur de l'attribut data-rating
                $(this).rateYo({
                    fullStar: false,
                    starWidth: "30px",
                    spacing: "5px",
                    rating: rating, // cette valeur est mise ici afin de générer des étoiles correspondant a la valeur de la note 
                    readOnly: true
                });
            });

            $('#btnPublier').click(function() {
                let valueCommentaire = $('#textCommentaire').val();
                let valueNote = $("#rateYo").rateYo("rating");
                // On vérifie si le commentaire est vide
                if (!valueCommentaire.trim()) {
                    alert('Le commentaire ne peut pas être vide');
                    return;
                }

                let formData = new FormData();
                formData.append('valueCommentaire', valueCommentaire);
                formData.append('idUser', idUser);
                formData.append('idFilm', idFilm);
                formData.append('valueNote', valueNote);

                fetch('c_cinenote.php?action=ajoutCommentaire', {
                        method: 'POST',
                        body: formData
                    })
                    .then(() => {
                        let newComment = '<div class="row justify-content-center"><div class="col-6"><div class="card mt-2"><div class="card-body"><p class="card-text">' + valueCommentaire + '</p><footer class="blockquote-footer">' + uPseudo + '</footer><div class="rate" data-rating="' + valueNote + '"></div></div></div></div></div>';
                        $('#divMereCommentaire').append(newComment);
                        $('#textCommentaire').val('');
                        $(".rate").last().rateYo({ // selectionne le dernier elements ayant la classe .rate et met la valeur de l'étoile 
                            fullStar: false,
                            starWidth: "30px",
                            spacing: "5px",
                            rating: valueNote,
                            readOnly: true
                        });
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            });

        });
    </script>
</body>

</html>