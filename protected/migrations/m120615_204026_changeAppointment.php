<?php

class m120615_204026_changeAppointment extends CDbMigration
{
	public function up()
	{
	    $this->dropColumn('Appointment', 'hours');
	    $this->dropColumn('Appointment', 'start');
	    $this->addColumn('Appointment', 'date', 'DATE NOT NULL');
	    $this->addColumn('Appointment', 'hour', 'int(2) NOT NULL');
	    $this->addColumn('Appointment', 'length', 'int(1) NOT NULL DEFAULT 1');
	}

	public function down()
	{
	    $this->dropColumn('Appointment', 'date');
	    $this->dropColumn('Appointment', 'hour');
	    $this->dropColumn('Appointment', 'length');
	    $this->addColumn('Appointment', 'start', 'INT(11) NOT NULL');
	    $this->addColumn('Appointment', 'hours', 'int(2) NOT NULL');
	}
}