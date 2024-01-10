<?php

namespace Ispahbod\LimoSms\core;

class Response
{
    protected array $response;

    public function __construct($response)
    {
        $this->response = $response;
    }
    public function isSuccessful()
    {
        if (isset($this->response['Success']) && $this->response['Success'] == true) {
            return true;
        }
        return false;
    }
    public function getMessage()
    {
        if (isset($this->response['Message'])) {
            return $this->response['Message'];
        }
        return null;
    }
    public function getMessageId()
    {
        if (isset($this->response['MessageId'])) {
            return $this->response['MessageId'];
        }
        return null;
    }
}