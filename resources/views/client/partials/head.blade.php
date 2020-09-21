<!-- Bootstrap Core CSS -->
<link rel="stylesheet" href="{{ URL::asset('admin-assets/sb-admin-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('admin-assets/sb-admin-2/bower_components/bootstrap-social/bootstrap-social.css') }}">


<!-- MetisMenu CSS -->
<link rel="stylesheet" href="{{ URL::asset('admin-assets/sb-admin-2/bower_components/metisMenu/dist/metisMenu.min.css') }}">

<!-- Timeline CSS -->
<link rel="stylesheet" href="{{ URL::asset('admin-assets/sb-admin-2/dist/css/timeline.css') }}">

<!-- Custom CSS -->

    <link rel="stylesheet" href="{{ URL::asset('admin-assets/sb-admin-2/dist/css/sb-admin-2.css') }}">

<!-- Custom Fonts -->
<link rel="stylesheet" href="{{ URL::asset('admin-assets/sb-admin-2/bower_components/font-awesome/css/font-awesome.min.css') }}">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>

        <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>-->
{{--<script src = "https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js" ></script>--}}

<![endif]-->


<!-- bootstrap-datepicker    -->
<link rel="stylesheet" href="{{ URL::asset('admin-assets/datepicker/css/datepicker.min.css') }}">


<!-- jQuery -->
<script src="{{ URL::asset('admin-assets/sb-admin-2/bower_components/jquery/dist/jquery.min.js') }}"></script>

<style>
    table tbody tr td {
        font-size: 12px;
    }

    table thead tr th {
        font-size: 13px;
        text-align: center;
    }

    .text-valign-center {
        vertical-align: middle !important;
    }

    .loading {
        display: none;
        position: fixed;
        top: 15%;
        left: 10%;
        margin-top: -96px;
        margin-left: -96px;
        background-color: #ccc;
        opacity: .45;
        /* border-radius: 25px; */
        width: 100%;
        height: 100%;
        z-index: 99999;
    }

    .ajax-loader {
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        margin: auto; /* presto! */
    }

    .loading1 {
        display: none;
        position: fixed;
        top: 350px;
        left: 50%;
        margin-top: -96px;
        margin-left: -96px;
        background-color: #ccc;
        opacity: .85;
        border-radius: 25px;
        width: 192px;
        height: 192px;
        z-index: 99999;
    }
</style>