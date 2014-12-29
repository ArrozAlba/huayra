<?php
    session_start();   
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_r_familiar.php",$ls_permisos,$la_seguridad,$la_permisos);
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$ls_reporte=$io_sno->uf_select_config("SNR","REPORTE","LISTADO_FAMILIAR","sigesp_snorh_rpp_familiar.php","C");
	unset($io_sno);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Reporte Listado de Familiar</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de N�mina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table>
	 </td>
  </tr>
 <?php

	if (isset($_GET["valor"]))
	{ $ls_valor=$_GET["valor"];	}
	else
	{ $ls_valor="";}
	
	if ($ls_valor!='srh')
	{
	   print ('<tr>');
	   print ('<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>' );
	   print ('</tr>');
	}
	
	
  ?>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif"  title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <?php

	if (isset($_GET["valor"]))
	{ $ls_valor=$_GET["valor"];	}
	else
	{ $ls_valor="";}
	
	if ($ls_valor!='srh')
	{
	    print ('<td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title=Salir alt="Salir" width="20" height="20" border="0"></a></div></td>' );	   
	}
	else
	{
	 print ('<td class="toolbar" width="25"><div align="center"><a href="javascript: close();"><img src="../shared/imagebank/tools20/salir.gif"  title=Salir alt="Salir" width="20" height="20" border="0"></a></div></td>' );	
	}
	
  ?>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="5" class="titulo-ventana">Reporte Listado de Familiar </td>
        </tr>
        <tr>
          <td height="20" colspan="5" class="titulo-celdanew">Intervalo de N&oacute;mina </td>
          </tr>
        <tr>
          <td width="144" height="22"><div align="right"> Desde </div></td>
          <td width="114"><div align="left">
            <input name="txtcodnomdes" type="text" id="txtcodnomdes" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarnominadesde();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="105"><div align="right">Hasta </div></td>
          <td colspan="2"><div align="left">
            <input name="txtcodnomhas" type="text" id="txtcodnomhas" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarnominahasta();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="20" colspan="5" class="titulo-celdanew">Intervalo de Personal </td>
          </tr>
        <tr>
          <td width="144" height="22"><div align="right"> Desde </div></td>
          <td width="114"><div align="left">
            <input name="txtcodperdes" type="text" id="txtcodperdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarpersonaldesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="105"><div align="right">Hasta </div></td>
          <td colspan="2"><div align="left">
            <input name="txtcodperhas" type="text" id="txtcodperhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarpersonalhasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22" colspan="5" class="titulo-celdanew">Estatus del Personal </td>
          </tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td><div align="right">Activo
              <input name="chkactivo" type="checkbox" class="sin-borde" id="chkactivo" value="1" checked>
</div></td>
          <td>
            <div align="right">Egresado
              <input name="chkegresado" type="checkbox" class="sin-borde" id="chkegresado" value="1">
            </div></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="22" colspan="5" class="titulo-celdanew">Estatus del Personal en N&oacute;mina </td>
          </tr>
        <tr>
          <td height="22"><div align="right">Activo
            <input name="chkactivono" type="checkbox" class="sin-borde" id="chkactivono" value="1" checked>
          </div></td>
          <td><div align="right">Vacaciones
            <input name="chkvacacionesno" type="checkbox" class="sin-borde" id="chkvacacionesno" value="1" checked>
          </div></td>
          <td><div align="right">Egresado
            <input name="chkegresadono" type="checkbox" class="sin-borde" id="chkegresadono" value="1" checked>
          </div></td>
          <td><div align="right">Suspendido
            <input name="chksuspendidono" type="checkbox" class="sin-borde" id="chksuspendidono" value="1" checked>
          </div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="22" colspan="5" class="titulo-celdanew">G&eacute;nero del Personal </td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td><div align="right">Masculino 
            <label>
            <input name="chkpersonalmas" type="checkbox" class="sin-borde" id="chkpersonalmas" value="1" checked>
            </label>
          </div></td>
          <td><div align="right">Femenino 
            <label>
            <input name="chkpersonalfem" type="checkbox" class="sin-borde" id="chkpersonalfem" value="1" checked>
            </label>
          </div></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="22" colspan="5" class="titulo-celdanew">Nexo del Familiar </td>
          </tr>
        <tr>
          <td height="22"><div align="right">Conyugue
                <input name="chkconyuge" type="checkbox" class="sin-borde" id="chkconyuge" value="1" checked>
          </div></td>
          <td><div align="right">Progenitor
              <input name="chkprogenitor" type="checkbox" class="sin-borde" id="chkprogenitor" value="1" checked>
          </div></td>
          <td><div align="right">Hijo
              <input name="chkhijo" type="checkbox" class="sin-borde" id="chkhijo" value="1" checked>
