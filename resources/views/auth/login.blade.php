<html>
    <head>
        @include('shared.header')
    </head>
    <body>

        @include('shared.navbar')

        <section class="vh-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <h1 class="text-center">Panel logowania</h1>
                    <br><br>
                    <form>
                      <!-- Email input -->
                      <div class="form-outline mb-4">
                        <input type="email" id="form1Example13" class="form-control form-control-lg" />
                        <label class="form-label" for="form1Example13">Email</label>
                      </div>

                      <!-- Password input -->
                      <div class="form-outline mb-4">
                        <input type="password" id="form1Example23" class="form-control form-control-lg" />
                        <label class="form-label" for="form1Example23">Hasło</label>
                      </div>

                      <div class="d-flex justify-content-around align-items-center mb-4">
                        <p>Nie masz jeszcze konta? <a href="#!">Zarejestruj się!</a></p>
                      </div>

                      <!-- Submit button -->
                      <div class="d-flex justify-content-around align-items-center" >
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
                      </div>
                    </form>

                </div>
            </div>

        </section>


        <script src="js/bootstrap.bundle.js"></script>
    </body>
</html>
