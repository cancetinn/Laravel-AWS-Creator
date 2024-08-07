<x-app-layout>
    <x-slot name="header">
        {{ __('Sunucular') }}
    </x-slot>

    <div class="bg-gray-800 shadow sm:rounded-lg">
        <div class="p-6 text-gray-100">
            <h1 class="text-2xl mb-4">Mevcut Sunucular</h1>
            @if(session('errors'))
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    {{ session('errors') }}
                </div>
            @endif

            <!-- AWS EC2 örneklerini listeleyin -->
            <h2 class="text-xl mt-6 mb-4">AWS EC2 Sunucuları</h2>
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-700 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                            Instance ID
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-700 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                            Instance Type
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-700 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                            State
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-700 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                            Public IP
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($instances as $instance)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm">
                                {{ $instance['InstanceId'] }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm">
                                {{ $instance['InstanceType'] }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm">
                                {{ $instance['State'] }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm">
                                {{ $instance['PublicIpAddress'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm">
                                Herhangi bir EC2 örneği bulunamadı.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Veritabanındaki sunucuları listeleyin -->
            <h2 class="text-xl mt-6 mb-4">Kullanıcı Sunucuları</h2>
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-700 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                            Sunucu Adı
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-700 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                            Durum
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-700 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                            IP Adresi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servers as $server)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm">
                                {{ $server->server_name }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm">
                                {{ $server->status }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm">
                                {{ $server->ip_address }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
