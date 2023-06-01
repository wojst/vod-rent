<!doctype html>
<html lang="en">
<head>
    @include('shared.header')
</head>
<body>
    @include('shared.navbar')

    <div class="container">
        <h1>Kategorie filmów</h1>

        <h2>Dodaj kategorię</h2>

        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <div class="mb-3">
                <label for="category_name" class="form-label">Nazwa kategorii:</label>
                <input type="text" name="category_name" id="category_name" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Dodaj</button>
        </form>

        <h2>Lista kategorii</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nazwa kategorii</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->category_id }}</td>
                        <td>{{ $category->category_name }}</td>
                        <td>
                            <a href="{{ route('categories.edit', $category->category_id) }}" class="btn btn-primary">Edytuj</a>
                            <form method="POST" action="{{ route('categories.destroy', $category->category_id) }}" style="display: inline-block">
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
