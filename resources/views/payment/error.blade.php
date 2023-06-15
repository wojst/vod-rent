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
                        <h3 class="card-title">Wystąpił błąd podczas płatności</h3>
                        <p class="card-text">Przepraszamy, ale wystąpił błąd podczas przetwarzania płatności. Prosimy spróbować ponownie.</p>
                        <a href="{{ route('profile') }}" class="btn btn-primary btn-lg">Powrót do profilu</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>
