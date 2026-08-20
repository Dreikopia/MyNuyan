<aside class="fixed inset-y-0 left-0 z-40 w-60 bg-card flex flex-col">

    {{-- Logo / Brand area --}}
    <div class="h-16 shrink-0 flex items-center gap-2 px-5 border-b border-base-300">
        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center">
            <span class="text-primary-content font-bold text-xs">M</span>
        </div>

        <span class="text-foreground font-semibold text-lg">
            Mynuyan
        </span>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="menu w-full gap-1 px-3">

            <li>
                <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    Dashboard
                </x-admin.nav-link>
            </li>

            <li class="space-y-1">
                <x-admin.nav-link :href="route('admin.complaints')" :active="request()->routeIs('admin.complaints')">
                    Complaints
                </x-admin.nav-link>

                <x-admin.nav-link :href="route('admin.categories')" :active="request()->routeIs('admin.categories')">
                    Categories
                </x-admin.nav-link>
            </li>

            <li>
                <x-admin.nav-link :href="route('admin.news')" :active="request()->routeIs('admin.news')">
                    News & Announcements
                </x-admin.nav-link>

                <x-admin.nav-link :href="route('admin.news.categories')" :active="request()->routeIs('admin.news.categories')">
                    News Categories
                </x-admin.nav-link>
            </li>

            <li>
                <x-admin.nav-link>
                    Hotlines
                </x-admin.nav-link>
            </li>

            <li>
                <x-admin.nav-link>
                    Users
                </x-admin.nav-link>
            </li>

        </ul>
    </nav>

    {{-- Admin --}}
    <div class="shrink-0 border-t border-base-300 p-4">
        <div class="flex items-center gap-3">

            <div
                class="w-9 h-9 rounded-full bg-base-300 flex items-center justify-center text-foreground text-xs font-semibold">
                {{ substr(Auth::guard('admin')->user()->username, 0, 1) }}
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-foreground text-xs font-medium truncate">
                    {{ Auth::guard('admin')->user()->username }}
                </p>

                <p class="text-muted-foreground text-xs truncate">
                    Admin
                </p>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                @method('DELETE')

                <button type="submit" class="cursor-pointer">
                    <x-icons.logout />
                </button>
            </form>

        </div>
    </div>

</aside>
