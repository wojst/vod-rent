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
                Twój 5-znakowy kod do wprowadzenia w naszej platformie streamingowej: <strong>{{ Session::get('transaction_code') }}</strong>
            </div>
        @endif

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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders->sortByDesc('rent_start') as $order)
                                <tr>
                                    <td>{{ $order->order_id }}</td>
                                    <td>{{ $order->movie->title }}</td>
                                    <td>{{ $order->cost }}</td>
                                    <td>{{ $order->rent_start }}</td>
                                    <td>{{ $order->rent_end }}</td>
                                    <td>{{ $order->code }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

          </div>



        <script src="js/bootstrap.bundle.js"></script>
    </body>



</html>
