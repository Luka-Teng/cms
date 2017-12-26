jQuery(document).ready(function($) {
	//error handling
	function showError (msg) {
		msg === '' ? msg = 'error' : ''
		$("#error")
			.addClass("active")
			.find('.error-content').html(msg)
	}
	function refreshError () {
		$("#error")
			.removeClass("active")
			.find('.error-content').html("")
	}
	function showLoading () {
		$("#loading").addClass("active")
	}
	function refreshLoading () {
		$("#loading").removeClass("active")
	}
	function canDownload (url_pattern) {
		var list = $('a[href*="'+url_pattern+'"]')
		list.each(function () {
			var name = $(this).html()
			$(this).prop("download", name)
		})
	}  
	$(function () {
		//init
		$("#error .error-remove").click(function () {
			refreshError()
		})
		canDownload('/wp-content/uploads')
	})
	
	//form validate
	$("input.required").validate({
		test: [{
			case: 'nonzero',
			message: 'cant not be blank!!'
		}]
	})
	
	//check valid
	function isValid() {
		if ($(".alert-panel").length > 0) return false 
		return true
	}
	
	//new post
	$(function () {
		$("#post-btn").click(function () {
			//check if it is valid
			if (!isValid()) {
				$("html,body").scrollTop($(".alert-panel").first().offset().top-120)
				return
			}
			
			showLoading ()
			var content
			var categories = [$("#category").val()]
			var title = $("#title").val()
			var editor = tinyMCE.get('newpost')
			if (editor) {
			    content = editor.getContent()
			} else {
			    content = $('#'+'newpost').val()
			}
			var url = magicalData.siteURL + '/wp-json/wp/v2/posts'
			var data = {
				title: title,
				content: content,
				status: 'publish',
				categories: categories
			}
			$.ajax({
				type: 'post',
				url: url,
				data: data,
				beforeSend: function (xhr) {
			        xhr.setRequestHeader('X-WP-Nonce', magicalData.nonce)
			    },
			    success: function (data) {
			    	window.location = '/admin-post-show/?cat_id=' + categories[0]
			    },
			    error: function (data) {
			    	refreshLoading ()
			    	showError(data.responseJSON.message)
			    }
			})
		})
	})

	//edit a post
	$(function () {
		$("#editpost-btn").click(function () {
			//check if it is valid
			if (!isValid()) {
				$("html,body").scrollTop($(".alert-panel").first().offset().top-120)
				return
			}
			
			showLoading ()
			var content
			var categories = [$("#category").val()]
			var title = $("#title").val()
			var editor = tinyMCE.get('newpost')
			if (editor) {
			    content = editor.getContent()
			} else {
			    content = $('#'+'newpost').val()
			}
			var url = magicalData.siteURL + '/wp-json/wp/v2/posts/' + $("#editpost-id").val()
			var data = {
				title: title,
				content: content,
				categories: categories
			}
			$.ajax({
				type: 'post',
				url: url,
				data: data,
				beforeSend: function (xhr) {
			        xhr.setRequestHeader('X-WP-Nonce', magicalData.nonce)
			    },
			    success: function (data) {
			    	window.location = '/admin-post-show/?cat_id=' + categories[0]
			    },
			    error: function (data) {
			    	refreshLoading ()
			    	showError(data.responseJSON.message)
			    }
			})
		})
	})
	
	//delete a post
	$(function () {
		$("#delpost-btn").click(function () {
			var r=confirm("Are you sure!")
			if (r==true) {
				showLoading()
				var id = $(this).data('value')
				var url = magicalData.siteURL + '/wp-json/wp/v2/posts/' + id
				$.ajax({
					type: 'delete',
					url: url,
					beforeSend: function (xhr) {
						xhr.setRequestHeader('X-WP-Nonce', magicalData.nonce)
					},
					success: function (data) {
						window.location.reload()
					},
					error: function (data) {
						refreshLoading ()
						showError(data.responseJSON.message)
					}
				})
			}			
		})
	})
	
	//upload
	$(function () {
		$("#upload-btn").click(function () {
			showLoading ()
			var file = document.getElementById('file').files[0]
			var form_data=new FormData()
			form_data.append("file",file)
			var url = magicalData.siteURL + '/wp-json/apis/upload'
			$.ajax({
				type: 'post',
				url: url,
				data: form_data,
				contentType:false,
				processData:false,
			    success: function (data) {
			    	refreshLoading ()
			    	console.log(data)
			    },
			    error: function (data) {
			    	refreshLoading ()
			    	showError(data.responseJSON.message)
			    }
			})
		})
	})

	//login
	$(function () {
		$("#login-btn").click(function () {
			showLoading ()
			var username = $("#login-username").val()
			var password = $("#login-password").val()
			var url = magicalData.siteURL + '/wp-json/apis/login'
			var data = {
				username: username,
				password: password
			}
			$.ajax({
				type: 'post',
				url: url,
				data: data,
			    success: function (data) {
			    	if (data.status === 'success') {
			    		window.location = '/admin-index'
			    	} else if (data.status === 'error') {
			    		refreshLoading ()
			    		showError(data.message)
			    	}
			    },
			    error: function (data) {
			    	refreshLoading ()
			    	showError(data.message)
			    }
			})
		})
	})
	
})
