<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pdf extends CI_Controller {

	/**
	 * Example: FPDF 
	 *
	 * Documentation: 
	 * http://www.fpdf.org/ > Manual
	 *
	 */
	public function index() {	
		$this->load->library('fpdf_gen');
		
		$this->fpdf->SetFont('Arial','',13);
		$this->fpdf->Text(20,20,'blocco lava piano cuba simples');
		$this->fpdf->SetFont('Arial','',11);
		$this->fpdf->Text(240,20,utf8_decode('ficha técnica de venda'));


		// Linhas Rodapé
		$this->fpdf->Line(15, 183, 284, 183);
		$this->fpdf->Line(15, 199, 284, 199);
		$this->fpdf->Line(45, 191, 284, 191);

		$this->fpdf->Line(15, 183, 15, 199);
		$this->fpdf->Line(284, 183, 284, 199);
		$this->fpdf->Line(45, 183, 45, 199);

		// Conteúdo Rodapé
		$this->fpdf->SetFont('Arial','',7);
		$this->fpdf->Text(47,186,'nome do produto');
		$this->fpdf->Text(111,186,'tamanho');
		$this->fpdf->Text(167,186,'base utilizada');
		$this->fpdf->Text(213,186,'ambiente');
		$this->fpdf->Text(256,186,'data');

		$this->fpdf->Text(47,194,'cliente');
		$this->fpdf->Text(111,194,'pedido');
		$this->fpdf->Text(167,194,utf8_decode('código'));
		$this->fpdf->Text(213,194,'assinatura do cliente');

		$this->fpdf->SetFont('Arial','',9);
		$this->fpdf->Text(47,190,'Blocco Lava Piano');
		$this->fpdf->Text(111,190,'2400 x 500 x 150');
		$this->fpdf->Text(167,190,'Bianco Covelano 60x120');
		$this->fpdf->Text(213,190,'Banheiro');
		$this->fpdf->Text(256,190, date('d/m/Y'));

		$this->fpdf->Text(47,198,'Carlos Eduardo de Azevedo');
		$this->fpdf->Text(111,198,'2336821-A');
		$this->fpdf->Text(167,198,'D65S88S4AEDFFE8');

		$this->fpdf->Image('logo_pboff.png', 17, 185, 26, 12);


		// Imagem 3D
		$this->fpdf->Image('3d.png', 20, 30, 95, 100);

		// Vistas
		$this->fpdf->SetFont('Arial','B',10);

		$this->fpdf->Image('frontal.png', 115, 30, 120, 35);
		$this->fpdf->Text(130, 70, 'vista frontal');


		$this->fpdf->Image('lateral.png', 240, 30, 45, 35);
		$this->fpdf->Text(250, 70, 'vista lateral');

		$this->fpdf->Image('superior.png', 115, 75, 120, 60);
		$this->fpdf->Text(130, 135, 'vista superior');

		$this->fpdf->SetFont('Arial','',7);

		// Descritivo técnico
		$descritivo = utf8_decode("Cuba = simples central\nFrontão = 150mm\nPosicionamento do frontão = posterior e direita");
		$this->fpdf->SetXY(120, 160);
		$this->fpdf->MultiCell(80, 3, $descritivo, 0, "R");

		// Observações
		$this->fpdf->SetFont('Arial','B',7);
		$this->fpdf->Text(20,145,utf8_decode('Observações'));
		$this->fpdf->SetFont('Arial','',7);
		$observacoes = utf8_decode("Altura sugerida para fixação da bancada = 90cm ateé o final da cuba.\n\nTodas as bancadas Blocco possuem fechamento na parte frontal e nas laterais esquerda e direita, em porcelanato. Na parte inferior utiliza-se tamponamento removível em alumínio.\n\nTodas as cubas possuem fundo diamantado em inox");
		$this->fpdf->SetXY(20, 150);
		$this->fpdf->MultiCell(100, 3, $observacoes, 0, "L");


		// Cotas
		$cotas = array(
			'A' => '2400mm',
			'B' => '500mm',
			'C' => '100mm',
			'D' => '480mm',
			'E' => '860mm',
			'F' => '180mm',
			'G' => '60mm',
			'H' => '1200mm',
			'I' => '120mm',
			'J' => '150mm',
			'K' => '50mm',
			'L' => '810mm',
			'M' => '680mm'
		);

		$x = 230;
		$y = $y_inicial = 140;
		$linhas_por_coluna = 10;
		$multiplicador = 0;

		$this->fpdf->SetFont('Arial','',9);

		$i = 1;

		foreach($cotas as $label => $valor){

			$this->fpdf->Text($x, $y, $label);
			$this->fpdf->Text($x+4, $y, $valor);

			$this->fpdf->Line($x-1, $y+1, $x+17, $y+1);

			$y += 4;
			$i++;

			if(count($cotas) == $i+($multiplicador*10)){
				$this->fpdf->Line($x+3, $y_inicial-3, $x+3, $y-3+($multiplicador*8));				
			}

			if($i == $linhas_por_coluna){

				$this->fpdf->Line($x+3, $y_inicial-3, $x+3, $y-3);

				$x = $x+25;
				$y = $y_inicial;
				$i = 1;
				$multiplicador += 1;
			}

		}

		echo $this->fpdf->Output('portobello.pdf','S');
	}
}
