<!doctype html>
<html lang="en">
<head>
    @include('shared.header')
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-body text-center">
                        <h3 class="card-title">Płatność zakończona</h3>
                        <p class="card-text">Dziękujemy za dokonanie płatności. Sprawdź status swojej płatności w profilu</p>
                        <a href="{{ route('profile') }}" class="btn btn-primary">Powrót do profilu</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>
