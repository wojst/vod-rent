<!doctype html>
<html lang="en">
    <head>
        @include('shared.header')
    </head>
    <body>
        @include('shared.navbar')
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

        @include('shared.carousel')

        @if(count($topMovies) > 0)
            <div class="card mt-4">
                <h2 class="text-center">Najpopularniejsze filmy dzisiaj</h2>
                <div class="row">
                    @foreach($topMovies as $movie)
                        <div class="col-md-4 cik-lg-6 col-xl-4">
                            <div class="card">
                                <img src="{{ asset($movie->img_path) }}" class="card-img-top" alt="{{ $movie->title }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $movie->title }}</h5>
                                    @if(Auth::check() && Auth::user()->admin_role)
                                        <p class="card-text">Popularność: {{ $movie->popularity }}</p>
                                    @endif
                                </div>
                                @if(Auth::check())
                                    <a href="{{ route('summary', ['movie_id' => $movie->movie_id]) }}" class="btn btn-primary">Wypożycz</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary">Zaloguj się, aby wypożyczyć</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p></p>
        @endif

        <br><br>

        <div class="container" id="filmy">
            <h2 style="text-align: center">NASZE HITY FILMOWE</h3>
            <br>
            <div class="row align-items-stretch">
                @foreach($movies as $movie)
                <div class="col-md-4 col-lg-6 col-xl-3 d-flex">
                    <div class="card flex-fill">
                        <img src="{{ asset($movie->img_path) }}" class="card-img-top" alt="obr1">
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title">{{ $movie->title }}</h4>
                            <p class="card-text h-100">{{ $movie->description }}</p>
                            <p class="card-text">Obsada:
                                @php
                                    $actors = $movie->actors->pluck('actor_name')->toArray();
                                    echo implode(', ', $actors);
                                @endphp
                            </p>
                            <p class="card-text">Reżyser: {{ $movie->director }}</p>
                            <p class="card-text">Gatunek: {{ optional($movie->category)->category_name ?? '' }}</p>
                            <p class="card-text">{{ $movie->release_year }}</p>
                            <h6 class="card-text">Cena: {{ $movie->price }} zł</h6>
                            @if(Auth::check())
                                <a href="{{ route('summary', ['movie_id' => $movie->movie_id]) }}" class="btn btn-primary">Wypożycz</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">Zaloguj się, aby wypożyczyć</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <br><br>

        <div class="d-grid gap-2 col-6 mx-auto">
            <a href="{{ url('/ourmovies') }}" class="btn btn-primary btn-lg">Więcej filmów</a>
        </div>

        <br><br>

        @include('shared.contactform')

        <script src="js/bootstrap.bundle.js"></script>
    </body>
    </html>
