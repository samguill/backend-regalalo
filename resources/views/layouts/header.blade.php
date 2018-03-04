<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 28/02/18
 * Time: 14:25
 */
?>
<header class="app-header">
    <a class="app-header__logo" href="index.html">Regalalo</a>
    <!-- Sidebar toggle button-->
    <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">
        <!--Notification Menu-->
        <li class="dropdown">
            <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Ver notificaciones">
                <i class="fa fa-bell-o fa-lg"></i>
            </a>
            <ul class="app-notification dropdown-menu dropdown-menu-right">
                <li class="app-notification__title">You have 4 new notifications.</li>
                <div class="app-notification__content">
                    <li>
                        <a class="app-notification__item" href="javascript:;">
                            <span class="app-notification__icon">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fa fa-envelope fa-stack-1x fa-inverse"></i></span>
                            </span>
                            <div>
                                <p class="app-notification__message">Lisa sent you a mail</p>
                                <p class="app-notification__meta">2 min ago</p>
                            </div>
                        </a>
                    </li>
                </div>
                <li class="app-notification__footer"><a href="#">Ver todas las notificaciones.</a></li>
            </ul>
        </li>
        <!-- User Menu-->
        <li class="dropdown">
            <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu">
                <i class="fa fa-user fa-lg"></i>
            </a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
                <li>
                    <a class="dropdown-item" href="page-user.html">
                        <i class="fa fa-cog fa-lg"></i> Opciones
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="page-user.html">
                        <i class="fa fa-user fa-lg"></i> Mi perfil
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out fa-lg"></i> Salir
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</header>
