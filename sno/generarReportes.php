<?php
setlocale(LC_ALL,"es_VE.UTF8");
require_once("clsHtml.php");
require_once("clsConexion.php");
require_once("clsLetras.php");
$lobjPdf= new PDF_HTML();
$objNomina = new clsConexion();
$objLetras = new EnLetras();
//DARLE FORMATO DE LETRA AL MES
function mes($mes){
$Mes = "";
switch ($mes) {
    case '01':
       $Mes = "Enero";
        break;
    case '02':
        $Mes = "Febrero";
        break;
    case '03':
        $Mes = "Marzo";
        break;
    case '04':
        $Mes = "Abril";
        break;
    case '05':
        $Mes = "Mayo";
        break;
    case '06':
        $Mes = "Junio";
        break;
    case '07':
        $Mes = "Julio";
        break;
    case '08':
        $Mes = "Agosto";
        break;
    case '09':
        $Mes = "Septiembre";
        break;
    case '10':
        $Mes = "Octubre";
        break;
    case '11':
        $Mes = "Noviembre";
        break;
    case '12':
        $Mes = "Diciembre";
        break;
    }
    return $Mes;
}
extract($_POST);
/*VARIBLAES QUE LLEGAN DEL POST SON 
** CEDULA
** NOMINA
**FUNCION QUE VIENE HACE LA CONSULTA DE LOS DEMAS DATOS A LA BD*/
$datos = $objNomina->fBuscarPersonal($cedula, $nomina);
//recibiendo DATOS DESDE LA BD
$ape = pg_fetch_result($datos,0,'apeper');
$nom = pg_fetch_result($datos,0,'nomper');
$nac = pg_fetch_result($datos,0,'nacper');
$cargo = pg_fetch_result($datos,0,'descar');
$codcar = pg_fetch_result($datos,0,'codcar');
$fecha = pg_fetch_result($datos,0,'fecingper');
$sueldo_base = pg_fetch_result($datos,0,'sueper');
$unidad_admin = pg_fetch_result($datos,0,'desuniadm');
//transferencias
/*$emp_transferencia = pg_fetch_result($datos,0,'desempant');
$fecha_inicio = pg_fetch_result($datos,0,'feciniant');
$fecha_fin = pg_fetch_result($datos,0,'fecfinant');*/
//formato de fecha de empresa anterior inicio
/*$dia_a = substr($fecha_inicio,8,2);
$mes_a = substr($fecha_inicio,5,2);
$ano_a = substr($fecha_inicio,0,4);*/
$dia_a = substr($fecha_inicio,0,2);
$mes_a = substr($fecha_inicio,3,2);
$ano_a = substr($fecha_inicio,6,4);
$mes_a = mes($mes_a);
//formtato de fecha de empresa anterior hasta
$dia_b = substr($fecha_fin,0,2);
$mes_b = substr($fecha_fin,3,2);
$ano_b = substr($fecha_fin,6,4);
$mes_b = mes($mes_b);
// ecribiendo nombre completo
$nombre = $ape." ".$nom;
//SEPARANDO LAS FECHAS
$dia=substr($fecha,8,2);
$mesp=substr($fecha,5,2);
$ano=substr($fecha,0,4);
//FORMATENADO EL MES
$mes = mes($mesp);
/*--------------------------------------------------------------------*/
/*CALCULO DE NVO SUELDO MIENTRAS TANTO ACUALIZACION 30 ENERO DE 2014*/
/*--------------------------------------------------------------------*/

