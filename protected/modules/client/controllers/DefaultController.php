<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionAppointment($id) {
	    
	}
	
	public function actionCreateAppointment() {
	    
	    $this->render('createAppointment', array(
            'hairdressers' => Profile::model()->hairdresserScope()->findAll(),
        ));
	}
	
	public function actionAppointments() {
	    $this->render('appointments', array(
	            'appointments' => Appointment::model()->userScope(Yii::app()->user->id)->findAll(),
	    ));
	}
}