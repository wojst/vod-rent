<html>
    <head>
        @include('shared.header')
    </head>
    <body>

        @include('shared.navbar4loginpanel')

        <section class="vh-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->has('login'))
                    <div class="alert alert-danger">
                        {{ $errors->first('login') }}
                    </div>
                @endif
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <h1 class="text-center">Panel logowania</h1>
                    <br><br>
                    <form method="POST" action="{{ route('login') }}">@csrf
                      <!-- Email input -->
                      <div class="form-outline mb-4">
                        <input type="email" id="email" name="email" class="form-control form-control-lg" required/>
                        <label class="form-label" for="email">Email</label>
                      </div>

                      <!-- Password input -->
                      <div class="form-outline mb-4">
                        <input type="password" id="password" name="password" class="form-control form-control-lg" required/>
                        <label class="form-label" for="password">Hasło</label>
                      </div>

                      <div class="d-flex justify-content-around align-items-center mb-4">
                        <p>Nie masz jeszcze konta? <a href="{{ route('register') }}">Zarejestruj się!</a></p>
                      </div>

                      <!-- Submit button -->
                      <div class="d-flex justify-content-around align-items-center" >
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Zaloguj!</button>
                      </div>
                    </form>

                </div>
            </div>

        </section>


        <script src="js/bootstrap.bundle.js"></script>
    </body>
</html>
