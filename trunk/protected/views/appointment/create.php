<?php
$this->breadcrumbs=array(
	'Appointments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Appointment', 'url'=>array('index')),
	array('label'=>'Manage Appointment', 'url'=>array('admin')),
);
?>

<h1>Create Appointment</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>