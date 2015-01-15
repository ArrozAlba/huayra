<?php
session_start();  
require_once("config_apertura.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../cfg/class_folder/sigesp_cfg_c_empresa.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
$io_conect = new sigesp_include();
$msg=new class_mensajes();
if(array_key_exists("dbdestino",$_POST))
{
  $ls_dbdestino=$_POST["dbdestino"];     	
}
else
{
  $ls_dbdestino="";
}		
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}	

$_SESSION["ls_data_des"] = $ls_dbdestino;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Sistema de Apertura</title>
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
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css"></head>

<body>
<table width="570" border="0" align="center" cellpadding="1" cellspacing="0" class="contorno">
  <tr>
    <td width="570" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="570" height="40"></td>
  </tr>
  
  <tr>
    <td width="332" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
          <td width="432" height="20"  class="descripcion_sistema">Apertura de Sigesp </td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque�as"><b><?PHP print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a e");?></b></span></div></td>
	  	</table>
    </td>
  </tr>
  
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"></a><a href="javascript:ue_salir();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<p>
  <?php
	function uf_conectar_destino() 
	{
		global $msg;	

		if (strtoupper($_SESSION["ls_gestor_destino"])==strtoupper("mysql"))
		{
		    $conec = @mysql_connect($_SESSION["ls_hostname_destino"],$_SESSION["ls_login_destino"],$_SESSION["ls_password_destino"]);						
			if($conec===false)
			{
				$msg->message("No pudo conectar con el servidor de datos MYSQL,".$_SESSION["ls_hostname_destino"]." , contacte al administrador del sistema");	
			}
			else
			{			    
				$lb_ok=@mysql_select_db(trim($_SESSION["ls_database_destino"]),$conec);
				if (!$lb_ok)
				{
					$msg->message("No existe la base de datos ".$_SESSION["ls_database_destino"]);					
				}
			}
		return $conec;
		}		
		if(strtoupper($_SESSION["ls_gestor_destino"])==strtoupper("postgre"))
		{
			$conec = @pg_connect("host=".$_SESSION["ls_hostname_destino"]." port=".$_SESSION["ls_port_destino"]."  dbname=".$_SESSION["ls_database_destino"]." user=".$_SESSION["ls_login_destino"]." password=".$_SESSION["ls_password_destino"]); 
		
			if (!$conec)
			{
				$msg->message("No pudo conectar al servidor de base de datos POSTGRES, contacte al administrador del sistema");				
			}
      	 return $conec;
	    }		
	}		
	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];

		if ($ls_operacion=="MOSTRAR")
		   {
			$posicion=$_POST["cmbdb"];
			//Realizo la conexion a la base de datos
			if($posicion=="") {	}
			else
			  {
				$_SESSION["ls_database_destino"] = $empresa["database"][$posicion];							
				$_SESSION["ls_hostname_destino"] = $empresa["hostname"][$posicion];
				$_SESSION["ls_login_destino"]    = $empresa["login"][$posicion];
				$_SESSION["ls_password_destino"] = $empresa["password"][$posicion];
				$_SESSION["ls_gestor_destino"]   = $empresa["gestor"][$posicion];	
				$_SESSION["ls_port_destino"]     = $empresa["port"][$posicion];	
				$_SESSION["ls_width_destino"]    = $empresa["width"][$posicion];
				$_SESSION["ls_height_destino"]   = $empresa["height"][$posicion];	
				$_SESSION["ls_logo_destino"]     = $empresa["logo"][$posicion];	
			}
			print "<script language=JavaScript>";
			print "location.href='sigespwindow_blank.php'" ;
			print "</script>";
		}
		elseif($ls_operacion="SELEMPRESA")
		{			
			$ls_codemp=$_POST["cmbempresa"];
			$con_destino=uf_conectar();
			$obj_sql=new class_sql($con_destino);
			$ls_sql="SELECT * FROM sigesp_empresa where codemp='".$ls_codemp."' ";
			$result=$obj_sql->select($ls_sql);
			$li_row=$obj_sql->num_rows($result);
			$li_pos=0;
			if($row=$obj_sql->fetch_row($result))
			{
				$la_empresa=$row;   
				$_SESSION["la_empresa"]=$la_empresa;
				$a=$_SESSION["la_empresa"];
				print "<script language=JavaScript>";
				print "location.href='sigesp_inicio_sesion.php'" ;
				print "</script>";
			}
		}
	}
	else
	{ 
		$ls_operacion="";		
		/*if(!isset($_SESSION))
		{
			unset($_SESSION);
		}*/
	}	
?>
</p>
<form name="form1" method="post" action="">
  <table width="200" border="0" align="center">
    <tr>
      <td><div align="center">
        <table width="570" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
         <td height="22" colspan="3" class="titulo-celdanew">Sistema de Apertura </td>
      </tr>
      <tr class="formato-blanco">
         <td height="13" colspan="3">&nbsp;</td>
      </tr>
      <?php
      if($ls_operacion=="")
      {
      ?>                    
          <tr>
            <td height="22" colspan="3" class="titulo-celdanew"><div align="right"></div>
                <div align="center">Base de Datos </div></td>
          </tr>
          <tr>
            <td width="156" height="21"><input name="operacion" type="hidden" id="operacion" value="<?php $_REQUEST["OPERACION"] ?>"></td>
            <td width="330" height="21" colspan="-1">&nbsp;</td>
            <td width="12" height="21" colspan="-1">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" >Recuerde que debe ejecutar el relase en la Base de Datos Origen para evitar inconvenientes al momento de hacer la apertura. </td>
            <td colspan="-1">&nbsp;</td>
          </tr>
          <tr>
            <td ><div align="right">
                  <p><strong>Base de Datos Destino</strong></p>
                  </div></td>
            <td colspan="-1">
              <p>
                <?php
   	$li_total = count($empresa["database"]);
    ?>
		<select name="cmbdb" style="width:120px " onChange="javascript:selec();">
		<option value="">Seleccione</option>
        <?php
			for($i=1; $i <= $li_total ; $i++)
			{
				if($posicion==$i)
				{
					$selected="selected";
				}
				else
				{
					$selected="";
				}
		?>
				<option value="<?php echo $i;?>" <?php print $selected; ?>>
					<?php
						echo $empresa["database"][$i];				
					?>
				</option>
        <?php
		}
		?>
        </select>
		<input name="dbdestino" type="hidden" id="dbdestino" value="<?php print $ls_dbdestino;?>">
              </p>            </td>
            <td colspan="-1">&nbsp;</td>
          </tr>
          <?php
		  }		 
		  ?>
        </table>
      </div></td>
    </tr>
  </table>
  </form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function selec()
{	
	f=document.form1;
	
	var indice = f.cmbdb.selectedIndex
	var textoDB = f.cmbdb.options[indice].text
	
	f.operacion.value="MOSTRAR";	
	f.action="sigesp_apr_conexion.php";
	valor = f.cmbdb.text;
	f.dbdestino.value=f.cmbdb.options[indice].text;
	f.submit();
}

function ue_salir()
{
   location.href='../index_modules.php' 
}
</script>
</html>
