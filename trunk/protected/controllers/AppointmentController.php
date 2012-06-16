<?php

class AppointmentController extends Controller
{
    
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array(),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(
	                'ajaxGetClientApps',
			        'ajaxGetDresserApps',
			        'ajaxConfirmApp',
			        'ajaxCreateApp',
		        ),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete', 'create','update', 'index','view','report'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionReport() {
	    //TODO
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Appointment;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Appointment']))
		{
			$model->attributes=$_POST['Appointment'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Appointment']))
		{
			$model->attributes=$_POST['Appointment'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Appointment');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Appointment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Appointment']))
			$model->attributes=$_GET['Appointment'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionAjaxGetClientApps($id) {
	    if(empty($id)) {
	        $id = Yii::app()->user->id;
	    }
	    
	    $avs = Appointment::getAppsForFullCalendar($id, 0);
	    echo json_encode($avs);
	}
	
	public function actionAjaxGetDresserApps($id) {
	    if(empty($id)) {
	        $id = Yii::app()->user->id;
	    }
	    $avs = Appointment::getAppsForFullCalendar($id, 1);
	    echo json_encode($avs);
	}
	
	public function actionAjaxConfirmApp($id) {
	    $this->filterClient();
	    $app = Appointment::model()->findByPk($id);
	    if($app->belongsToCurrentClient()) {
	        if($app->confirm()) {
	            echo json_encode(true);
	        }else {
 	            throw new CHttpException('400', "unknown error");
	        }
	    }else {
	        throw new CHttpException('403', 'that\'s not your appointment');
	    }
	}
	
	public function actionAjaxCreateApp() {
	     $this->filterClient();
	     
	     $app = new Appointment();
	     $app->start = $_POST['start'];
	     $app->end = $_POST['end'];
	     $app->clientId = Yii::app()->user->id;
	     $app->hairdresserId = $_POST['hairdresserId'];
	     
	     
	     if($app->validate() && $app->getSplittableAv()) {
	         $app->save();
	         echo json_encode($app->attributes);
	     }else {
	         throw new CHttpException('400', '{"box":["You need to drop the box on hairdressers worktime with enough time to serve you."]}');
	     }
	}
	
	public function actionAjaxDeleteApp($id) {
	    $this->filterClient();
	    $app = Appointment::model()->findByPk($id);
	    
	    if($app->belongsToCurrentClient()) {
    	    if($app->delete()) {
    	        echo json_encode(true);
    	    }else {
    	        throw new CHttpException('400', "unknown error");
    	    }
	    }else {
	        throw new CHttpException('403', 'that\'s not your appointment');
	    }
	}
	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Appointment::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='appointment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	private function filterClient() {
	    $user = Yii::app()->getModule('user')->user();
	    if(!$user || $user->profile->isHairdresser) {
	        throw new CHttpException(403, "You're not a client");
	    }
	}
}
