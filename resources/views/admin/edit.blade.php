<!doctype html>
<html lang="en">
    <head>
        @include('shared.header')
    </head>
    <body>

        @include('shared.navbar')

        <form method="POST" action="{{ route('movies.update', $movie->id) }}">
            @csrf
            @method('PUT')

            <div>
                <label for="title">Tytuł:</label>
                <input type="text" name="title" id="title" value="{{ $movie->title }}" required>
            </div>

            <div>
                <label for="description">Opis:</label>
                <textarea name="description" id="description" required>{{ $movie->description }}</textarea>
            </div>

            <div>
                <label for="director">Reżyser:</label>
                <input type="text" name="director" id="director" value="{{ $movie->director }}" required>
            </div>

            <div>
                <label for="category">Kategoria:</label>
                <select name="category" id="category" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ $category->category_id == $movie->category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="release_year">Rok produkcji:</label>
                <input type="number" name="release_year" id="release_year" value="{{ $movie->release_year }}" required>
            </div>

            <div>
                <label for="price">Cena:</label>
                <input type="number" step="0.01" name="price" id="price" value="{{ $movie->price }}" required>
            </div>

            <div>
                <label for="image">Ścieżka do obrazka:</label>
                <input type="text" name="image" id="image" value="{{ $movie->img_path }}" required>
            </div>

            <button type="submit">Zapisz zmiany</button>
        </form>

    </body>
</html>
