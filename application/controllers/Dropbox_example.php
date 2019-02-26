<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dropbox_example extends CI_Controller {


	public function index(){


        $this->load->library('dropbox');

        $this->dropbox->addfile('./filename.txt');
        $this->dropbox->setremote('/');
        $this->dropbox->upload();


	}

	
}
