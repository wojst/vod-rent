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
                        <div class="card-header">Zmień hasło</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('change-password') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="current_password" class="col-md-4 col-form-label text-md-right">Obecne hasło</label>

                                    <div class="col-md-6">
                                        <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>

                                        @error('current_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <label for="new_password" class="col-md-4 col-form-label text-md-right">Nowe hasło</label>

                                    <div class="col-md-6">
                                        <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required>

                                        @error('new_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <label for="new_password_confirmation" class="col-md-4 col-form-label text-md-right">Potwierdź nowe hasło</label>

                                    <div class="col-md-6">
                                        <input id="new_password_confirmation" type="password" class="form-control" name="new_password_confirmation" required>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Zmień hasło
                                        </button>
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
