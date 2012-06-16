<?php 
$this->menu=array(
	array('label'=>'Manage availability', 'url'=>array('availability')),
	array('label'=>'See appointments', 'url'=>array('appointments')),
);

$this->widget('zii.widgets.CMenu', array(
        'items'=>$this->menu
));
?>

<script type="text/javascript">

$(document).ready(function(){
	$('#dragger').draggable({
		zIndex: 999,
		revert: true,
		revertDuration: 0,
	});
	makeDresserCalendar("availabilities");
});

</script>

<div class="dragger">Drag me to the calendar to add time</div>
<div id="calendarOverlay">wait a sec</div>
<div id="availabilities"></div>