<?php


namespace App\utils;


class CurlCobain
{
    public $url;
    public $method;
    public $data;
    public $headers;
    public $cookies;
    public $requireSSL = false;
    private $ch;

    /**
     * CurlCobain constructor.
     * @param $url
     * @param $method
     */
    public function __construct($url, $method = 'GET')
    {
        $this->ch = curl_init();
        $this->basicSetUp();
        $this->url = $url;
        $this->method = $method;
    }
    public function enableSSL(){
        $this->requireSSL = true;
        $this->setCurlOption( CURLOPT_SSL_VERIFYPEER, $this->requireSSL);
    }
    public function disableSSL(){
        $this->requireSSL = false;
        $this->setCurlOption( CURLOPT_SSL_VERIFYPEER, $this->requireSSL);
    }

    private function basicSetUp():void
    {
        $this->setCurlOption(CURLOPT_URL,$this->url);
        $this->setCurlOption( CURLOPT_RETURNTRANSFER, true); //Get text instead of void
        $this->setCurlOption( CURLOPT_SSL_VERIFYHOST, $this->requireSSL);
        $this->setCurlOption( CURLOPT_SSL_VERIFYPEER, $this->requireSSL);
    }

    public function setCurlOption($option,$value):void{
        curl_setopt($this->ch,$option,$value);
    }


}
