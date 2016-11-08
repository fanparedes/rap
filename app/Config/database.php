<?php

class DATABASE_CONFIG {

	public $default = array(
		'datasource' => 'Database/Oracle',
		'driver'	=> 'oracle',
		'connect'	=>	'oci_pconnect',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'portalrap',
		'password' =>'p0Rt4lR4p.D3s4',
		'database' => '(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.10.150.16)(PORT=1521)))(CONNECT_DATA=(SID=dbdesa)))',
		'prefix' => '',
		'schema' => 'PORTALRAP',
		'encoding' => 'utf8',
	); 
}
