<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_morsas extends CI_Migration
{
	public function __construct()
	{
		parent::__construct();
	}

	public function up()
	{
		error_log('Migrando Morsas 1');

		execute_script(APPPATH . 'migrations/sqlscripts/migraciones_morsas.sql');

		error_log('Migrando Morsas 1');
	}

	public function down()
	{
	}
}
?>