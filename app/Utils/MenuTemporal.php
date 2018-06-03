<?php
/**
 * Created by PhpStorm.
 * User: Propietario
 * Date: 12/03/2018
 * Time: 1:39 AM
 */

namespace App\Utils;


class MenuTemporal
{

    const STORE = [
        ['icon'=>'fa-sitemap','menu'=>'Sucursales','route' => 'branches'],
        ['icon'=>'fa-shopping-bag','menu'=>'Productos','route' => 'products'],
        ['icon'=>'fa-binoculars','menu'=>'Servicios','route' => 'services'],
        ['icon'=>'fa-archive','menu'=>'Inventario','route' => 'inventory'],
        ['icon'=>'fa-ticket','menu'=>'Cupones','route' => 'coupons'],
        ['icon'=>'fa-paper-plane','menu'=>'Ventas','route' => 'orders'],
        ['icon'=>'fa-picture-o','menu'=>'Multimedia','route' => 'multimedia']

    ];
    const ADMIN = [

        ['icon'=>'fa-building','menu'=>'Tiendas','route' => 'stores', 'options' => []],
        ['icon'=>'fa-shopping-bag','menu'=>'Productos','route' => 'product', 'options' => []],
        ['icon'=>'fa-binoculars','menu'=>'Servicios','route' => 'service', 'options' => []],
        ['icon'=>'fa-users','menu'=>'Clientes','route' => 'clients', 'options' => []],
        ['icon'=>'fa-paper-plane','menu'=>'Ventas','route' => 'sales', 'options' => []],
        ['icon'=>'fa-comments','menu'=>'Reclamos','route' => 'claims', 'options' => []],
        ['icon'=>'fa-window-restore','menu'=>'Página web','route' => 'webpage', 'options' => [
            ['icon'=>'fa-comments','menu'=>'Slider','route' => 'slides']
        ]],
        ['icon'=>'fa-user-secret','menu'=>'Usuarios','route' => 'users', 'options' => []],
        ['icon'=>'fa-sliders','menu'=>'Parámetros generales','route' => '#', 'options' => [
            ['icon'=>'fa-comments','menu'=>'Ocasiones','route' => 'events'],
            ['icon'=>'fa-comments','menu'=>'Intereses','route' => 'interests'],
            ['icon'=>'fa-comments','menu'=>'Experiencias','route' => 'experiences'],
            ['icon'=>'fa-comments','menu'=>'Características de producto','route' => 'productcharacteristics'],
            ['icon'=>'fa-comments','menu'=>'Características de servicio','route' => 'servicecharacteristics']
        ]]

    ];


    public function __construct(){

    }
}