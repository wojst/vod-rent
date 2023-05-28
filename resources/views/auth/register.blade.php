<html>
    <head>
        @include('shared.header')
    </head>
    <body>
        @include('shared.navbar4loginpanel')

        <section class="vh-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <h1 class="text-center">Panel rejestracji</h1>
                    <br><br>
                    <form action="{{ route('register') }}" method="POST">
                    @csrf
                      <!-- Email input -->
                      <div class="form-outline mb-4">
                          <input type="text" id="name" name="name" class="form-control form-control-lg" />
                          <label class="form-label" for="name">Name</label>
                      </div>

                      <!-- Email input -->
                      <div class="form-outline mb-4">
                        <input type="email" id="email" name="email" class="form-control form-control-lg" />
                        <label class="form-label" for="email">Email</label>
                      </div>

                      <!-- Password input -->
                      <div class="form-outline mb-4">
                        <input type="password" id="password" name="password" class="form-control form-control-lg" />
                        <label class="form-label" for="password">Hasło</label>
                      </div>

                      {{-- Password confirm input --}}
                      <div class="form-outline mb-4">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg" />
                        <label class="form-label" for="password_confirmation">Potwierdź hasło</label>

                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        <br><br>
                        @enderror
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        <br><br>
                        @enderror
                      </div>

                      <div class="d-flex justify-content-around align-items-center mb-4">
                        <p>Masz już konto? <a href="{{ route('login') }}">Zaloguj się!</a></p>
                      </div>

                      <!-- Submit button -->
                      <div class="d-flex justify-content-around align-items-center" >
                        <button type="submit" name="register" class="btn btn-primary btn-lg btn-block">Zarejestruj!</button>
                      </div>
                    </form>
                </div>
            </div>
        </section>

        <script src="js/bootstrap.bundle.js"></script>
    </body>
</html>
