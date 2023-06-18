<!doctype html>
<html lang="en">
    <head>
        @include('shared.header')
    </head>
    <body>
        @include('shared.navbar')

        @if(Session::has('success'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('success') }}
                <strong>{{ Session::get('transaction_code') }}</strong>
            </div>
        @endif

        <div class="user-profile text-center">
            <h1 class="text-center">Profil użytkownika</h1>
            <br>
            <div class="row">
                <div class="col-md-6 d-flex">
                    <div class="card flex-fill h-100">
                        <div class="card-body">
                            <h3>Dane użytkownika</h3>
                            <table class="table">
                                <tr>
                                    <td>Nazwa użytkownika:</td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td>Liczba zamówień:</td>
                                    <td>{{ $user->orders_count }}</td>
                                </tr>
                                <tr>
                                    <td>Karta lojalnościowa:</td>
                                    <td>{{ $user->loyalty_card ? 'Tak' : 'Nie' }}</td>
                                </tr>
                            </table>

                            <a href="{{ route('change-password') }}">Zmień hasło</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex">
                        <div class="card flex-fill h-100">
                            <div class="card-body">
                                <h3>Twoje "ulubione"</h3>
                                <div>
                                    <table class="table">
                                        <tr>
                                            <td>Nazwa:</td>
                                            <td>
                                                @if(isset($favMovieTitle))
                                                    {{ $favMovieTitle }}
                                                @else
                                                    Brak danych
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kategoria:</td>
                                            <td>
                                                @if(isset($favCategoryName))
                                                    {{ $favCategoryName }}
                                                @else
                                                    Brak danych
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Aktor:</td>
                                            <td>
                                                @if(isset($favActor))
                                                    {{ $favActor }}
                                                @else

                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            <div class="card mt-4 d-flex align-items-center justify-content-center">
                <h2 class="text-center">Polecane dla ciebie</h2>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="card flex-fill">
                        @if (!empty($randomMovieFromLastCategory))
                            <img src="{{ asset($randomMovieFromLastCategory[0]->img_path) }}" class="card-img-top" alt="obr1">
                            <div class="card-body d-flex flex-column">
                                <h4 class="card-title">{{ $randomMovieFromLastCategory[0]->title }}</h4>
                                <p class="card-text h-100">{{ $randomMovieFromLastCategory[0]->description }}</p>
                                <p class="card-text">Obsada:
                                    @if(!empty($actors))
                                        {{ implode(', ', $actors->pluck('actor_name')->toArray()) }}
                                    @endif
                                </p>
                                <p class="card-text">Reżyser: {{ $randomMovieFromLastCategory[0]->director }}</p>
                                <p class="card-text">Gatunek: {{ $categoryName ?? 'null' }}</p>
                                <p class="card-text">{{ $randomMovieFromLastCategory[0]->release_year }}</p>
                                <h6 class="card-text">Cena: {{ $randomMovieFromLastCategory[0]->price }} zł</h6>
                                @if(Auth::check())
                                    <a href="{{ route('summary', ['movie_id' => $randomMovieFromLastCategory[0]->movie_id]) }}" class="btn btn-primary">Wypożycz</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary">Zaloguj się, aby wypożyczyć</a>
                                @endif
                            </div>
                        @else
                            <p>Brak dostępnych filmów w ostatniej kategorii.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h3 class="card-title">Zamówienia użytkownika</h3>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Numer zamówienia</th>
                                <th>Tytuł filmu</th>
                                <th>Cena</th>
                                <th>Data zamówienia</th>
                                <th>Data końca wypożyczenia</th>
                                <th>Kod</th>
                                <th>Status płatności</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders->sortByDesc('rent_start') as $order)
                                <tr>
                                    <td>{{ $order->order_id }}</td>
                                    <td>{{ $order->movie->title ?? 'null' }}</td>
                                    <td>{{ $order->cost }}</td>
                                    <td>{{ $order->rent_start }}</td>
                                    <td>{{ $order->rent_end }}</td>
                                    <td>{{ $order->code }}</td>
                                    <td>
                                        @if ($order->payment_status == 'succeed')
                                          Zaakceptowana
                                        @elseif ($order->payment_status == 'failed')
                                          Odrzucona
                                        @elseif ($order->payment_status == 'pending')
                                          W trakcie
                                        @endif
                                      </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
        @include('shared.down')
        <script src="js/bootstrap.bundle.js"></script>
    </body>
</html>
