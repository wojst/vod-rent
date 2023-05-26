<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="css/bootstrap.css" rel="stylesheet">


  </head>
  <body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
          <a class="navbar-brand" href="#"><b>RentVOD</b></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="#">Filmy</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#cennik">Cennik</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#kontakt">Kontakt</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Dropdown
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
               </li>
            </ul>
                  <a href="#" class="btn btn-outline-success">Sprawdź swoje konto</a>
           </div>
        </div>
    </nav>

    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="false">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="img/carousel/ticket.jpg" class="d-block w-100" alt="carousel1">
            <div class="carousel-caption d-none d-md-block">
              <h3>Najlepsze filmy bez wychodzenia z domu!</h3>
            </div>
          </div>
          <div class="carousel-item">
            <img src="img/carousel/watching.jpg" class="d-block w-100" alt="carousel2">
            <div class="carousel-caption d-none d-md-block">
              <h3>Daj się porwać filmowym emocjom!</h3>
            </div>
          </div>
          <div class="carousel-item">
            <img src="img/carousel/popcorn.jpg" class="d-block w-100" alt="carousel3">
            <div class="carousel-caption d-none d-md-block">
              <h3>Szykuj popcorn!</h3>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
    </div><br><br>

    <div class="container" id="wycieczki">
        <h2 style="text-align: center">NASZE HITY FILMOWE</h3>
            <br>
        <div class="row">
          <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card">
                <img src="img/movies/godfather.jpg" class="card-img-top" alt="obr1">
                <div class="card-body">
                  <h4 class="card-title">Ojciec Chrzestny</h4>
                  <p class="card-text">Opowieść o nowojorskiej rodzinie mafijnej. Starzejący się Don Corleone pragnie przekazać władzę swojemu synowi.</p>
                  <p class="card-text">Obsada: Marlon Brando, Al Pacino</p>
                  <p class="card-text">Reżyser: Francis Ford Coppola</p>
                  <p class="card-text">Gatunek: Gangsterski </p>
                  <p class="card-text">1972</p>
                  <a href="#" class="btn btn-primary">Wypożycz</a>
                </div>
              </div>
          </div>
          <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card" >
                <img src="img/movies/savingprivateryan.jpg" class="card-img-top" alt="obr2">
                <div class="card-body">
                  <h4 class="card-title">Szeregowiec Ryan</h4>
                  <p class="card-text">W poszukiwaniu zaginionego szeregowca wysłany zostaje doborowy oddział żołnierzy. Czy uda się im wykonać zadanie?</p>
                  <p class="card-text">Obsada: Tom Hanks, Matt Damon</p>
                  <p class="card-text">Reżyser: Steven Spielberg </p>
                  <p class="card-text">Gatunek: Wojenny </p>
                  <p class="card-text">1998</p>
                  <a href="#" class="btn btn-primary">Wypożycz</a>
                </div>
              </div>
          </div>
          <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card" >
                <img src="img/movies/fightclub.jpg" class="card-img-top" alt="obr3">
                <div class="card-body">
                  <h4 class="card-title">Podziemny Krąg</h4>
                  <p class="card-text">Cierpiący na bezsenność mężczyzna poznaje gardzącego konsumpcyjnym stylem życia Tylera Durdena, który jest jego zupełnym przeciwieństwem.</p>
                  <p class="card-text">Obsada: Brad Pitt, Edward Norton</p>
                  <p class="card-text">Reżyser: David Fincher </p>
                  <p class="card-text">Gatunek: Thriller </p>
                  <p class="card-text">1999</p>
                  <a href="#" class="btn btn-primary">Wypożycz</a>
                </div>
              </div>
          </div>
          <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card" >
                <img src="img/movies/gladiator.jpg" class="card-img-top" alt="obr4">
                <div class="card-body">
                    <h4 class="card-title">Gladiator</h4>
                    <p class="card-text">Generał Maximus - prawa ręka cesarza, szczęśliwy mąż i ojciec - w jednej chwili traci wszystko. Jako niewolnik-gladiator musi walczyć na arenie o przeżycie.</p>
                    <p class="card-text">Obsada: Russell Crowe, Joaquin Pheonix</p>
                    <p class="card-text">Reżyser: Ridley Scott</p>
                    <p class="card-text">Gatunek: Dramat </p>
                    <p class="card-text">2000</p>
                    <a href="#" class="btn btn-primary">Wypożycz</a>
                </div>
              </div>
          </div>
        </div>
    </div>

    <br><br>

    <div class="d-grid gap-2 col-6 mx-auto">
        <a href="#" class="btn btn-primary">Więcej filmów</a>
    </div>

    <br><br>

    <div class="container text-center" id="cennik"  class="mx-auto mx-sm-auto mx-md-auto mx-lg-auto">
        <table class="table table-striped">
      <h1 style="text-align: left">Cennik</h1>
          <thead>
            <tr>
              <th scope="col">Czas wypożyczenia</th>
              <th scope="col">Cena</th>
              <th scope="col">Zniżka z kartą stałego klienta</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="col">24 godzin</th>
              <th scope="col">15 zł</th>
              <th scope="col">10%</th>
            </tr>
          </tbody>
        </table>

        <p>Opłatę proszę dokonać na numer konta: <b>00 0000 0000 0000 0000 0000 0000</b></p>
        <p>W tytule przelewu podać ID zamówienia i email</p>
    </div>

    <br><br>

    <div id="kontakt" class="container mt-5">
        <h2>Kontakt</h2>
        <form>
          <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" class="form-control" id="email" placeholder="Wprowadź adres e-mail" required>
          </div>
          <div class="form-group">
            <label for="question">Pytanie:</label>
            <textarea class="form-control" id="question" placeholder="Wprowadź pytanie" rows="5" required></textarea>
          </div>
          <br>
          <button type="submit" class="btn btn-primary">Wyślij</button>
        </form>
    </div>

      <br><br>

    <script src="js/bootstrap.bundle.js"></script>
  </body>
</html>
