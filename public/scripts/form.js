$(document).ready(function() {

	var domain = "anesb.zzz.com.ua";

	function ajaxSend(ajaxType, ajaxUrl, ajaxData) {
		$.ajax({
			type: ajaxType,
			url: ajaxUrl,
			data: ajaxData,
			contentType: false,
			cache: false,
			processData: false,
			success: function(result) {
				var json = jQuery.parseJSON(result);
				if (json.url) {
					if (json.url == '/') {
						document.location.reload();
					} else {
						window.location.href = json.url;
					}
				} else {
					alert(json.message + ' (' + json.status + ')');
				}
			}
		});
	}

	$('form').submit(function(event) {
		event.preventDefault();
		ajaxSend($(this).attr('method'), $(this).attr('action'), new FormData(this));
	});

	function setCookie(cookieName, cookieValue) {
		var myDate = new Date();
		myDate.setMonth(myDate.getMonth() + 12);
		document.cookie = cookieName +"=" + cookieValue + ";expires=" + myDate 
		                  + ";domain=." + domain + ";path=/";
	}

	$('.sort').click(function() {
		setCookie('sortType', $(this).data('sortType') == 'asc' ? 'desc' : 'asc');
		setCookie('sortField', $(this).data('sortField'));
		document.location.reload();
	});

	$('.task-apply').click(function() {
		$tr = $(this).closest('tr');
		var status = $tr.find('.task-done:checked');
		status = status.length > 0 ? 1 : 0;

		var mydata = {
			"description" : $tr.find('.task-description').val(),
			"status" : status,
			"id" : $(this).data('id')
		}

		var form_data = new FormData();
		for (var key in mydata) {
		    form_data.append(key, mydata[key]);
		}

		ajaxSend('post', '/admin/updatetask', form_data);
	});

});