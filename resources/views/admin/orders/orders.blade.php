<!doctype html>
<html lang="en">
<head>
    @include('shared.header')
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
    @include('shared.navbar')

    <div class="container">
        <h1>Zamówienia</h1>

        <!-- Dodawanie zamówienia -->
        <h2>Dodaj zamówienie</h2>
        <form method="POST" action="{{ route('orders.store') }}">
            @csrf
            <div class="mb-3">
                <label for="user_id" class="form-label">Użytkownik:</label>
                <select name="user_id" id="user_id" class="form-select" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->user_id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="movie_id" class="form-label">Film:</label>
                <select name="movie_id" id="movie_id" class="form-select" required>
                    @foreach ($movies as $movie)
                        <option value="{{ $movie->movie_id }}">{{ $movie->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="rent_start" class="form-label">Data rozpoczęcia:</label>
                <input type="datetime-local" name="rent_start" id="rent_start" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="rent_end" class="form-label">Data zakończenia:</label>
                <input type="datetime-local" name="rent_end" id="rent_end" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="cost" class="form-label">Koszt:</label>
                <input type="number" name="cost" id="cost" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">Kod:</label>
                <input type="text" name="code" id="code" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Dodaj</button>
        </form>

        <!-- Wyświetlanie zamówień -->
        <h2>Lista zamówień</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Użytkownik</th>
                    <th>Film</th>
                    <th>Data rozpoczęcia</th>
                    <th>Data zakończenia</th>
                    <th>Koszt</th>
                    <th>Kod</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders->sortByDesc('order_id') as $order)
                    <tr>
                        <td>{{ $order->order_id }}</td>
                        <td>{{ optional($order->user)->name ?? 'null' }}</td>
                        <td>{{ optional($order->movie)->title ?? 'null' }}</td>
                        <td>{{ $order->rent_start }}</td>
                        <td>{{ $order->rent_end }}</td>
                        <td>{{ $order->cost }}</td>
                        <td>{{ $order->code }}</td>
                        <td>{{ $order->payment_status }}</td>
                        <td>
                            <form action="{{ route('orders.destroy', $order->order_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Usuń</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>
