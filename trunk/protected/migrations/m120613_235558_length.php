<?php

class m120613_235558_length extends CDbMigration
{
	public function up()
	{
	    $this->addColumn('Availability', 'length', 'INTEGER(1) NOT NULL DEFAULT 1');
	}

	public function down()
	{
		 $this->dropColumn('Availability', 'length');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}