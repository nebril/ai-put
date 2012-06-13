<?php
$this->breadcrumbs=array(
	'Availabilities'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Availability', 'url'=>array('index')),
	array('label'=>'Create Availability', 'url'=>array('create')),
	array('label'=>'View Availability', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Availability', 'url'=>array('admin')),
);
?>

<h1>Update Availability <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>