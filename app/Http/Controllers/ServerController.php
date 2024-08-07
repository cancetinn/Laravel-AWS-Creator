<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\CreateServerJob;
use App\Services\AWSService;
use Illuminate\Support\Facades\Log;

class ServerController extends Controller
{
    protected $awsService;

    public function __construct(AWSService $awsService)
    {
        $this->awsService = $awsService;
    }

    public function dashboard()
    {
        // Dashboard için gerekli verileri hazırlayın
        $servers = Server::where('user_id', Auth::id())->get();
        return view('dashboard', compact('servers'));
    }

    public function index()
    {
        $servers = Server::where('user_id', Auth::id())->get();

        try {
            $instances = $this->awsService->getEC2Instances();
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->withErrors('Sunucular listelenirken hata oluştu: ' . $e->getMessage());
        }

        return view('servers.index', compact('servers', 'instances'));
    }

    public function create()
    {
        return view('servers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'server_name' => 'required|string|max:255',
            'instance_type' => 'required|string',
        ]);

        try {
            $job = new CreateServerJob($request->server_name, $request->instance_type);
            dispatch($job);

            return response()->json(['message' => 'Sunucu başarıyla oluşturuldu!', 'error' => false]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Sunucu oluşturulurken hata oluştu: ' . $e->getMessage(), 'error' => true]);
        }
    }

    public function viewMetrics($instanceId)
    {
        return view('servers.show', ['instanceId' => $instanceId]);
    }

    public function getServerStatuses()
    {
        try {
            $instances = $this->awsService->getEC2Instances();
            return response()->json(['instances' => $instances]);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function startInstance($instanceId)
    {
        try {
            $this->awsService->startEC2Instance($instanceId);
            return response()->json(['message' => 'Sunucu başarıyla başlatıldı!', 'error' => false]);
        } catch (\Exception $e) {
            Log::error('Sunucu başlatma hatası: ' . $e->getMessage());
            return response()->json(['message' => 'Sunucu başlatılırken hata oluştu: ' . $e->getMessage(), 'error' => true]);
        }
    }

    public function stopInstance($instanceId)
    {
        try {
            $this->awsService->stopEC2Instance($instanceId);
            return response()->json(['message' => 'Sunucu başarıyla durduruldu!', 'error' => false]);
        } catch (\Exception $e) {
            Log::error('Sunucu durdurma hatası: ' . $e->getMessage());
            return response()->json(['message' => 'Sunucu durdurulurken hata oluştu: ' . $e->getMessage(), 'error' => true]);
        }
    }

    public function restartInstance($instanceId)
    {
        try {
            $this->awsService->restartEC2Instance($instanceId);
            return response()->json(['message' => 'Sunucu başarıyla yeniden başlatıldı!', 'error' => false]);
        } catch (\Exception $e) {
            Log::error('Sunucu yeniden başlatma hatası: ' . $e->getMessage());
            return response()->json(['message' => 'Sunucu yeniden başlatılırken hata oluştu: ' . $e->getMessage(), 'error' => true]);
        }
    }

    // Add this method
    public function getInstanceStatus($instanceId)
    {
        try {
            $status = $this->awsService->getEC2InstanceStatus($instanceId);
            return response()->json(['status' => $status, 'error' => false]);
        } catch (\Exception $e) {
            Log::error('Sunucu durum hatası: ' . $e->getMessage());
            return response()->json(['error' => true, 'message' => 'Sunucu durumu alınamadı.']);
        }
    }
}
