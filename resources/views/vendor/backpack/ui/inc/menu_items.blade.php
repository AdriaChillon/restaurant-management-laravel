{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Productos" icon="la la-question" :link="backpack_url('producto')" />
<x-backpack::menu-item title="Menus" icon="la la-question" :link="backpack_url('menu')" />
<x-backpack::menu-item title="Categorias" icon="la la-question" :link="backpack_url('categoria')" />
<x-backpack::menu-item title="Mesas" icon="la la-question" :link="backpack_url('mesa')" />
<x-backpack::menu-item title="Comandas" icon="la la-question" :link="backpack_url('comanda')" />