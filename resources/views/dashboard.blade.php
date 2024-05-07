<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{csrf_token()}}">
<title>Lasureco-ERP</title>
<link rel="shortcut icon" href="/img/logo.png">
<link rel="stylesheet" href="{{asset('css/app.css')}}">
<link rel="stylesheet" href="{{asset('css/responsive.css')}}">
<link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('css/fontawesome.css')}}">
<link rel="stylesheet" href="{{asset('css/brands.css')}}">
<link rel="stylesheet" href="{{asset('css/regular.css')}}">
<link rel="stylesheet" href="{{asset('css/solid.css')}}">
<script src="{{asset('js/jquery-3.5.1.js')}}"></script>
<script src="{{asset('js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/main-header.js')}}"></script>
<script src="{{asset('js/sweetalert2.min.js')}}"></script>
<script src="{{asset('js/ajaxHeaderAuthorized.js')}}"></script>
<script src="{{asset('js/app.js')}}"></script>
<style>
body{
    background-size: 105%;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh;
    background-position-x: -5px;
}
.small-box {
    border-radius: .25rem;
    box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
    display: block;
    margin-bottom: 10px;
    position: relative;
    color: #fff;
}
.small-box>.inner {
    padding: 10px;
}
.small-box .icon>i {
  font-size: 68px;
  position: absolute;
  right: 15px;
  top: 15px;
  opacity: 0.5;
}
.main-td{
  padding: 2%;
}
.d-flex span{
  color: black;
}
@media screen and (max-width: 1920px) {
  .main-td {
    height: 880px;
  }
}
</style>
</head>
<body>
    @include('include.header')
    <section>
        <div class="content dashboard-content">
            @include('include.sidebar')
            <div  class="parent"> <button class = "btnToggle">button</button></div>
            <br>
            <div class="content3">
              <div class="main-td">
                <div class="row">
                  <div class="col-md-3">
                    <div class="small-box bg-info">
                      <div class="inner">
                        <h3 class="active-consumer"></h3>
                        <p>Active Consumers</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-user-plus"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="small-box bg-success">
                      <div class="inner">
                        <h3 class="inactive-consumer"></h3>
                        <p>In-active Consumers</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-user-minus"></i>
                      </div>
                    </div>
                  </div>
                  @role('Admin|Billing Report|Billing Transaction')
                  <div class="col-md-3">
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3 class="total_sales_box"></h3>
                        <p>Total Sales</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                      </div>
                    </div>
                  </div>
                  @endrole
                  <div class="col-md-3">
                    <div class="small-box bg-primary">
                      <div class="inner">
                        <h3 class="all-users"></h3>
                        <p>Users</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                </div>
                @role('Admin|Billing Report|Billing Transaction')
                <div class="row">
                  <div class="col-md-12">
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex">
                            <p class="d-flex flex-column">
                              <span class="text-bold text-lg total-sales"></span>
                              <span>Sales Over Time</span>
                            </p>
                          </div>
                          <div class="position-relative mb-4"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <canvas id="sales-chart" height="250" style="display: block; height: 200px; width: 572px;" width="715" class="chartjs-render-monitor"></canvas>
                          </div>
                          <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2">
                              <i class="fas fa-square" style="color: rgb(0,123,255)"></i> This year
                            </span>
          
                            <span>
                              <i class="fas fa-square" style="color: rgb(33,37,41)"></i> Last year
                            </span>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>
                @endrole
              </div>
            </div>
        </div>
    </section>
    @include('include.modals')
    @yield('scripts')
    @include('include.sidebarScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script>
      var _xdata_lastyear
      var _xdata_thisyear
      $(document).ready(function(){
        
        /*-- get active consumer --*/
        $.ajax({
          url: "{{route('api.consumers.active')}}",
          method: "get",
          dataType: "json",
          success: function(data){
            $('.active-consumer').text(data)
          },
          error: function(error){
          }
        })
        //// get inactive consumer
        $.ajax({
          url: "{{route('api.consumers.inactive')}}",
          method: "get",
          dataType: "json",
          success: function(data){
            $('.inactive-consumer').text(data)
          },
          error: function(error){
          }
        })
        // get totalsales4
        $.ajax({
          url: "{{route('api.sales.total')}}",
          method: "get",
          dataType: "json",
          success: function(data){
            $('.total-sales').text('P ' + data);
            $('.total_sales_box').text('P ' + data);
          }
        })
        // get all users
        $.ajax({
          url: "{{route('api.users.all')}}",
          method: "get",
          dataType: "json",
          success: function(data){
            $('.all-users').text(data)
          }
        })
        var ctx = document.getElementById("sales-chart");
        var myLineChart = new Chart(ctx, {
          type: 'bar',
          data: 
            {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: "Last year",
                        backgroundColor: "rgb(33,37,41)",
                        borderColor: "rgb(33,37,41)",
                        data: [],
                    },
                    {
                        label: "This year",
                        backgroundColor: "rgb(0,123,255)",
                        borderColor: "rgb(0,123,255)",
                        data: [],
                    }
                ],
            },
          options: {
            scales: {
                yAxes: {
                  beginAtZero: true,
                }
            },
            legend: {
              display: false
            },
            
          }
        });
        getThisYear(myLineChart)
        getLastYear(myLineChart)
      })
      function getThisYear(chart){
        // get this year sales
        $.ajax({
          url: "{{route('api.sales.this.year')}}",
          method: "get",
          async:true,
          dataType: "json",
          success: function(data){
            chart.data.datasets[1].data = data
            chart.update()
          },
          error: function(error){
          }
        })
      }
      function getLastYear(chart){
          //get last year sales
          $.ajax({
          url: "{{route('api.sales.last.year')}}",
          method: "get",
          async:true,
          dataType: "json",
          success: function(data){
            chart.data.datasets[0].data = data
            chart.update()
          },
          error: function(error){
          }
        })
      }
    </script>
    {{-- <script src="{{asset('js/sales-chart.js')}}"></script>     --}}
</body>
@include('include.footer')


