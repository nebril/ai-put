<?php

class m120527_221336_appointment extends CDbMigration
{
	public function up()
	{
	    $pk = "int(11) NOT NULL";
	    
	    $this->createTable('Availability', array(
            'id' => $pk . ' PRIMARY KEY AUTO_INCREMENT',
            'hairdresserId' => $pk,
	        'date' => 'DATE NOT NULL',
	        'hour' => 'int(2) NOT NULL',
        ), 'ENGINE=InnoDB');

	    $this->addForeignKey('fk_Availability_Hairdresser', 'Availability',
	            'hairdresserId', 'User', 'id');
	    
	    $this->createTable('Appointment', array(
            'id' => $pk . ' PRIMARY KEY AUTO_INCREMENT',
            'hairdresserId' => $pk,
            'clientId' => $pk,
            'start' => 'INT(11) NOT NULL',
            'hours' => 'int(2) NOT NULL',
            'isConfirmed' => 'BOOLEAN NOT NULL DEFAULT FALSE',
	    ),'ENGINE=InnoDB');
	    
	    $this->addForeignKey('fk_Appointment_Hairdresser', 'Appointment', 
	            'hairdresserId', 'User', 'id');
	    
	    $this->addForeignKey('fk_Appointment_Client', 'Appointment',
	            'clientId', 'User', 'id');
	}

	public function down()
	{
		$this->dropTable('Appointment');
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