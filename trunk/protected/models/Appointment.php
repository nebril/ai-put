<?php

/**
 * This is the model class for table "Appointment".
 *
 * The followings are the available columns in table 'Appointment':
 * @property integer $id
 * @property integer $hairdresserId
 * @property integer $clientId
 * @property integer $isConfirmed
 * @property string $date
 * @property integer $hour
 * @property integer $length
 * @property integer $createTime
 *
 * The followings are the available model relations:
 * @property User $client
 * @property User $hairdresser
 */
class Appointment extends Event
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Appointment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Appointment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hairdresserId, clientId, date, hour, createTime', 'required'),
			array('hairdresserId, clientId, isConfirmed, hour, length, createTime', 'numerical', 'integerOnly'=>true),
		    array('length', 'numerical', 'min' => 1, 'max' => 2), 	
		// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, hairdresserId, clientId, isConfirmed, date, hour, length', 'safe', 'on'=>'search'),
	        array('date', 'pastCheck'),
        );
	}
	
	public function pastCheck($attribute, $params) {
	    if($this->isInPast()) {
	        $this->addError($attribute, 'You can\'t operate on past events');
	    }
	}

	public function confirm() {
	    $transaction = Yii::app()->db->beginTransaction();
	    try {
	        $av = $this->getSplittableAv();
	        if(!empty($av)) {
	            if(!$av->split($this->hour, $this->length)){
	                throw new Exception('Wrong split!');
	            }
	            $this->isConfirmed = 1;
	            if(!$this->save()) {
	                throw new Exception('Wrong save!');
	            }
	        }else {
	            $transaction->rollback();
	            return false;
	        }
	        
	        $transaction->commit();
	        return true;
	    } catch(Exception $e) {
	        $transaction->rollback();
	        echo $e->getMessage();
	        return false;
	    }
	}
	
	public function getSplittableAv() {
	    $av = Availability::model()->find(array(
	        'condition' => 'hairdresserId=:hid 
	                AND `date`=:date
	                AND `hour`<=:hour 
	                AND `hour`+length>=:hour+:length',
            'params' => array(
                ':hid' => $this->hairdresserId,
                ':date' => $this->date,
                ':hour' => $this->hour,
                ':length' => $this->length,
            ),        
        ));
	    
	    return $av;
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'client' => array(self::BELONGS_TO, 'User', 'clientId'),
			'hairdresser' => array(self::BELONGS_TO, 'User', 'hairdresserId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'hairdresserId' => 'Hairdresser',
			'clientId' => 'Client',
			'isConfirmed' => 'Is Confirmed',
			'date' => 'Date',
			'hour' => 'Hour',
			'length' => 'Length',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('hairdresserId',$this->hairdresserId);
		$criteria->compare('clientId',$this->clientId);
		$criteria->compare('isConfirmed',$this->isConfirmed);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('hour',$this->hour);
		$criteria->compare('length',$this->length);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function userDresserScope($id) {
	    $this->getDbCriteria()->mergeWith(array(
	            'condition' => 'hairdresserId=:uid',
	            'params' => array(':uid' => $id),
	    ));
	    return $this;
	}
	
	public function userClientScope($id) {
	    $this->getDbCriteria()->mergeWith(array(
	            'condition' => 'clientId=:uid',
	            'params' => array(':uid' => $id),
	    ));
	    return $this;
	}
	
	public function unconfirmedScope() {
	    $this->getDbCriteria()->mergeWith(array(
	            'condition' => 'isConfirmed=0',
	    ));
	    return $this;
	}
	
	public function beforeSave() {
	    if($this->isNewRecord) {
	        $this->createTime = time();
	    }
	    
	    return parent::beforeSave();
	}
	
	public static function getAppsForFullCalendar($id, $isHairdresser) {
	    $criteria = array(
            'with' => array(
                'client' => array('select' => array()),
                'client.profile' => array('alias' => 'p1'),
                'hairdresser' => array('select' => array()),
                'hairdresser.profile' => array('alias' => 'p2'),
            ),
            'condition' => 'isConfirmed=1',
        );
	    
	    if($isHairdresser) {
	        $models = Appointment::model()->userDresserScope($id)->findAll($criteria);
	    }else {
	        $models = Appointment::model()->userClientScope($id)->findAll($criteria);
	    }
	    
	    $result = array();
	    foreach($models as $model) {
	        //var_dump(strtotime($model->date));
	        $startTime = strtotime($model->date) + 3600 * $model->hour;
	    
	        $result[] = array(
	                'id' => 'av' . $model->id,
	                'appointmentId' => $model->id,
	                'title' => $model->client->profile->getFullName() . ' served by ' . $model->hairdresser->profile->getFullName(),
	                'start' => $startTime,
	                'end' => $startTime + 3600 * $model->length,
	                'allDay' => false,
	                'pictureUrl' => Yii::app()->request->baseUrl . '/' . $model->client->profile->photo,
	                'clientName' => $model->client->profile->getFullName(),
	                'type' => 'app',
	        );
	    }
	    return $result;
	}
	
	public function belongsToCurrentClient() {
	    return $this->clientId == Yii::app()->user->id;
	}
}