<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Formato de salida  de la Orden de Compra
//  ORGANISMO: ARROZ DEL ALBA
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_estcondat,$as_numordcom,$ad_fecordcom,$as_coduniadm,$as_denuniadm, $as_codfuefin,
	                                    $as_denfuefin,$as_codigo,$as_nombre,$as_conordcom,$as_rifpro,$as_diaplacom,$as_dirpro,
									    $ls_forpagcom,$ls_orgrif,$as_telpro,$as_obscom,$as_nomdep,$as_dirdep,$as_fecent,$as_conent,$as_orgnom,
										$as_orgtit,$ls_monant,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_estcondat  ---> tipo de la orden de compra
		//	    		   as_numordcom ---> numero de la orden de compra
		//	    		   ad_fecordcom ---> fecha de registro de la orden d compra
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		
		$io_pdf->addText(200,767,10,"<b>".$as_orgnom."<b>"); 
	        $dirempresa="Ctra Principal  v�a  a  Turen  Local  antigua sede Cargill de Venezuela, Sector Piritu Edo Portuguesa";
                $telecorreo="Tel�fonos: 0256-3361333/3361377  unidad.compras@arrozdelalba.gob.ve";
                $io_pdf->addText(115,757,10,"<b>".$dirempresa."<b>");
                $io_pdf->addText(168,747,10,"<b>".$telecorreo."<b>");
		
		
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(140,705,446,40);
		$io_pdf->line(400,705,400,745);
		$io_pdf->line(400,725,586,725);
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo.jpg',20,715,80,60);
		if(($as_estcondat=="B") || ($as_estcondat=="-") || ($as_estcondat=="")) 
        {
             $ls_titulo="Orden de Compra";	
			 $ls_titulo_grid="Bienes";
        }
        else
        {
             $ls_titulo="Orden de Servicio";
			 $ls_titulo_grid="Servicios";
        }
		$io_pdf->addText(20,700,9,"RIF: ".$ls_orgrif); // Agregar RIF de la Empresa.
		$li_tm=$io_pdf->getTextWidth(14,$ls_titulo);
		$tm=230;
		$io_pdf->addText($tm,720,14,$ls_titulo); // Agregar el t�tulo
		$io_pdf->addText(420,730,10," <b>No.: </b> ".$as_numordcom); // agrega ord comp
		$io_pdf->addText(420,712,10,"<b>Fecha: </b>".$ad_fecordcom); // Agregar 		$io_pdf->addText(554,750,7,date("d/m/Y")); // Agregar la Fecha	
		

                // cuadro inferior
		$io_pdf->line(15,100,586,100);	//HORIZONTAL	
		$io_pdf->addText(30,105,7,"ELABORADO POR"); // Agregar el t�tulo
		$io_pdf->addText(45,45,6,"COMPRAS"); // Agregar el t�tulo
		$io_pdf->line(114,40,114,115);	//VERTICAL	
		$io_pdf->addText(137,105,7,"REVISADO POR"); // Agregar el t�tulo
		$io_pdf->addText(145,45,6,"PRESUPUESTO"); // Agregar el t�tulo
		$io_pdf->line(228,40,228,115);	//VERTICAL		
		$io_pdf->addText(250,105,7,"APROBADO POR"); // Agregar el t�tulo
		$io_pdf->addText(255,45,6,"ADMINISTRACI�N"); // Agregar el t�tulo
		$io_pdf->line(342,40,342,115);	//VERTICAL		
		$io_pdf->addText(367,105,7,"AUTORIZADO POR"); // Agregar el t�tulo
                $io_pdf->addText(375,45,6,"PRESIDENCIA"); // Agregar el t�tulo
                $io_pdf->line(456,40,456,115);	//VERTICAL		
		$io_pdf->addText(498,105,7,"PROVEEDOR"); // Agregar el t�tulo
                $io_pdf->addText(470,45,6,"FIRMA,CEDULA,SELLO Y FECHA"); // Agregar el t�tulo
		$io_pdf->line(15,55,586,55); //HORIZONTAL		
		$io_pdf->rectangle(15,40,571,75);
		$io_pdf->ezSetY(695);
		$la_data = array(array('fila'=>'<b>Proveedor  :</b> '.$as_nombre.' <b> - RIF: </b>'.$as_rifpro),
		            	 array('fila'=>'<b>Direcci�n     : </b> '.$as_dirpro.'<b> - Tel�fono:</b> '.$as_telpro),					   
					     array('fila'=>'<b>Concepto:</b> '.$as_conordcom));

		$la_columna=array('fila'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center'); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);

//para imprimir cuadro y variables de condiciones de entrega		
		$ls_uniadm   = $as_coduniadm."  -  ".$as_denuniadm;
		$la_data[1]  = array('columna1'=>'<b>Dependencia:</b>','columna2'=>'<b>Direcci�n de Entrega</b>','columna3'=>'<b>Condicion de Entrega:</b>');
		$la_data[2]  = array('columna1'=>$as_nomdep,'columna2'=>$as_dirdep,'columna3'=>''.$as_conent);
		$la_columnas = array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config   = array('showHeadings'=>0, // Mostrar encabezados
						     'fontSize' => 9, // Tama�o de Letras
						     'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						     'showLines'=>1, // Mostrar L�neas
						     'shaded'=>0, // Sombra entre l�neas
						     'width'=>570, // Ancho de la tabla
						 	 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 	 'xOrientation'=>'center', // Orientaci�n de la tabla
						 	 'cols'=>array('columna1'=>array('justification'=>'left','width'=>135), // Justificaci�n y ancho de la columna
						 			       'columna2'=>array('justification'=>'left','width'=>300),
									       'columna3'=>array('justification'=>'left','width'=>135))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$ls_fuefin=$as_codfuefin."  -  ".$as_denfuefin;
		$la_data[1] = array('columna1'=>'<b>Fecha de entrega</b>','columna2'=>'<b>Plazo en D�as</b>','columna3'=>'<b>Condici�n de Pago</b>','columna4'=>'<b>Anticipo de Pago</b>');
		$la_data[2] = array('columna1'=>'','columna2'=>$as_diaplacom,'columna3'=>$ls_forpagcom,'columna4'=>$ls_monant);
		$la_columnas=array('columna1'=>'','columna2'=>'','columna3'=>'','columna4'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>142), // Justificaci�n y ancho de la columna
						 		       'columna2'=>array
('justification'=>'left','width'=>142),
					        		       'columna3'=>array
('justification'=>'left','width'=>143), // Justificaci�n y ancho de la columna
					        		       'columna4'=>array
('justification'=>'left','width'=>143))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$li_subtot,$li_totcar,$li_montot,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data ---> arreglo de informaci�n
		//	    		   io_pdf ---> Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle 
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_estmodest, $ls_bolivares;
		if($ls_estmodest==1)
		{
			$ls_titulo_grid="Bienes";
		}
		else
		{
			$ls_titulo_grid="Servicios";
		}
		$io_pdf->ezSetDy(-10);
		$la_datatitulo[1]=array('columna1'=>'<b> Detalle de los '.$ls_titulo_grid.'</b>');
		$la_columnas=array('columna1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatitulo,$la_columnas,'',$la_config);
		unset($la_datatitulo);
		unset($la_columnas);
		unset($la_config);
		
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('renglon'=>'<b>No.</b>',
						   'codigo'=>'C�digo',
						   'denominacion'=>'Denominaci�n',
						   'cantidad'=>'Cant.',
						   'precio'=>'Precio '.$ls_bolivares,
						   'subtotal'=>'Sub-Total '.$ls_bolivares,
						   'cargo'=>'Cargo '.$ls_bolivares,
						   'montot'=>'Total '.$ls_bolivares);
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('renglon'=>array('justification'=>'center','width'=>25),
						 			   'codigo'=>array('justification'=>'center','width'=>55), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>150), // Justificaci�n y ancho de la columna
						 			   'cantidad'=>array('justification'=>'right','width'=>40), // Justificaci�n y ancho de la columna
						 			   'precio'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
						 			   'subtotal'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
						 			   'cargo'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
						 			   'montot'=>array('justification'=>'right','width'=>75))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]  = array('titulo'=>'<b>Sub Total '.$ls_bolivares.'</b>','contenido'=>$li_subtot);
		$la_columnas = array('titulo'=>'','contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('titulo'=>'<b>Cargos '.$ls_bolivares.'</b>','contenido'=>$li_totcar);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		
		$la_data[1]=array('titulo'=>'<b>Total '.$ls_bolivares.'</b>','contenido'=>$li_montot);
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cuentas($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: la_data ---> arreglo de informaci�n
		//	    		   io_pdf ---> Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 21/06/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		global $ls_estmodest, $ls_bolivares;
		if($ls_estmodest==1)
		{
			$ls_titulo="Estructura Presupuestaria";
		}
		else
		{
			$ls_titulo="Estructura Program�tica";
		}
		$la_datatit[1]=array('titulo'=>'<b> Detalle de Presupuesto </b>');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>570))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codestpro'=>'<b>'.$ls_titulo.'</b>',
						   'cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominaci�n</b>',
						   'monto'=>'<b>Total '.$ls_bolivares.'</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('codestpro'=>array('justification'=>'center','width'=>170), // Justificaci�n y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'denominacio'=>array('justification'=>'center','width'=>200), // Justificaci�n y ancho de la columna
									   'monto'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($li_montot,$ls_monlet,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: li_montot  --> Monto total
		//	    		   ls_monlet   //Monto en letras
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: funci�n que imprime los totales
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creaci�n: 21/06/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		global $ls_bolivares;
		
		$io_pdf->ezSetDy(-5);
		$la_data[1] = array('monlet'=>'<b>MONTO TOTAL EN LETRAS ('.$ls_bolivares.')</b>','monnum'=>'<b>MONTO TOTAL ('.$ls_bolivares.')</b>');
		$la_data[2] = array('monlet'=>$ls_monlet,'monnum'=>$li_montot);
		$la_columnas=array('monlet'=>'','monnum'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('monlet'=>array('justification'=>'left','width'=>400),
						               'monnum'=>array('justification'=>'right','width'=>170))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);


				//imprime las clausulas especiales
				$ls_clafiecum=" Al Suscribirse esta orden, se exigir� al beneficiario Fianza de Fiel Cumplimiento Equivalente al ____ %";
				$ls_clafiecum2= "del Monto de la Orden (sin IVA), Otorgada por un Banco o Compa��a de seguros, Notariada y Vigente hasta   la total Recepci�n de la Mercancia.";
		
				$ls_clapenal=" Al Suscribirse esta Orden, se exigir� al Beneficiario Fianza de Anticipo en base a un ____% del monto del Anticipo de la orden (sin IVA),"; 
				$ls_clapenal2="Otorgada por un Banco o Compa��a de seguros, Notariada y Vigente hasta la total Recepci�n de la Mercancia.";
		
				$ls_claesp=" Al Suscribirse esta orden,se har� una retenci�n al beneficiario de un ____% del monto de la orden (sin incluir IVA),"; 
				$ls_claesp2="de conformidad de las previsiones legales que rigen la materia.";
				$ls_clasocial=" Al Suscribirse esta orden, se exigir� al beneficiario de un donativo al equivalente al ____% del monto de la orden (sin IVA),"; 
				$ls_clasocial2="de conformidad de las previsiones legales correspondientes al cumplimiento de la responsabilidad social.";
				

$la_data[1] = array('columna1'=>'<b>FIANZA DE FIEL CUMPLIMIENTO</b>','columna2'=>'<b>FIANZA DE ANTICIPO</b>','columna3'=>'<b>RETENCION PATRONAL</b>','columna4'=>'<b>RESPONSABILIDAD SOCIAL</b>');
		$la_data[2] = array('columna1'=>$ls_clafiecum." ".$ls_clafiecum2,'columna2'=>$ls_clapenal." ".$ls_clapenal2,'columna3'=>$ls_claesp." ".$ls_claesp2,'columna4'=>$ls_clasocial." ".$ls_clasocial2);
		$la_columnas=array('columna1'=>'','columna2'=>'','columna3'=>'','columna4'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>143), // Justificaci�n y ancho de la columna
						 		       'columna2'=>array
('justification'=>'left','width'=>143),
					        		       'columna3'=>array
('justification'=>'left','width'=>142), // Justificaci�n y ancho de la columna
					        		       'columna4'=>array
('justification'=>'left','width'=>142))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);


