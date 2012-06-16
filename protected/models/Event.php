<?php
/*
 * @property string $date
 * @property integer $hour
 * @property integer $length
 */

class Event extends CActiveRecord {
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