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
            <table class="min-w-full leading-normal" id="servers-table">
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
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-700 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                            Status Indicator
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-700 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody id="servers-body">
                    @forelse($instances as $instance)
                        <tr id="instance-{{ $instance['InstanceId'] }}">
                            <td class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm">
                                {{ $instance['InstanceId'] }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm">
                                {{ $instance['InstanceType'] }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm instance-state">
                                {{ $instance['State'] }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm">
                                {{ $instance['PublicIpAddress'] }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm" id="status-{{ $instance['InstanceId'] }}">
                                <div class="flex items-center">
                                    <span class="inline-block h-3 w-3 rounded-full {{ $instance['State'] == 'running' ? 'bg-green-500' : ($instance['State'] == 'pending' ? 'bg-yellow-500' : 'bg-red-500') }} animate-pulse"></span>
                                    <span class="ml-2">{{ ucfirst($instance['State']) }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm">
                                <a href="{{ route('servers.view', $instance['InstanceId']) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    <x-heroicon-o-eye class="w-4 h-4 mr-2" />
                                    İncele
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm">
                                Herhangi bir EC2 örneği bulunamadı.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(fetchServerStatuses, 1000);

            function fetchServerStatuses() {
                fetch('{{ route('servers.statuses') }}')
                    .then(response => response.json())
                    .then(data => {
                        if (!data.error) {
                            updateServerStatuses(data.instances);
                        } else {
                            console.error('Hata: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('AJAX hatası: ', error);
                    });
            }

            function updateServerStatuses(instances) {
                instances.forEach(instance => {
                    const row = document.getElementById('instance-' + instance.InstanceId);
                    if (row) {
                        const stateCell = row.querySelector('.instance-state');
                        const statusIndicator = row.querySelector('.flex.items-center span');

                        // Durum metnini güncelle
                        stateCell.textContent = instance.State;

                        // Durum göstergesini güncelle
                        if (instance.State === 'running') {
                            statusIndicator.classList.add('bg-green-500');
                            statusIndicator.classList.remove('bg-yellow-500', 'bg-red-500');
                        } else if (instance.State === 'pending') {
                            statusIndicator.classList.add('bg-yellow-500');
                            statusIndicator.classList.remove('bg-green-500', 'bg-red-500');
                        } else {
                            statusIndicator.classList.add('bg-red-500');
                            statusIndicator.classList.remove('bg-green-500', 'bg-yellow-500');
                        }

                        statusIndicator.nextElementSibling.textContent = instance.State.charAt(0).toUpperCase() + instance.State.slice(1);
                    }
                });
            }
        });
    </script>
</x-app-layout>
