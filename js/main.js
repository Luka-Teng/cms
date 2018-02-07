jQuery(document).ready(function($) {
	//common tools handling
	function showError (msg) {
		msg.responseJSON ? msg = msg.responseJSON.message : ''
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
	function showflash (msg, type) {
		msg.responseJSON ? msg = msg.responseJSON.message : ''
		var colors = {
			error: '#ed222e',
			success: '#99cc00'
		}
		$("#flash")
			.clone().appendTo("body")
			.show()
			.find('.flash-content').html(msg).css('background', colors[type])
			.parent()
			.fadeOut(2000, function () {
				$(this).remove()
			})
	}
	function canDownload (url_pattern) {
		var list = $('a[href*="'+url_pattern+'"]')
		list.each(function () {
			var name = $(this).html()
			$(this).prop("download", name)
		})
	}

	//show images when uploading files
	function onFilePicked(file, target) {
		let filename = file.name
		if (filename.lastIndexOf('.') <= 0) {
		  return alert('Please add a valid file!')
		}
		const fileReader = new FileReader()
		fileReader.addEventListener('load', () => {
		  target.src = fileReader.result
		})
		fileReader.readAsDataURL(file)
	}
	
	//init
	$(function () {
		//取消错误
		$("#error .error-remove").click(function () {
			refreshError()
		})
		//提供下载链接
		canDownload('/wp-content/uploads');
		//轮播图上传图片显示
		[1,2,3,4,5].forEach(function (element) {
			$('#carousel_' + element + '_1').change(function (event) {
				onFilePicked(event.target.files[0], document.getElementById('carousel-target-' + element + '-1'))
			})
			$('#carousel_' + element + '_2').change(function (event) {
				onFilePicked(event.target.files[0], document.getElementById('carousel-target-' + element + '-2'))
			}) 			
		});
		
		//banner上传图片显示
		[1,2,3,4,5,6,7,8].forEach(function (element) {
			$('#banner_' + element).change(function (event) {
				onFilePicked(event.target.files[0], document.getElementById('banner-target-' + element))
			})		
		})
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
			    	showError(data)
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
			    	showError(data)
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
						showError(data)
					}
				})
			}			
		})
	})
	
	//banner upload
	$(function () {
		$(".banner-upload-btn").click(function () {
			showLoading ()
			var that = $(this)
			var banner_name = $(this).data('banner')
			var file = document.getElementById(banner_name).files[0]
			var form_data=new FormData()
			form_data.append(banner_name, file)
			form_data.append('title', $('#' + banner_name + '_title').val())
			form_data.append('link', $('#' + banner_name + '_link').val())
			form_data.append('uid', banner_name.charAt(banner_name.length-1))
			var url = magicalData.siteURL + '/wp-json/apis/update_banner'
			$.ajax({
				type: 'post',
				url: url,
				data: form_data,
				contentType:false,
				processData:false,
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-WP-Nonce', magicalData.nonce)
				},
			    success: function (data) {
			    	refreshLoading ()
			    	showflash('上传成功', 'success')
			    	that.parent().addClass("active")
			    	console.log(data)
			    },
			    error: function (data) {
			    	refreshLoading ()
			    	showflash(data, 'error')
			    }
			})
		})
	})
	
	
	//carousel upload
	$(function () {
		$(".carousel-upload-btn").click(function () {
			showLoading ()
			var that = $(this)
			var carousel_name = $(this).data('carousel')
			var file = document.getElementById(carousel_name).files[0]
			var form_data=new FormData()
			form_data.append(carousel_name,file)
			var url = magicalData.siteURL + '/wp-json/apis/carousel_upload'
			$.ajax({
				type: 'post',
				url: url,
				data: form_data,
				contentType:false,
				processData:false,
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-WP-Nonce', magicalData.nonce)
				},
			    success: function (data) {
			    	refreshLoading ()
			    	showflash('上传成功', 'success')
			    	that.parent().addClass("active")
			    	console.log(data)
			    },
			    error: function (data) {
			    	refreshLoading ()
			    	showflash(data, 'error')
			    }
			})
		})
	})
	
	//carousel delete
	$(function () {
		$(".carousel-delete-btn").click(function () {
			var r=confirm("删除后将无法恢复!")
			if (r==true) {
				showLoading ()
				var carousel_name = $(this).data('carousel')
				var that = $(this)
				var data = {
					carousel_name: carousel_name
				}
				var url = magicalData.siteURL + '/wp-json/apis/carousel_delete'
				$.ajax({
					type: 'delete',
					url: url,
					data: data,
					beforeSend: function (xhr) {
						xhr.setRequestHeader('X-WP-Nonce', magicalData.nonce)
					},
				    success: function (data) {
				    	refreshLoading ()
				    	showflash('删除成功', 'success')
				    	document.getElementById(carousel_name).value = ''
				    	document.getElementById('carousel-target-' + carousel_name.split('carousel_')[1].replace('_', '-' )).src 
				    		= window.location.origin + '/wp-content/themes/cms/img/alt.jpg'
				    	that.parent().removeClass("active")
				    	console.log(data)
				    },
				    error: function (data) {
				    	refreshLoading ()
				    	showflash(data, 'error')
				    }
				})
			}
		})
	})
	
	//export excel for media applicants	
	$(function () {
		$('#export-media-excel').click(function () {
			showLoading ()
			var url = magicalData.siteURL + '/wp-json/apis/applicant_excel'
			$.ajax({
				type: 'get',
				url: url,
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-WP-Nonce', magicalData.nonce)
				},
				success: function (data) {
					refreshLoading ()
					console.log(data)
					window.location = window.location.origin + '/' + data.url
				},
				error: function () {
					refreshLoading ()
				    showflash(data, 'error')
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
			    		window.location = '/admin'
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
	
	//申请人相关
	//发送验证码
	$(function () {
		$("#getcode-btn").click(function () {
			showLoading ()
			var url = magicalData.siteURL + '/wp-json/apis/email_code'
			var email = $("#email").val()
			$.ajax({
				type: 'post',
				url: url,
				data: {
					email: email
				},
			    success: function (data) {
					refreshLoading ()
			    	console.log(data)
					showflash('已发送，请查收', 'success')
			    },
			    error: function (data) {
					refreshLoading ()
			    	showError(data)
					showflash('发送失败', 'error')
			    }
			})
		})
	})
	//创建媒体申请人
	$(function () {
		$("#create_media_applicant").click(function (e) {
			e.preventDefault()
			showLoading ()
			var url = magicalData.siteURL + '/wp-json/apis/create_applicant'
			$.ajax({
				type: 'post',
				url: url,
				data: {
					email: $("#email").val(),
					email_code: $("#email_code").val(),
					name: $("#name").val(),
					company: $("#company").val(),
					job: $("#job").val(),
					phone: $("#phone").val(),
					type: $("#type").val(),
					payment_type: $("#payment_type").val(),
					total_amount: '0'
				},
			    success: function (data) {
			    	console.log(data)
					showflash('创建成功', 'success')
					if ($("#payment_type").val() === 'free') {
						refreshLoading ()
					}
					payment_type === 'free' ? refreshLoading () : $("body").append(data)									
			    },
			    error: function (data) {
					refreshLoading ()
			    	showError(data)
					showflash('创建失败', 'error')
			    }
			})
		})
	})
})
