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
            <div id="message" class="mt-4"></div>
        </div>
    </div>

    <script>
    document.getElementById('server-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Formun varsayılan gönderimini engelle
        const formData = new FormData(this);

        fetch('{{ route('servers.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (!data.error) {
                document.getElementById('message').innerHTML = '<div class="bg-green-500 text-white p-4 rounded">' + data.message + '</div>';
                setTimeout(() => {
                    window.location.href = '{{ route('servers.index') }}';
                }, 2000); // 2 saniye sonra yönlendirme
            } else {
                console.error('Sunucu oluşturulurken hata oluştu:', data.message);
                document.getElementById('message').innerHTML = '<div class="bg-red-500 text-white p-4 rounded">Hata: ' + data.message + '</div>';
            }
        })
        .catch(error => {
            console.error('Sunucu oluşturulurken AJAX hatası:', error);
            document.getElementById('message').innerHTML = '<div class="bg-red-500 text-white p-4 rounded">AJAX hatası oluştu!</div>';
        });
    });
    </script>
</x-app-layout>
