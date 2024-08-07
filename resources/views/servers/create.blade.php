<x-app-layout>
    <x-slot name="header">
        {{ __('Yeni Sunucu Oluştur') }}
    </x-slot>

    <div class="bg-gray-800 shadow sm:rounded-lg">
        <div class="p-6 text-gray-100">
            <h1 class="text-2xl mb-4">Yeni Sunucu Oluştur</h1>
            <form id="server-form" method="POST" action="{{ route('servers.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="server_name" class="block text-gray-400">Sunucu Adı</label>
                    <input type="text" name="server_name" id="server_name" class="mt-1 block w-full bg-gray-900 border border-gray-700 rounded p-2 text-gray-300" required>
                </div>
                <div class="mb-4">
                    <label for="instance_type" class="block text-gray-400">Sunucu Tipi</label>
                    <select name="instance_type" id="instance_type" class="mt-1 block w-full bg-gray-900 border border-gray-700 rounded p-2 text-gray-300" required>
                        <option value="t2.micro">t2.micro (Ücretsiz Katman)</option>
                        <!-- Diğer seçenekler -->
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded">
                    Oluştur
                </button>
            </form>

            <!-- Yükleme ekranı ve ilerleme çubuğu -->
            <div id="loader" class="hidden mt-6">
                <h2 class="text-white">Sunucu Oluşturuluyor...</h2>
                <div id="progress" class="w-full bg-gray-300 rounded h-4 mt-2">
                    <div id="progress-bar" class="bg-green-500 h-4 rounded" style="width: 0%"></div>
                </div>
                <p id="status-text" class="text-white mt-2">Adım 1: Başlatılıyor...</p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('server-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Formun varsayılan gönderimini engelle
            const formData = new FormData(this);
            const progressBar = document.getElementById('progress-bar');
            const statusText = document.getElementById('status-text');
            let currentStep = 0;

            // Yükleme ekranını göster
            document.getElementById('loader').classList.remove('hidden');

            fetch('{{ route('servers.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            })
            .then(response => response.json())
            .then(data => {
                const steps = data.steps;
                function updateProgress() {
                    if (currentStep < steps.length) {
                        statusText.textContent = steps[currentStep];
                        const progressPercent = ((currentStep + 1) / steps.length) * 100;
                        progressBar.style.width = progressPercent + '%';
                        currentStep++;

                        setTimeout(updateProgress, 2000); // Her adım için 2 saniye bekle
                    } else {
                        document.getElementById('loader').style.display = 'none';
                        if (data.error) {
                            alert('Sunucu oluşturulurken hata oluştu!');
                        } else {
                            alert('Sunucu oluşturma işlemi tamamlandı!');
                            window.location.href = '{{ route('servers.index') }}'; // Sunucular sayfasına yönlendir
                        }
                    }
                }
                updateProgress();
            })
            .catch(error => console.error('Hata:', error));
        });
    </script>
</x-app-layout>
