var globalGrainMinutes = 60;
var calendarLoaded;

$(document).ready(function(){

});

function makeDresserCalendar(calendarId) {
	calendarLoaded = true;
	$("#"+calendarId).empty();
	$.get(basePath+'/availability/ajaxGetAvs/id/',
		function(data) {
			$('#'+calendarId).fullCalendar({
				aspectRatio : 0.5,
				contentHeight : 700,
				minTime : 10,
				maxTime : 20,
				firstDay: 1,
				defaultView: 'agendaWeek',
				slotMinutes: globalGrainMinutes,
				allDaySlot : false,
				editable: true,
				droppable: true,
				events: data,
				drop: function(date, allDay) {
					var eventObject = {};
					
					// assign it the date that was reported
					eventObject.start = date.getTime() / 1000;
					eventObject.end = date.getTime() / 1000 + 2*3600;
					eventObject.allDay = false;
					eventObject.title = 'worktime',
					
					blockEdition(calendarId);
					
					$.ajax({
						type: 'POST',
						url: basePath+'/availability/ajaxCreateAv',
						data: {
							start : eventObject.start,
							end : eventObject.end
						},
						success: function(data) {
							eventObject.availabilityId = data;
							$('#'+calendarId).fullCalendar('renderEvent', eventObject, true);
							allowEdition();
						},
						error: function(data) {
							reportError(data);
							allowEdition();
						}
					});
					
				},
				eventDrop: function( event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view ) { 
					blockEdition(calendarId);
					editAvailability(event, revertFunc);
					jsEvent.stopPropagation();
				},
				eventResize: function( event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view ) {
					blockEdition(calendarId);
					editAvailability(event, revertFunc);
				},
				eventDragStop : function( event, jsEvent, ui, view ) {
					if(!(jsEvent.pageX > $('#'+calendarId).offset().left 
							&& jsEvent.pageX < $('#'+calendarId).offset().left + $('#'+calendarId).width()
							&& jsEvent.pageY > $('#'+calendarId).offset().top 
							&&jsEvent.pageY < $('#'+calendarId).offset().top + $('#'+calendarId).height()
					)) {
						$.ajax({
							type: 'POST',
							url: basePath+'/availability/ajaxDeleteAv',
							data: {
								availabilityId : event.availabilityId
							},
							success: function() {
								$('#'+calendarId).fullCalendar('removeEvents', function(checkedEvent) {
									return checkedEvent.availabilityId == event.availabilityId;
								});
							},
							error: function(data) {
								reportError(data);
							}
						});
					}
				},
				eventClick : function( event, jsEvent, view ) {
					
					return false;
				}
			});
		},
		'json'
	);
}

function editAvailability(event, revertFunc) {
	console.log(event.start.getTime());
	$.ajax({
		type: 'POST',
		url: basePath+'/availability/ajaxEditAv',
		data: {
			start : event.start.getTime() / 1000,
			end : event.end.getTime() / 1000,
			availabilityId : event.availabilityId,
		},
		success: function() {
			allowEdition();
		},
		error: function(data) {
			reportError(data);
			allowEdition();
			revertFunc();
		}
	});
}

function makeClientCalendar(calendarId, dresserId) {
	calendarLoaded = true;
	$("#"+calendarId).empty();
	$.get(basePath+'/availability/ajaxGetAvs/id/'+dresserId,
		function(data) {
			
			$('#'+calendarId).fullCalendar({
				aspectRatio : 0.1,
				minTime : 10,
				maxTime : 20,
				firstDay: 1,
				defaultView: 'agendaWeek',
				slotMinutes: globalGrainMinutes,
				allDaySlot : false,
				editable: false,
				droppable: true,
				events: data,
				drop: function(date, allDay) {
					var eventObject = {};
					
					// assign it the date that was reported
					eventObject.start = date.getTime() / 1000;
					eventObject.end = date.getTime() / 1000 + 2*3600;
					eventObject.allDay = false;
					eventObject.title = 'worktime',
					
					blockEdition(calendarId);
					
					$.ajax({
						type: 'POST',
						url: basePath+'/appointment/ajaxCreateApp',
						data: {
							start : eventObject.start,
							end : eventObject.end
						},
						success: function(data) {
							eventObject.availabilityId = data;
							$('#'+calendarId).fullCalendar('renderEvent', eventObject, true);
							allowEdition();
						},
						error: function(data) {
							reportError(data);
							allowEdition();
						}
					});
					
				},
				eventClick : function( event, jsEvent, view ) {
					alert('lala');
					return false;
				}
			});
			
			
			$('#'+calendarId).fullCalendar('addEventSource', {
				url : basePath + '/appointment/ajaxGetClientApps/id/',
				color : 'green',
			});
			
		},
		'json'
	);
}

function reportError(error) {
	var errorReport = "";
	$.each(JSON.parse(error.responseText), function(key, value){
		errorReport += key + " - " + value + "\n";
	})
	alert(errorReport);
}

function blockEdition(calendarId) {
	$("#calendarOverlay").css({
		display: 'block',
		position: 'absolute',
		top: $("#"+calendarId).offset().top,
		left: $("#"+calendarId).offset().left,
		width: $("#"+calendarId).width(),
		height: $("#"+calendarId).height(),
	},100);
}

function allowEdition() {
	$("#calendarOverlay").hide();
}