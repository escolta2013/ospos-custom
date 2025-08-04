<?php

class Migration_nuker1 extends CI_Migration {

	public function __construct()
	{
		parent::__construct();
	}

	public function up()
	{
		error_log('Error Nuker 1');

		execute_script(APPPATH . 'migrations/sqlscripts/nuker/nuker1.sql');

		error_log('Error Nuker 1');
	}

	public function down()
	{
	}
}
