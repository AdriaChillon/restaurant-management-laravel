
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
        <!-- Contenido del navbar -->
        <div class="flex items-center">
            <!-- Navigation Links -->
                <!-- Productos Link -->
                <x-nav-link :href="route('productos.index')" :active="request()->routeIs('productos.index')" class="text-white hover:text-yellow-300">
                    {{ __('Productos') }}
                </x-nav-link>

                <!-- Mesas Link -->
                <x-nav-link :href="route('mesas.index')" :active="request()->routeIs('mesas.index')" class="text-white hover:text-yellow-300">
                    {{ __('Mesas') }}
                </x-nav-link>

                <!-- Comandas Link -->
                <x-nav-link :href="route('comandas.index')" :active="request()->routeIs('comandas.index')" class="text-white hover:text-yellow-300">
                    {{ __('Comandas') }}
                </x-nav-link>

                <!-- Categorias Link -->
                <x-nav-link :href="route('categorias.index')" :active="request()->routeIs('categorias.index')" class="text-white hover:text-yellow-300">
                    {{ __('Categorias') }}
                </x-nav-link>

                <!-- Camarero Link -->
                <x-nav-link :href="route('camarero.index')" :active="request()->routeIs('camarero.index')" class="text-white hover:text-yellow-300">
                    {{ __('Camarero') }}
                </x-nav-link>

                <!-- Cocinero Link -->
                <x-nav-link :href="route('cocinero.index')" :active="request()->routeIs('cocinero.index')" class="text-white hover:text-yellow-300">
                    {{ __('Cocinero') }}
                </x-nav-link>

                <!-- Barra Link -->
                <x-nav-link :href="route('barra.index')" :active="request()->routeIs('barra.index')" class="text-white hover:text-yellow-300">
                    {{ __('Barra') }}
                </x-nav-link>

                <!-- Usuarios Link -->
                <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')" class="text-white hover:text-yellow-300">
                    {{ __('Usuarios') }}
                </x-nav-link>
            </div>
        </div>


        <!-- Settings Dropdown and Hamburger -->
        <div class="flex items-center">
            <div class="hidden md:flex md:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:text-yellow-300 hover:bg-blue-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-white hover:text-yellow-300">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="md:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('productos.index')" :active="request()->routeIs('productos.index')" class="text-white hover:text-yellow-300">
                {{ __('Productos') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('mesas.index')" :active="request()->routeIs('mesas.index')" class="text-white hover:text-yellow-300">
                {{ __('Mesas') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('comandas.index')" :active="request()->routeIs('comandas.index')" class="text-white hover:text-yellow-300">
                {{ __('Comandas') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('categorias.index')" :active="request()->routeIs('categorias.index')" class="text-white hover:text-yellow-300">
                {{ __('Categorias') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('camarero.index')" :active="request()->routeIs('camarero.index')" class="text-white hover:text-yellow-300">
                {{ __('Camarero') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('cocinero.index')" :active="request()->routeIs('cocinero.index')" class="text-white hover:text-yellow-300">
                {{ __('Cocinero') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('barra.index')" :active="request()->routeIs('barra.index')" class="text-white hover:text-yellow-300">
                {{ __('Barra') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')" class="text-white hover:text-yellow-300">
                {{ __('Roles') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-blue-400">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-blue-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-white hover:text-yellow-300">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
