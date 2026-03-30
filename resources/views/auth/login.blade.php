@include('includes.head')
<body style="background-color: #f3f5f8;">
<div class="container" >
    <br><br>
    <div class="row justify-content-center mt-4">
        <div class="col-md-10">
            <div class="card">

                <div class="card-body" >
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row"  style="padding:0px;margin:-20px;margin-bottom:-37;">
                            <div class="col-md-7" style="padding:0px;">
                                <img src="{{ asset('assets/dist/img/1.jpg') }}" width="100%" height="100%" alt="Naret Logo" >
                            </div>

                             <div class="col-md-5" >
                                    <div class="row justify-content-center" >
                                        <img src="{{ asset('assets/dist/img/naret.jpg') }}" width="140" height="180" alt="Naret Logo" >
                                    </div>

                                    <div class="row justify-content-center" >
                                       <div class="col-md-10 ml-5">

                                        <div class="col-md-10 mb-3">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }} "required autocomplete="email" autofocus placeholder="Email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                    </div>

                                    <div class="col-md-10 mb-3">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-10 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-10 ">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                                {{ __('Login') }}
                                            </button>


                                        </div>
                                    </div>
                                       </div>
                                       <div class="footer">
                                        <p>For any inquiry please contact : naret@naret.co.tz</p>
                                        <ul class="footer-login-copy">
                                            <li>
                                                Phone 24 hours :(+255) 753995084
                                            </li>
                                        </ul>
                                    </div>
                                    </div>
                             </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

