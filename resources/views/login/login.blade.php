<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" style="height: 100%">

<head>
    <meta charset="utf-8" />
    <title>CMS - Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link href="{{asset("assets/global/plugins/font-awesome/css/font-awesome.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/global/plugins/bootstrap/css/bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/global/css/components.min.css")}}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{asset("css/login-4.css")}}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="favicon.ico" /> </head>
<body class="login" style="background: #ffc107 !important;">
<div class="logo">
    <a href="index.html">
        <img src="{{asset("img/logo.png")}}" alt="" />
    </a>
</div>
<div class="content" style="padding-top: 40px;">
    <form class="login-form" action="" method="post">
        {{ csrf_field() }}
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Usuario</label>
            <div class="input-icon">
                <i class="fa fa-user"></i>
                <input class="form-control placeholder-no-fix" type="text" value="{{ old("username") }}" autocomplete="off" placeholder="Usuario" name="username" /> </div>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Contraseña</label>
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                <input class="form-control placeholder-no-fix" type="password" value="{{ old("password") }}" autocomplete="off" placeholder="Contraseña" name="password" /> </div>
        </div>
        <?php if(Session::has("message")){
            ?>
             <div class="alert alert-danger">
            <?php echo Session::get("message") ?>
        </div>
            <?php
        } ?>
       
        
        <div class="form-actions" style="margin-bottom: 25px">
            <button type="submit" class="btn btn-primary col-xs-12"> Ingresar </button>
        </div>
    </form>
</div>

<div class="copyright"> <strong>Copyright &copy; 2019 <a style="color:#bf0811;" href="http://www.nezkastudio.com/" target="_blank">Nezka Studio </a>.</strong> All rights reserved. </div>

<script src="{{asset("assets/global/plugins/jquery.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/global/plugins/bootstrap/js/bootstrap.min.js")}}" type="text/javascript"></script>
</body>

</html>
