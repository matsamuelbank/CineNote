<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/accueil.css">
    <title>Détails du film</title>

</head>

<body>

    <input id="idFilm" type="hidden" value=<?php echo $idFilm; ?>>
    <input id="idUser" type="hidden" value=<?php echo $user['uId']; ?>>
    <input id="uPseudo" type="hidden" value=<?php echo $user['uPseudo']; ?>>

    <div class="container my-5 py-5">
        <div class="row gx-3">
            <!-- La carte de film sera ajoutée ici par le JavaScript -->
        </div>

        <div id="divMereCommentaire" class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-6">
                    <label for="textCommentaire" class="form-label">Donner votre avis</label>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <textarea name="textCommentaire" id="textCommentaire" class="form-control" rows="3"></textarea>
                        <button id="btnPublier" class="btn btn-success">Publier</button>
                    </div>
                </div>
            </div>
            <?php
            foreach ($lesCommentaires as $commentaire) {
                echo '<div class="row justify-content-center"><div class="col-6"><div class="card mt-2"><div class="card-body"><p class="card-text">' . htmlspecialchars($commentaire['coText']) . '</p><footer class="blockquote-footer">' . htmlspecialchars($commentaire['uPseudo']) . '</footer></div></div></div></div>';
            }
            ?>
        </div>

    </div>

    <script>
        $(document).ready(function() {

            let idFilm = $('#idFilm').val();
            let btnPublier = $("#btnPublier");
            let divMereCommentaire = $("#divMereCommentaire");
            let idUser = $("#idUser").val();
            let uPseudo = $("#uPseudo").val();
            const options = {
                method: 'GET',
                headers: {
                    accept: 'application/json',
                    Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI0NzBlNTJmY2M3YWIxYzlmNTQwYWVjYWZhMmY4MmE0OCIsInN1YiI6IjY1Y2U3OTk1NDU3NjVkMDE3Y2RiMzU2ZSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.GYEVwIHcb20wAq8mikJ8RTtABgcdGryCzTXmmnj59yw'
                }
            };

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
                    <div class="col-3 d-flex align-items-center justify-content-center card  w-30 mx-auto">
                        <div class="card" style="width: 100%;">
                            <img src="${imageUrl}" class="card-img-top" alt="${movie.title} poster">
                            <div class="card-body">
                                <h5 class="card-title">${movie.title}</h5>
                                <p class="card-text">${movie.overview}</p>
                                <p>Date de sortie: ${movie.release_date}</p>
                                <a href="../controleurs/c_cinenote.php?action=details&idFilm=${movie.id}" class="btn btn-primary">plus d'information</a>
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

            $('#btnPublier').click(function() {
                let valueCommentaire = $('#textCommentaire').val();
                console.log(valueCommentaire);
                let formData = new FormData();
                formData.append('valueCommentaire', valueCommentaire);
                formData.append('idUser', idUser);
                formData.append('idFilm', idFilm);

                fetch('c_cinenote.php?action=ajoutCommentaire', {
                        method: 'POST',
                        body: formData
                    })
                    .then(() => {
                        // Ajoutez le commentaire à la fin de la div mère avec le même style
                        $('#divMereCommentaire').append('<div class="row justify-content-center"><div class="col-6"><div class="card mt-2"><div class="card-body"><p class="card-text">' + valueCommentaire + '</p><footer class="blockquote-footer">' + uPseudo + '</footer></div></div></div></div>');
                    })
                    .catch((error) => {
                    console.error('Error:', error);
                });
            });

        });
    </script>
</body>
</html>