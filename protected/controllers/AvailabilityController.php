<?php

class AvailabilityController extends Controller
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(
				        'ajaxGetAvs',
				        'ajaxEditAv',
				        'ajaxCreateAv',
				        'ajaxDeleteAv',
				        'test',
			        ),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(
				        'admin',
				        'delete',
				        'create',
				        'update',
				        'index',
				        'view',
				        'report',
				        'ajaxLengthData',
				        'ajaxCountClients',
			        ),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionAjaxLengthData($start, $end) {
	    $avData = User::getLengthReportData($start, $end);
	    
	    echo json_encode($avData);
	}
	
	public function actionAjaxCountClients($start, $end) {
	    $avData = User::getClientCountData($start, $end);
	     
	    echo json_encode($avData);
	}

	
	public function actionReport() {
	    $this->render('report');
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
		$model=new Availability;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Availability']))
		{
			$model->attributes=$_POST['Availability'];
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

		if(isset($_POST['Availability']))
		{
			$model->attributes=$_POST['Availability'];
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
		$dataProvider=new CActiveDataProvider('Availability');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionAjaxGetAvs($id) {
	    if(empty($id)) {
	        $id = Yii::app()->user->id;
	    }
	    $avs = Availability::getAvsForFullCalendar($id);
	    echo json_encode($avs);
	}
	
	public function actionAjaxCreateAv() {
	    $this->filterDresser();
	    $av = new Availability();
	    
	    $av->start = $_POST['start'];
	    $av->end= $_POST['end'];
	    $av->hairdresserId = Yii::app()->user->id;
	    
	    $transaction = Yii::app()->db->beginTransaction();
	    try {
    	    if($av->save()) {
    	        $transaction->commit();
    	        echo $av->id;
    	    }else {
    	        throw new CHttpException('400', json_encode($av->getErrors()));
    	    }
	    } catch(CHttpException $e) {
	        throw $e;
	    } catch(Exception $e) {
	        throw new CHttpException('500', $e->getMessage());
	    }
	}
	
	public function actionAjaxEditAv() {
        $this->filterDresser();
        $av = Availability::model()->findByPk($_POST['availabilityId']);
        $av->start = $_POST['start'];
        $av->end= $_POST['end'];
	     
	    
	     
        try {
            $transaction = Yii::app()->db->beginTransaction();
            if($av->save()) {
                $transaction->commit();
                echo json_encode(true);
            }else {
                $transaction->rollback();
                throw new CHttpException('400', json_encode($av->getErrors()));
            }
        } catch(CHttpException $e) {
            throw $e;
        } catch(Exception $e) {
            throw new CHttpException('500', $e->getMessage());
        }
	}
	
	public function actionAjaxDeleteAv() {
	    $this->filterDresser();
	    $av = Availability::model()->findByPk($_POST['availabilityId']);
	    
	    if($av->delete()) {
	        echo json_encode(true);
	    }else {
	        throw new CHttpException('400', "unknown error");
	    }
	}

	public function actionTest() {
	    $av = Availability::model()->findByPk(1);
	    
	    $av->save();
	    
	    $this->render('update', array('model' => $av));
	}
	
	private function filterDresser() {
	    $user = Yii::app()->getModule('user')->user();
	    if(!$user || !$user->profile->isHairdresser) {
	        throw new CHttpException(403, "You're not a hairdresser");
	    }
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Availability('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Availability']))
			$model->attributes=$_GET['Availability'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Availability::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='availability-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
