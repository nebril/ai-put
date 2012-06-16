<?php

class m120616_003930_appCreateTime extends CDbMigration
{
	public function up()
	{
	    $this->addColumn("Appointment", 'createTime', "INT(11) UNSIGNED NOT NULL DEFAULT 0");
	}

	public function down()
	{
        $this->dropColumn("Appointment", 'createTime');
	}


}