<?php

namespace App\Jobs;

use App\Models\Server;
use App\Services\AWSService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateServerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $serverName;
    protected $instanceType;

    public function __construct($serverName, $instanceType)
    {
        $this->serverName = $serverName;
        $this->instanceType = $instanceType;
    }

    public function handle(AWSService $awsService)
    {
        try {
            // EC2 örneği oluştur
            $instanceDetails = $awsService->createEC2Instance($this->serverName, $this->instanceType);

            // Oluşturulan instance ID ve IP adresini al
            $instanceId = $instanceDetails['InstanceId'];
            $publicIp = $instanceDetails['PublicIpAddress'];

            // Sunucu modelini kaydet
            $server = new Server();
            $server->user_id = Auth::id();
            $server->server_name = $this->serverName;
            $server->ip_address = $publicIp;
            $server->status = 'active';
            $server->capacity = 1;
            $server->type = $this->instanceType;
            $server->instance_id = $instanceId; // Eğer bu alan modelde yoksa ekleyin
            $server->save();

            Log::info('Sunucu başarıyla oluşturuldu ve kaydedildi.', ['instanceId' => $instanceId]);

        } catch (\Exception $e) {
            Log::error('Sunucu oluşturulurken hata: ' . $e->getMessage());
            throw $e;
        }
    }
}
