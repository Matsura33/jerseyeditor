<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Statistics Cards -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Ornaments</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $ornamentsCount }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Jerseys</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $jerseysCount }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Users</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $usersCount }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">User Jerseys</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $userJerseysCount }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Quick Links -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('admin.ornaments.index') }}" class="p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <div class="flex items-center">
                                    <svg class="h-6 w-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                    </svg>
                                    <span class="text-blue-600 font-medium">Ornaments</span>
                                </div>
                            </a>
                            <a href="{{ route('admin.jerseys.index') }}" class="p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                <div class="flex items-center">
                                    <svg class="h-6 w-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-green-600 font-medium">Jerseys</span>
                                </div>
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                                <div class="flex items-center">
                                    <svg class="h-6 w-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span class="text-yellow-600 font-medium">Users</span>
                                </div>
                            </a>
                            <a href="{{ route('admin.user-jerseys.index') }}" class="p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                <div class="flex items-center">
                                    <svg class="h-6 w-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="text-purple-600 font-medium">User Jerseys</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                        <div class="space-y-4">
                            <!-- Add recent activity items here -->
                            <p class="text-gray-500">No recent activity to display.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin> 