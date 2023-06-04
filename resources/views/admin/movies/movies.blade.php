<!doctype html>
<html lang="en">
    <head>
        @include('shared.header')
    </head>
    <body>

        @include('shared.navbar')

        <div class="container">
            <h1>Dodaj film</h1>

            <form method="POST" action="{{ route('movies.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Tytuł:</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Opis:</label>
                    <textarea name="description" id="description" class="form-control" required></textarea>
                </div>

                <div class="row">
                    @foreach($actors as $actor)
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="actors[]" value="{{ $actor->actor_id }}" id="actor_{{ $actor->actor_id }}">
                                <label class="form-check-label" for="actor_{{ $actor->actor_id }}">
                                    {{ $actor->actor_name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mb-3">
                    <label for="director" class="form-label">Reżyser:</label>
                    <input type="text" name="director" id="director" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Kategoria:</label>
                    <select name="category" id="category" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="release_year" class="form-label">Rok produkcji:</label>
                    <input type="number" name="release_year" id="release_year" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Cena:</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Ścieżka do obrazka:</label>
                    <input type="text" name="image" id="image" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Dodaj film</button>
            </form>

            <h1>Lista filmów</h1>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID filmu</th>
                        <th>Tytuł</th>
                        <th>Opis</th>
                        <th>Obsada</th>
                        <th>Reżyser</th>
                        <th>Kategoria</th>
                        <th>Rok produkcji</th>
                        <th>Cena</th>
                        <th>Ilość wypożyczeń</th>
                        <th>Ścieżka do obrazka</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movies->sortByDesc('movie_id') as $movie)
                        <tr>
                            <td>{{ $movie->movie_id }}</td>
                            <td>{{ $movie->title }}</td>
                            <td>{{ $movie->description }}</td>
                            <td>
                                @php
                                    $actors = $movie->actors->pluck('actor_name')->implode(', ');
                                @endphp
                                {{ $actors }}
                            </td>
                            <td>{{ $movie->director }}</td>
                            <td>{{ $movie->category->category_name }}</td>
                            <td>{{ $movie->release_year }}</td>
                            <td>{{ $movie->price }}</td>
                            <td>{{ $movie->rentals_count }}</td>
                            <td>{{ $movie->img_path }}</td>
                            <td>
                                <a href="{{ route('movies.edit', $movie->movie_id) }}" class="btn btn-primary">Edytuj</a>
                                <form method="POST" action="{{ route('movies.destroy', $movie->movie_id) }}" style="display: inline-block">
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
