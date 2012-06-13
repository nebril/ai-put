<?php
class DefaultController extends Controller
{
    private function filterDresser() {
        $user = Yii::app()->getModule('user')->user();
        if(!$user || !$user->profile->isHairdresser) {
            throw new CHttpException(403, "You're not a hairdresser");
        } 
    }
    
    
	public function actionIndex()
	{
	    $this->filterDresser();
		$this->render('index');
	}
	
	public function actionAvailability() {
        $this->filterDresser();
	    $this->render('availability');
	}
	
	public function actionAppointments() {
	    $this->filterDresser();
	    $this->render('appointments');
	}
}