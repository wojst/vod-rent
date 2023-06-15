<!doctype html>
<html lang="en">
    <head>
        @include('shared.header')
    </head>
    <body>

        @include('shared.navbar')

        @include('shared.carousel')

        <div class="container" id="filmy">
            <h2 style="text-align: center">NASZA BAZA FILMÓW</h2>
            <br>

            {{-- searching form --}}

            <div class="d-flex justify-content-center">
                <form class="d-flex justify-content-center" role="search" action="{{ route('searchMovies') }}" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Wpisz tytuł, aby wyszukać..." aria-label="Search">

                    <select class="form-select" name="category">
                        <option value="">Wszystkie kategorie</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>

                    <button class="btn btn-outline-success" type="submit">Szukaj</button>
                </form>
            </div>

            <br>

            {{-- displaying container --}}

            <div class="row align-items-stretch">

                @foreach($movies as $movie)
                    <div class="col-md-6 col-lg-6 col-xl-3 d-flex mb-4">
                        <div class="card flex-fill">
                            <img src="{{ asset($movie->img_path) }}" class="card-img-top" alt="img">
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
                                <p class="card-text">Gatunek: {{ optional($movie->category)->category_name ?? 'null' }}</p>
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

            {{-- pagination --}}

            {{-- <div class="d-flex justify-content-center mt-4">
                <ul class="pagination">
                    <li class="page-item {{ $movies->previousPageUrl() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $movies->previousPageUrl() }}" aria-label="Poprzednia">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @foreach ($movies->getUrlRange(1, $movies->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $movies->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ $movies->nextPageUrl() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $movies->nextPageUrl() }}" aria-label="Następna">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </div> --}}

        </div>

        <br><br>

        @include('shared.contactform')

        <script src="js/bootstrap.bundle.js"></script>
    </body>
</html>
