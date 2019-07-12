<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8" />
    <title>CMS</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    
    <link href="{{asset("assets/global/plugins/font-awesome/css/font-awesome.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/global/plugins/simple-line-icons/simple-line-icons.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/global/plugins/bootstrap/css/bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/global/css/components.min.css")}}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{asset("assets/global/css/plugins.min.css")}}" rel="stylesheet" type="text/css" />
    {{--<link href="{{asset("assets/global/plugins/bootstrap-sweetalert/sweetalert.css")}}" rel="stylesheet" type="text/css" />--}}
    <link href="{{asset("assets/global/plugins/sweetalert2/sweetalert2.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/layouts/layout/css/layout.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/layouts/layout/css/themes/darkblue.min.css")}}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{asset("assets/layouts/layout/css/custom.min.css")}}" rel="stylesheet" type="text/css" />

    <style type="text/css">
        .error{
            color: #f30f00;
            border-color: #f30f00 !important;
        }
    </style>

    @yield('css')
</head>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-footer-fixed">
<div class="page-wrapper">
    @include('layout.partials.topmenu')
    <div class="page-container">
        @include('layout.partials.menu')
        <div class="page-content-wrapper">
            <div class="page-content">
                @include('layout.partials.breadcrumb')
                @yield('body')
            </div>
        </div>
    </div>
    <div class="page-footer">
        <div class="page-footer-inner">
            <div class="copyright"> <strong>Copyright &copy; 2018 <a style="color:#ec6608;" href="http://www.masuno.pe/" target="_blank">Más Uno S.A.C </a>.</strong> All rights reserved. </div>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
</div>

<div class="quick-nav-overlay"></div>
<!-- BEGIN CORE PLUGINS -->
<script src="{{asset("assets/global/plugins/jquery.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/global/plugins/bootstrap/js/bootstrap.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/global/plugins/js.cookie.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/global/plugins/jquery.blockui.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/global/scripts/app.min.js")}}" type="text/javascript"></script>
{{--<script src="{{asset("assets/global/plugins/bootstrap-sweetalert/sweetalert.js")}}" type="text/javascript"></script>--}}
<script src="{{asset("assets/global/plugins/sweetalert2/sweetalert2.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/layouts/layout/scripts/layout.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/layouts/layout/scripts/demo.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/layouts/global/scripts/quick-sidebar.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/layouts/global/scripts/quick-nav.min.js")}}" type="text/javascript"></script>
<script type="text/javascript">
        function cleanHTML(input) {
            var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g; 
            var output = input.replace(stringStripper, ' ');
            var commentSripper = new RegExp('<!--(.*?)-->','g');
            var output = output.replace(commentSripper, '');
            var tagStripper = new RegExp('<(/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>','gi');
            output = output.replace(tagStripper, '');
            var badTags = ['style', 'script','applet','embed','noframes','noscript'];

            for (var i=0; i< badTags.length; i++) {
                tagStripper = new RegExp('<'+badTags[i]+'.*?'+badTags[i]+'(.*?)>', 'gi');
                output = output.replace(tagStripper, '');
            }
            var badAttributes = ['style', 'start'];
            for (var i=0; i< badAttributes.length; i++) {
                var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"','gi');
                output = output.replace(attributeStripper, '');
            }
            return output;
        }
</script>
@yield('scripts')
@include('sweet::alert')
</body>
</html>