<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="/img/logo.png">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/twitter-bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/buttons.bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontawesome.css')}}">
    <link rel="stylesheet" href="{{asset('css/brands.css')}}">
    <link rel="stylesheet" href="{{asset('css/regular.css')}}">
    <link rel="stylesheet" href="{{asset('css/solid.css')}}">
    <script src="{{asset('js/jquery-3.5.1.js')}}"></script>
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <script src="{{asset('js/main-header.js')}}"></script>
    <script src="{{asset('js/ajaxHeaderAuthorized.js')}}"></script>
    <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/datatable-button.min.js')}}"></script>
    <script src="{{asset('js/buttons.print.min.js')}}"></script>
    @yield('stylesheet')
</head>
<style>
.content {
    display: flex;
    flex:1;
    color: #000;
}
.content2 {
    display: flex;
    flex:1;
    color: #000;
}
.content3 {
    display: flex;
    flex: 5;
    color: white;
    width:100%;
}
.content4 {
    display: flex;
    flex: 1;
    color: #000;
}
@media screen and (max-width: 800px){
    .content{
    	display:block;
    }
    table{
    	margin-bottom:10px;
    }
    .content3 {
        margin-left:0;
    }
    .bodytable{
        display:block;
    }
}
@media screen and (max-width: 800px) {
    #accNo {
        height:800px;
        width:920px;
        margin-left:-220px;
    }
}
@media screen and (max-width: 1366px){
    .modal-content{
        height:450px;
    }
    button{
        font-size:12px;
        font-family:calibri;
    }
}

@media screen and (max-width: 1920px) {
    .main-td {
        height: 880px;
    }
}

.btnToggle {
    opacity: 0;
    position: absolute;
    transition: opacity 0.8s;
}
.parent:hover .btnToggle{
    opacity: 1;
}
.parent{
    margin:0;
    left:50%;
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
}
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgba(0, 0, 0, 0.9); /* Black fallback color */
}

/* Hover cursor pointer on thumbnail images */
.img-thumbnail:hover {
  cursor: zoom-in;
}
#preview {
  display: none;
}
#viewApp .modal-dialog {
  width: 50%;
  max-width: 50%;
  height: 80vh;
}

</style>
<body>
    @include('include.header')
    <section>
        <div class="content">
            @include('include.sidebar')
            <div  class="parent"> <button class = "btnToggle">button</button></div>
            <div class="content3">
                <div class = "main-td">
                    @yield('content')
                </div>
            </div>
        </div>
    </section>
    @include('include.modals')
    {{-- <script src="{{asset('js/bootstrap-datatable.min.js')}}"></script> --}}
    @yield('scripts')
    @include('include.sidebarScript')
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/datatable-button.min.js')}}"></script>
    <script src="{{asset('js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('js/responsive.bootstrap4.min.js')}}"></script>
</body>
@include('include.footer')
</body>
