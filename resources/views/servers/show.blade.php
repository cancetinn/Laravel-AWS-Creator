<x-app-layout>
    <x-slot name="header">
        {{ __('Sunucu Kontrol Paneli') }}
    </x-slot>

    <div class="bg-gray-800 shadow sm:rounded-lg p-6 text-gray-100">
        <h1 class="text-2xl mb-4">Sunucu Kontrol Paneli</h1>
        
        <!-- Sunucu kontrol butonları -->
        <div class="flex space-x-4 mb-4">
            <button id="startBtn" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded">Başlat</button>
            <button id="stopBtn" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded">Durdur</button>
            <button id="restartBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded">Yeniden Başlat</button>
        </div>

        <!-- Sunucu Durumu -->
        <div class="mt-4">
            <h2 class="text-lg">Sunucu Durumu: <span id="serverStatusIndicator" class="px-2 py-1 rounded-full">Yükleniyor...</span></h2>
        </div>

        <div id="message" class="mt-4"></div>
    </div>
    </x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const instanceId = '{{ $instanceId }}';

    document.getElementById('startBtn').addEventListener('click', () => controlServer('start'));
    document.getElementById('stopBtn').addEventListener('click', () => controlServer('stop'));
    document.getElementById('restartBtn').addEventListener('click', () => controlServer('restart'));

    function controlServer(action) {
        document.getElementById('message').innerText = 'İşlem gönderildi. Lütfen Bekleyiniz!';

        fetch(`/api/server/${instanceId}/${action}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .finally(() => {
            setTimeout(() => {
                location.reload();
            }, 2000);
        });
    }

    function updateServerStatus() {
        fetch(`/api/server/${instanceId}/status`)
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    const statusIndicator = document.getElementById('serverStatusIndicator');
                    if (data.status === 'running') {
                        statusIndicator.classList.add('bg-green-500');
                        statusIndicator.classList.remove('bg-yellow-500', 'bg-red-500');
                        statusIndicator.innerText = 'Çalışıyor';
                    } else if (data.status === 'pending') {
                        statusIndicator.classList.add('bg-yellow-500');
                        statusIndicator.classList.remove('bg-green-500', 'bg-red-500');
                        statusIndicator.innerText = 'Başlatılıyor';
                    } else {
                        statusIndicator.classList.add('bg-red-500');
                        statusIndicator.classList.remove('bg-green-500', 'bg-yellow-500');
                        statusIndicator.innerText = 'Kapalı';
                    }
                }
            })
            .catch(error => {
                console.error('Sunucu durumu güncelleme hatası:', error);
            });
    }

    updateServerStatus();
});
</script>

