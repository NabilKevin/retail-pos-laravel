<header class="bg-background border-b border-border p-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <button onclick="toggleSidebar()" class="group lg:hidden p-2.5 rounded-xl hover:bg-muted transition-all duration-200 transform hover:scale-110 hover:shadow-md">
                <i data-lucide="menu" class="h-5 w-5 text-foreground transition-transform duration-200 group-hover:scale-110"></i>
            </button>

            <button onclick="toggleSidebar()" class="group hidden lg:flex items-center justify-center p-2.5 rounded-xl hover:bg-primary/10 hover:text-primary transition-all duration-200 transform hover:scale-110 hover:shadow-md">
                <i data-lucide="panel-left" class="h-5 w-5 text-foreground group-hover:text-primary transition-all duration-200 group-hover:scale-110"></i>
            </button>

            <h1 class="text-lg lg:text-xl font-semibold text-foreground">@yield('page-title', 'Dashboard')</h1>
        </div>

        <div class="flex items-center space-x-2 lg:space-x-4">

            <button onclick="toggleTheme()" class="group p-2.5 rounded-xl hover:bg-accent/10 hover:text-accent transition-all duration-200 transform hover:scale-110 hover:shadow-md" title="Toggle theme">
                <i id="theme-icon" data-lucide="moon" class="h-5 w-5 text-foreground group-hover:text-accent transition-all duration-200 group-hover:scale-110"></i>
            </button>

            <div class="group relative">
                <div class="flex items-center space-x-2 px-3 py-2 rounded-xl hover:bg-muted transition-all duration-200 transform hover:scale-105 hover:shadow-md">
                    <div class="w-8 h-8 bg-gradient-to-br from-primary to-primary/80 rounded-full flex items-center justify-center shadow-md group-hover:shadow-lg transition-all duration-200">
                        <i data-lucide="user" class="h-4 w-4 text-primary-foreground"></i>
                    </div>
                    <div class="hidden sm:block text-left">
                        <span class="text-sm font-medium text-foreground">{{ auth()->user()->username }}</span>
                        <p class="text-xs text-muted-foreground">Kasir</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
