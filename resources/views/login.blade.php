@extends('layout')

@section('content')

<!--=================================
 login-->

<section class="login-form dark-bg page-section-ptb bg-overlay-black-30 bg" style="background: url({{ asset('images/pattern/02.png') }}) no-repeat 0 0;">
    <div class="container">

        @if (session()->has('flash_error'))
            <div class="row justify-content-center">
                <div class="col-md-6 alert alert-danger text-center">
                    {{ session()->get('flash_error') }}
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-1-form clearfix text-center">
                    <h4 class="title divider-3 text-white">SIGN IN</h4>
                    <div class="section-field mb-2">
                        <div class="field-widget"> <i class="glyph-icon flaticon-user"></i>
                            <input id="name" class="web" type="text" placeholder="User name" name="web">
                        </div>
                    </div>
                    <div class="section-field mb-1">
                        <div class="field-widget"> <i class="glyph-icon flaticon-padlock"></i>
                            <input id="Password" class="Password" type="password" placeholder="Password" name="Password">
                        </div>
                    </div>
                    <div class="section-field text-uppercase"> <a href="#" class="float-right text-white">Forgot Password?</a> </div>
                    <div class="clearfix"></div>
                    <div class="section-field text-uppercase text-right mt-2"> <a class="button  btn-lg btn-theme full-rounded animated right-icn"><span>sign in<i class="glyph-icon flaticon-hearts" aria-hidden="true"></i></span></a> </div>
                    <div class="clearfix"></div>
                    <div class="section-field mt-2 text-center text-white">
                        <p class="lead mb-0">Don’t have an account? <a class="text-white" href="register.html"><u>Register now!</u> </a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--=================================
 login-->

@endsection