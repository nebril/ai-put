<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/jscharts.js');
?>

<script type="text/javascript">
$(document).ready(function(){
	$( "#from" ).datepicker({
		defaultDate: "-1w",
		changeMonth: true,
		numberOfMonths: 1,
		onSelect: function( selectedDate ) {
			$( "#to" ).datepicker( "option", "minDate", selectedDate );
		},
		dateFormat : 'yy-mm-dd'
	});
	$( "#to" ).datepicker({
		changeMonth: true,
		numberOfMonths: 1,
		onSelect: function( selectedDate ) {
			$( "#from" ).datepicker( "option", "maxDate", selectedDate );
		},
		dateFormat : 'yy-mm-dd'
	});

	$('#chooseDate button').click(function() {

		var lengthChart = new JSChart('lengthChart', 'bar');
		lengthChart.setDataJSON(basePath + '/availability/ajaxLengthData/start/'+$('#from').val()+'/end/'+$('#to').val());

		lengthChart.setTitle('Length of work per hairdresser');
		lengthChart.setTitleColor('#8E8E8E');
		lengthChart.setAxisNameX('');
		lengthChart.setAxisNameY('Length');
		lengthChart.setAxisNameFontSize(16);
		lengthChart.setAxisNameColor('#999');
		lengthChart.setAxisValuesAngle(30);
		lengthChart.setAxisValuesColor('#777');
		lengthChart.setAxisColor('#B5B5B5');
		lengthChart.setAxisWidth(1);
		lengthChart.setBarValuesColor('#2F6D99');
		lengthChart.setAxisPaddingTop(60);
		lengthChart.setAxisPaddingBottom(60);
		lengthChart.setAxisPaddingLeft(45);
		lengthChart.setTitleFontSize(11);
		lengthChart.setBarColor('#2D6B96', 1);
		lengthChart.setBarColor('#9CCEF0', 2);
		lengthChart.setBarBorderWidth(0);
		lengthChart.setBarSpacingRatio(50);
		lengthChart.setBarOpacity(0.9);
		lengthChart.setFlagRadius(6);
		lengthChart.setLegendShow(true);
		lengthChart.setLegendPosition('right top');
		lengthChart.setLegendForBar(1, 'Available hours');
		lengthChart.setLegendForBar(2, 'Appointment hours');
		lengthChart.setSize(616, 321);
		lengthChart.setGridColor('#C6C6C6');

		lengthChart.draw();

		var countChart = new JSChart('countChart', 'bar');
		countChart.setDataJSON(basePath + '/availability/ajaxCountClients/start/'+$('#from').val()+'/end/'+$('#to').val());

		countChart.setTitle('Appointments made');
		countChart.setTitleColor('#8E8E8E');
		countChart.setAxisNameX('');
		countChart.setAxisNameY('Count');
		countChart.setAxisNameFontSize(16);
		countChart.setAxisNameColor('#999');
		countChart.setAxisValuesAngle(30);
		countChart.setAxisValuesColor('#777');
		countChart.setAxisColor('#B5B5B5');
		countChart.setAxisWidth(1);
		countChart.setBarValuesColor('#2F6D99');
		countChart.setAxisPaddingTop(60);
		countChart.setAxisPaddingBottom(60);
		countChart.setAxisPaddingLeft(45);
		countChart.setTitleFontSize(11);
		//countChart.setBarColor('#2D6B96', 1);
		countChart.setBarBorderWidth(0);
		countChart.setBarSpacingRatio(50);
		countChart.setBarOpacity(0.9);
		countChart.setFlagRadius(6);
		countChart.setLegendShow(true);
		countChart.setLegendPosition('right top');
		countChart.setSize(616, 321);
		countChart.setGridColor('#C6C6C6');

		countChart.draw();

	});
});
</script>

<h3>Choose report date range</h3>
<div id="chooseDate">
    <label for="from">From</label>
    <input type="text" id="from" name="from"/>
    <label for="to">to</label>
    <input type="text" id="to" name="to"/>
    <button>Go!</button>
</div>

<div id="charts">
<div id="lengthChart"></div>
<div id="countChart"></div>
</div>
