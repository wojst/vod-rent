<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ route('homepage') }}"><b>RentVOD</b></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ url('/ourmovies') }}">Filmy</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#cennik">Cennik</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#kontakt">Kontakt</a>
            </li>
            <li class="nav-item">
                @if (Auth::check())
                    <a class="nav-link" href="{{ route('profile') }}">Profil</a>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Profil</a>
                @endif
            </li>
        </ul>
        @if (Auth::check())
            <form action="{{ route('logout') }}" method="POST">@csrf
                <button type="submit" class="btn btn-outline-success">Wyloguj się</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-success">Zaloguj lub Zarejestruj się</a>
        @endif

       </div>
    </div>
</nav>
