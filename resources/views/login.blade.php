<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Title -->
	<title>Thesis</title>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="dexignlabs">
	<meta name="robots" content="index, follow">

	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	
	<!-- STYLESHEETS -->
	<link href="{{ asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
    <link class="main-css" rel="stylesheet" href="{{ asset('css/style.css')}}">

</head>
<body>
    <div class="fix-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6">
                    <div class="card mb-0 h-auto">
                        <div class="card-body">
                            <div class="text-center mb-2">
                                <img src="{{ asset('images/logo-white-3.png')}}" alt="Logo" width="100">
                            </div>
                            <h4 class="text-center mb-4">Sign in your account</h4>
                            <form action="index.html">
                                <div class="form-group">
                                    <label class="form-label" for="username">Username</label>
                                    <input type="text" class="form-control" placeholder="username" id="username">
                                </div>
                                <div class="mb-4 position-relative">
                                    <label class="form-label" for="dlabPassword">Password</label>
                                    <input type="password" id="dlabPassword" class="form-control" value="123456">
                                    <span class="show-pass eye">
                                        <i class="fa fa-eye-slash"></i>
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                                <div class="form-row d-flex flex-wrap justify-content-between mt-4 mb-2">
                                    <div class="form-group">
                                        <div class="form-check custom-checkbox ms-1">
                                            <input type="checkbox" class="form-check-input" id="basic_checkbox_1">
                                            <label class="form-check-label" for="basic_checkbox_1">Remember my preference</label>
                                        </div>
                                    </div>
                                    <div class="form-group ms-2">
                                        <a class="btn-link" href="page-forgot-password.html">Forgot Password?</a>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-block">Sign Me In</button>
                                </div>
                            </form>
                            <div class="new-account mt-3">
                                <p>Don't have an account? <a class="text-primary" href="./page-register.html">Sign up</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('vendor/global/global.min.js')}}"></script>
	<script src="{{ asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
    
    <script src="{{ asset('js/custom.min.js')}}"></script>
    <script src="{{ asset('js/dlabnav-init.js')}}"></script>
    
    

</body>
</html>