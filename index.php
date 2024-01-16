<!DOCTYPE html>
<html>

<head>
	<title>calendar php</title>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" />
	<!-- JS for jQuery -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<!-- JS for full calender -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
	<!-- bootstrap css and js -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		.fc-title {
			color: white;
			vertical-align: middle;
		}
		.fc-day-grid-event{
			border-radius: 0px;
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-lg-12 mt-2">
				<h5 align="center">Manage your activities with this calendar</h5>
				<div id="calendar"></div>
			</div>
		</div>
	</div>
	<!-- Start popup dialog box -->
	<div class="modal fade" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalLabel">Add New Event</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">
							<i class="fa fa-close"></i>
						</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="img-container">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="event_name">Event name</label>
									<input type="text" name="event_name" id="event_name" class="form-control"
										placeholder="Enter your event name">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="event_start_date">Event start</label>
									<input type="date" name="event_start_date" id="event_start_date"
										class="form-control onlydatepicker" placeholder="Event start date">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="event_end_date">Event end</label>
									<input type="date" name="event_end_date" id="event_end_date" class="form-control"
										placeholder="Event end date">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="save" class="btn btn-primary" onclick="save_event()">Save Event</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="event_edit_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalLabel">Edit New Event</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">
							<i class="fa fa-close"></i>
						</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="img-container">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="event_name">Event name</label>
									<input type="text" name="event_edit_name" id="event_edit_name" class="form-control"
										placeholder="Enter your event name">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="event_start_date">Event start</label>
									<input type="date" name="event_edit_start_date" id="event_edit_start_date"
										class="form-control onlydatepicker" placeholder="Event start date">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="event_end_date">Event end</label>
									<input type="date" name="event_edit_end_date" id="event_edit_end_date"
										class="form-control" placeholder="Event end date">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="update-event-btn" class="btn btn-primary">Update Event</button>
				</div>
			</div>
		</div>
	</div>
	<br>
</body>
<script>
	$(document).ready(function () {
		display_events();
	});
	function display_events() {
		var events = new Array();
		$.ajax({
			url: 'display_event.php',
			dataType: 'json',
			success: function (response) {
				var result = response.data;
				$.each(result, function (i, item) {
					events.push({
						event_id: result[i].event_id,
						title: result[i].title,
						start: result[i].start,
						end: result[i].end
					});
				})
				var calendar = $('#calendar').fullCalendar({
					defaultView: 'month',
					timeZone: 'local',
					editable: true,
					selectable: true,
					selectHelper: true,
					overlap: true,
					select: function (start, end) {
						$('#event_start_date').val(moment(start).format('YYYY-MM-DD'));
						$('#event_end_date').val(moment(end).format('YYYY-MM-DD'));
						$('#event_entry_modal').modal('show');
					},
					events: events,
					eventRender: function (event, element, view) {
						element.find('.fc-title').append('<i style="float:right;padding:5px;" class="fa fa-trash delete-event" data-event-id="' + event.event_id + '"></i>');
						element.find('.fc-title').append('<i style="float:right;padding:5px;margin-top:1px;" class="fa fa-edit update-event" data-event-id="' + event.event_id + '"></i>');

						element.find('.delete-event').click(function () {
							Swal.fire({
								title: 'Are you sure?',
								text: 'You will not be able to recover this event!',
								icon: 'warning',
								showCancelButton: true,
								confirmButtonText: 'Yes, delete it!',
								cancelButtonText: 'No, keep it',
								reverseButtons: true
							}).then((result) => {
								if (result.value) {
									deleteEvent(event.event_id);
								}
							});
						});

						element.find('.update-event').click(function () {
							updateEvent(event.event_id);
						});
					}
				}); //end fullCalendar block	
			},//end success block
			error: function (xhr, status) {
				alert(response.msg);
			}
		});//end ajax block	
	}
	function deleteEvent(eventId) {
		$.ajax({
			url: 'delete_event.php', // URL de votre script PHP pour supprimer l'événement
			type: 'POST',
			data: { event_id: eventId },
			dataType: 'json',
			success: function (response) {
				if (response.status == true) {
					Swal.fire({
						icon: 'success',
						title: 'Event deleted!',
						text: 'Your event has been deleted.',
					}).then(() => {
						location.reload();
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Failed to delete the event.',
					});
				}
			},
			error: function (xhr, status) {
				console.log('AJAX error: ' + xhr.statusText);
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'An error occurred while trying to delete the event.',
				});
			}
		});
	}
	function updateEvent(eventId) {
		$.ajax({
			url: 'get_event_details.php',
			type: 'POST',
			data: { event_id: eventId },
			dataType: 'json',
			success: function (response) {
				if (response.status == true) {
					$('#event_edit_name').val(response.data.event_name);
					$('#event_edit_start_date').val(response.data.event_start_date);
					$('#event_edit_end_date').val(response.data.event_end_date);
					$('#event_edit_modal').modal('show');
					$('#update-event-btn').unbind('click').click(function () {
						EditEvent(eventId);
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Failed to retrieve event details.',
					});
				}
			},
			error: function (xhr, status) {
				console.log('AJAX error: ' + xhr.statusText);
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'An error occurred while trying to retrieve event details.',
				});
			}
		});
	}
	function EditEvent(eventId) {
		var event_name = $('#event_edit_name').val();
		var event_start_date = $('#event_edit_start_date').val();
		var event_end_date = $('#event_edit_end_date').val();

		$.ajax({
			url: 'update_event.php',
			type: 'POST',
			dataType: 'json',
			data: {
				event_id: eventId,
				event_name: event_name,
				event_start_date: event_start_date,
				event_end_date: event_end_date
			},
			success: function (response) {
				if (response.status == true) {
					$('#event_edit_modal').modal('hide');
					Swal.fire({
						icon: 'success',
						title: 'Success',
						text: response.msg,
					}).then(() => {
						location.reload();
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: response.msg,
					});
				}
			},
			error: function (xhr, status) {
				console.log('AJAX error: ' + xhr.statusText);
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'An error occurred while trying to update the event.',
				});
			}
		});
	}
	function save_event() {
		var event_name = $("#event_name").val();
		var event_start_date = $("#event_start_date").val();
		var event_end_date = $("#event_end_date").val();
		if (event_name == "" || event_start_date == "" || event_end_date == "") {
			Swal.fire({
				icon: 'error',
				title: 'Missing input',
				text: 'Please enter all required details.',
			});
			return false;
		}
		if (event_start_date > event_end_date) {
			Swal.fire({
				icon: 'error',
				title: 'Invalid date range',
				text: 'The start date cannot be greater than the end date.',
			});
			return false;
		}
		$.ajax({
			url: "save_event.php",
			type: "POST",
			dataType: 'json',
			data: { event_name: event_name, event_start_date: event_start_date, event_end_date: event_end_date },
			success: function (response) {
				$('#event_entry_modal').modal('hide');
				if (response.status == true) {
					Swal.fire({
						icon: 'success',
						title: 'Success',
						text: response.msg,
					}).then(() => {
						location.reload();
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: response.msg,
					});
				}
			},
			error: function (xhr, status) {
				console.log('ajax error = ' + xhr.statusText);
				alert(response.msg);
			}
		});
		return false;
	}

</script>

</html>