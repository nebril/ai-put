<?php

class DefaultController extends Controller
{
    private function filterClient() {
        $user = Yii::app()->getModule('user')->user();
        if(!$user || $user->profile->isHairdresser) {
            throw new CHttpException(403, "You're not a client");
        } 
    }
    
    
	public function actionIndex()
	{
	    $this->filterClient();
	    
	    $dressers = Profile::getHairdresserIdNameArray();
		
	    $dataProvider=new CActiveDataProvider(
	            Appointment::model()->userClientScope(Yii::app()->user->id)->unconfirmedScope(), 
            array(    
	            'pagination'=>array(
	                    'pageSize'=>false,
	            ),
	    ));
		
		$this->render('index', array(
            'dressers' => $dressers,
	        'dataProvider' => $dataProvider,
        ));
	}
}