<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top" style="background-color: white">
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
                @if (Auth::check())
                    <a class="nav-link" href="{{ route('profile') }}">Profil</a>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Profil</a>
                @endif
            </li>
            @if(Auth::check() && Auth::user()->admin_role)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <b>Panel administratora</b>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="{{ route('movies.index') }}">Filmy</a>
                        <a class="dropdown-item" href="{{ route('categories.index') }}">Kategorie</a>
                        <a class="dropdown-item" href="{{ route('actors.index') }}">Aktorzy</a>
                        <a class="dropdown-item" href="{{ route('orders.index') }}">Zamówienia</a>
                        <a class="dropdown-item" href="{{ route('users.index') }}">Użytkownicy</a>
                    </div>
                  </li>
            @endif
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
