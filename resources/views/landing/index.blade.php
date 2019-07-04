@extends('layouts.app')

@section('extra_header')

<style>
    .product_footer{
        position: relative !important;
    }
</style>

@endsection

@section('content')
    <!-- Masthead -->
    <section class="masthead text-white text-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <h1 class="mb-5">Meet The Disk Jokey and Go To Music Request Room!</h1>
                </div>
                <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                    <form id="redirect-to-room" onsubmit="return redirectForm(event)">
                        <div class="form-row">
                            <div class="col-12 col-md-9 mb-2 mb-md-0">
                                <input type="text" class="form-control form-control-lg" id="url" name="url" placeholder="Enter Room URL...">
                            </div>
                            <div class="col-12 col-md-3">
                                <button type="submit" class="btn btn-block btn-lg btn-primary">Take Me!</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
        function redirectForm(e){
            e.preventDefault();
            var formValue = document.getElementById('url').value;
            window.location = "/room/"+formValue;
            return false
        }
    </script>
@endsection
