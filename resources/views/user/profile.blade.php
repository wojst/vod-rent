<!doctype html>
<html lang="en">
    <head>
        @include('shared.header')
    </head>
    <body>
        @include('shared.navbar')

        <div class="user-profile">
            <h1>Profil użytkownika</h1>
            <div class="row">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Nazwa użytkownika: {{ $user->name }}</h5>
                    <h5 class="card-text">Email: {{ $user->email }}</h5>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <p class="card-text">Liczba zamówień: {{ $user->orders_count }}</p>
                    <p class="card-text">Karta lojalnościowa: {{ $user->loyalty_card ? 'Tak' : 'Nie' }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>


        <script src="js/bootstrap.bundle.js"></script>
    </body>



</html>
