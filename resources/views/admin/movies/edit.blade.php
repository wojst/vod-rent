<!doctype html>
<html lang="en">
    <head>
        @include('shared.header')
    </head>
    <body>

        @include('shared.navbar')

        <div class="container mt-4">
            <h1>Edytuj film</h1>

            <form method="POST" action="{{ route('movies.update', $movie->movie_id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Tytuł:</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $movie->title }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Opis:</label>
                    <textarea name="description" id="description" class="form-control" required>{{ $movie->description }}</textarea>
                </div>

                <div class="row">
                    @foreach($actors as $actor)
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="actors[]" value="{{ $actor->actor_id }}" id="actor_{{ $actor->actor_id }}" {{ in_array($actor->actor_id, $selectedActors) ? 'checked' : '' }}>
                                <label class="form-check-label" for="actor_{{ $actor->actor_id }}">
                                    {{ $actor->actor_name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>





                <div class="mb-3">
                    <label for="director" class="form-label">Reżyser:</label>
                    <input type="text" name="director" id="director" class="form-control" value="{{ $movie->director }}" required>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Kategoria:</label>
                    <select name="category" id="category" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" {{ $category->category_id == $movie->category_id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="release_year" class="form-label">Rok produkcji:</label>
                    <input type="number" name="release_year" id="release_year" class="form-control" value="{{ $movie->release_year }}" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Cena:</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ $movie->price }}" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Ścieżka do obrazka:</label>
                    <input type="text" name="image" id="image" class="form-control" value="{{ $movie->img_path }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            </form>
        </div>


        <script src="js/bootstrap.bundle.js"></script>
    </body>
</html>