/////////////////////////imprime otra clausulas especiales//////////////////////////////////////
		$ls_claobligacion="A) Se obliga a ejecutar de manera responsable la presente orden en las condiciones establecidas en la misma.";
		$ls_claobligacion2= " B) Correr�n por cuenta exclusiva de El Proveedor, los gastos de traslados de las maquinarias, equipos y personal requerido para la ejecuci�n del objeto de la presente Orden.";
		
		$ls_clarescision=" EMPRESA MIXTA SOCIALISTA ARROZ DEL ALBA S,A; podr� rescindir unilateralmente la presente Orden en cualquier"; 
		$ls_clarescision2=" momento antes de su vencimiento, sin que El Proveedor tenga derecho a reclamo o al pago de indemnizaci�n alguna.";
		
		$ls_clapenalidad=" De no iniciare la ejecuci�n del servicio, obra o suministro en el tiempo estipulado en la presente Orden, se deber� pagar por cada d�a de retraso el valor equivalente al uno por ciento (1%) del monto total de la misma, hasta un m�ximo"; 
		$ls_clapenalidad2=" del quince por ciento (15%), cuando las penalidades superen este porcentaje se podr� rescindir unilateralmente la Orden y se proceder� a tomar las acciones legales conducentes en contra de El Proveedor.";
				

