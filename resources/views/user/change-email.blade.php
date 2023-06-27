<!doctype html>
<html lang="en">
<head>
    @include('shared.header')
</head>
<body>
    @include('shared.navbar')
    <br><br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Zmień adres e-mail</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('change-email') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="new_email" class="col-md-4 col-form-label text-md-right">Nowy adres e-mail</label>

                                <div class="col-md-6">
                                    <input id="new_email" type="email" class="form-control @error('new_email') is-invalid @enderror" name="new_email" value="{{ old('new_email') }}" required>

                                    @error('new_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Zmień adres e-mail
                                    </button>
                                    <a href="{{ route('profile') }}" class="btn btn-secondary">Powrót do profilu</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/bootstrap.bundle.js"></script>
</body>
</html>
