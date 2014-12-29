<?
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "opener.location.href='../sigesp_conexion.php';";
	print "close();";
	print "</script>";
}

$la_datemp=$_SESSION["la_empresa"];
if(!array_key_exists("campo",$_POST))
{
	$ls_campo="cod_pro";
	$ls_orden="ASC";
}
else
{
	$ls_campo=$_POST["campo"];
	$ls_orden=$_POST["orden"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Clasificaci&oacute;n de Rubro</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/number_format.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699#006699;
}
.style6 {color: #000000}
-->
</style></head>

<body>
<form name="form1" method="post" action="">
<input type="hidden" name="campo" id="campo" value="<? print $ls_campo;?>" >
<input type="hidden" name="orden" id="orden" value="<? print $ls_orden;?>">
<?

require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_sfc_class_utilidades.php");
require_once("../shared/class_folder/class_datastore.php");
$io_datastore= new class_datastore();
$io_utilidad = new sigesp_sfc_class_utilidades();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$ls_codemp=$la_datemp["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_dentipo="%".$_POST["dentipo"]."%";

}
else
{
	$ls_operacion="";
	$ls_dentipo="";

}

?>
  <p align="center">
    <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
  </p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Clasificaci&oacute;n de Rubro</td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
     <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td><div align="left">
          <input name="dentipo" type="text" id="dentipo"  size="60">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
    <?


if($ls_operacion=="BUSCAR")
{
$ls_cadena=" select c.*,r.denominacion as denrubro,r.id_renglon,re.denominacion as denrenglon,re.id_tipoexplotacion,te.denominacion as denexplotacion from sfc_clasificacionrubro c,sfc_rubro r,sfc_renglon re,
sfc_tipoexplotacion as te WHERE c.id_rubro=r.id_rubro AND r.id_renglon=re.id_renglon AND re.id_tipoexplotacion=te.id_tipoexplotacion AND c.denominacion ilike '".$ls_dentipo."' ORDER BY c.id_clasificacion ASC";
			//print $ls_cadena;
 			$rs_datauni=$io_sql->select($ls_cadena);
			if($rs_datauni==false&&($io_sql->message!=""))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
				if($row=$io_sql->fetch_row($rs_datauni))
				{
					print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
					print "<tr class=titulo-celda>";
					print "<td><a href=javascript:ue_ordenar('codtipo','BUSCAR');><font color=#FFFFFF>ID</font></a></td>";
					print "<td><font color=#FFFFFF>C&oacute;digo</font></a></td>";
					print "<td><font color=#FFFFFF>Denominaci&oacute;n</font></a></td>";
					print "<td><font color=#FFFFFF>Rubro</font></a></td>";
					print "<td><font color=#FFFFFF>Renglon</font></a></td>";
					print "<td><font color=#FFFFFF>Tipo de Explotaci&oacute;n</font></a></td>";
					$la_tipo=$io_sql->obtener_datos($rs_datauni);
					$io_data->data=$la_tipo;
					$totrow=$io_data->getRowCount("id_rubro");

					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$idclarubro=$io_data->getValue("id_clasificacion",$z);
						$codclarubromac=$io_data->getValue("cod_clasificacion",$z);
		                $nomclarubro=$io_data->getValue("denominacion",$z);
						$codrubro=$io_data->getValue("id_rubro",$z);
						$codrubromac=$io_data->getValue("cod_rubro",$z);
		                $nomrubro=$io_data->getValue("denrubro",$z);
						$codrenglon=$io_data->getValue("id_renglon",$z);
		                $nomrenglon=$io_data->getValue("denrenglon",$z);
						$codtipoexplotacion=$io_data->getValue("id_tipoexplotacion",$z);
		                $nomtipoexplotacion=$io_data->getValue("denexplotacion",$z);
						$animal_has=$io_data->getValue("animal_has",$z);
						$prod_estimada=$io_data->getValue("prod_estimada",$z);
						$animal_has=number_format($animal_has,2, ',', '.');
		  				$prod_estimada=number_format($prod_estimada,2, ',', '.');
				        print "<td><a href=\"javascript: aceptar('$idclarubro','$codclarubromac','$nomclarubro','$codrubro','$codrubromac','$nomrubro','$codrenglon','$nomrenglon','$codtipoexplotacion','$nomtipoexplotacion','$animal_has','$prod_estimada');\">".$idclarubro."</a></td>";
						print "<td align=left>".$codclarubromac."</td>";
						print "<td align=left>".$nomclarubro."</td>";
						print "<td align=left>".$nomrubro."</td>";
						print "<td align=left>".$nomrenglon."</td>";
						print "<td align=left>". $nomtipoexplotacion."</td>";
						print "</tr>";
					}
				}
				else
				{
					$io_msg->message("No se han registrado Rubros");
				}
		}
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(idclarubro,codclarubromac,nomclarubro,codrubro,codrubromac,nomrubro,codrenglon,nomrenglon,codtipoexplotacion,nomtipoexplotacion,animal_has,prod_estimada)
  {
    opener.ue_cargar_clasificacionrubro(idclarubro,codclarubromac,nomclarubro,codrubro,codrubromac,nomrubro,codrenglon,nomrenglon,codtipoexplotacion,nomtipoexplotacion,animal_has,prod_estimada);
	close();
  }

  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_clasificacionrubro.php";
  f.submit();
  }

</script>
</html>
