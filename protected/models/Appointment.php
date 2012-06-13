<?php

/**
 * This is the model class for table "Appointment".
 *
 * The followings are the available columns in table 'Appointment':
 * @property integer $id
 * @property integer $hairdresserId
 * @property integer $clientId
 * @property integer $start
 * @property integer $hours
 * @property integer $isConfirmed
 *
 * The followings are the available model relations:
 * @property User $client
 * @property User $hairdresser
 */
class Appointment extends CActiveRecord
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
			array('hairdresserId, clientId, start, hours', 'required'),
			array('hairdresserId, clientId, start, hours, isConfirmed', 'numerical', 'integerOnly'=>true),
	        array('start', 'date', 'format' => 'dd.MM.yyyy H:00'),
		// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, hairdresserId, clientId, start, hours, isConfirmed', 'safe', 'on'=>'search'),
		);
	}

	public function beforeSave() {
	    $this->start = strtotime($this->start);
	}
	
	public function afterFind() {
	    $this->start = date("d.m.Y G:i", $this->start);
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
			'start' => 'Start',
			'hours' => 'Hours',
			'isConfirmed' => 'Is Confirmed',
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
		$criteria->compare('start',$this->start);
		$criteria->compare('hours',$this->hours);
		$criteria->compare('isConfirmed',$this->isConfirmed);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}