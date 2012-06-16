<?php
/**
 * Description of DbHelper
 *
 * @author mkwiek
 */
class DbHelper extends CApplicationComponent{
    public function getQueryResult($query, $params = array()) {
        $command = Yii::app()->db->createCommand($query);
        return $command->queryAll(true, $params);
    }
    
    public function executeQuery($query, $params = array()) {
        $command = Yii::app()->db->createCommand($query, $params);
        return $command->execute($params);
    }
}

?>
