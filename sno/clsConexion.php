<?php
class clsConexion{
private $arCon;
protected function fConectar(){
	/*$lcServidor="localhost";
   	$lcUsuario="infoalex";
   	$lcContrasena="20643647";
   	$lcPuerto = "5433";
   	$lcBaseDatos="arroz2013";*/
   	$this->arCon=pg_connect("host= 192.168.2.5 port= 5432 dbname= db_arrozalba_2015 user= huayra password=SRVbd25GTINFA");
}
//ejecutar las sentencias con postrgres
public function fQuery($sql){
	$query = pg_query($this->arCon, $sql);
	return $query;
}

public function fListarNomina(){
	$this->fConectar();
	$sql="SELECT codnom, desnom FROM sno_nomina ORDER BY codnom ASC";
	return ($this->fQuery($sql));
}

public function fBuscarPersonal($ced, $codnom){
	$this->fConectar();
	$sql = "SELECT DISTINCT P.nomper, P.apeper, P.cedper, P.nacper, P.fecingper, PN.sueper, UA.desuniadm, C.descar, C.codcar 
	FROM sno_personal AS P, sno_personalnomina AS PN, sno_unidadadmin AS UA, sno_cargo AS C
	WHERE P.codper = PN.codper AND PN.prouniadm = UA.prouniadm AND PN.codcar = C.codcar AND P.cedper = '$ced' AND PN.codnom='$codnom' ORDER by PN.sueper DESC" ;
	return ($this->fQuery($sql));	
}
/*
public function flistarDetalles($cedula, $codnom, $prima){
	$this->fConectar();
	$sql = "SELECT sno_hsalida.valsal FROM sno_hsalida, sno_personal WHERE sno_personal.codper = sno_hsalida.codper AND
 sno_personal.cedper='$cedula' and sno_hsalida.codconc = '$prima' and sno_hsalida.codnom = '$codnom' and sno_hsalida.codperi='012'";
 return ($this->fQuery($sql));
}
*/
public function fProfesion($cedula, $codnom, $prima){
	$this->fConectar();
	$sql = "SELECT sno_conceptopersonal.aplcon FROM sno_conceptopersonal, sno_personal WHERE sno_personal.codper = sno_conceptopersonal.codper AND
 sno_personal.cedper='$cedula' and sno_conceptopersonal.codconc = '$prima' and sno_conceptopersonal.codnom = '$codnom'";
   return ($this->fQuery($sql));
}

public function fNroHijos($cedula, $codnom){
	$this->fConectar();
	$sql = "select CP.moncon from sno_constantepersonal as CP, sno_personal P where P.cedper = '$cedula' and codcons = '0000000006' and P.codper = CP.codper and CP.codnom = '$codnom'";
  return ($this->fQuery($sql));

}
}
?>
