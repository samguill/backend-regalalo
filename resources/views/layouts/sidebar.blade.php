@php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 28/02/18
 * Time: 14:35
 */

$menustore = App\Utils\MenuTemporal::STORE;
$menuadmin = App\Utils\MenuTemporal::ADMIN;

@endphp

<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user">
        <img class="app-sidebar__user-avatar" src="{{ asset('img/user-icon.png') }}" height="50" alt="User Image">
        <div>
            @if(\Illuminate\Support\Facades\Auth::user()->type =='S')
                <p class="app-sidebar__user-name">{{ Auth::user()->store->comercial_name }}</p>
            @else
                <p class="app-sidebar__user-name">{{ Auth::user()->name }}</p>
            @endif
            <p class="app-sidebar__user-designation">{{ Auth::user()->email }}</p>
        </div>
    </div>
    <ul class="app-menu">
        <li>
            <a class="app-menu__item{{ Request::is('/') ? ' active' : null }}" href="{{url('/')}}">
                <i class="app-menu__icon fa fa-dashboard"></i>
                <span class="app-menu__label">Escritorio</span>
            </a>
        </li>
        @if(\Illuminate\Support\Facades\Auth::user()->type =='S')
            @foreach($menustore as $item)
                <li>
                    <a class="app-menu__item{{ Request::is($item['route']) ? ' active' : null }}" href="{{url($item['route'])}}">
                        <i class="app-menu__icon fa {{$item['icon']}}"></i>
                        <span class="app-menu__label">{{$item['menu']}}</span>
                    </a>
                </li>
            @endforeach
        @endif

        @if(\Illuminate\Support\Facades\Auth::user()->type =='A')
            @foreach($menuadmin as $item)
                <li @if(count($item['options']) > 0) class="treeview" @endif>
                    <a class="app-menu__item{{ Request::is($item['route']) ? ' active' : null }}" @if(count($item['options']) > 0) data-toggle="treeview" @endif href="{{url($item['route'])}}">
                        <i class="app-menu__icon fa {{$item['icon']}}"></i>
                        <span class="app-menu__label">{{$item['menu']}}</span>
                        @if(count($item['options']) > 0)
                            <i class="treeview-indicator fa fa-angle-right"></i>
                        @endif
                    </a>
                    @if(count($item['options']) > 0)
                        <ul class="treeview-menu">
                            @foreach($item['options'] as $option)
                                <li>
                                    <a class="treeview-item{{ Request::is($option['route']) ? ' active' : null }}" href="{{url($option['route'])}}">
                                        {{$option['menu']}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
</aside>