<!doctype html>
<html lang="en">
    <head>
        @include('shared.header')
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Formularz płatności') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('make-payment') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Imię i nazwisko') }}</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" required autofocus>

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="card_number" class="col-md-4 col-form-label text-md-right">{{ __('Numer karty') }}</label>

                                    <div class="col-md-6">
                                        <input id="card_number" type="text" class="form-control @error('card_number') is-invalid @enderror" name="card_number" required>

                                        @error('card_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="expiry_date" class="col-md-4 col-form-label text-md-right">{{ __('Data ważności') }}</label>

                                    <div class="col-md-6">
                                        <input id="expiry_date" type="text" class="form-control @error('expiry_date') is-invalid @enderror" name="expiry_date" required>

                                        @error('expiry_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="cvv" class="col-md-4 col-form-label text-md-right">{{ __('CVV') }}</label>

                                    <div class="col-md-6">
                                        <input id="cvv" type="text" class="form-control @error('cvv') is-invalid @enderror" name="cvv" required>

                                        @error('cvv')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Zatwierdź płatność') }}
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
