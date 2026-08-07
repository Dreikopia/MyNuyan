 <aside class="fixed w-60 top-0 bottom-0 left-0 bg-card flex flex-col">
     {{-- Logo / Brand area --}}
     <div class="h-16 flex items-center gap-2 px-5 border-b border-base-300">
         <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center">
             <span class="text-primary-content font-bold text-xs">M</span>
         </div>
         <span class="text-foreground font-semibold text-lg">Mynuyan</span>
     </div>

     {{-- Navigation --}}
     <nav class="flex-1 overflow-y-auto py-4">
         <ul class="menu w-full gap-1 px-3">

             <div>
                 <p class="text-xs text-muted-foreground">Overview</p>
                 <li>
                     <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                         Dashboard
                     </x-admin.nav-link>
                 </li>
             </div>

             <div>
                 <p class="text-xs text-muted-foreground">Complaints</p>

                 <li>
                     <x-admin.nav-link :href="route('admin.complaints')" :active="request()->routeIs('admin.complaints')">
                         All Complaints
                     </x-admin.nav-link>
                     <x-admin.nav-link :href="route('admin.categories')" :active="request()->routeIs('admin.categories')">
                         Categories
                     </x-admin.nav-link>
                 </li>
             </div>


             <div>
                 <p class="text-xs text-muted-foreground">Content</p>

                 <li>
                     <x-admin.nav-link :href="route('admin.news')" :active="request()->routeIs('admin.news')">
                         News Article
                     </x-admin.nav-link>
                     <x-admin.nav-link :href="route('admin.news.categories')" :active="request()->routeIs('admin.news.categories')">
                         News Categories
                     </x-admin.nav-link>
                 </li>
             </div>

             <div>
                 <p class="text-xs text-muted-foreground">Directory</p>
                 <li>
                     <x-admin.nav-link>
                         Hotlines
                     </x-admin.nav-link>
                 </li>
             </div>

             <div>
                 <p class="text-xs text-muted-foreground">Administraion</p>
                 <li>
                     <x-admin.nav-link>
                         Users
                     </x-admin.nav-link>
                 </li>
             </div>

         </ul>
     </nav>

     {{-- Bottom: logged-in admin + logout --}}
     <div class="border-t border-base-300 p-4">
         <div class="flex items-center gap-3">
             <div
                 class="w-9 h-9 rounded-full bg-base-300 flex items-center justify-center text-foreground text-xs font-semibold">
                 {{ substr(Auth::guard('admin')->user()->username, 0, 1) }}
             </div>
             <div class="flex-1 min-w-0">
                 <p class="text-foreground text-xs font-medium truncate">Andrei Heather</p>
                 <p class="text-muted-foreground text-xs truncate">Admin</p>
             </div>
             <div>

                 <form method="POST" action="{{ route('admin.logout') }}">
                     @csrf
                     @method('DELETE')
                     <button type="submit" class="cursor-pointer">
                         <x-icons.logout />
                     </button>
                 </form>

             </div>
         </div>
     </div>
 </aside>
