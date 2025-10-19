<!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">
				
					<div class="sidebar-header">
						<div class="sidebar-title" style="color: white">
							<a style="color: white" href="{{ route('home')}}">
								@if(!empty($settings->admin_title)) 

								{{ $settings->admin_title}}
								@endif
</a>
						</div>
						<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
							<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
						</div>
					</div>
				
					<div class="nano">
						<div class="nano-content">
							<nav id="menu" class="nav-main" role="navigation">
								<ul class="nav nav-main">

									@foreach($sidebar_menus as $menu)
										@if($menu->menu_parent == 0)
									<li @if(empty($menu->menu_url)) class="nav-parent" @endif  class=" {{ request()->routeIs($menu->menu_url) ? 'active' : '' }}"  >

										@if(!empty($menu->menu_url))

										
											 @can($menu->menu_permission)
										<a href="{{route($menu->menu_url)}}">
											<i class="fa {{$menu->menu_icon}}" aria-hidden="true"></i>
											<span>{{$menu->menu_name}}</span>
										</a>
											 @endcan
										
							
										@else
										
										 @can($menu->menu_permission)
										<a class="nav-active">
											<i class="fa {{$menu->menu_icon}}" aria-hidden="true"></i>
											<span>{{$menu->menu_name}}</span>
										</a>
										 @endcan
										
										
										@endif

										@php 
								$sub_menu_lists = DB::table('menus')->where('menu_parent',$menu->id)->get();
										// dd($sub_menu_lists);
										@endphp

											<ul class="nav nav-children">
										@foreach($sub_menu_lists as $sub_menu_list)

										@php 
									$sub_menu_lists2 = DB::table('menus')
									->where('menu_parent',$sub_menu_list->id)->get();
										// dd($sub_menu_lists);
										@endphp
											<li @if(empty($sub_menu_list->menu_url)) class="nav-parent" @endif>
												@if(!empty( $sub_menu_list->menu_url))
												<a href="{{route($sub_menu_list->menu_url)}}"  class="{{ request()->is($sub_menu_list->menu_url) ? 'sub-nav-active' : '' }}">
													<i class="fa {{$sub_menu_list->menu_icon}}" aria-hidden="true"></i>
													 {{$sub_menu_list->menu_name}}
												</a>
													
												<ul class="nav nav-children">
													@foreach($sub_menu_lists2 as $sub_menu_itemtwo)
													<li class="nav-parent">
														<a>
															<i class="fa {{$sub_menu_itemtwo->menu_icon}}" aria-hidden="true"></i>
															 @can($menu->menu_permission)
															{{$sub_menu_itemtwo->menu_name}}
															@endcan
														</a>
													</li>
													@endforeach
												</ul>
											
												
												@else
												<a><i class="fa {{$sub_menu_list->menu_icon}}" aria-hidden="true"></i>
													 {{$sub_menu_list->menu_name}}
												</a>
													<ul class="nav nav-children">
													@foreach($sub_menu_lists2 as $sub_menu_itemtwo)
													<li>
														<a  href="{{route($sub_menu_itemtwo->menu_url)}}"  class="{{ request()->is($sub_menu_itemtwo->menu_url) ? 'sub-nav-active' : '' }}">
															 @can($menu->menu_permission)
															<i class="fa {{$sub_menu_itemtwo->menu_icon}}" aria-hidden="true"></i>
															{{$sub_menu_itemtwo->menu_name}}
															@endcan
														</a>
													</li>
													@endforeach
												</ul>

												@endif
												
											</li>
										

											@endforeach

											
										</ul>

										
									</li>
										@endif
								

									
									@endforeach

									
									
			
								</ul>
							</nav>
				
						</div>
				
					</div>
				
				</aside>
				<!-- end: sidebar -->