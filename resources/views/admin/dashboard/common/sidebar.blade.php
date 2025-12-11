<!-- start: sidebar -->
<aside id="sidebar-left" class="sidebar-left">

    <div class="sidebar-header">
        <div class="sidebar-title" style="color: white">
            <a style="color: white" href="{{ route('home')}}">
                {{ $settings->admin_title ?? '' }}
            </a>
        </div>
        <div class="sidebar-toggle hidden-xs"
             data-toggle-class="sidebar-left-collapsed"
             data-target="html"
             data-fire-event="sidebar-left-toggle">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">

                    @foreach($sidebar_menus as $menu)
                        @if($menu->menu_parent == 0)

                            @php
                                $children = DB::table('menus')
                                    ->where('menu_parent', $menu->id)
                                    ->get();
                            @endphp

                            <li class="nav-parent {{ isMenuOpen($children) }}">

                                {{-- Parent menu --}}
                                @can($menu->menu_permission)
                                <a href="{{ $menu->menu_url ? route($menu->menu_url) : 'javascript:void(0)' }}">
                                    <i class="fa {{ $menu->menu_icon }}"></i>
                                    <span>{{ $menu->menu_name }}</span>
                                </a>
                                @endcan

                                {{-- Child items --}}
                                <ul class="nav nav-children">
                                    @foreach($children as $child)

                                        @php
                                            $child2 = DB::table('menus')
                                                ->where('menu_parent', $child->id)
                                                ->get();
                                        @endphp

                                        <li class="nav-parent {{ isMenuOpen($child2) }}">
                                            
                                            {{-- Level 2 --}}
                                            <a href="{{ $child->menu_url ? route($child->menu_url) : 'javascript:void(0)' }}"
                                               class="{{ isActiveUrl($child->menu_url) }}">
                                                <i class="fa {{ $child->menu_icon }}"></i>
                                                {{ $child->menu_name }}
                                            </a>

                                            {{-- Level 3 --}}
                                            @if($child2->count())
                                            <ul class="nav nav-children">
                                                @foreach($child2 as $sub)
                                                    <li>
                                                        <a href="{{ route($sub->menu_url) }}"
                                                           class="{{ isActiveUrl($sub->menu_url) }}">
                                                            <i class="fa {{ $sub->menu_icon }}"></i>
                                                            {{ $sub->menu_name }}
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
