<?php

namespace App\Services;

use Aws\Ec2\Ec2Client;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;

class AWSService
{
    protected $ec2Client;

    public function __construct()
    {
        $this->ec2Client = new Ec2Client([
            'version' => 'latest',
            'region' => config('services.aws.region'),
            'credentials' => [
                'key' => config('services.aws.key'),
                'secret' => config('services.aws.secret'),
            ],
        ]);
    }

    public function getEC2Instances()
    {
        $instances = [];

        try {
            $result = $this->ec2Client->describeInstances();

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
            Log::error('AWS EC2 hatası: ' . $e->getMessage());
            throw $e;
        }

        return $instances;
    }

    public function createEC2Instance($serverName, $instanceType)
    {
        try {
            $result = $this->ec2Client->runInstances([
                'ImageId' => 'ami-0dd35f81b9eeeddb1',
                'InstanceType' => $instanceType,
                'MinCount' => 1,
                'MaxCount' => 1,
                'KeyName' => 'my-key-pair',
            ]);

            $instanceId = $result['Instances'][0]['InstanceId'];

            $this->ec2Client->waitUntil('InstanceRunning', [
                'InstanceIds' => [$instanceId],
            ]);

            $instanceInfo = $this->ec2Client->describeInstances([
                'InstanceIds' => [$instanceId],
            ]);

            $publicIp = $instanceInfo['Reservations'][0]['Instances'][0]['PublicIpAddress'] ?? 'TBD';

            return [
                'InstanceId' => $instanceId,
                'PublicIpAddress' => $publicIp,
            ];
        } catch (AwsException $e) {
            Log::error('AWS EC2 oluşturma hatası: ' . $e->getMessage());
            throw $e;
        }
    }

    public function startEC2Instance($instanceId)
    {
        try {
            $this->ec2Client->startInstances([
                'InstanceIds' => [$instanceId],
            ]);
            $this->ec2Client->waitUntil('InstanceRunning', [
                'InstanceIds' => [$instanceId],
            ]);
            Log::info('EC2 örneği başarıyla başlatıldı.', ['instanceId' => $instanceId]);
        } catch (AwsException $e) {
            Log::error('AWS EC2 başlatma hatası: ' . $e->getMessage());
            throw $e;
        }
    }

    public function stopEC2Instance($instanceId)
    {
        try {
            $this->ec2Client->stopInstances([
                'InstanceIds' => [$instanceId],
            ]);
            $this->ec2Client->waitUntil('InstanceStopped', [
                'InstanceIds' => [$instanceId],
            ]);
            Log::info('EC2 örneği başarıyla durduruldu.', ['instanceId' => $instanceId]);
        } catch (AwsException $e) {
            Log::error('AWS EC2 durdurma hatası: ' . $e->getMessage());
            throw $e;
        }
    }

    public function restartEC2Instance($instanceId)
    {
        try {
            $this->stopEC2Instance($instanceId);
            $this->startEC2Instance($instanceId);
            Log::info('EC2 örneği başarıyla yeniden başlatıldı.', ['instanceId' => $instanceId]);
        } catch (AwsException $e) {
            Log::error('AWS EC2 yeniden başlatma hatası: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getEC2InstanceStatus($instanceId)
    {
        try {
            $result = $this->ec2Client->describeInstances([
                'InstanceIds' => [$instanceId],
            ]);
    
            $state = $result['Reservations'][0]['Instances'][0]['State']['Name'];
            return $state;
        } catch (AwsException $e) {
            Log::error('AWS EC2 durum hatası: ' . $e->getMessage());
            throw $e;
        }
    }    
}
