<?php

class Dropbox {

	var $filepath;
	var $filename;
	var $remotepath;

	public function __construct(){
		$this->_CI = &get_instance();
		$this->_CI->config->load('dropbox');

	}

	public function addfile($filename){

		$this->filepath = $filename;

		$this->filename = basename($this->filepath);

	}

	public function setremote($path = '/'){

		$this->remotepath = $path;

	}

    public function upload(){

		$url = $this->_CI->config->item('dropbox-uploadURL');

    	$headers = array(
    		'Authorization: Bearer '.$this->_CI->config->item('dropbox-accessToken'),
    		'Dropbox-API-Arg: {"path": "'.$this->remotepath.$this->filename.'","mode": "add","autorename": true,"mute": false,"strict_conflict": false}',
    		'Content-Type: application/octet-stream'
    	);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($this->filepath)); 
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	

        $retorno = curl_exec($ch);

        return json_decode($retorno);

    }

}