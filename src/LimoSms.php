<?php

namespace Ispahbod\LimoSms;

use GuzzleHttp\Client;
use Ispahbod\LimoSms\core\Response;

class LimoSms
{
    const BASE_URL = "https://api.limosms.com/api/";
    const SSL = false;
    protected Client $client;
    protected string $token;
    protected string $SenderNumber;
    protected string $Message;
    protected int $OtpId;
    protected bool $SendToBlocksNumber;
    protected float $SendTimeSpan;
    protected $MobileNumber;
    protected array $ReplaceToken = [];

    public function __construct($token)
    {
        $this->client = new Client();
        $this->token = $token;
    }

    public function setSender($SenderNumber)
    {
        $this->SenderNumber = $SenderNumber;
        return $this;
    }

    public function setMessage($Message)
    {
        $this->Message = $Message;
        return $this;

    }

    public function SendToBlocksNumber($SendToBlocksNumber = true)
    {
        $this->SendToBlocksNumber = $SendToBlocksNumber;
        return $this;

    }

    public function setTimestamp($SendTimeSpan)
    {
        $this->SendTimeSpan = $SendTimeSpan;
        return $this;

    }

    public function setPhoneNumbers($phoneNumber)
    {
        $this->MobileNumber[] = $phoneNumber;
        return $this;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->MobileNumber = $phoneNumber;
        return $this;
    }

    public function setReplaces($ReplaceToken)
    {
        $this->ReplaceToken = $ReplaceToken;
        return $this;

    }

    public function setPatternId($OtpId)
    {
        $this->OtpId = $OtpId;
        return $this;

    }

    public function send()
    {
        $data = [];
        $json = array_filter([
            'SenderNumber' => $this->SenderNumber,
            'OtpId' => $this->OtpId ?? null,
            'ReplaceToken' => $this->ReplaceToken ?? null,
            'Message' => $this->Message ?? null,
            'MobileNumber' => $this->MobileNumber,
            'SendTimeSpan' => $this->SendTimeSpan ?? null,
            'SendToBlocksNumber' => $this->SendToBlocksNumber ?? false,
        ]);
        $method = empty($this->OtpId) ? "sendsms" : "sendpatternmessage";
        try {
            $response = $this->client->post(self::BASE_URL . $method, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'cache-control' => 'no-cache',
                    'Accept' => 'application/json',
                    'ApiKey' => "$this->token",
                ],
                'json' => $json,
                'verify' => self::SSL
            ]);
            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                $body = $response->getBody();
                $content = $body->getContents();
                $data = json_decode($content, true);
            }
        } catch (\Exception $e) {

        }
        return new Response($data);
    }

    public function getCredit()
    {
        $response = $this->client->post(self::BASE_URL . 'getcurrentcredit', [
            'headers' => [
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'Accept' => 'application/json',
                'ApiKey' => "$this->token",
            ],
            'json' => $json,
            'verify' => self::SSL
        ]);
        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            $body = $response->getBody();
            $content = $body->getContents();
            $data = json_decode($content, true);
        }
        return $data;
    }
}
