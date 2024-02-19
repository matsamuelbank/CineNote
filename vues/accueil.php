<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/accueil.css">


<body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

   <?php include('header.php');?>

    <div class="container " id="genre">

    </div>

    <div class="container my-5 py-5">
        <div class="row gx-3">
            <!-- Les cartes de film seront ajoutées ici par le JavaScript -->
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const options = {
                method: 'GET',
                headers: {
                    accept: 'application/json',
                    Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI0NzBlNTJmY2M3YWIxYzlmNTQwYWVjYWZhMmY4MmE0OCIsInN1YiI6IjY1Y2U3OTk1NDU3NjVkMDE3Y2RiMzU2ZSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.GYEVwIHcb20wAq8mikJ8RTtABgcdGryCzTXmmnj59yw'
                }
            };
            let nb = 0
            let leGenre = ''
            // requette HTTP
            $.ajax({
                url: 'https://api.themoviedb.org/3/discover/movie?include_adult=false&include_video=false&language=en-US&page=1&sort_by=popularity.desc',
                type: 'GET',
                headers: options.headers,
                success: function(data) { // Cette fonction est appelée lorsque la requête réussit
                    const movies = data.results;
                    const container = $('.row.gx-3'); // On sélectionne l'élément

                    movies.forEach(movie => {
                        nb = nb + 1
                        const imageUrl = movie.poster_path ? `https://image.tmdb.org/t/p/w500${movie.poster_path}` : '../images/nature.jpeg';
                        const card = `
                        <div class="col-3">
                            <div class="card" style="width: 100%;">
                                <img src="${imageUrl}" class="card-img-top" alt="${movie.title} poster">
                                <div class="card-body">
                                    <h5 class="card-title">${movie.title}</h5>
                                    <p class="card-text">${movie.overview}</p>
                                    <p>Date de sortie: ${movie.release_date}</p>
                                    <a href="c_cinenote?action=details&idFilm=${movie.id}" class="btn btn-primary">plus d'information</a>
                                </div>
                            </div>
                        </div>
                        `;
                        container.append(card); // On ajoute la carte à l'élément

                    });
                    console.log("nombre de film trouvé " + nb)
                },
                error: function(err) { // Cette fonction est appelée lorsque la requête échoue
                    console.error(err);
                }
            });

            $("#inputRecherche").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".card").filter(function() {
                    $(this).toggle($(this).find('h5').text().toLowerCase().indexOf(value) > -1)
                });
            });


            $.ajax({
                url: 'https://api.themoviedb.org/3/genre/movie/list?api_key=470e52fcc7ab1c9f540aecafa2f82a48&language=en',
                type: 'GET',
                headers: options.headers,
                success: function(data) {
                    const genres = data.genres;
                    let divGenre = document.getElementById('genre');

                    genres.forEach(genre => {
                        leGenre = `<button class="btnGenre me-3 mb-3" data-id=${genre.id}  style="border-radius: 10px; width: 10%">${genre.name}</button>`;
                        divGenre.innerHTML += leGenre;
                    });

                    $(".btnGenre.me-3.mb-3").each(function() {
                        $(this).click(function() {
                            let valueGenre = parseInt($(this).attr('data-id'))
                            let page = 1;

                            function fetchMovies(page) {
                                $.ajax({
                                    url: 'https://api.themoviedb.org/3/discover/movie?api_key=470e52fcc7ab1c9f540aecafa2f82a48&include_adult=false&include_video=false&language=en-US&page=' + page + '&sort_by=popularity.desc&with_genres=' + valueGenre,
                                    type: 'GET',
                                    headers: options.headers,
                                    success: function(data) { // Cette fonction est appelée lorsque la requête réussit
                                        const movies = data.results;
                                        const container = $('.row.gx-3'); // On sélectionne l'élément
                                        container.empty();

                                        movies.forEach(movie => {
                                            nb = nb + 1
                                            const imageUrl = movie.poster_path ? `https://image.tmdb.org/t/p/w500${movie.poster_path}` : '../images/nature.jpeg';
                                            const card = `
                                            <div class="col-3">
                                                <div class="card" style="width: 100%;">
                                                    <img src="${imageUrl}" class="card-img-top" alt="${movie.title} poster">
                                                    <div class="card-body">
                                                        <h5 class="card-title">${movie.title}</h5>
                                                        <p class="card-text">${movie.overview}</p>
                                                        <p>Date de sortie: ${movie.release_date}</p>
                                                        <a href="c_cinenote?action=details&idFilm=${movie.id}" class="btn btn-primary">plus d'information</a>
                                                    </div>
                                                </div>
                                            </div>
                                            `;
                                            container.append(card); // On ajoute la carte à l'élément

                                        });
                                        console.log("nombre de film trouvé " + nb)

                                        if (data.page < data.total_pages && page < 3) { 
                                            fetchMovies(page + 1);
                                        }
                                    },
                                    error: function(err) { // Cette fonction est appelée lorsque la requête échoue
                                        console.error(err);
                                    }
                                });
                            }

                            fetchMovies(page); // Start fetching movies
                        });
                    });
                }
            });




        })
    </script>

</body>

</html>