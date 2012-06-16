<script type="text/javascript">
function makeCountdowns() {
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

function deleteApp() {
	var appId = $(this).parent().attr('data-app-id');
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
	    url: basePath + + '/appointment/ajaxConfirmApp/id/' + appId,
	    type : 'get',
	    success : function(data) {
            location.reload();
        },
        error : function(data) {
        	reportError(data);
        }
	});
}

$(document).ready(function(){
	$('.dragger').draggable({
		zIndex: 999,
		revert: true,
		revertDuration: 0,
	});

	$('#dressers').change(function(){
		makeClientCalendar("clientCalendar", $(this).val());
	});

	$('[data-app-id] a').click(function(){
	    confirmApp($(this).parent().attr('data-app-id'));
	});

    makeCountdowns();
});

</script>

<h3>Choose your fabulous hairdresser!</h3>
<?php echo CHtml::dropDownList('dresser', ' ', $dressers, array('id' => 'dressers')); ?>

<div id="oneHour" class="dragger">short appointment</div>
<div id="twoHour" class="dragger">long appointment</div>
<div id="calendarOverlay">wait a sec</div>
<div id="clientCalendar"></div>

<h3>Uncomfirmed appointments</h3>

<?php 
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_notConfirmed',
)); ?>