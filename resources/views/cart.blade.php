<!doctype html>
<html lang="en">
    <head>
        @include('shared.header')
    </head>
    <body>

        {{-- @include('shared.navbar') --}}

        @section('content')
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <h2>Koszyk</h2>

                        @if($cartMovie)
                            <div class="row mb-4 d-flex justify-content-between align-items-center">
                                <div class="col-md-2 col-lg-2 col-xl-2">
                                    <img src="{{ $cartMovie->img_path }}" class="img-fluid rounded-3" alt="{{ $cartMovie->title }}">
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3">
                                    <h6 class="text-muted">{{ $cartMovie->category->name }}</h6>
                                    <h6 class="text-black mb-0">{{ $cartMovie->title }}</h6>
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
                        @else
                            <p>Koszyk jest pusty.</p>
                        @endif

                        <div class="pt-5">
                            <h6 class="mb-0"><a href="#!" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Wróć do strony głównej</a></h6>
                        </div>
                    </div>
                </div>
            </div>
        @endsection


        <script src="js/bootstrap.bundle.js"></script>
    </body>
</html>