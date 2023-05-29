<!doctype html>
<html lang="en">
    <head>
        @include('shared.header')
    </head>
    <body>
        @include('shared.navbar')

        <div class="user-profile">
            <h1 class="text-center">Profil użytkownika</h1>
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="name" class="form-label">Nazwa użytkownika:</label>
                      <input type="text" class="form-control" id="name" value="{{ $user->name }}" readonly>
                    </div>
                    <div class="mb-3">
                      <label for="email" class="form-label">Email:</label>
                      <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="orders" class="form-label">Liczba zamówień:</label>
                      <input type="number" class="form-control" id="orders" value="{{ $user->orders_count }}" readonly>
                    </div>
                    <div class="mb-3">
                      <label for="loyalty-card" class="form-label">Karta lojalnościowa:</label>
                      <input type="text" class="form-control" id="loyalty-card" value="{{ $user->loyalty_card ? 'Tak' : 'Nie' }}" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>



        <script src="js/bootstrap.bundle.js"></script>
    </body>



</html>
