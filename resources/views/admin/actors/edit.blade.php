<!doctype html>
<html lang="en">
<head>
    @include('shared.header')
</head>
<body>
    @include('shared.navbar')

    <div class="container">
        <h1>Edytuj Aktora</h1>

        <form method="POST" action="{{ route('actors.update', $actor->actor_id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="actor_name" class="form-label">Imię i Nazwisko Aktora:</label>
                <input type="text" name="actor_name" id="actor_name" class="form-control" value="{{ $actor->actor_name }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            <a href="{{ route('actors.index') }}" class="btn btn-secondary">Anuluj</a>
        </form>
    </div>

    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>
