<?php

class m120527_193756_user extends CDbMigration
{
	public function up()
	{
	    $this->execute("CREATE TABLE `Profile` (
          `user_id` int(11) NOT NULL,
          `lastname` varchar(50) NOT NULL DEFAULT '',
          `firstname` varchar(50) NOT NULL DEFAULT '',
          `phone` varchar(255) NOT NULL DEFAULT '',
          `photo` varchar(255) NOT NULL DEFAULT '',
          `isHairdresser` tinyint(1) NOT NULL DEFAULT '0',
          PRIMARY KEY (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	    
	    $this->execute("INSERT INTO `Profile` VALUES (1,'Admin','Administrator','','',0),(2,'Demo','Demo','','',0),(3,'Kwiek','Maciej','48609651056','assets/android.jpg',0),(4,'aaa','aaa','55555','images/games-animated-gif-002.gif',0);");
	
	    $this->execute("CREATE TABLE `ProfileField` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `varname` varchar(50) NOT NULL,
          `title` varchar(255) NOT NULL,
          `field_type` varchar(50) NOT NULL,
          `field_size` int(3) NOT NULL DEFAULT '0',
          `field_size_min` int(3) NOT NULL DEFAULT '0',
          `required` int(1) NOT NULL DEFAULT '0',
          `match` varchar(255) NOT NULL DEFAULT '',
          `range` varchar(255) NOT NULL DEFAULT '',
          `error_message` varchar(255) NOT NULL DEFAULT '',
          `other_validator` varchar(5000) NOT NULL DEFAULT '',
          `default` varchar(255) NOT NULL DEFAULT '',
          `widget` varchar(255) NOT NULL DEFAULT '',
          `widgetparams` varchar(5000) NOT NULL DEFAULT '',
          `position` int(3) NOT NULL DEFAULT '0',
          `visible` int(1) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`),
          KEY `varname` (`varname`,`widget`,`visible`)
        ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;"
        );
	    
	    $this->execute("INSERT INTO `ProfileField` VALUES (1,'lastname','Last Name','VARCHAR',50,3,1,'','','Incorrect Last Name (length between 3 and 50 characters).','','','','',1,3),(2,'firstname','First Name','VARCHAR',50,3,1,'','','Incorrect First Name (length between 3 and 50 characters).','','','','',0,3),(4,'phone','Phone','VARCHAR',255,0,1,'/^\\+?[0-9\\-]*$/','','Phone number is not valid','','','','',0,1),(5,'photo','Photo','VARCHAR',255,0,1,'','','','{\"file\":{\"allowEmpty\":\"true\",\"maxFiles\":\"1\",\"types\":\"jpg,png,gif\"}}','','UWfile','{\"path\":\"images\"}',0,3),(6,'isHairdresser','Is hairdresser','BOOL',0,0,0,'','1==Yes;0==No','','','0','','',0,0);");
	
	    $this->execute("CREATE TABLE `User` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `username` varchar(20) NOT NULL,
          `password` varchar(128) NOT NULL,
          `email` varchar(128) NOT NULL,
          `activkey` varchar(128) NOT NULL DEFAULT '',
          `createtime` int(10) NOT NULL DEFAULT '0',
          `lastvisit` int(10) NOT NULL DEFAULT '0',
          `superuser` int(1) NOT NULL DEFAULT '0',
          `status` int(1) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`),
          UNIQUE KEY `username` (`username`),
          UNIQUE KEY `email` (`email`),
          KEY `status` (`status`),
          KEY `superuser` (`superuser`)
        ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;"
        );
	    
	    $this->execute("INSERT INTO `User` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3','webmaster@example.com','9a24eff8c15a6a141ece27eb6947da0f',1261146094,1338143390,1,1),(2,'demo','fe01ce2a7fbac8fafaed7c982a04e229','demo@example.com','099f825543f7850cc038b90aaff39fac',1261146096,0,0,1),(3,'lal','2e3817293fc275dbee74bd71ce6eb056','maciej.iai@gmail.com','e6a9cfbf0125796786c32227eeb21a80',1338142876,0,0,0),(4,'lala','12949e83a49a0989aa46ab7e249ca34d','aaa@aa.com','e3f70f37726e6536b2ad24e833fad7c3',1338143316,0,0,0);");
	}

	public function down()
	{
		$this->dropTable("ProfileField");
		$this->dropTable("Profile");
		$this->dropTable("User");
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