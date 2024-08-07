<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Aws\Ec2\Ec2Client;
use Aws\Exception\AwsException;

class ServerController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function index()
    {
        // Kullanıcının sunucularını al
        $servers = Server::where('user_id', Auth::id())->get();

        // AWS EC2 Client oluştur
        $ec2Client = new Ec2Client([
            'version' => 'latest',
            'region'  => config('services.aws.region'),
            'credentials' => [
                'key'    => config('services.aws.key'),
                'secret' => config('services.aws.secret'),
            ],
        ]);

        $instances = [];

        try {
            // Tüm EC2 örneklerini al
            $result = $ec2Client->describeInstances();

            foreach ($result['Reservations'] as $reservation) {
                foreach ($reservation['Instances'] as $instance) {
                    $instances[] = [
                        'InstanceId' => $instance['InstanceId'],
                        'InstanceType' => $instance['InstanceType'],
                        'State' => $instance['State']['Name'],
                        'PublicIpAddress' => $instance['PublicIpAddress'] ?? 'N/A',
                    ];
                }
            }
        } catch (AwsException $e) {
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
        set_time_limit(0);
    
        $steps = [];
    
        // Adım 1: Veritabanı kaydı oluşturuluyor
        $steps[] = "Adım 1: Veritabanı kaydı oluşturuluyor...";
        sleep(2); // Simülasyon için bekleme süresi
    
        // Formu doğrula
        $request->validate([
            'server_name' => 'required|string|max:255',
            'instance_type' => 'required|string',
        ]);
    
        // AWS EC2 Client oluştur
        $ec2Client = new Ec2Client([
            'version' => 'latest',
            'region'  => config('services.aws.region'),
            'credentials' => [
                'key'    => config('services.aws.key'),
                'secret' => config('services.aws.secret'),
            ],
            'http'    => [
                'debug' => false,
            ],
        ]);
    
        try {
            // Adım 2: API çağrısı yapılıyor
            $steps[] = "Adım 2: API çağrısı yapılıyor...";
            sleep(2); // Simülasyon için bekleme süresi
    
            $result = $ec2Client->runInstances([
                'ImageId' => 'ami-0dd35f81b9eeeddb1', // Doğru AMI ID'sini kontrol edin
                'InstanceType' => $request->instance_type,
                'MinCount' => 1,
                'MaxCount' => 1,
                'KeyName' => 'my-key-pair', // Doğru key pair adı
                // 'SecurityGroupIds' => ['sg-12345678'], // Gerekirse güvenlik grubu ekleyin
                // 'SubnetId' => 'subnet-12345678', // Gerekirse subnet ekleyin
            ]);
    
            $instanceId = $result['Instances'][0]['InstanceId'];
    
            // Adım 3: Dosya indiriliyor
            $steps[] = "Adım 3: Dosya indiriliyor...";
            sleep(2); // Simülasyon için bekleme süresi
    
            $ec2Client->waitUntil('InstanceRunning', [
                'InstanceIds' => [$instanceId],
            ]);
    
            $server = new Server();
            $server->user_id = Auth::id();
            $server->server_name = $request->server_name;
            $server->ip_address = 'TBD'; // IP adresi AWS'den alınacak
            $server->capacity = 1;
            $server->type = $request->instance_type;
            $server->status = 'active';
            $server->aws_instance_id = $instanceId;
            $server->save();
    
            $steps[] = "Sunucu oluşturma tamamlandı!";
        } catch (AwsException $e) {
            $steps[] = 'Sunucu oluşturulurken hata oluştu: ' . $e->getMessage();
            return response()->json(['steps' => $steps, 'error' => true]);
        }
    
        return response()->json(['steps' => $steps, 'error' => false]);
    }
}



