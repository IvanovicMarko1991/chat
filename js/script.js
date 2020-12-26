$(document).ready(function () {
	fetch_user();

	function fetch_user(data) {


		if (data === undefined || data === 'Last seen online...') {
			$.ajax({
				url: 'fetch_user.php',
				method: 'POST',
				success: function (data) {
					$('#user_details').html(data);
				}
			});
		} else {
			$.ajax({

				url: "fetch_sorted.php",
				method: "POST",
				data: {
					selected: data
				},
				success: function (data) {

					$('#user_details').empty();
					$('#user_details').html(data);
				}
			});

		}
	}

	$('.update-button').click(function () {
		fetch_user();
		update_last_activity();
		update_chat_history_data();
		fetch_group_chat_history();
	});

	setInterval(function () {
		data = document.querySelector("#selected-sort").value;
		fetch_user(data);
		update_last_activity();
		update_chat_history_data();
		fetch_group_chat_history();
	}, 5000);

	$('.btn-logout').click(function () {
		update_last_activity();
	});

	function update_last_activity() {
		$.ajax({
			url: 'update_last_activity.php',
			success: function () {}
		});
	}

	function make_chat_dialog_box(to_user_id, to_user_name) {
		var modal_content =
			'<div id="user_dialog_' +
			to_user_id +
			'" class="user_dialog" title="You have chat with ' +
			to_user_name +
			'">';
		modal_content +=
			'<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="' +
			to_user_id +
			'" id="chat_history_' +
			to_user_id +
			'">';
		modal_content += fetch_user_chat_history(to_user_id);
		modal_content += '</div>';
		modal_content += '<div class="form-group-' + to_user_id + '">';
		modal_content +=
			'<textarea name="chat_message_' +
			to_user_id +
			'" id="chat_message_' +
			to_user_id +
			'" class="form-control chat_message">';
		modal_content += '</textarea>';

		modal_content += '</div>';
		modal_content += '<div class="form-group" align="right">';
		modal_content +=
			'<button type="button" name="send_chat" id="' +
			to_user_id +
			'" class="btn btn-info send_chat">Send</button></div></div>';
		$('#user_model_details').html(modal_content);
	}

	$(document).on('click', '.start_chat', function () {
		var to_user_id = $(this).data('touserid');
		var to_user_name = $(this).data('tousername');

		make_chat_dialog_box(to_user_id, to_user_name);

		$('#user_dialog_' + to_user_id).dialog({
			autoOpen: false,
			width: 400
		});

		$('#user_dialog_' + to_user_id).dialog('open');

		$('#chat_message_' + to_user_id).emojioneArea({
			pickerPosition: 'top',
			toneStyle: 'bullet'
		});

		modal_content_file =
			'<form enctype="multipart/form-data"  method="POST"  id = "uploadFile_' + to_user_id + '" >';
		modal_content_file += '<input class="uploadSingle" type="file"  id="fileInput_' + to_user_id + '">';
		modal_content_file += ' </form>';

		if ($('.form-group-' + to_user_id).length === 1) {
			$('.form-group-' + to_user_id).append(modal_content_file);
		}
	});

	$('#uploadImageChat').on('change', function () {
		document.querySelector('#uploadImageChat > input').ajaxSubmit({
			target: $('#uploadImageChat > input').closest('div .emojionearea'),
			resetForm: true
		});
	});

	$(document).on('click', '.send_chat', function (e) {
		var to_user_id = $(this).attr('id');
		var chat_message = $.trim($('#chat_message_' + to_user_id).val());

		var form = document.getElementById(`uploadFile_${to_user_id}`);
		var fileSelect = document.getElementById(`fileInput_${to_user_id}`);
		var files = fileSelect.files;

		if (files.length > 0) {
			var formData = new FormData();
			var file = files[0];
			formData.append('file', file, file.name);
			formData.append('to_user_id', to_user_id);
		}

		dataToSend = {
			to_user_id: to_user_id,
			chat_message: chat_message
		};

		if (chat_message.length > 0) {
			$.ajax({
				url: 'insert_chat.php',
				method: 'POST',
				data: dataToSend,
				success: function (data) {
					e.preventDefault();

					var element = $('#chat_message_' + to_user_id).emojioneArea();
					element[0].emojioneArea.setText('');
					$('#chat_history_' + to_user_id).html(data);
				}
			});
		} else if (files.length > 0) {
			$.ajax({
				type: 'POST',
				enctype: 'multipart/form-data',
				url: 'insert_chat.php',
				data: formData,
				async: true,
				cache: false,
				contentType: false,
				processData: false,

				success: function (data) {

					e.preventDefault();
					var element = $('#chat_message_' + to_user_id).emojioneArea();
					element[0].emojioneArea.setText('');
					$('#chat_history_' + to_user_id).html(data);
					document.getElementById('uploadFile_' + to_user_id).reset();

				},
				error: function (request, status, error) {
					message = JSON.parse(request.responseText);
					document.getElementById('uploadFile_' + to_user_id).reset();
					form = $("#fileInput_" + to_user_id);
					messageError = '<p class="alert-message">' + message.error;
					form.after(messageError);
				}

			});
		} else {
			alert('Type something');
		}
	});

	function fetch_user_chat_history(to_user_id) {
		$.ajax({
			url: 'fetch_user_chat_history.php',
			method: 'POST',
			data: {
				to_user_id: to_user_id
			},
			success: function (data) {
				$('#chat_history_' + to_user_id).html(data);
			}
		});
	}

	function update_chat_history_data() {
		$('.chat_history').each(function () {
			var to_user_id = $(this).data('touserid');
			fetch_user_chat_history(to_user_id);
		});
	}

	$(document).on('click', '.ui-button-icon', function () {
		$(this).remove();
	});

	$(document).on('focus', '.chat_message', function () {
		var is_type = 'yes';
		$.ajax({
			url: 'update_is_type_status.php',
			method: 'POST',
			data: {
				is_type: is_type
			},
			success: function () {}
		});
	});

	$(document).on('blur', '.chat_message', function () {
		var is_type = 'no';
		$.ajax({
			url: 'update_is_type_status.php',
			method: 'POST',
			data: {
				is_type: is_type
			},
			success: function () {}
		});
	});

	$('#group_chat_dialog').dialog({
		autoOpen: false,
		width: 400
	});

	$('#group_chat').click(function () {
		$('#group_chat_dialog').dialog('open');
		$('#is_active_group_chat_window').val('yes');
		fetch_group_chat_history();
	});

	$('#send_group_chat').click(function () {
		var chat_message = $.trim($('#group_chat_message').html());
		var action = 'insert_data';

		if (chat_message != '') {
			$.ajax({
				url: 'group_chat.php',
				method: 'POST',
				data: {
					chat_message: chat_message,
					action: action
				},
				success: function (data) {
					$('#group_chat_message').html('');
					$('#group_chat_history').html(data);

				}
			});
		} else {
			alert('Type something');
		}
	});

	function fetch_group_chat_history() {
		var group_chat_dialog_active = $('#is_active_group_chat_window').val();
		var action = 'fetch_data';
		if (group_chat_dialog_active == 'yes') {
			$.ajax({
				url: 'group_chat.php',
				method: 'POST',
				data: {
					action: action
				},
				success: function (data) {
					$('#group_chat_history').html(data);
				}
			});
		}
	}

	$('#uploadFile').on('change', function () {
		$('#uploadImage').ajaxSubmit({
			target: '#group_chat_message',
			resetForm: true
		});
	});

	$(document).on('click', '.remove_chat', function () {
		var chat_message_id = $(this).attr('id');
		if (confirm('Are you sure you want to remove this chat?')) {
			$.ajax({
				url: 'remove_chat.php',
				method: 'POST',
				data: {
					chat_message_id: chat_message_id
				},
				success: function (data) {
					update_chat_history_data();
				}
			});
		}
	});
});
