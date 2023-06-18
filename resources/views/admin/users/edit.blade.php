<!doctype html>
<html lang="en">
<head>
    @include('shared.header')
</head>
<body>
    @include('shared.navbar')

    <div class="container">
        <h1>Edytuj Użytkownika</h1>

        <form method="POST" action="{{ route('users.update', $user->user_id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nazwa użytkownika:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            {{-- <div class="mb-3">
                <label for="password" class="form-label">Hasło:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div> --}}

            <div class="mb-3">
                <label for="admin_role" class="form-label">Rola administratora:</label>
                <input type="checkbox" name="admin_role" id="admin_role" class="form-check-input" {{ $user->admin_role ? 'checked' : '' }}>
            </div>

            <div class="mb-3">
                <label for="orders_count" class="form-label">Liczba zamówień:</label>
                <input type="number" name="orders_count" id="orders_count" class="form-control" value="{{ $user->orders_count }}" readonly required>
            </div>

            <div class="mb-3">
                <label for="loyalty_card" class="form-label">Karta lojalnościowa:</label>
                <input type="checkbox" name="loyalty_card" id="loyalty_card" class="form-check-input">
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

            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Anuluj</a>
        </form>
    </div>
    <br><br>

    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>
