<!-- Bootstrap Core CSS -->
{{--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">--}}
{{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">--}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">

{{--<link rel="stylesheet"--}}
{{--      href="{{ URL::asset('admin-assets/sb-admin-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">--}}
<link rel="stylesheet"
      href="{{ URL::asset('build/admin-assets/sb-admin-2/bower_components/bootstrap-social/bootstrap-social.css') }}">


<!-- MetisMenu CSS -->
<link rel="stylesheet"
      href="{{ URL::asset('build/admin-assets/sb-admin-2/bower_components/metisMenu/dist/metisMenu.min.css') }}">

<!-- Timeline CSS -->
<link rel="stylesheet" href="{{ URL::asset('build/admin-assets/sb-admin-2/dist/css/timeline.css') }}">

<!-- Custom CSS -->

<link rel="stylesheet" href="{{ URL::asset('admin-assets/sb-admin-2/dist/css/sb-admin-2.css') }}">

<!-- Custom Fonts -->
<link rel="stylesheet"
      href="{{ URL::asset('build/admin-assets/sb-admin-2/bower_components/font-awesome/css/font-awesome.min.css') }}">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>

        <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>-->
{{--<script src = "https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js" ></script>--}}

<![endif]-->


<!-- bootstrap-datepicker    -->
{{--<link rel="stylesheet" href="{{ URL::asset('build/admin-assets/datepicker/css/datepicker.min.css') }}">--}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />


<!-- jQuery -->
{{--<script src="{{ URL::asset('build/admin-assets/sb-admin-2/bower_components/jquery/dist/jquery.min.js') }}"></script>--}}
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>


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
