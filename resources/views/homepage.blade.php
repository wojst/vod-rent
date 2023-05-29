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

    <div class="container" id="filmy">
        <h2 style="text-align: center">NASZE HITY FILMOWE</h3>
        <br>
        <div class="row align-items-stretch">
            @foreach($movies as $movie)
            <div class="col-md-6 col-lg-6 col-xl-3 d-flex">
                <div class="card flex-fill">
                    <img src="{{ $movie->img_path }}" class="card-img-top" alt="obr1">
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
                        <p class="card-text">Gatunek: {{  $movie->category->category_name }}</p>
                        <p class="card-text">{{ $movie->release_year }}</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">Wypożycz</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <br><br>

    <div class="d-grid gap-2 col-6 mx-auto">
        <a href="{{ url('/ourmovies') }}" class="btn btn-primary">Więcej filmów</a>
    </div>

    <br><br>

    @include('shared.pricelist')

    @include('shared.contactform')

    <script src="js/bootstrap.bundle.js"></script>
  </body>
</html>
