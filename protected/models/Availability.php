<?php

/**
 * This is the model class for table "Availability".
 *
 * The followings are the available columns in table 'Availability':
 * @property integer $id
 * @property integer $hairdresserId
 * @property string $date
 * @property integer $hour
 *
 * The followings are the available model relations:
 * @property User $hairdresser
 */
class Availability extends CActiveRecord
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
			array('hairdresserId, date, hour', 'required'),
			array('hairdresserId, hour', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, hairdresserId, date, hour', 'safe', 'on'=>'search'),
		);
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
}