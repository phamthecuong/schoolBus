<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Alsofronie\Uuid\Uuid32ModelTrait;
use LIBRESSLtd\LBForm\Traits\LBDatatableTrait;
use App\Models\Push_application;
use GuzzleHttp\Client;
use App\Models\Push_notification_sent;

class Push_notification extends Model
{
    use Uuid32ModelTrait, LBDatatableTrait;
    protected $fillable = ['status_id'];

    public function send()
    {
        if (!$this->device)
        {
            Push_notification_sent::whereId($this->id)->update(["status_id" => 3]);
        }
        if ($this->device->application->type_id == 1)
        {
            $this->sendIOS();
            // $this->sendOnesignal();
        }
        else
        {
            $this->sendFCM();
        }
    }

    public function sendFCM()
    {
        $client = new Client();
        $headers = ['Content-Type' => 'application/json', 'Authorization' => 'key='.$this->device->application->server_key];
        $body = [
            "data" => [
                "title" => $this->title,
                "message" => $this->message,
                "type" => $this->type,
                "conversation_id" => $this->conversation_id
            ], 
            "to" => $this->device->device_token
        ];
        $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', [
            "headers" => $headers, 
            "json" => $body,
            'http_errors' => false
        ]);

        $object = json_decode($response->getBody());
        if ($response->getStatusCode() == 200 && $object)
        {
            if ($object->success == 1)
            {
                Push_notification_sent::whereId($this->id)->update(["status_id" => 2]);
            }
            else
            {
                Push_notification_sent::whereId($this->id)->update(["status_id" => 3]);
            }
        }
        else
        {
            Push_notification_sent::whereId($this->id)->update(["status_id" => 3]);
        }
    }

    public function sendIOS()
    {
        $client = new Client();
        $headers = ['Content-Type' => 'application/json', 'Authorization' => 'Basic OGRhMDgxMWEtNDkzOS00M2JlLThkYTMtZGU5NGUxZjJmY2Y2'];
        $body = [
            "app_id" => "2b9d836d-9c53-481b-a45c-939307a768c7",
            "contents" => [
                "en" => $this->message
            ],
            "headings" => [
                "en" => $this->title
            ],
            "include_player_ids" => [$this->device->device_token]
        ];
        $response = $client->request('POST', 'https://onesignal.com/api/v1/notifications', [
            "headers" => $headers, 
            "json" => $body,
            'http_errors' => false
        ]);

        $object = json_decode($response->getBody());
    }


    function sendHTTP2Push($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token)
    {
        // url (endpoint)
        $url = "{$http2_server}/3/device/{$token}";
     
        // certificate
        $cert = realpath($apple_cert);
     
        // headers
        $headers = array(
            "apns-topic: {$app_bundle_id}",
            "User-Agent: My Sender"
        );
     
        // other curl options
        curl_setopt_array($http2ch, array(
            CURLOPT_URL => $url,
            CURLOPT_PORT => 443,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $message,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSLCERT => $cert,
            CURLOPT_HEADER => 1
        ));
     
        // go...
        $result = curl_exec($http2ch);
        if ($result === FALSE) {
          throw new Exception("Curl failed: " .  curl_error($http2ch));
        }
     
        // get response
        $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
     
        return $status;
    }

    public function payload($application)
    {
        if ($application->type_id == 1)
        {
            // ios
        }
        else
        {
            // android
        }
    }

    // relationship
    public function device()
    {
        return $this->belongsTo("App\Models\Push_device", "device_id");
    }
}