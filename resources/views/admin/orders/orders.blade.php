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

        <!-- Wyświetlanie zamówień -->
        <h2>Lista zamówień</h2>
        <div class="table-responsive">
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
    </div>

    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>
