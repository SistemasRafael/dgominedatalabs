<?php

require_once("config.php");

function mailboxpowerloginrd($user,$pass){
	 $ldaprdn = trim($user).'@'.DOMINIO; 
     $ldappass = trim($pass); 
     $ds = DOMINIO; 
     $dn = DN;  
     $puertoldap = 389; 
     $ldapconn = ldap_connect($ds,$puertoldap);
       ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION,3); 
       ldap_set_option($ldapconn, LDAP_OPT_REFERRALS,0); 
       $ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass); 
       if ($ldapbind){
		 $filter="(|(SAMAccountName=".trim($user)."))";
         $fields= array("SAMAccountName", "givenname", "sn","mail","memberof"); 
          //$email = array("mail"); 
         //=$info[$x]['mail'][0];
         $sr = @ldap_search($ldapconn, $dn, $filter, $fields); 
         $info = @ldap_get_entries($ldapconn, $sr);
         
         $array = array('cuenta' => $info[0]["samaccountname"][0]
                        ,'nombre' => $info[0]["givenname"][0]
                        ,'last' => $info[0]["sn"][0]
                        ,'correo' => $info[0]["mail"][0]
                        ,'member' => $info[0]["memberof"][0]
                    ); 
         
        // var_dump ($array);
         //$nombre_usuario = $array['nombre']." ".$array['last'];
        // $apellido_usuario = $usuario['last'];
       // echo $nombre_usuario;
        // var_dump ($nombre_usuario);
         //var_dump ($apellido_usuario);       
       //(die);
   	   }else{ 
         	$array=0;
       } 
                            //  (die);
     ldap_close($ldapconn);       
	 return ($array);
} 
?>
