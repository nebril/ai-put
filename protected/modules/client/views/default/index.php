<script type="text/javascript">
function prepareUncomfirmedApps() {
	$('[data-app-id] a').unbind('click');
	$('[data-app-id] a').click(function(){
	    confirmApp($(this).parent().attr('data-app-id'));
	});
	
    $(".countdown").empty();

    
    $(".countdown").each(function(){
        var timestamp = parseInt($(this).parent().attr('data-expires-on'))
        $(this).countdown({
            until: new Date(timestamp * 1000),
            format : 'MS',
            onExpiry : deleteApp,
            alwaysExpire : true
        });
    });
}

function deleteCallback() {
	deleteApp($(this).parent().attr('data-app-id'));
}

function deleteApp(appId) {
    $.ajax({
        url : basePath + '/appointment/ajaxDeleteApp/id/' + appId,
        type : 'get',
        success : function(data) {
            $('[data-app-id='+appId+']').remove();
        }
    });
}

function confirmApp(appId) {
	$.ajax({
	    url: basePath + '/appointment/ajaxConfirmApp/id/' + appId,
	    type : 'get',
	    success : function(data) {
            location.reload();
        },
        error : function(data) {
        	reportError(JSON.parse(data.responseText));
        	deleteApp(appId);
        },
        dataType : 'json'
	});
}

$(document).ready(function(){

	$('#dressers').change(function(){
		makeClientCalendar("clientCalendar", $(this).val());
	});

	makeClientCalendar("clientCalendar", 0);
	prepareUncomfirmedApps();
});

</script>

<h3>Choose your fabulous hairdresser!</h3>
<?php echo CHtml::dropDownList('dresser', ' ', $dressers, array('id' => 'dressers')); ?>

<h3>Drag your chosen appointment to the work time of </h3>
<div id="oneHour" class="dragger" data-hours="1">short appointment (1 hour)</div>
<div id="twoHour" class="dragger" data-hours="2">long appointment (2 hour)</div>
<div id="calendarOverlay"></div>
<div id="clientCalendar"></div>

<h3>Uncomfirmed appointments</h3>

<?php 
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_notConfirmed',
    'id' => 'appList',
    'summaryText' => '',
)); ?>