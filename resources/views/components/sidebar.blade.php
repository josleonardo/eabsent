<div x-show="sidebarOpen"
    class="fixed inset-0 bg-black opacity-85 z-40 lg:hidden"
    @click="sidebarOpen = false">
</div>

<aside id="dashboardSidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar"
    :class="{'-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen}"
    x-cloak>
    <div class="h-full px-3 p-4 overflow-y-auto dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach ($sideMenus as $sideMenu)
                <li>
                    <a href="{{ url($sideMenu->url) }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <span class="ms-3">{{ $sideMenu->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</aside>