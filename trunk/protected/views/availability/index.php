<?php
$this->breadcrumbs=array(
	'Availabilities',
);

$this->menu=array(
	array('label'=>'Create Availability', 'url'=>array('create')),
	array('label'=>'Manage Availability', 'url'=>array('admin')),
);
?>

<h1>Availabilities</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
