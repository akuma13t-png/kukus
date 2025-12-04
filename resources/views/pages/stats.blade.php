<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#1b2838] border border-gray-700 p-8 shadow-2xl rounded-sm">
                <h1 class="text-3xl font-black text-white mb-8 uppercase tracking-widest border-b border-gray-600 pb-4">
                    SteamClone <span class="text-[#66c0f4]">Status</span>
                </h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Database Status -->
                    <div class="bg-[#16202d] p-6 rounded border {{ $dbStatus == 'Online' ? 'border-green-500' : 'border-red-500' }}">
                        <h2 class="text-gray-400 uppercase text-xs font-bold tracking-widest mb-2">Database Connection</h2>
                        <div class="text-4xl font-black {{ $dbStatus == 'Online' ? 'text-green-400' : 'text-red-500' }}">
                            {{ $dbStatus }}
                        </div>
                        <p class="text-sm text-gray-500 mt-2">MySQL Database Service</p>
                    </div>

                    <!-- Web Server -->
                    <div class="bg-[#16202d] p-6 rounded border border-green-500">
                        <h2 class="text-gray-400 uppercase text-xs font-bold tracking-widest mb-2">Web Server</h2>
                        <div class="text-4xl font-black text-green-400">
                            Online
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Laravel Application Server</p>
                    </div>
                </div>

                <div class="mt-8 bg-[#2a475e] p-4 rounded text-center text-sm text-gray-300">
                    Last updated: {{ now()->toDateTimeString() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>