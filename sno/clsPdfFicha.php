<?php
require_once("fpdf.php");
class clsPdfFicha extends FPDF{

	function Header(){
		$this->Image('banner.jpg',5,5,205, 30);
		// Arial bold 15
		$this->SetFont('Times','B',12);
		// Movernos a la derecha
						// Salto de línea
				$this->Ln();
		}
		
		//Pie de página
		public function Footer(){
			//Posición: a 2 cm del final
			$this->SetY(-20);
			//Arial italic 8
			$this->SetFont("Arial","I",10);
			//Dirección
			$this->Cell(0,5,utf8_decode("ACCION CENTRAL.- Carretera Nacional Vía Turen, Sector E, Planta Arroz del Alba, S.A. Piritu."),0,1,"C");
			$this->Cell(0,5,utf8_decode("Estado Portuguesa. Rif-G-20008205-4  Teléfono 0256-3361455"),0,1,"C");
			//Número de página
		}
	function TablaColores($header, $base, $resp, $comp, $pro, $hijo, $hogar){
	//	$this->setX(150);
		$this->setY(98);
	//	$this->SetMargins(20, 25 , 20); 
		//Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(157,152,152);
		$this->SetTextColor(0);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('Arial','B',12);
		//Cabecera

		for($i=0;$i<count($header);$i++)
		$this->Cell(70,7,$header[$i],1,0,'C',1);
		$this->Ln();

		//PIE DE PAGINA

		$total = $base+$pro+$hijo+$hogar+$comp+$resp;
		$footer = array('Total Salario Integral',$total." Bsf" );

		//Restauración de colores y fuentes
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		//Datos

		$fill=false;
		

		$this->Cell(70,7,utf8_decode("Sueldo Básico"),'LR',0,'L',$fill);
		$this->Cell(70,7, $base." Bsf",'LR',0,'C',$fill);
		$this->Ln();
		$fill=true;
		$this->Cell(70,7,"Prima Responsabilidad",'LR',0,'L',$fill);
		$this->Cell(70,7, $resp." Bsf" ,'LR',0,'C',$fill);
		$this->Ln();
		$fill=false;
		$this->Cell(70,7,"Prima Profesional",'LR',0,'L',$fill);
		$this->Cell(70,7, $pro." Bsf",'LR',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;
		$this->Cell(70,7,"Prima por Hijo",'LR',0,'L',$fill);
		$this->Cell(70,7, $hijo." Bsf" ,'LR',0,'C',$fill);
		$this->Ln();
		$fill=false;
		$this->Cell(70,7,"Prima por Hogar",'LR',0,'L',$fill);
		$this->Cell(70,7, $hogar." Bsf",'LR',0,'C',$fill);
		$this->Ln();
		$fill=true;
		$this->Cell(70,7,"Prima Complementaria",'LR',0,'L',$fill);
		$this->Cell(70,7, $comp." Bsf",'LR',0,'C',$fill);
		$this->Ln();
		//ACAMBIANDO LO COLORES PARA EL PIE DE PAGINA
		$this->SetFillColor(157,152,152);
		$this->SetTextColor(0);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('Arial','B',12);


		for($i=0;$i<count($footer);$i++)
		$this->Cell(70,7,$footer[$i],1,0,'C',1);

		$this->Cell(120,0,'','L');
	}
}
?>