/*--------------------------------------------------------------------*/
/*--------------------------------------------------------------------*/
//AQUI TOMO LA PRIMA DE PROFESIONALIZACION Q ES DE CODIGO 0000000004
//actuazlizacion 16/09/2013
$auxp = $objNomina->fProfesion($cedula, $nomina, "0000000004");
$au = pg_fetch_result($auxp,0,'aplcon');
if($au==1){
    $profesion=$sueldo_base * 0.12;
}
//AHORA PRIMA POR HIJO ES 000000006
$aux = $objNomina->fNroHijos($cedula,$nomina);
$hijo= pg_fetch_result($aux,0,'moncon');
//PARA PRIMA HOGAR
$hogar = 100;
//PRIMA COMPLEMENTARIAA
$comp = $sueldo_base*0.25;
/* CALCULAR LA PRIMA DE RESPONSABILIDAD SOBRE COORDINADORES, GERENTES DE AREAS Y GERENTE GENRAL COMO
COORDINADORES 
9%
PRESIDENTE, VICEPRECIDENTES Y GERENTE GENERAL
12%
** LOS VICEPRECIDENTES Y PRESIDENTE
GERENTES DE AREAS:
11%
------------------------------
43 // CONSULTOR JURIDICO
31 // GERENCIA DE COMERCIALIZACION
35 // PRODUCCION INDUSTRIAL
20 // GERENCIA DE ATENCION AL CIUDADANO
32 // 
44 //
04 // GERENCIA DE PLANIFICIACION Y PRESUPUESTO
42 // CONSULTOR ENCARGADO
18 // CONSULTOR 
05 // manuel sosa
36 //
06 // GERENCIA DE ASISTENCIA TECNICA Y DESARROLLO TECNOLOGICO
-------
*/
if (($codcar == '0000000008') || ($codcar == '0000000030') || ($cedula=='9569409')) {
    $resp = $sueldo_base * 0.09;
}
else if( ($codcar == '0000000001') || ($codcar == '0000000002') || ($codcar == '0000000003')){
    $resp = $sueldo_base * 0.12;
}
else if(($codcar=='0000000043')||($codcar=='0000000031')||($codcar=='0000000035')||($codcar=='0000000020')||($codcar=='0000000032')||($codcar=='0000000044')||($codcar=='0000000004')||($codcar=='0000000042')||($codcar=='0000000005')||($codcar=='0000000036') || ($codcar=='0000000006')){
    $resp = $sueldo_base * 0.11;   
}
else{
    $resp = 0;
}
//calculo sueldo integral.
//SUELDO EN LETRAS A VER QUE LO QUE 
$sueldo_integral = $sueldo_base+$resp+$comp+$profesion+$hijo+$hogar;
$sueldo_letras = $objLetras->ValorEnLetras($sueldo_integral,"Bolívares");
//FECHA ACTUAL 
$ls_dia = date("d");
$mes_t = date("m");
$ls_ano = date("Y");
$ls_mes = mes($mes_t);
//formatenano la cedula
$cedula = number_format($cedula,0,",",".");
if ($ape!=""){
	if($tipo_constancia){
	$total = $sueldo_base+$resp+$comp+$profesion+$hijo+$hogar;
	//$lobjPdf->AliasNbPages();
    $lobjPdf->AddPage("P","letter");
    $lobjPdf->SetMargins(20, 25 , 20); 
    $lobjPdf->SetFont("Arial","B",16);          
    $lobjPdf->Cell(0,9,utf8_decode(""),0,1,"C");
    $lobjPdf->Ln();
    $lobjPdf->Ln();
    //DIRIGIDO A ALGUIENE ESPECIFICO
    if($dirigido!=""){
    	$lobjPdf->SetFont("Arial","B",14); 
    	$lobjPdf->Cell(0,5,utf8_decode("Atención"),0,1,"");	
    	$lobjPdf->Cell(0,5,utf8_decode($dirigido),0,1,"");	
    	$lobjPdf->Ln();
    	$lobjPdf->Ln();
   	}
   	else{
   		$lobjPdf->Ln();
   		$lobjPdf->Ln();
	   	$lobjPdf->SetFont("Arial","B",16); 
   	}
	$lobjPdf->Cell(0,5,utf8_decode("CONSTANCIA DE TRABAJO"),0,1,"C");
	$lobjPdf->Ln();
	$lobjPdf->Ln();																																																			
	if($emp_transferencia!=""){
		$lobjPdf->newFlowingBlock(180, 8, 'J');
	    $lobjPdf->SetFont('Arial', '', 12);
	    $lobjPdf->Ln();
	        $lobjPdf->WriteFlowingBlock("       Quien suscribe, ");
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode("Lcda. Inostroza Loreto,"));
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" titular de la cédula de identidad Nro V-24.320.049, en mi carácter de "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock("Gerente de Recursos Humanos de la Empresa Mixta Socialista Arroz del ALBA S.A,");
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(" hace constar que el (la) ciudadano (a): ");
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($nombre);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" titular de la cédula de identidad Nro "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($nac."-".$cedula);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" prestó sus servicios en la empresa "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($emp_transferencia);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" desde el: "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($dia_a." de ".$mes_a." de ".$ano_a);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" hasta el: "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($dia_b." de ".$mes_b." de ".$ano_b);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" y fue transferido (a) a esta institución en calidad de "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($cargo);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" adscrito (a) "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($unidad_admin);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" desde el "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($dia." de ".$mes." de ".$ano);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" devengando la cantidad de "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode($sueldo_letras).".");
		setlocale(LC_MONETARY, 'es_VE');
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" ( ".money_format('%=*(#6.2n**', $total).") "));
	        $lobjPdf->finishFlowingBlock();
	    }
	else{
	     //colocando las negritas por partes que lala
	     //encabezado 
	        $lobjPdf->newFlowingBlock(180, 8, 'J');
	        $lobjPdf->Ln();
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock("       Quien suscribe, ");
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode("Lcda. Inostroza Loreto,"));
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" titular de la cédula de identidad Nro V-24.320.049, en mi carácter de "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock("Gerente de Recursos Humanos de la Empresa Mixta Socialista Arroz del ALBA S.A,");
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(" hace constar que el (la) ciudadano (a): ");
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($nombre);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" titular de la cédula de identidad Nro "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($nac."-".$cedula);
	    $lobjPdf->SetFont('Arial', '', 12);
	    $lobjPdf->WriteFlowingBlock(utf8_decode(" presta sus servicios en esta institución en calidad de "));
	    $lobjPdf->SetFont('Arial', 'B', 12);
	    $lobjPdf->WriteFlowingBlock($cargo);
	    $lobjPdf->SetFont('Arial', '', 12);
	    $lobjPdf->WriteFlowingBlock(utf8_decode(" adscrito(a) a la "));
	    $lobjPdf->SetFont('Arial', 'B', 12);
	    $lobjPdf->WriteFlowingBlock($unidad_admin);
	    $lobjPdf->SetFont('Arial', '', 12);        
	    $lobjPdf->WriteFlowingBlock(utf8_decode(" desde el "));
	    $lobjPdf->SetFont('Arial', 'B', 12);
	    $lobjPdf->WriteFlowingBlock($dia." de ".$mes." de ".$ano);
	    $lobjPdf->SetFont('Arial', '', 12);
	    $lobjPdf->WriteFlowingBlock(utf8_decode(" devengando la cantidad de "));
	    $lobjPdf->SetFont('Arial', 'B', 12);
	    $lobjPdf->WriteFlowingBlock(utf8_decode($sueldo_letras).".");
	    $lobjPdf->WriteFlowingBlock(utf8_decode(" ( ".money_format('%=*(#6.2n**', $total).") "));
	    $lobjPdf->finishFlowingBlock();
	}
    //parte de los tickets es opcional
    if ($cesta){
        $lobjPdf->setY(150);
        $lobjPdf->setX(20);
        $lobjPdf->SetFont("Arial","",12);
        $lobjPdf->MultiCell(175,7,utf8_decode("        Aunado a esto percibe por beneficio de Ticket de Alimentación la cantidad de Dos mil ochocientos cincuenta y siete con 80/100 céntimos ( Bs. 2857,80)"));  
        $lobjPdf->Cell(0,2,utf8_decode(""),0,1,"C");
        $lobjPdf->Ln();
        $lobjPdf->setX(20);
        $lobjPdf->MultiCell(175,7,utf8_decode(" Constancia que se expide en Píritu, a petición de la parte interesada a los ".$ls_dia." días de mes de ".$ls_mes." del año ".$ls_ano)); 
    }
    else{
        $lobjPdf->SetFont("Arial","",12);
        $lobjPdf->Ln();$lobjPdf->Ln();$lobjPdf->Ln();
        $lobjPdf->setY(160);
        $lobjPdf->setX(20);
        $lobjPdf->MultiCell(175,7,utf8_decode(" Constancia que se expide en Píritu, a petición de la parte interesada a los ".$ls_dia." días de mes de ".$ls_mes." del año ".$ls_ano));   
    }
    //ultima parte
    $lobjPdf->Ln();
    $lobjPdf->setX(20);
    $lobjPdf->Cell(175,7,utf8_decode("Atentamente"),0,1,"C");
    // ULTIMA PARTE DEL DOCUMENTO 
    $lobjPdf->SetFont("Arial","B",12);
    $lobjPdf->Ln();
    $lobjPdf->setX(20);
    $lobjPdf->Cell(175,7,utf8_decode("______________________"),0,1,"C");
    $lobjPdf->setX(20);
    $lobjPdf->Cell(175,7,utf8_decode("Lcda. Loreto Inostroza "),0,1,"C");
    $lobjPdf->setX(20);
    $lobjPdf->Cell(175,7,utf8_decode("Gerente de Recursos Humanos"),0,1,"C");
    $lobjPdf->setX(20);
    $lobjPdf->Cell(175,7,utf8_decode("Segun Punto de Cuenta Nro 020-2014 de Fecha 14/02/2014"),0,1,"C");
    $lobjPdf->setX(20);
    $lobjPdf->Cell(175,7,utf8_decode("Empresa Mixta Socialista Arroz del Alba, S.A."),0,1,"C");
    $lobjPdf->Output();
	}
	else{
    //$lobjPdf->AliasNbPages();
    $lobjPdf->AddPage("P","letter");
    $lobjPdf->SetMargins(20, 25 , 20); 
    $lobjPdf->SetFont("Arial","B",16);          
    $lobjPdf->Cell(0,9,utf8_decode(""),0,1,"C");
    $lobjPdf->Ln();
    //DIRIGIDO A ALGUIENE ESPECIFICO
    if($dirigido!=""){
    	$lobjPdf->SetFont("Arial","B",14); 
    	$lobjPdf->Cell(0,5,utf8_decode("Atención"),0,1,"");	
    	$lobjPdf->Cell(0,5,utf8_decode($dirigido),0,1,"");	
    	$lobjPdf->Ln();
   	}
   	else{
   		$lobjPdf->Ln();
	   	$lobjPdf->SetFont("Arial","B",16); 
   	}
	$lobjPdf->Cell(0,5,utf8_decode("CONSTANCIA DE TRABAJO"),0,1,"C");
	$lobjPdf->Ln();																																																			
	if($emp_transferencia!=""){
		$lobjPdf->newFlowingBlock(180, 6, 'J');
	    $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock("       Quien suscribe, ");
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode("Lcda. Inostroza Loreto,"));
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" titular de la cédula de identidad Nro V-24.320.049, en mi carácter de "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock("Gerente de Recursos Humanos de la Empresa Mixta Socialista Arroz del ALBA S.A,");
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(" hace constar que el (la) ciudadano (a): ");
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($nombre);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" titular de la cédula de identidad Nro "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($nac."-".$cedula);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" prestó sus servicios en la empresa "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($emp_transferencia);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" desde el: "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($dia_a." de ".$mes_a." de ".$ano_a);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" hasta el: "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($dia_b." de ".$mes_b." de ".$ano_b);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" y fue transferido (a) a esta institución en calidad de "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($cargo);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" adscrito (a) "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($unidad_admin);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" desde el "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($dia." de ".$mes." de ".$ano);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" devengando la cantidad de "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode($sueldo_letras).".");
	        $lobjPdf->finishFlowingBlock();
	    }
	    else{
	     //colocando las negritas por partes que lala
	        //encabezado 
	        $lobjPdf->newFlowingBlock(180, 7, 'J');
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock("       Quien suscribe, ");
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode("Lcda. Inostroza Loreto,"));
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" titular de la cédula de identidad Nro V-24.320.049, en mi carácter de "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock("Gerente de Recursos Humanos de la Empresa Mixta Socialista Arroz del ALBA S.A,");
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(" hace constar que el (la) ciudadano (a): ");
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($nombre);
	        $lobjPdf->SetFont('Arial', '', 12);
	        $lobjPdf->WriteFlowingBlock(utf8_decode(" titular de la cédula de identidad Nro "));
	        $lobjPdf->SetFont('Arial', 'B', 12);
	        $lobjPdf->WriteFlowingBlock($nac."-".$cedula);
	    $lobjPdf->SetFont('Arial', '', 12);
	    $lobjPdf->WriteFlowingBlock(utf8_decode(" presta sus servicios en esta institución en calidad de "));
	    $lobjPdf->SetFont('Arial', 'B', 12);
	    $lobjPdf->WriteFlowingBlock($cargo);
	    $lobjPdf->SetFont('Arial', '', 12);
	    $lobjPdf->WriteFlowingBlock(utf8_decode(" adscrito(a) a la "));
	    $lobjPdf->SetFont('Arial', 'B', 12);
	    $lobjPdf->WriteFlowingBlock($unidad_admin);
	    $lobjPdf->SetFont('Arial', '', 12);        
	    $lobjPdf->WriteFlowingBlock(utf8_decode(" desde el "));
	    $lobjPdf->SetFont('Arial', 'B', 12);
	    $lobjPdf->WriteFlowingBlock($dia." de ".$mes." de ".$ano);
	    $lobjPdf->SetFont('Arial', '', 12);
	    $lobjPdf->WriteFlowingBlock(utf8_decode(" devengando la cantidad de "));
	    $lobjPdf->SetFont('Arial', 'B', 12);
	    $lobjPdf->WriteFlowingBlock(utf8_decode($sueldo_letras."."));
	    $lobjPdf->finishFlowingBlock();
	}
    //tabla
    //para centrar un poquito mas ps
    $lobjPdf->SetMargins(40, 25, 20); 
    $header=array(utf8_decode('Descripción'),'Monto Bs. F');
    $lobjPdf->TablaColores($header, $sueldo_base, $resp, $comp, $profesion, $hijo, $hogar);
    $lobjPdf->Ln();
    //parte de los tickets es opcional
    if ($cesta){
        $lobjPdf->setY(163);
        $lobjPdf->setX(20);
        $lobjPdf->SetFont("Arial","",12);
        $lobjPdf->MultiCell(175,7,utf8_decode("        Aunado a esto percibe por beneficio de Ticket de Alimentación la cantidad de Dos mil ochocientos cincuenta y siete con 80/100 céntimos ( Bs. 2857,80)"));  
        $lobjPdf->Cell(0,2,utf8_decode(""),0,1,"C");
        $lobjPdf->Ln();
        $lobjPdf->setX(20);
        $lobjPdf->MultiCell(175,7,utf8_decode(" Constancia que se expide en Píritu, a petición de la parte interesada a los ".$ls_dia." días de mes de ".$ls_mes." del año ".$ls_ano));   
    }
    else{
        $lobjPdf->SetFont("Arial","",12);
        $lobjPdf->Ln();$lobjPdf->Ln();$lobjPdf->Ln();
        $lobjPdf->setY(172);
        $lobjPdf->setX(20);
        $lobjPdf->MultiCell(175,7,utf8_decode(" Constancia que se expide en Píritu, a petición de la parte interesada a los ".$ls_dia." días de mes de ".$ls_mes." del año ".$ls_ano));  
    }
    //ultima parte
    $lobjPdf->Ln();
    $lobjPdf->setX(20);
    $lobjPdf->Cell(175,7,utf8_decode("Atentamente"),0,1,"C");
    // ULTIMA PARTE DEL DOCUMENTO 
    $lobjPdf->SetFont("Arial","B",12);
    $lobjPdf->Ln();
    $lobjPdf->setX(20);
    $lobjPdf->Cell(175,7,utf8_decode("______________________"),0,1,"C");
    $lobjPdf->setX(20);
    $lobjPdf->Cell(175,7,utf8_decode("Lcda. Loreto Inostroza "),0,1,"C");
    $lobjPdf->setX(20);
    $lobjPdf->Cell(175,7,utf8_decode("Gerente de Recursos Humanos"),0,1,"C");
    $lobjPdf->setX(20);
    $lobjPdf->Cell(175,7,utf8_decode("Segun Punto de Cuenta Nro 020-2014 de Fecha 14/02/2014"),0,1,"C");
    $lobjPdf->setX(20);
    $lobjPdf->Cell(175,7,utf8_decode("Empresa Mixta Socialista Arroz del Alba, S.A."),0,1,"C");
    $lobjPdf->Output();
}
}
else{
    echo "ERROR! No ha seleccionado correctamente la nomina! o Numero de Cedula incorrecto";
}
?>
