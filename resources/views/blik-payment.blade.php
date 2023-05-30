<!doctype html>
<html lang="en">
<head>
    @include('shared.header')
</head>
<body>
    <div class="container h-100 py-5">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-10">
                <div class="d-flex justify-content-center align-items-center mb-4">
                    <h3 class="fw-normal mb-0 text-center">Płatność BLIK</h3>
                </div>

                <div class="card rounded-3 mb-4">
                    <div class="card-body">
                        <!-- Tutaj umieść formularz płatności BLIK -->
                        <form action="{{ route('process-blik-payment') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="blik-code" class="form-label">Kod BLIK</label>
                                <input type="text" class="form-control" id="blik-code" name="blik_code" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Zatwierdź płatność</button>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('homepage') }}" class="btn btn-primary">Powrót do strony głównej</a>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>