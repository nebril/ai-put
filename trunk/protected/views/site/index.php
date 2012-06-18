<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?php 
$this->menu=array(
	array('label'=>'Appointments', 'url'=>array('/appointment/admin')),
	array('label'=>'Availabilities', 'url'=>array('/availability/admin')),
        array('label'=>'User', 'url'=>array('/user/admin')),
        array('label'=>'Reports', 'url'=>array('/availability/report')),
);
?>

		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>$this->menu
        ));?>