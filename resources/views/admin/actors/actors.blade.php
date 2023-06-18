<!doctype html>
<html lang="en">
<head>
    @include('shared.header')
</head>
<body>
    @include('shared.navbar')
    <div class="container">
        <h1>Aktorzy</h1>

        <!-- Dodawanie aktora -->
        <h2>Dodaj aktora</h2>
        <form method="POST" action="{{ route('actors.store') }}">
            @csrf
            <div class="mb-3">
                <label for="actor_name" class="form-label">Imię i nazwisko:</label>
                <input type="text" name="actor_name" id="actor_name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Dodaj</button>
        </form>

        <!-- Wyświetlanie aktorów -->
        <h2>Lista aktorów</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID aktora</th>
                        <th>Imię i nazwisko</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($actors->sortByDesc('actor_id') as $actor)
                        <tr>
                            <td> {{$actor->actor_id}} </td>
                            <td>{{ $actor->actor_name }}</td>
                            <td>
                                <form method="POST" action="{{ route('actors.destroy', $actor->actor_id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Usuń</button>
                                </form>
                                <a href="{{ route('actors.edit', $actor->actor_id) }}" class="btn btn-primary">Edytuj</a>
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
