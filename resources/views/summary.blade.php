<!doctype html>
<html lang="en">
    <head>
        @include('shared.header')
    </head>
    <body>
        <div class="container h-100 py-5">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-10">
                    <div class="d-flex justify-content-center align-items-center mb-4">
                        <h3 class="fw-normal mb-0 text-center">Podsumowanie</h3>
                    </div>

                    <div class="card rounded-3 mb-4">
                        @if($movie)

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="{{ asset($movie->img_path) }}" alt="{{ $movie->title }}" class="rounded-3 img-fluid">
                                    </div>
                                    <div class="col-md-9">
                                        <h5 class="card-title">{{ $movie->title }}</h5>
                                        <p class="card-text">Gatunek: {{ $movie->category->category_name }}</p>
                                        <p class="movie-price">Czas wypożyczenia:
                                            <select name="rental_duration" id="rental_duration">
                                                <option value="24">24 godziny</option>
                                                <option value="48">48 godzin</option>
                                            </select>
                                        </p>
                                        <p class="movie-price">Cena: <span id="movie-price"></span> zł</p>
                                    </div>
                                </div>
                            </div>

                        @else
                            <p class="empty-cart">Koszyk jest pusty.</p>
                        @endif
                    </div>

                    <div class="card text-center">
                        <div class="card-body align-items-center">
                            <a href="{{ route('payment') }}" class="btn btn-warning btn-block btn-lg">Przejdź do płatności</a>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('homepage') }}" class="btn btn-primary">Powrót do strony głównej</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Obsługa zmiany czasu wypożyczenia
            document.getElementById('rental_duration').addEventListener('change', function() {
                var duration = this.value;
                var price = duration === '24' ? 15 : 25; // Cena zależna od czasu wypożyczenia

                document.getElementById('movie-price').textContent = price;
            });
        </script>








        <script src="js/bootstrap.bundle.js"></script>
    </body>

</html>
