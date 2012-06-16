<?php

/**
 * This is the model class for table "Availability".
 *
 * The followings are the available columns in table 'Availability':
 * @property integer $id
 * @property integer $hairdresserId
 * @property string $date
 * @property integer $hour
 * @property integer $length
 *
 * The followings are the available model relations:
 * @property User $hairdresser
 */
class Availability extends Event
{
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Availability the static model class
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
		return 'Availability';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hairdresserId, date, hour, length', 'required'),
			array('hairdresserId, hour, length', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, hairdresserId, date, hour', 'safe', 'on'=>'search'),
		    array('hour', 'weeklyLoadCheck', 'allowedLoad' => 40),
		    array('hour', 'hoursCheck'),
            array('date', 'roomCheck'),
 	        array('hour', 'overlapCheck'),
		    array('date', 'pastCheck'),
		        
		);
	}
	
	public function weeklyLoadCheck($attribute, $params) {
	    $row = Yii::app()->dbHelper->getQueryResult(
            "SELECT SUM(length) as summary FROM Availability WHERE 
            `hairdresserId`=:hid
	        AND
            `date` 
                BETWEEN 
                    date_sub(:date, interval WEEKDAY(:date) day)
                AND 
                    date_add(date_sub(:date, interval WEEKDAY(:date) day), interval 6 day)
            UNION SELECT SUM(length) as summary FROM Appointment WHERE 
            `hairdresserId`=:hid
            AND
            `date` 
            BETWEEN 
                date_sub(:date, interval WEEKDAY(:date) day)
            AND 
                date_add(date_sub(:date, interval WEEKDAY(:date) day), interval 6 day)"
            ,array(
                ':date' => $this->date,
                ':hid' => $this->hairdresserId,
            )
        );

	    $summary = $row[0]['summary'] + (count($row) == 2 ? $row[1]['summary'] : 0);

	    if(!$this->isNewRecord) {
	        $row = Yii::app()->dbHelper->getQueryResult("SELECT length FROM Availability WHERE id=:id", array(":id" => $this->id));
	        $oldLength = $row[0]['length'];
	    }
	    
	    $invalidNew = $this->isNewRecord && ($summary + $this->length > $params['allowedLoad']);
	    $invalidOld = !$this->isNewRecord && ($summary + $this->length - $oldLength > $params['allowedLoad']);
	    if($invalidNew || $invalidOld) {
	        $this->addError($attribute,'You exceeded your allowed weekly workload!');
	    }
	}
	
    public function hoursCheck($attribute, $params) {
        
        if(date('N', strtotime($this->date)) == 7) {
            
            $this->addError($attribute, 'You can\'t work on sundays');
        }elseif($this->hour < 10) {
            $this->addError($attribute, 'Too early');
        }elseif(date('N', strtotime($this->date)) == 6 && (($this->hour > 15 || $this->hour + $this->length > 16))) {
            $this->addError($attribute, 'Too late');
        }elseif($this->hour > 19 || $this->hour + $this->length > 20) {
            $this->addError($attribute, 'Too late');
        }
    }
    
    public function roomCheck($attribute, $params) {
        if($this->getMaxOverlappingEventsCount() >= Yii::app()->params['workstations']) {
            $this->addError($attribute, 'There is no room for you to work at this time.');
        }
    }
    
    public function overlapCheck($attribute, $params) {
        if($this->isOverlappingSameHairdresserEvent()) {
            $this->addError($attribute, 'You can\'t do two things at one time');
        }
    }
    
    public function pastCheck($attribute, $params) {
        if($this->isInPast()) {
            $this->addError($attribute, 'You can\'t operate on past events');
        }
    }
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
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
			'date' => 'Date',
			'hour' => 'Hour',
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('hour',$this->hour);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function currentUserScope() {
	    return $this->userScope(Yii::app()->user->id);
	}
	
	public function userScope($id) {
	    $this->getDbCriteria()->mergeWith(array(
	            'condition' => 'hairdresserId=:uid',
	            'params' => array(':uid' => $id),
	    ));
	     
	    return $this;
	}
	
	public function split($hour, $length) {
	    $firstLength = $hour - $this->hour;
	    
	    $secondHour = $hour + $length;
	    $secondLength = $this->hour + $this->length - ($hour + $length);
	    
	    $this->delete();
	    
	    $a = true;
	    if($firstLength > 0) {
	        $av1 = new Availability();
	        $av1->attributes = $this->attributes;
	        $av1->id = null;
	        $av1->length = $firstLength;
	        $a = $av1->save();
	    }
	    
	    $b = true;
	    if($secondLength > 0) {
	        $av2 = new Availability();
	        $av2->attributes = $this->attributes;
	        $av2->id = null;
	        $av2->hour = $secondHour;
	        $av2->length = $secondLength;
	        $b = $av2->save();
	    }
	    
	    return $a && $b;
	}
	
	
	public static function getAvsForFullCalendar($id) {
	    $models = Availability::model()->userScope($id)->findAll();
	    $result = array();
	    foreach($models as $model) {
	        //var_dump(strtotime($model->date));
	        $startTime = strtotime($model->date) + 3600 * $model->hour;

	        $result[] = array(
                'id' => 'av' . $model->id,
	            'availabilityId' => $model->id,
	            'title' => 'worktime',
                'start' => $startTime,
	            'end' => $startTime + 3600 * $model->length,
	            'allDay' => false,
	            'type' => 'av',
            );
	    }
	    return $result;
	}
}