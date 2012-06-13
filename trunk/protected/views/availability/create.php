<?php
$this->breadcrumbs=array(
	'Availabilities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Availability', 'url'=>array('index')),
	array('label'=>'Manage Availability', 'url'=>array('admin')),
);
?>

<h1>Create Availability</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>