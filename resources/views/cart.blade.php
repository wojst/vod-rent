<!doctype html>
<html lang="en">
    <head>
        @include('shared.header')
    </head>
    <body>

        {{-- @include('shared.navbar') --}}

        <section class="h-100 h-custom" style="background-color: #d2c9ff;">
            <div class="container py-5 h-100">
              <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12">
                  <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                    <div class="card-body p-0">
                      <div class="row g-0">
                        <div class="col-lg-8">
                          <div class="p-5">
                            <div class="d-flex justify-content-between align-items-center mb-5">
                              <h1 class="fw-bold mb-0 text-black">Koszyk</h1>
                            </div>
                            <hr class="my-4">

                            @foreach ($movies as $movie)
                                <div class="row mb-4 d-flex justify-content-between align-items-center">
                                    <div class="col-md-2 col-lg-2 col-xl-2">
                                        <img src="{{ $movie->img_path }}" class="img-fluid rounded-3" alt="{{ $movie->title }}">
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-3">
                                        <h6 class="text-muted">{{ $movie->category->name }}</h6>
                                        <h6 class="text-black mb-0">{{ $movie->title }}</h6>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                        <button class="btn btn-link px-2" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input id="form1" min="0" name="quantity" value="1" type="number" class="form-control form-control-sm" />
                                        <button class="btn btn-link px-2" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                        <h6 class="mb-0">€ 44.00</h6>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                        <a href="#!" class="text-muted"><i class="fas fa-times"></i></a>
                                    </div>
                                </div>
                            @endforeach




                            <hr class="my-4">

                            <div class="pt-5">
                              <h6 class="mb-0"><a href="#!" class="text-body"><i
                                    class="fas fa-long-arrow-alt-left me-2"></i>Wróć do strony głównej</a></h6>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4 bg-grey">
                          <div class="p-5">
                            <h3 class="fw-bold mb-5 mt-2 pt-1">Podsumowanie</h3>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between mb-5">
                              <h5 class="text-uppercase">Cena końcowa</h5>
                              <h5>€ 137.00</h5>
                            </div>

                            <button type="button" class="btn btn-dark btn-block btn-lg"
                              data-mdb-ripple-color="dark">Zapłać</button>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>


        <script src="js/bootstrap.bundle.js"></script>
    </body>
</html>
