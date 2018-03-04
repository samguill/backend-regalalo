<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 28/02/18
 * Time: 14:35
 */
?>

<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user">
        <img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="User Image">
        <div>
            <p class="app-sidebar__user-name">{{ Auth::user()->name }}</p>
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
        <li>
            <a class="app-menu__item{{ Request::is('/tiendas') ? ' active' : null }}" href="{{url('/tiendas')}}">
                <i class="app-menu__icon fa fa-archive"></i>
                <span class="app-menu__label">Productos</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item{{ Request::is('/stores') ? ' active' : null }}" href="{{url('/stores')}}">
                <i class="app-menu__icon fa fa-building"></i>
                <span class="app-menu__label">Tiendas</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item{{ Request::is('/tiendas') ? ' active' : null }}" href="{{url('/tiendas')}}">
                <i class="app-menu__icon fa fa-users"></i>
                <span class="app-menu__label">Clientes</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item{{ Request::is('/tiendas') ? ' active' : null }}" href="{{url('/tiendas')}}">
                <i class="app-menu__icon fa fa-paper-plane"></i>
                <span class="app-menu__label">Ventas</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item{{ Request::is('/tiendas') ? ' active' : null }}" href="{{url('/tiendas')}}">
                <i class="app-menu__icon fa fa-comments"></i>
                <span class="app-menu__label">Reclamos</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item{{ Request::is('/tiendas') ? ' active' : null }}" href="{{url('/tiendas')}}">
                <i class="app-menu__icon fa fa-window-restore"></i>
                <span class="app-menu__label">Página web</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item{{ Request::is('/tiendas') ? ' active' : null }}" href="{{url('/tiendas')}}">
                <i class="app-menu__icon fa fa-user-secret"></i>
                <span class="app-menu__label">Usuarios</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item{{ Request::is('/tiendas') ? ' active' : null }}" href="{{url('/tiendas')}}">
                <i class="app-menu__icon fa fa-sliders"></i>
                <span class="app-menu__label">Parámetros generales</span>
            </a>
        </li>
    </ul>
</aside>