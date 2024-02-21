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

    let averageRating = $("#averageRating").data("rating");
    $("#averageRating").rateYo({
        fullStar: false,
        starWidth: "30px",
        spacing: "5px",
        rating: averageRating,
        readOnly: true
    });

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