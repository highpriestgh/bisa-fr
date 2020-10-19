@extends('layouts.app')

@section('stylesheet')
    <style>
        body {
            background: #f6fafb;
        }

        @media only screen and (max-width: 766px) {
            .intro-img-col {
                display: none;
            }

            .auth-logo {
                display: block;
            }

            .auth-login-sub-div {
                padding-top: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container auth-main-div">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-6 intro-img-col">
                <div class="intro-img-div">
                    <img src="{{ asset('images/logo.png') }}" class="intro-logo img-fluid" alt=""><br>
                    <h5 class="text-center"><b>Bienvenue. Avec Nous, Vous Avez Toujours Un Médecin</b></h5>
                    <img src="{{ asset('images/doctors.svg') }}" class="img-fluid" alt="">
                </div>
            </div>
            <div class="col-md-4 auth-login-sub-div">

                <div class="auth-logo-div">
                    <img src="{{ asset('images/logo.png') }}" class="auth-logo img-fluid" alt="">
                </div>

                <h5 class="text-center"><b>Connexion au compte</b></h5>

                <div class="auth-form-div">
                    <form class="anonymous-user-signup-form">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-sm username" placeholder="nom de l'utilisateur...">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control form-control-sm user-password" placeholder="mot de passe...">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control form-control-sm user-password-conf" placeholder="confirmer votre mot de passe...">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-sm btn-custom btn-block auth-btn">S'inscrire</button>
                        </div><br>

                        <p class="text-center"><small> <a href="<?php echo Config::get('constants.USER_APP_DIRECTORY'); ?>/login">J'ai un compte</a></small></p>
                    </form>

                </div>

            </div>
            <div class="col-md-1"></div>

        </div>
    </div>
@endsection

@section('javascript')
	<script type="text/javascript" src="{{ asset('js/constants.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/controllers/user/user-auth-controller.js') }}"></script>
@endsection
