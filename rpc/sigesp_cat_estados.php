<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat�logo de Estados</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Estados</td>
    </tr>
</table>
<br>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_dsest=new class_datastore();
$io_sql=new class_sql($conn);
if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion=$_POST["operacion"];
	 $ls_codpais=$_POST["hidpais"];
   }
else
   {
     $ls_operacion="";
 	 $ls_codpais=$_GET["hidpais"];
   }
$ls_sql=" SELECT codest,desest ".
        " FROM sigesp_estados ".
		" WHERE codpai= '".$ls_codpais."' ".
		" ORDER BY codest ASC";
$rs_estado=$io_sql->select($ls_sql);
$data=$rs_estado;
if($row=$io_sql->fetch_row($rs_estado))
{
     $data=$io_sql->obtener_datos($rs_estado);
	 $arrcols=array_keys($data);
	 $totcol=count($arrcols);
	 $io_dsest->data=$data;
	 $totrow=$io_dsest->getRowCount("codest");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
	 print "<tr class=titulo-celda>";
     print "<td>C�digo</td>";
     print "<td>Denominaci�n</td>";
	 print "</tr>";
	 for($z=1;$z<=$totrow;$z++)
	 {
		print "<tr class=celdas-blancas>";
		$codigo=$data["codest"][$z];
		$denominacion=$data["desest"][$z];
		print "<td><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
		print "<td>".$denominacion."</td>";
		print "</tr>";			
	 }
     print "</table>";
     $io_sql->free_result($rs_estado);
}
else
{ ?>
  <script language="javascript">
  alert("No se han creado Estados Para este Pais !!!");
  close();
  </script>
<?php
}

?>
</p>
  <form name="form1" method="post" action="">
    <input name="hidpais" type="hidden" id="hidpais">
  </form>
  <p>&nbsp;  </p>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion)
  {
    opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtcodigo.readOnly=true;
    opener.document.form1.txtdenominacion.value=denominacion;
	close();
  }
  
  
</script>
</html>