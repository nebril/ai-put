<?php
/*
 * @property string $date
 * @property integer $hour
 * @property integer $length
 */

class Event extends CActiveRecord {
    public $start;
    public $end;
    
    public function getMaxOverlappingEventsCount() {
        $events = array_merge(
            Availability::model()->findAllByAttributes(array('date' => $this->date)),
            Appointment::model()->findAllByAttributes(array('date' => $this->date, 'isConfirmed' => 1))
        );
        
        $maxCount = 0;
        for($hour = $this->hour ; $hour < $this->length + $this->hour; $hour++){
            $count = 0;
            foreach($events as $ev) {
                if(!(get_class($ev) == get_class($this) && $ev->id == $this->id) && $ev->isAtHour($hour)) {
                    $count++;
                }
            }
            	
            if($count > $maxCount) {
                $maxCount = $count;
            }
        }
         
        return $maxCount;
    }
    
    public function isAtHour($hour) {
        return $hour >= $this->hour && $hour < $this->hour + $this->length;
    }
    
    public function beforeValidate() {
        if(isset($this->start) && isset($this->end)) {
            $this->date = date('Y-m-d', $this->start);
            $this->hour = date('G', $this->start);
            $this->length = ($this->end - $this->start) / 3600;
        }
         
        return parent::beforeValidate();
    }
    
    public function isInPast() {
        $currentHour = strtotime(date('Y-m-d H:00:00'));
        $eventTimestamp = strtotime($this->date . ' ' . $this->hour . ':00:00');
        
        return $currentHour >= $eventTimestamp;
    }
    
    public static function getAllLengthsByHairId($start, $end) {
        $result = Yii::app()->dbHelper->getQueryResult(
            'SELECT hairdresserId as id, SUM(avlength) as avlength, SUM(applength) as applength FROM (
            	SELECT hairdresserId, SUM(length) as avlength, 0 as applength FROM Availability av WHERE date BETWEEN DATE(:start) AND DATE(:end) GROUP BY hairdresserId
            	UNION
            	SELECT hairdresserId, 0 as avlength, SUM(length) as applength FROM Appointment ap WHERE date BETWEEN DATE(:start) AND DATE(:end) GROUP BY hairdresserId
            ) s GROUP BY id',
                array(
                        ':start' => $start,
                        ':end' => $end
                )
        );
        
        return $result;
    }
    
    public static function getLengthByHairId($start, $end) {
        $result = Yii::app()->dbHelper->getQueryResult("SELECT hairdresserId as id, SUM(length) as value
                FROM " . get_called_class() . 
                ' WHERE date BETWEEN DATE(:start) AND DATE(:end)
                GROUP BY hairdresserId',
            array(
                ':start' => $start,
                ':end' => $end
            )
        );

        return $result;
    }
    
    public static function getCountByHairId($start, $end) {
        $result =  Yii::app()->dbHelper->getQueryResult("SELECT hairdresserId as id, COUNT(length) as count
                FROM " . get_called_class() .
                ' WHERE date BETWEEN DATE(:start) AND DATE(:end)
                GROUP BY hairdresserId',
                array(
                        ':start' => $start,
                        ':end' => $end
                )
        );
        
        return $result;
    }
    
    public function isOverlappingSameHairdresserEvent() {
        $events = array_merge(
            Availability::model()->findAllByAttributes(
                array(
                    'date' => $this->date,
                    'hairdresserId' => $this->hairdresserId
                )
            ),
            Appointment::model()->findAllByAttributes(
                array(
                    'date' => $this->date,
                    'hairdresserId' => $this->hairdresserId,
                    'isConfirmed' => 1,
                )
            )
        );
        
        for($hour = $this->hour ; $hour < $this->length + $this->hour; $hour++){
            $count = 0;
            foreach($events as $ev) {
                if(!(get_class($ev) == get_class($this) && $ev->id == $this->id) && $ev->isAtHour($hour)) {
                    return true;
                }
            }
        }
        
        return false;
    }
}