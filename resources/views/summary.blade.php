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
                        <h1 class="text-center">Podsumowanie</h1>
                    </div>

                    <div class="card rounded-3 mb-4">
                        @if($movie)

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="{{ asset($movie->img_path) }}" alt="{{ $movie->title }}" class="rounded-3 img-fluid">
                                    </div>
                                    <div class="col-md-9">
                                        <h4 class="card-title">{{ $movie->title }}</h4>
                                        <p class="card-text">Gatunek: {{ $movie->category->category_name }}</p>
                                        <p class="movie-price">Czas wypożyczenia: wszystkie nasze filmy wypożyczane są na 24 godziny</p>
                                        <br><br>
                                        <h3 class="card-text">Koszt zakupu: @if(auth()->user()->loyalty_card) {{ number_format($movie->price * 0.9, 2) }} @else {{ number_format($movie->price, 2) }} @endif zł</h3>
                                        <br><br><br><br><br><br>
                                        <p>Po udanej transakcji swój kod dostępu do filmu znajdziesz w swoim profilu. Miłego seansu!</p>
                                    </div>
                                </div>
                            </div>

                        @else
                            <p class="empty-cart">Koszyk jest pusty.</p>
                        @endif
                    </div>

                    <div class="card text-center">
                        <div class="card-body align-items-center">
                            <a href="" class="btn btn-warning btn-block btn-lg">Przejdź do płatności</a>
                        </div>
                        <div class="card-body align-items-center">
                            <a href="{{ route('blik-payment', ['movie_id' => $movie->movie_id]) }}" class="btn btn-warning btn-block btn-lg">BLIK</a>
                        </div>
                    </div>


                    <div class="text-center mt-4">
                        <a href="{{ route('homepage') }}" class="btn btn-primary">Powrót do strony głównej</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- <script>
            // Obsługa zmiany czasu wypożyczenia
            document.getElementById('rental_duration').addEventListener('change', function() {
                var duration = this.value;
                var price = duration === '24' ? 15 : 25; // Cena zależna od czasu wypożyczenia

                document.getElementById('movie-price').textContent = price;
            });
        </script> --}}


        <script src="js/bootstrap.bundle.js"></script>
    </body>

</html>
