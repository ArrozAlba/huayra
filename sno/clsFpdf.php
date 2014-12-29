<?php
   
	require_once("fpdf.php");
	class clsFpdf extends FPDF
	{
			
		//Cabecera de página
		public function Header()
		{
			
			$this->Image('../imagenes/cabezeraC.JPG',15,8,180,11);
			$this->SetY(20);			
		}
		
		
		//Pie de página
		public function Footer()
		{
			//Posición: a 2 cm del final
			$this->SetY(-20);
			//Arial italic 8
			$this->SetFont("Arial","I",6);
			//Dirección
			$this->Cell(0,5,utf8_decode("Direccion del Hilarion "),0,1,"C");
			//Número de página
			$this->Cell(0,5,utf8_decode("Página ").$this->PageNo()."/{nb}",0,1,"C");
			//Fecha
			$lcFecha=date("d/m/Y  h:m a");
			$this->Cell(0,3,$lcFecha,0,0,"C");
		}
		
		
	}
?>
