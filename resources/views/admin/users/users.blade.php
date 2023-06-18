<!doctype html>
<html lang="en">
<head>
    @include('shared.header')
</head>
<body>
    @include('shared.navbar')

    <div class="container">
        <h1>Użytkownicy</h1>

        <!-- Dodawanie użytkownika -->
        <h2>Dodaj użytkownika</h2>
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nazwa użytkownika:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Hasło:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="orders_count" class="form-label">Liczba zamówień:</label>
                <input type="number" name="orders_count" id="orders_count" class="form-control" value="0" readonly required>
            </div>

            <div class="mb-3">
                <label for="loyalty_card" class="form-label">Karta lojalnościowa:</label>
                <input type="checkbox" name="loyalty_card" id="loyalty_card" class="form-check-input">
            </div>
            <div class="mb-3">
                <label for="admin_role" class="form-label">Rola administratora:</label>
                <input type="checkbox" name="admin_role" id="admin_role" class="form-check-input">
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <button type="submit" class="btn btn-primary">Dodaj</button>
        </form>

        <!-- Wyświetlanie użytkowników -->
        <h2>Lista użytkowników</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nazwa użytkownika</th>
                        <th>Email</th>
                        <th>Rola administratora</th>
                        <th>Liczba zamówień</th>
                        <th>Karta lojalnościowa</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->admin_role ? 'Tak' : 'Nie' }}</td>
                            <td>{{ $user->orders_count }}</td>
                            <td>{{ $user->loyalty_card }}</td>
                            <td>
                                <form method="POST" action="{{ route('users.destroy', $user->user_id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Usuń</button>
                                </form>
                                <a href="{{ route('users.edit', $user->user_id) }}" class="btn btn-primary">Edytuj</a>
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
