<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Register extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function index_post(){

        $this->load->model('Sys_usuario', 'usuario');
        $this->load->model('Atd_protocolo', 'protocolo');
        $this->load->model('Atd_movimentacao', 'movimentacao');

        // Verifica a existência de um status
        if(!$this->post('status')){
            $erro = $this->Error_model->get_error('011', 'Campo "status"');
            $this->response($erro, REST_Controller::HTTP_BAD_REQUEST);
        }

        $status = $this->post('status');

        // Verifica a existência de um código de protocolo
        if(strlen($this->post('protocolo')) != 32){
            $erro = $this->Error_model->get_error('011', 'Protocolo inválido');
            $this->response($erro, REST_Controller::HTTP_BAD_REQUEST);
        }

        $protocolo = $this->protocolo->get(array('numero' => $this->post('protocolo')));

        if(!$protocolo){
            $erro = $this->Error_model->get_error('040', "Protocolo desconhecido");
            $this->response($erro, REST_Controller::HTTP_NOT_FOUND);
        }

        // Normaliza o conteúdo da requisição. Apenas há conteúdo se for 
        // o caso de retorno para o usuário
        if($this->post('content')){
            $content = $this->post('content');
        } else {
            $content = null;
        }

        //Verifica se já existe movimentação igual para o mesmo protocolo
        if($this->movimentacao->get(array('cd_atd_protocolo' => $protocolo->id, 'status_cod' => $status))){
            $erro = $this->Error_model->get_error('041');
            $this->response($erro, REST_Controller::HTTP_BAD_REQUEST);
        }

        $data_movimentacao = array(
            'datahora_cadastro' => date('Y-m-d H:i:s'),
            'cd_atd_protocolo' => $protocolo->id,
            'timestamp' => time(),
            'status_cod' => $status,
            'status_text' => $this->movimentacao->transaction_status($status),
            'data' => $content
        );

        $id = $this->movimentacao->add($data_movimentacao);

        if(!$id){
            $erro = $this->Error_model->get_error('041', 'Não foi possível gerar movimentação do protocolo');
            $this->response($erro, REST_Controller::HTTP_BAD_REQUEST);
        }

        $data_movimentacao['id'] = $id;

        $this->response($data_movimentacao, REST_Controller::HTTP_OK);

    }

}