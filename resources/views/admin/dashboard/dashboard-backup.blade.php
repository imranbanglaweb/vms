@extends('admin.dashboard.master')
@section('main_content')
<style type="text/css">
	.overview{

    margin-top: 114px;
    text-align: center;
    border: 1px solid #34495e;
    border-radius: 50%;
    width: 70%;
    /* height: 100px; */
    display: block;
    padding: 70px 50px;
    color: #fff;
    font-weight: bold;
    margin-left: 30px;
  background: url('https://images.pexels.com/photos/2325446/pexels-photo-2325446.jpeg?auto=compress&cs=tinysrgb&h=650&w=940');
  background-position: center;
  background-size: cover;
	}
	.overview_content{

    margin-top: 70px;

	}
	.overview_content ul li{
		/*margin-bottom: 0;*/
    /*padding-left: 27px;*/
    list-style-type: disclosure-closed;
    color: brown;
    font-weight: bold;
    font-family: ui-monospace;
    margin-left: 20px
	}
	.overview_content p{
    color: #7169aa;
    border-bottom: 1px solid #e7e7e7;
    padding: 10px;
    font-size: 18px;
    font-weight: bold;
    font-family: georgia;
	}
</style>
<section role="main" class="content-body">
					<header class="page-header">
						<h2>Dashboard</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="{{ route('home')}}">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Dashboard</span></li>
							</ol>
					
							<a class="sidebar-right-toggle" data-open="sidebar-right"></a>
						</div>
					</header>
					<!-- start: page -->
					<div class="row">
					
						<div class="col-md-6 col-lg-12 col-xl-6">
							<div class="row">
								<div class="col-md-6 col-lg-6 col-xl-6">
									<section class="panel panel-featured-left panel-featured-primary">
										<div class="panel-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-primary">
														<i class="fa fa-photo"></i>
													</div>
												</div>
												<div class="widget-summary-col">
												<a href="{{ route('supports.index')}}">
														<div class="summary">
														<h4 class="title">Total Task Completed</h4>
														<div class="info">
															{{-- {{$task_completed}}  --}}
															{{-- <strong 
																class="amount">1281</strong> --}}
															{{-- <span class="text-primary">(14 unread)</span> --}}
															<div class="info">
															<strong class="amount">{{$task_completed}} </strong>
														</div>
														</div>
													</div>
													</a>
													<div class="summary-footer">
														{{-- <a class="text-muted text-uppercase">(view all)</a> --}}
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								
								<div class="col-md-12 col-lg-6 col-xl-6">
									<section class="panel panel-featured-left panel-featured-tertiary">
										<div class="panel-body">
												<a href="#">
													<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-tertiary">
														<i class="fa fa-home"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Upcoming Projects</h4>
														<div class="info">
															{{-- <strong class="amount">38</strong> --}}
														</div>
													</div>
													<div class="summary-footer">
														{{-- <a class="text-muted text-uppercase">(statement)</a> --}}
													</div>
												</div>
											</div>
												</a>
										</div>
									</section>
								</div>
								<div class="col-md-12 col-lg-6 col-xl-6">
									<section class="panel panel-featured-left panel-featured-quartenary">
										<div class="panel-body">
										
												<a href="{{ route('emergency-task')}}">
													<input type="hidden" name="emergency_issue">
												<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-quartenary">
														<i class="fa fa-lock"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Emergency Issue</h4>
														<div class="info">
															<strong class="amount">
															{{$emergency}}</strong>
														</div>
													</div>
													<div class="summary-footer">
														{{-- <a class="text-muted text-uppercase">(report)</a> --}}
													</div>
												</div>
											</div>
											</a>
											
										</div>
									</section>
								</div>
								<div class="col-md-12 col-lg-6 col-xl-6">
									<section class="panel panel-featured-left panel-featured-quartenary">
										<div class="panel-body">
											<a href="{{ route('pendingsupport')}}">
												<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-primary">
														<i class="fa fa-list"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Overall Pending Job</h4>
														<div class="info">
															<strong class="amount">{{$month_task_incompleted}} </strong>
														</div>
													</div>
													<div class="summary-footer">
														{{-- <a class="text-muted text-uppercase">(report)</a> --}}
													</div>
												</div>
											</div>
											</a>
										</div>
									</section>
								</div>
							</div>
						</div>
					</div>

				<div class="dashboard_bottom_info" style="background-color: #fff;padding: 0 15px;min-height: 350px">
						<div class="row" style="margin-top: 15px">
						<div class="col-md-6">
							{{-- <h4 class="overview">Overview</h4> --}}
							  <!-- PIE CHART -->
			 <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-7">
        

            <!-- DONUT CHART -->
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Task In Chart</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas id="donutChart" style=" height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

         

          </div>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
					<!-- end: page -->
						</div>
						<div class="col-md-3 overview_content">
							
								<p>Daily Job Status</p>
							<ul>
								<li>Completed</li>
								<li>In-Completed</li>
							</ul>
								<p>Weekly Job Status</p>
							<ul>
								<li>Completed</li>
								<li>In-Completed</li>
							</ul>
								<p>Monthly Job Status</p>
							<ul>
								<li>Completed</li>
								<li>In-Completed</li>
							</ul>
						</div>
						
						<div class="col-md-2 overview_content">
							
								<p>Progress</p>
							<ul>
								<li>{{$task_completed}} job </li>
								
								<li>{{$task_incompleted}} job </li>
							</ul>
							<p>Progress</p>
							<ul>
									<li>{{ $current_week_completed }} job </li>
								<li>{{ $current_week_incompleted }} job </li>
							</ul>
								<p>Progress</p>
							<ul>
								<li>{{ $month_task_completed }} job </li>
								<li>{{ $month_task_incompleted }} job </li>
						</div>
					</div>
					<br>
					<br>
				</div>
	
				</section>

<!-- jQuery -->
<script src="{{ asset('public/admin_resource/')}}/plugins/jquery/jquery.min.js"></script>
<script src="{{ asset('public/admin_resource/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('public/admin_resource/')}}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
{{-- <script src="{{ asset('public/admin_resource/')}}/dist/js/demo.js"></script> --}}
<!-- ChartJS -->
<script src="{{ asset('public/admin_resource/')}}/plugins/chart.js/Chart.min.js"></script>
<script>
  $(function () {
var task_completed = {{ Js::from($task_completed) }};
var month_task_completed = {{ Js::from($month_task_completed) }};
var current_week_completed = {{ Js::from($current_week_completed) }};
// var task_completed = '50';

// alert(task_completed);

    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: [
          'Daily',
          'Weekly',
          'Monthly',
      ],
      datasets: [
        {
          data: [task_completed,50,400],
          backgroundColor : ['#f56954', '#00a65a', '#f39c12'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions
    })

  

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })
    // This will get the first returned node in the jQuery collection.
    new Chart(areaChartCanvas, {
      type: 'line',
      data: areaChartData,
      options: areaChartOptions
    })
  //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    var lineChartOptions = $.extend(true, {}, areaChartOptions)
    var lineChartData = $.extend(true, {}, areaChartData)
    lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, {
      type: 'line',
      data: lineChartData,
      options: lineChartOptions
    })
  })
</script>
@endsection