$la_data[1] = array('columna1'=>'<b>OBLIGACIONES DEL PROVEEDOR</b>','columna2'=>'<b>RESCISION</b>','columna3'=>'<b>PENALIDADES<b>');
$la_data[2] = array('columna1'=>$ls_claobligacion." ".$ls_claobligacion2,'columna2'=>$ls_clarescision." ".$ls_clarescision2,'columna3'=>$ls_clapenalidad." ".$ls_clapenalidad2);
		$la_columnas=array('columna1'=>'','columna2'=>'','columna3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('columna1'=>array('justification'=>'left','width'=>190), // Justifi y ancho de la columna
						 	       'columna2'=>array('justification'=>'left','width'=>190),
					        	       'columna3'=>array('justification'=>'left','width'=>190)));
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabeceramonto_bsf($li_montotaux,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		    Acess: private 
		//	    Arguments: li_montotaux ---> Total de la Orden Bs.F.
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: Funci�n que imprime el total de la Orden de Compra en Bolivares Fuertes.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 25/09/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'<b>Monto Bs.F.</b>','contenido'=>$li_montotaux,);
		$la_columnas=array('titulo'=>'','contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>450), // Justificaci�n y ancho de la columna
						 			   'contenido'=>array('justification'=>'right','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("sigesp_soc_class_report.php");	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../class_folder/class_funciones_soc.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_funciones.php");

	$in           = new sigesp_include();
	$con          = $in->uf_conectar();
	$io_sql       = new class_sql($con);	
	$io_funciones = new class_funciones();	
	$io_fun_soc   = new class_funciones_soc();
	$io_report    = new sigesp_soc_class_report($con);
	$ls_estmodest = $_SESSION["la_empresa"]["estmodest"];
	$ls_orgrif	  = $_SESSION["la_empresa"]["rifemp"];
	$ls_orgnom    = $_SESSION["la_empresa"]["nombre"];
	$ls_orgtit	  = $_SESSION["la_empresa"]["titulo"];

	//Instancio a la clase de conversi�n de numeros a letras.
	require_once("../../shared/class_folder/class_numero_a_letra.php");
	$numalet= new class_numero_a_letra();
	//imprime numero con los valore por defecto
	//cambia a minusculas
	$numalet->setMayusculas(1);
	//cambia a femenino
	$numalet->setGenero(1);
	//cambia moneda
	$numalet->setMoneda("Bolivares");
	//cambia prefijo
	$numalet->setPrefijo("***");
	//cambia sufijo
	$numalet->setSufijo("***");
	$ls_tiporeporte=$io_fun_soc->uf_obtenervalor_get("tiporeporte",1);
	$ls_bolivares="Bs.";
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_soc_class_reportbsf.php");
		$io_report=new sigesp_soc_class_reportbsf();
		$ls_bolivares="Bs.F.";
		$numalet->setMoneda("Bolivares Fuerte");
	}
		
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_numordcom=$io_fun_soc->uf_obtenervalor_get("numordcom","");
	$ls_estcondat=$io_fun_soc->uf_obtenervalor_get("tipord","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$rs_data   = $io_report->uf_select_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido); // Cargar los datos del reporte
	if($lb_valido==false) // Existe alg�n error � no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		$ls_descripcion="Gener� el Reporte de Orden de Compra";
		$lb_valido=$io_fun_soc->uf_load_seguridad_reporte("SOC","sigesp_soc_p_registro_orden_compra.php",$ls_descripcion);
		if($lb_valido)	
		{
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(7.5,4.5,3,3); // Configuraci�n de los margenes en cent�metros
			if ($row=$io_sql->fetch_row($rs_data))
			{
				$io_pdf->ezStartPageNumbers(588,760,8,'','',1);
				$ls_numordcom=$row["numordcom"];
				$ls_estcondat=$row["estcondat"];
				$ls_coduniadm=$row["coduniadm"];
				$ls_denuniadm=$row["denuniadm"];
				$ls_codfuefin=$row["codfuefin"];
				$ls_denfuefin=$row["denfuefin"];
				$ls_diaplacom=$row["diaplacom"];
				$ls_forpagcom=$row["forpagcom"];
				$ls_codpro=$row["cod_pro"];
				$ls_nompro=$row["nompro"];
				$ls_rifpro=$row["rifpro"];
				$ls_dirpro=$row["dirpro"];
				$ls_telpro=$row["telpro"];
				$ld_fecordcom=$row["fecordcom"];
				$ls_obscom=$row["obscom"];
				$ld_monsubtot=$row["monsubtot"];
				$ld_monimp=$row["monimp"];
				$ld_montot=$row["montot"];
				if ($ls_tiporeporte==0)
				   {
					 $ld_montotaux = $row["montotaux"];
					 $ld_montotaux = number_format($ld_montotaux,2,",",".");
				   }
				$ls_nomdep = $row["lugentnomdep"];
				$ls_dirdep = $row["lugentdir"];
                                $ls_fecent=$row["fecent"];
				$ls_concom = $row["concom"];
                                $ls_monant=$row["monant"];   //monto de anticipo de pago
				$numalet->setNumero($ld_montot);
				$ls_monto= $numalet->letra();
				$ld_montot=number_format($ld_montot,2,",",".");
				$ld_monsubtot=number_format($ld_monsubtot,2,",",".");
				$ld_monimp=number_format($ld_monimp,2,",",".");
                                $ls_monant=number_format($ls_monant,2,",",".");
				$ld_fecordcom=$io_funciones->uf_convertirfecmostrar($ld_fecordcom);
                                $ls_fecent=$io_funciones->uf_convertirfecmostrar($ls_fecent); 
		 
				uf_print_encabezado_pagina($ls_estcondat,$ls_numordcom,$ld_fecordcom,$ls_coduniadm,$ls_denuniadm,
				                           $ls_codfuefin,$ls_denfuefin,$ls_codpro,$ls_nompro,$ls_obscom,$ls_rifpro,
										   $ls_diaplacom,$ls_dirpro,$ls_forpagcom,$ls_orgrif,$ls_telpro,$ls_obscom,$ls_nomdep,$ls_dirdep,$ls_fecent,$ls_concom,$ls_orgnom,$ls_orgtit,$ls_monant,&$io_pdf);
				/////DETALLE  DE  LA ORDEN DE COMPRA
			   $rs_datos = $io_report->uf_select_detalle_orden_imprimir($ls_numordcom,$ls_estcondat,&$lb_valido);
			   if ($lb_valido)
			   {
		     	 $li_totrows = $io_sql->num_rows($rs_datos);
				 if ($li_totrows>0)
				 {
				    $li_i = 0;
				    while($row=$io_sql->fetch_row($rs_datos))
					{
						$li_i=$li_i+1;
						$ls_codartser=$row["codartser"];
						$ls_denartser=$row["denartser"];
						if($ls_estcondat=="B")
						{
							$ls_unidad=$row["unidad"];
						}
						else
						{
							$ls_unidad="";
						}
						if($ls_unidad=="D")
						{
						   $ls_unidad="Detal";
						}
						elseif($ls_unidad=="M")
						{
						   $ls_unidad="Mayor";
						}
						$li_cantartser=$row["cantartser"];
						$ld_preartser=$row["preartser"];
						$ld_subtotartser=$ld_preartser*$li_cantartser;
						$ld_totartser=$row["monttotartser"];
						$ld_carartser=$ld_totartser-$ld_subtotartser;
						
						$li_cantartser    = number_format($li_cantartser,2,",",".");
						$ld_preartser    = number_format($ld_preartser,2,",",".");
						$ld_subtotartser = number_format($ld_subtotartser,2,",",".");
						$ld_totartser	 = number_format($ld_totartser,2,",",".");
						$ld_carartser	 = number_format($ld_carartser,2,",",".");
						$la_data[$li_i]  = array('renglon'=>$li_i,
												 'codigo'=>$ls_codartser,
						                         'denominacion'=>$ls_denartser,
											     'cantidad'=>$li_cantartser,
											     'precio'=>$ld_preartser,
											     'subtotal'=>$ld_subtotartser,
											     'cargo'=>$ld_carartser,
											     'montot'=>$ld_totartser);
					}
					uf_print_detalle($la_data,$ld_monsubtot,$ld_monimp,$ld_montot,&$io_pdf);
					unset($la_data);
				    /////DETALLE  DE  LAS  CUENTAS DE GASTOS DE LA ORDEN DE COMPRA
					$rs_datos_cuenta=$io_report->uf_select_cuenta_gasto($ls_numordcom,$ls_estcondat,&$lb_valido); 
					if($lb_valido)
					{
						 $li_totrows = $io_sql->num_rows($rs_datos);
						 if ($li_totrows>0)
						 {
							$li_s = 0;
							while($row=$io_sql->fetch_row($rs_datos_cuenta))
							{
								$li_s=$li_s+1;
								$ls_codestpro1=trim($row["codestpro1"]);
								$ls_codestpro2=trim($row["codestpro2"]);
								$ls_codestpro3=trim($row["codestpro3"]);
								$ls_codestpro4=trim($row["codestpro4"]);
								$ls_codestpro5=trim($row["codestpro5"]);
								$ls_codestpro1 = substr($ls_codestpro1,-$_SESSION["la_empresa"]["loncodestpro1"]);
								$ls_codestpro2 = substr($ls_codestpro2,-$_SESSION["la_empresa"]["loncodestpro2"]);
								$ls_codestpro3 = substr($ls_codestpro3,-$_SESSION["la_empresa"]["loncodestpro3"]);
								$ls_codestpro4 = substr($ls_codestpro3,-$_SESSION["la_empresa"]["loncodestpro4"]);
								$ls_codestpro5 = substr($ls_codestpro3,-$_SESSION["la_empresa"]["loncodestpro5"]);
								$ls_spg_cuenta=$row["spg_cuenta"];
								$ld_monto=$row["monto"];
								$ld_monto=number_format($ld_monto,2,",",".");
								$ls_dencuenta="";
								$lb_valido = $io_report->uf_select_denominacionspg($ls_spg_cuenta,$ls_dencuenta);																																						
								if($ls_estmodest==1)
								{
									$ls_codestpro=$ls_codestpro1.'-'.$ls_codestpro2.'-'.$ls_codestpro3;
								}
								else
								{
									$ls_codestpro=substr($ls_codestpro1,-2)."-".substr($ls_codestpro2,-2)."-".substr($ls_codestpro3,-2)."-".substr($ls_codestpro4,-2)."-".substr($ls_codestpro5,-2);
								}
								$la_data[$li_s]=array('codestpro'=>$ls_codestpro,'denominacion'=>$ls_dencuenta,
													  'cuenta'=>$ls_spg_cuenta,'monto'=>$ld_monto);
							}	
							uf_print_detalle_cuentas($la_data,&$io_pdf);
							unset($la_data);
						}
				     }
			      }
		       }
	     	}
		}
		uf_print_piecabecera($ld_montot,$ls_monto,&$io_pdf);
	} 	  	 
	if($lb_valido) // Si no ocurrio ning�n error
	{
		$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi�n de los n�meros de p�gina
		$io_pdf->ezStream(); // Mostramos el reporte
	}
	else // Si hubo alg�n error
	{
		print("<script language=JavaScript>");
		print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
		print(" close();");
		print("</script>");		
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_soc);
?>
