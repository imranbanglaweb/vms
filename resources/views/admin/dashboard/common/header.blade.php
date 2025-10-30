<!doctype html>
<html class="fixed">
	<head>
		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Dashboard |  @if(!empty($settings->admin_title)) {{ $settings->admin_title}} @endif</title>
		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="@if(!empty($settings->admin_description)) @endif">
		<meta name="author" content="Imran Rahman">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="{{ asset('public/admin_resource/')}}/assets/vendor/bootstrap/css/bootstrap.css" />
		
		<link rel="stylesheet" href="{{ asset('public/admin_resource/')}}/assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="{{ asset('public/admin_resource/')}}/assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="{{ asset('public/admin_resource/')}}/assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<link rel="stylesheet" href="{{ asset('public/admin_resource/')}}/assets/vendor/select2/select2.css" />
		<link rel="stylesheet" href="{{ asset('public/admin_resource/')}}/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="{{ asset('public/admin_resource/')}}/assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
		<link rel="stylesheet" href="{{ asset('public/admin_resource/')}}/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="{{ asset('public/admin_resource/')}}/assets/vendor/morris/morris.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="{{ asset('public/admin_resource/')}}/assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="{{ asset('public/admin_resource/')}}/assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="{{ asset('public/admin_resource/')}}/assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="{{ asset('public/admin_resource/')}}/assets/vendor/modernizr/modernizr.js"></script>

<style>

	.logo img {
		width: 50px !important;
		height: 50px !important;
		display: block;
		text-align: center;
		margin:0 auto
	}
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
	</head>
	<body>
		<section class="body">

			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="{{route('home')}}" class="logo">
						@if(!empty($settings->admin_logo)) 

						<img src="{{ asset('public/admin_resource/assets/images/'.$settings->admin_logo)}}" height="35" alt="Hansa Admin" />
						@endif
					</a>
					<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
					</div>
				</div>
			
				<!-- start: search & user box -->
				<div class="header-right">
			
					<form action="pages-search-results.html" class="search nav-form">
						<div class="input-group input-search">
							<input type="text" class="form-control" name="q" id="q" placeholder="Search...">
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</form>
			
					<span class="separator"></span>
			
			
					<span class="separator"></span>
			
					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<figure class="profile-picture">
								@if(!empty(Auth::user()->user_image))
<img src="{{ asset('public/admin_resource/assets/images/'.Auth::user()->user_image)}}" alt="Joseph Doe" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
@else

<i class="fa fa-user"></i>
{{-- <img  alt="Joseph Doe" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" /> --}}
								@endif
							

							</figure>
							<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@JSOFT.com">
									@if(!empty(Auth::user()->name))
								<span class="name">{{Auth::user()->name}}</span>
								@endif
								{{-- <span class="role">administrator</span> --}}
							</div>
			
							<i class="fa custom-caret"></i>
						</a>
			
						<div class="dropdown-menu">
							<ul class="list-unstyled">
								<li class="divider"></li>

								<li>
									<a role="menuitem" tabindex="-1" href="{{ route('user-profile')}}">
										<i class="fa fa-user"></i> My Profile</a>
								</li>
								
								<li>
									


									    <a   role="menuitem" tabindex="-1"class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                            <i class="fa fa-power-off"></i>
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
								</li>

							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
			</header>
			<!-- end: header -->