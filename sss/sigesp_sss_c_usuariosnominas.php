<?php 
class sigesp_sss_c_usuariosnominas
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_usuariosnominas()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad;
		$this->io_msg=new class_mensajes();
		$this->io_funcion = new class_funciones();
	}

	function  uf_sss_select_usuario_nomina($as_codemp,$as_codnom,$as_codusu)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_usuario_nomina
		//         Access: public  
		//      Argumento: $as_codemp   // codigo de empresa
		//                 $as_codnom   // codigo de nomina
		//                 $as_codusu   // codigo de usuario
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que se encarga de verificar si una nomina existe para un determinado usuario
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 26/10/2006								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codusu FROM sss_permisos_internos".
				  " WHERE codemp = '".$as_codemp."'".
				  "   AND codintper ='".$as_codnom."'".
				  "   AND codusu ='".$as_codusu."'".
				  "   AND codsis ='SNO'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosnominas M�TODO->uf_sss_select_usuario_nomina ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_select_usuario_nomina

	function  uf_sss_select_grupo_nomina($as_codemp,$as_codnom,$as_codgru)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_grupo_nomina
		//         Access: public  
		//      Argumento: $as_codemp   // codigo de empresa
		//                 $as_codnom   // codigo de nomina
		//                 $as_codgru   // codigo de grupo
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que se encarga de verificar si una nomina existe para un determinado grupo
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creaci�n: 03/06/2010								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "SELECT codgru FROM sss_permisos_internos_grupo".
				  " WHERE codemp = '".$as_codemp."'".
				  "   AND codintper ='".$as_codnom."'".
				  "   AND codgru ='".$as_codgru."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosnominas M�TODO->uf_sss_select_grupo_nomina ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_select_usuario_nomina
	
	function  uf_sss_load_usuarios($as_codemp,&$aa_usuarios)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_usuarios
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $aa_usuarios    //arreglo de usuarios
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que carga los datos de los usuarios
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 01/11/2005							Fecha �ltima Modificaci�n : 01/11/2005 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_usuarios".
				" WHERE  codemp ='".$as_codemp."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosnominas M�TODO->uf_sss_load_usuarios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_usuarios[$li_pos]["nomusu"]=$row["nomusu"];  
				$aa_usuarios[$li_pos]["apeusu"]=$row["apeusu"];  
				$aa_usuarios[$li_pos]["codusu"]=$row["codusu"];  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_usuarios

	function  uf_sss_load_nominasdisponibles($as_codemp,$as_codusugrup,&$aa_disponibles,$as_usugrup)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_nominasdisponibles
		//         Access: public  
		//      Argumento: $as_codemp      //codigo de empresa
		//                 $as_codusugrup  //codigo de usuario o grupo
		//                 $aa_disponibles //arreglo de usuarios disponibles
		//  			   $as_usugrup     //Indica si es usuario o grupo
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que carga los datos de las nominas que estan disponibles para un determinado usuario o grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 26/10/2006				Fecha �ltima Modificaci�n : 03/06/2010 Por: Ing. Nelson Barraez
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		if($as_usugrup=='U')
		{
			$ls_sql=" SELECT codnom,desnom FROM sno_nomina".
					"  WHERE codemp='".$as_codemp."' ".
					"AND TRIM(codnom) NOT IN".
					" (SELECT TRIM(codintper) FROM sss_permisos_internos".
					"   WHERE codemp ='".$as_codemp."'".
					"     AND codusu ='".$as_codusugrup."'".
					"     AND codsis ='SNO') ORDER BY codnom";
		}
		else
		{
			$ls_sql=" SELECT codnom,desnom FROM sno_nomina".
					"  WHERE codemp='".$as_codemp."' ".
					"    AND TRIM(codnom) NOT IN (SELECT TRIM(codintper) FROM sss_permisos_internos_grupo".
					"   WHERE codemp ='".$as_codemp."'".
					"     AND codgru ='".$as_codusugrup."') ORDER BY codnom";
		}	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosnominas M�TODO->uf_sss_load_nominasdisponibles ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_disponibles["codnom"][$li_pos]=$row["codnom"];  
				$aa_disponibles["desnom"][$li_pos]=$row["desnom"];  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_nominasdisponibles

	function  uf_sss_load_nominasasignadas($as_codemp,$as_codusugrup,&$aa_asignados,$as_usugrup)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_nominasasignadas
		//         Access: public  
		//      Argumento: $as_codemp     //codigo de empresa
		//                 $as_codusugrup //codigo de usuario
		//                 $aa_asignados  //arreglo de usuarios asignados
		//				   $as_usugrup    //Indica si es usuario o grupo
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que carga los datos de las nominas que estan asignados para un determinado usuario o grupo
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 26/10/2006			Fecha �ltima Modificaci�n : 03/06/2010 Por : Ing. Nelson Barraez
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		if($as_usugrup=='U')
		{
			$ls_sql= "SELECT codintper,desnom".					 
					 " FROM sss_permisos_internos,sno_nomina ".
					 " WHERE sno_nomina.codemp= '".$as_codemp."'".
					 "   AND codusu= '".$as_codusugrup."'".
					 "   AND codsis= 'SNO' AND sss_permisos_internos.codintper <>'---------------------------------' ".
					 "   AND sno_nomina.codemp=sss_permisos_internos.codemp".
					 "   AND sno_nomina.codnom=sss_permisos_internos.codintper";
		}
		else
		{
			$ls_sql= "SELECT codintper,desnom".
					 " FROM sss_permisos_internos_grupo,sno_nomina ".
					 " WHERE sno_nomina.codemp= '".$as_codemp."'".
					 "   AND codgru= '".$as_codusugrup."' AND sss_permisos_internos_grupo.codintper <>'---------------------------------' ".
					 "   AND sno_nomina.codemp=sss_permisos_internos_grupo.codemp".
					 "   AND sno_nomina.codnom=sss_permisos_internos_grupo.codintper ";
		}			 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosnominas M�TODO->uf_sss_load_nominasasignadas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$aa_asignados["codintper"][$li_pos]=$row["codintper"];  
				$aa_asignados["desnom"][$li_pos]=$row["desnom"];  
				$li_pos=$li_pos+1;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_nominasasignadas

	function  uf_sss_insert_usuario_nomina($as_codemp,$as_codnom,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_usuario_nomina
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codnom    // codigo de nomina (codigo interno de permisologia)
		//      		   $as_codusu    // codigo de usuario
		//      		   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que inserta un usuario en determinado nomina en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 26/10/2006									Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_permisos_internos (codemp, codsis, codusu, codintper) ".
				  "     VALUES('".$as_codemp."','SNO','".$as_codusu."','".$as_codnom."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosnominas M�TODO->uf_sss_insert_usuario_nomina ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Relacion� la Nomina ".$as_codnom." al usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function  uf_sss_insert_usuario_nomina
	
	function  uf_sss_insert_grupo_nomina($as_codemp,$as_codnom,$as_codgru,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_grupo_nomina
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//      		   $as_codnom    // codigo de nomina (codigo interno de permisologia)
		//      		   $as_codgru    // codigo de grupo
		//      		   $aa_seguridad // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que inserta nomina a determinado grupos sen la tabla sss_permisos_internos_grupo
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creaci�n: 03/06/2006									Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_permisos_internos_grupo (codemp, codgru, codintper) ".
				  "     VALUES('".$as_codemp."', '".$as_codgru."','".$as_codnom."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosnominas M�TODO->uf_sss_insert_grupo_nomina ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Relacion� la Nomina ".$as_codnom." al grupo ".$as_codgru." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	} // end  function  uf_sss_insert_grupo_nomina

	function uf_sss_delete_usuario_nomina($as_codemp,$as_codnom,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_usuario_grupo
		//         Access: public  
		//      Argumento: $as_codemp     // codigo de empresa
		//      		   $as_codnom     // codigo de nomina
		//      		   $as_codusu     // codigo de usuario
		//      		   $aa_seguridad  // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que elimina un usuario en determinado nomina en la tabla sss_permisos_internos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 27/10/2006									Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= " DELETE FROM sss_permisos_internos".
				 " WHERE codemp= '".$as_codemp. "'".
				 "   AND codintper= '".$as_codnom. "'".
				 "   AND codusu= '".$as_codusu."'".
				 "   AND codsis='SNO'"; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosnominas M�TODO->uf_sss_delete_usuario_nomina ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Elimin� la Nomina ".$as_codnom." al usuario ".$as_codusu." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_sss_delete_usuario_nomina

	function uf_sss_delete_grupo_nomina($as_codemp,$as_codnom,$as_codgru,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_grupo_nomina
		//         Access: public  
		//      Argumento: $as_codemp     // codigo de empresa
		//      		   $as_codnom     // codigo de nomina
		//      		   $as_codgru     // codigo de grupo
		//      		   $aa_seguridad  // arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: funcion que elimina nominas asociadas al grupo en la tabla sss_permisos_internos_grupo
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creaci�n: 03/06/2010									Fecha �ltima Modificaci�n :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql= " DELETE FROM sss_permisos_internos_grupo".
				 " WHERE codemp= '".$as_codemp. "'".
				 "   AND codintper= '".$as_codnom. "'".
				 "   AND codgru= '".$as_codgru."'"; 
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosnominas M�TODO->uf_sss_delete_grupo_nomina ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
			$ls_evento="DELETE";
			$ls_descripcion ="Elimin� la Nomina ".$as_codnom." al grupo ".$as_codgru." Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////			
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function uf_sss_delete_grupo_nomina

	function  uf_sss_load_permisos($as_codemp,$as_codnom,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_load_permisos
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_codnom    // codigo de nomina
		//                 $as_codusu    // codigo de usuario
		//                 $aa_seguridad //arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que verifica si un usuario tiene definido algun perfil de seguridad en las nominas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 27/10/2006								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="  SELECT nomven,visible,enabled,leer,incluir,cambiar,eliminar,imprimir,administrativo,anular,ejecutar".
				"    FROM sss_derechos_usuarios".
				"   WHERE codemp= '".$as_codemp."'".
				"     AND codusu= '".$as_codusu."'".
				"     AND codsis='SNO' ".
				"GROUP BY nomven,visible,enabled,leer,incluir,cambiar,eliminar,imprimir,administrativo,anular,ejecutar";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosnominas M�TODO->uf_sss_load_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ls_nomven=$row["nomven"];  
				$li_visible=$row["visible"];  
				$li_enabled=$row["enabled"];  
				$li_leer=$row["leer"];  
				$li_incluir=$row["incluir"];  
				$li_cambiar=$row["cambiar"];  
				$li_eliminar=$row["eliminar"];  
				$li_imprimir=$row["imprimir"];  
				$li_administrador=$row["administrativo"];  
				$li_anular=$row["anular"];  
				$li_ejecutar=$row["ejecutar"];  
				$lb_valido=$this->uf_sss_insert_derecho_usuario($as_codemp,$as_codusu,'SNO',$ls_nomven,$li_visible,$li_enabled,
									   					 		$li_leer,$li_incluir,$li_cambiar,$li_eliminar,$li_imprimir,
														 		$li_administrador,$li_anular,$li_ejecutar,$as_codnom);
				if(!$lb_valido)
				{break;}
				$li_pos=$li_pos+1;
			}
			if(($li_pos>0)&&($lb_valido))
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion="Actualiz� el perfil de seguridad en la  Nomina ".$as_codnom." al usuario ".$as_codusu.
								 " Asociado a la empresa ".$as_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}  // end  function  uf_sss_load_permisos

	function  uf_sss_insert_derecho_usuario($as_codemp,$as_codusu,$as_codsis,$as_nomven,$ai_visible,$ai_enabled,$ai_leer,
											$ai_incluir,$ai_cambiar,$ai_eliminar,$ai_imprimir,$ai_administrador,$ai_anular,
											$ai_ejecutar,$as_codintper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_insert_derecho_usuario
		//         Access: public  
		//      Argumento: $as_codemp        // codigo de empresa
		//      		   $as_codusu        // codigo de usuario
		//      		   $as_codsis        // codigo de sistema
		//      		   $as_nomven        // nombre de la ventana (fisico)
		//      		   $ai_visible       // indica si puede ver o no la pantalla
		//      		   $ai_enabled       // indica si tiene permiso o no a la pantalla
		//      		   $ai_leer          // indica si tiene permiso o no de lectura
		//      		   $ai_incluir       // indica si tiene permiso o no de incluir
		//      		   $ai_cambiar       // indica si tiene permiso o no demodificar
		//      		   $ai_habilitada    // indica si tiene permiso o no 
		//      		   $ai_imprimir      // indica si tiene permiso o no de imprimir
		//      		   $ai_administrador // indica si tiene permiso o no de administrador
		//      		   $ai_anular        // indica si tiene permiso o no de anular
		//      		   $ai_ejecutar      // indica si tiene permiso o no de ejecutar
		//      		   $as_codintper     // codigo interno de permisos
		//    Description: Funci�n que se encarga de otorgar permisos a un usuario en determinada  pantalla
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 27/10/2006									Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql = "INSERT INTO sss_derechos_usuarios (codemp,codusu,codsis,nomven,visible,enabled,leer,incluir,cambiar,". 
				  "									  eliminar,imprimir,administrativo,anular,ejecutar,codintper) ".
				  "     VALUES('".$as_codemp."','".$as_codusu."','".$as_codsis."','".$as_nomven."',".$ai_visible.",".
				  " 	        ".$ai_enabled.",".$ai_leer.",".$ai_incluir.",".$ai_cambiar.",".$ai_eliminar.",".$ai_imprimir.",".
				  "             ".$ai_administrador.",".$ai_anular.",".$ai_ejecutar.",'".$as_codintper."')" ;
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->usuariosnominas M�TODO->uf_sss_insert_derecho_usuario ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_insert_derecho_usuario

	function  uf_sss_delete_permisos($as_codemp,$as_codnom,$as_codusu,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_delete_permisos
		//         Access: public  
		//      Argumento: $as_codemp    // codigo de empresa
		//                 $as_codusu    // codigo de usuario
		//                 $as_codnom    // codigo de nomina
		//                 $aa_seguridad //arreglo de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Funci�n que elimina los permisos de un usuario a alguna nomina en especifico
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 27/10/2006								Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="DELETE FROM sss_derechos_usuarios".
			    " WHERE codemp='" .$as_codemp ."'".
			    "   AND codusu='" .$as_codusu ."'".
			    "   AND codsis='SNO'".
			    "   AND codintper='" .$as_codnom ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->usuariosnominas M�TODO->uf_sss_delete_permisos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion="Elimin� el perfil de seguridad en la  Nomina ".$as_codnom." al usuario ".$as_codusu.
							 " Asociado a la empresa ".$as_codemp;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$lb_valido=true;
		}
		return $lb_valido;
	}  // end  function  uf_sss_delete_permisos
	
	
	function uf_llenar_combo_usuarios(&$aa_usuarios)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_llenar_combo_usuarios
		//         Access: public (sigesp_c_permisos_globales)
		//      Argumento: $aa_usuarios // arreglo de valores que puede tomar el combo.
		//    Description: Funci�n que se encarga de llenar el arreglo del combo de Usuarios
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creaci�n: 02/06/2010									Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT * FROM sss_usuarios".
				" ORDER BY codusu ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		$li_pos=0;
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->permisos_globales M�TODO->uf_llenar_combo_usuarios ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_pos=$li_pos+1;
				$aa_usuarios["codusu"][$li_pos]=$row["codusu"];   
				$aa_usuarios["codusu"][$li_pos]=$row["codusu"];   
			}
			$lb_valido=true;
		}
	} // end function uf_llenar_combo_usuarios

	function uf_pintar_combo_usuarios($aa_usuarios,$as_usuario)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintar_combo_usuarios
		//         Access: public (sigesp_c_permisos_globales)
		//      Argumento: $aa_usuarios // arreglo de valores que puede tomar el combo.
		//      		   $as_usuario // item seleccionado.
		//    Description: Funci�n que se encarga de cargar el combo de usuarios 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creaci�n: 02/06/2010									Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "<select name='cmbusuarios' id='cmbusuarios' onChange='ue_seleccionar();' style='width:200px'>";
		print "<option value= --- selected>--Seleccione Uno-- </option>";
		$li_total=count($aa_usuarios["codusu"]);
		for($i=1; $i <= $li_total ; $i++)
		{			
			if($aa_usuarios["codusu"][$i]==$as_usuario)
			{
				print "<option value='".$aa_usuarios["codusu"][$i]."' selected>".$aa_usuarios["codusu"][$i]."</option>";
			}
			else
			{
				print "<option value='".$aa_usuarios["codusu"][$i]."'>".$aa_usuarios["codusu"][$i]."</option>";
			}
		}
		print"</select>";
	}  //  end  function uf_pintar_combo_usuarios
	
	function uf_llenar_combo_grupos(&$aa_grupos)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_llenar_combo_grupos
		//         Access: public (sigesp_c_usuariospresupuesto)
		//      Argumento: $aa_grupos // arreglo de valores que puede tomar el combo.
		//    Description: Funci�n que se encarga de llenar el arreglo del combo de grupos
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creaci�n: 02/06/2010									Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="SELECT codgru,nomgru FROM sss_grupos".
				" ORDER BY codgru ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		$li_pos=0;
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->permisos_globales M�TODO->uf_llenar_combo_grupos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_pos=$li_pos+1;
				$aa_grupos["codgru"][$li_pos]=$row["codgru"];   
				$aa_grupos["nomgru"][$li_pos]=$row["nomgru"];   
			}
			$lb_valido=true;
		}
	} // end function uf_llenar_combo_usuarios

	function uf_pintar_combo_grupos($aa_grupos,$as_grupo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintar_combo_grupos
		//         Access: public (sigesp_c_usuariospresupuesto)
		//      Argumento: $aa_usuarios // arreglo de valores que puede tomar el combo.
		//      		   $as_grupos // item seleccionado.
		//    Description: Funci�n que se encarga de cargar el combo de usuarios 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creaci�n: 02/06/2010									Fecha �ltima Modificaci�n : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "<select name='cmbgrupos' id='cmbgrupos' onChange='ue_seleccionar();' style='width:200px'>";
		print "<option value= --- selected>--Seleccione Uno-- </option>";
		$li_total=count($aa_grupos["codgru"]);
		for($i=1; $i <= $li_total ; $i++)
		{			
			if($aa_grupos["codgru"][$i]==$as_grupo)
			{
				print "<option value='".$aa_grupos["codgru"][$i]."' selected>".$aa_grupos["nomgru"][$i]."</option>";
			}
			else
			{
				print "<option value='".$aa_grupos["codgru"][$i]."'>".$aa_grupos["nomgru"][$i]."</option>";
			}
		}
		print"</select>";
	}  //  end  function uf_pintar_combo_grupos
	
}//  end  class sigesp_sss_c_usuarios_grupos

?>
