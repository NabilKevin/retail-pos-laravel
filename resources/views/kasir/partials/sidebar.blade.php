<div id="sidebar" class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-sidebar border-r border-sidebar-border transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="p-4 lg:p-6">
        <div class="flex items-center space-x-2">
            <div class="sidebar-text">
                <h2 class="font-bold text-sidebar-foreground">Toko Obat</h2>
            </div>
        </div>
    </div>

    <nav class="px-4 space-y-2">
        @php
            $menuItems = [
                ['icon' => 'home', 'label' => 'Dashboard', 'route' => 'kasir.dashboard'],
                ['icon' => 'package', 'label' => 'POS', 'route' => 'kasir.pos'],
                ['icon' => 'package', 'label' => 'Data Obat', 'route' => 'kasir.obat'],
                ['icon' => 'users', 'label' => 'Riwayat Transaksi', 'route' => 'kasir.transaksi'],
            ];
        @endphp

        @foreach($menuItems as $item)
            @php
                $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*');
            @endphp
            <a href="{{ route($item['route']) }}"
               class="group flex items-center w-full px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 transform hover:scale-105
                      {{ $isActive
                         ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg'
                         : 'text-sidebar-foreground hover:bg-blue-700/10 hover:text-blue-700 hover:shadow-md' }}">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg {{ $isActive ? 'bg-white/20' : 'group-hover:bg-blue-700/20' }} transition-all duration-200">
                    <i data-lucide="{{ $item['icon'] }}" class="h-4 w-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110"></i>
                </div>
                <span class="ml-3 sidebar-label transition-all duration-200">{{ $item['label'] }}</span>
                @if($isActive)
                    <div class="ml-auto w-2 h-2 bg-blue-700-foreground rounded-full shadow-sm"></div>
                @endif
            </a>
        @endforeach
    </nav>

    <div class="absolute bottom-4 left-4 right-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="group flex items-center w-full px-4 py-3 rounded-xl text-sm font-medium text-destructive hover:bg-destructive/10 hover:shadow-md transition-all duration-200 transform hover:scale-105">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg group-hover:bg-destructive/20 transition-all duration-200">
                    <i data-lucide="log-out" class="h-4 w-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110"></i>
                </div>
                <span class="ml-3 sidebar-label transition-all duration-200">Logout</span>
            </button>
        </form>
    </div>
</div>
