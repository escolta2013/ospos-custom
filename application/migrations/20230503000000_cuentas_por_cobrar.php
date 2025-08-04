<?php

class Migration_cuentas_por_cobrar extends CI_Migration {

	public function __construct()
	{
		parent::__construct();
	}

	public function up()
	{
		error_log('Migrating cuentas_por_cobrar');

		execute_script(APPPATH . 'migrations/sqlscripts/nuker/cuentas_por_cobrar.sql');

		error_log('Migrating cuentas_por_cobrar');
	}

	public function down()
	{
	}
}
