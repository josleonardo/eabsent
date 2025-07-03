<div x-show="sidebarOpen"
    class="fixed inset-0 bg-black opacity-85 z-40 lg:hidden"
    @click="sidebarOpen = false">
</div>

<aside id="dashboardSidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar"
    :class="{'-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen}"
    x-cloak>
    <div class="h-full px-3 p-4 overflow-y-auto dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach ($menus as $menu)
                <li>
                    <a href="{{ url($menu['url']) }}" class="flex items-center p-2 text-gray-900 rounded-lg focus:bg-gray-100 focus:dark:bg-gray-700 focus:shadow-sm focus:outline-0 transition dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 hover:shadow-sm group">
                        <x-dynamic-component :component="$menu['icon']" class="size-5 text-gray-500 transition dark:text-gray-400 group-hover:text-gray-900 group-focus:text-gray-900 dark:group-hover:text-white dark:group-focus:text-white" />
                        <span class="ms-3">{{ $menu['name'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</aside>