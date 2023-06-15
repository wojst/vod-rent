<!doctype html>
<html lang="en">
    <head>
        @include('shared.header')
        <script src="https://js.stripe.com/v3/"></script>
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
                                        <p class="card-text">Gatunek: {{ optional($movie->category)->category_name ?? 'null' }}</p>
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
                        <div class="container">
                            <h3>Formularz płatności</h3>

                            <form action="{{ route('process-payment', ['amount' => $movie->price, 'movie' => $movie]) }}" method="POST" id="payment-form">
                                @csrf

                                <div class="mb-3">
                                    <label for="card-element" class="form-label">
                                        Karta kredytowa lub debetowa
                                    </label>
                                    <h3 class="card-text">@if(auth()->user()->loyalty_card) {{ number_format($amount * 0.9, 2) }} @else {{ number_format($amount, 2) }} @endif zł</h3>
                                    <div id="card-element" class="form-control">
                                        <!-- Element, w którym zostanie wyrenderowany formularz karty Stripe -->
                                    </div>

                                    <!-- Wyświetlanie błędów -->
                                    <div id="card-errors" class="invalid-feedback"></div>
                                </div>

                                <button type="submit" class="btn btn-primary">Zapłać</button>
                            </form>
                        </div>
                    </div>


                    <div class="text-center mt-4">
                        <a href="{{ route('homepage') }}" class="btn btn-primary">Powrót do strony głównej</a>
                    </div>
                </div>
            </div>
        </div>


        <script>
            // Utworzenie element karty Stripe
            var stripe = Stripe('pk_test_51NDBgPDXAO4dSNxMTrg8QDlvhPHhslVoXugK0rbjLLOaVCNH4ogkfuKPE6MDdlL9DIuWjuHwBxZwzICOw4xdKRDv00NYeuXR41');
            var elements = stripe.elements();
            var cardElement = elements.create('card');

            // Dodanie element karty do formularza
            cardElement.mount('#card-element');

            // Obsługa błędów
            var cardErrors = document.getElementById('card-errors');

            cardElement.addEventListener('change', function(event) {
                if (event.error) {
                    cardErrors.textContent = event.error.message;
                } else {
                    cardErrors.textContent = '';
                }
            });

            // Obsługa przesłania formularza płatności
            var form = document.getElementById('payment-form');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.createToken(cardElement).then(function(result) {
                    if (result.error) {
                        cardErrors.textContent = result.error.message;
                    } else {
                        var tokenInput = document.createElement('input');
                        tokenInput.type = 'hidden';
                        tokenInput.name = 'stripeToken';
                        tokenInput.value = result.token.id;
                        form.appendChild(tokenInput);

                        form.submit();
                    }
                });
            });
        </script>


        <script src="js/bootstrap.bundle.js"></script>
    </body>

</html>