</div></td>
          <td width="104"><div align="right">Hermano
              <input name="chkhermano" type="checkbox" class="sin-borde" id="chkhermano" value="1" checked>
          </div></td>
          <td width="71">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" colspan="5" class="titulo-celdanew">G&eacute;nero del Familiar </td>
          </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td><div align="right">Masculino
            <input name="chkmasculino" type="checkbox" class="sin-borde" id="chkmasculino" value="1" checked>
          </div></td>
          <td><div align="right">Femenino
            <input name="chkfemenino" type="checkbox" class="sin-borde" id="chkfemenino" value="1" checked>
          </div></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="22" colspan="5" class="titulo-celdanew">Edad del Familiar </td>
          </tr>
        <tr>
          <td height="22"><div align="right">Desde</div></td>
          <td>
            <div align="left">
              <input name="txtdesde" type="text" id="txtdesde" size="10" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
              </div>          </td>
          <td><div align="right">Hasta</div></td>
          <td><div align="left">
            <input name="txthasta" type="text" id="txthasta" size="10" maxlength="3" onKeyUp="javascript: ue_validarnumero(this);">
          </div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="20" colspan="5" class="titulo-celdanew"><div align="right" class="titulo-celdanew">Ordenado por </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo del Personal </div></td>
          <td><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="1" checked>
          </div></td>
          <td><div align="right">C&eacute;dula del Personal</div></td>
          <td><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="2">
          </div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Apellido del Personal</div></td>
          <td><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="3">
          </div></td>
          <td><div align="right">Nombre del Personal</div></td>
          <td><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="4">
          </div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>"></td>
          <td colspan="4">&nbsp;</td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
</body>
<script language="javascript">
function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		reporte=f.reporte.value;
		codnomdes=f.txtcodnomdes.value;
		codnomhas=f.txtcodnomhas.value;
		if(codnomdes<=codnomhas)
		{
			codperdes=f.txtcodperdes.value;
			codperhas=f.txtcodperhas.value;
			if(codperdes<=codperhas)
			{
				edaddesde=f.txtdesde.value;
				edadhasta=f.txthasta.value;
				if(edaddesde<=edadhasta)
				{
					conyuge="";
					progenitor="";
					hijo="";
					hermano="";
					masculino="";
					femenino="";
					personalmasculino="";
					personalfemenino="";
					activo="";
					egresado="";
					activono="";
					vacacionesno="";
					egresadono="";
					suspendidono="";
					if(f.chkconyuge.checked)
					{
						conyuge=1;
					}
					if(f.chkprogenitor.checked)
					{
						progenitor=1;
					}
					if(f.chkhijo.checked)
					{
						hijo=1;
					}
					if(f.chkhermano.checked)
					{
						hermano=1;
					}
					if(f.chkmasculino.checked)
					{
						masculino=1;
					}
					if(f.chkfemenino.checked)
					{
						femenino=1;
					}
					if(f.chkpersonalmas.checked)
					{
						personalmasculino=1;
					}
					if(f.chkpersonalfem.checked)
					{
						personalfemenino=1;
					}
					if(f.chkactivo.checked)
					{
						activo=1;
					}
					if(f.chkegresado.checked)
					{
						egresado=1;
					}
					if(f.chkactivono.checked)
					{
						activono=1;
					}
					if(f.chkvacacionesno.checked)
					{
						vacacionesno=1;
					}
					if(f.chkegresadono.checked)
					{
						egresadono=1;
					}
					if(f.chksuspendidono.checked)
					{
						suspendidono=1;
					}
					for(i=0;i<f.rdborden.length;i++)
					{
						if(f.rdborden[i].checked)
						{
							orden=f.rdborden[i].value;
						}
					}
					pagina="reportes/"+reporte+"?codperdes="+codperdes+"&codperhas="+codperhas;
					pagina=pagina+"&conyuge="+conyuge+"&progenitor="+progenitor+"&hijo="+hijo+"&hermano="+hermano;
					pagina=pagina+"&femenino="+femenino+"&masculino="+masculino+"&edaddesde="+edaddesde+"&edadhasta="+edadhasta;
					pagina=pagina+"&codnomdes="+codnomdes+"&codnomhas="+codnomhas+"&activo="+activo+"&egresado="+egresado+"&orden="+orden;
					pagina=pagina+"&activono="+activono+"&vacacionesno="+vacacionesno+"&egresadono="+egresadono+"&suspendidono="+suspendidono;
					pagina=pagina+"&personalmasculino="+personalmasculino+"&personalfemenino="+personalfemenino;
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
				}
				else
				{
					alert("El rango de edad del familiar est� erroneo");
				}
			}
			else
			{
				alert("El rango del personal est� erroneo");
			}
		}
		else
		{
			alert("El rango del n�mina est� erroneo");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operaci�n");
   	}		
}


function ue_buscarnominadesde()
{
	window.open("sigesp_snorh_cat_nomina.php?tipo=replisfamdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarnominahasta()
{
	f=document.form1;
	if(f.txtcodnomdes.value!="")
	{
		window.open("sigesp_snorh_cat_nomina.php?tipo=replisfamhas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar una n�mina desde.");
	}
}

function ue_buscarpersonaldesde()
{
	window.open("sigesp_snorh_cat_personal.php?tipo=replisfamdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonalhasta()
{
	f=document.form1;
	if(f.txtcodperdes.value!="")
	{
		window.open("sigesp_snorh_cat_personal.php?tipo=replisfamhas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un personal desde.");
	}
}
</script> 
</html>