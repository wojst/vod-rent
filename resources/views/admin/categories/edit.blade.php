<!doctype html>
<html lang="en">
<head>
    @include('shared.header')
</head>
<body>
    @include('shared.navbar')

    <div class="container">
        <h1>Edycja kategorii</h1>

        <form method="POST" action="{{ route('categories.update', $category->category_id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="category_name" class="form-label">Nazwa kategorii:</label>
                <input type="text" name="category_name" id="category_name" class="form-control" value="{{ $category->category_name }}" required>
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
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Anuluj</a>
        </form>
    </div>

    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>
