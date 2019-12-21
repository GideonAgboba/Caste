const Profile = {
	loaderWhite: '<div class="feed--dummy quick_loader_white"><div></div><div></div><div></div><div></div></div>',
	loaderPink: '<div class="feed--dummy quick_loader_pink"><div></div><div></div><div></div><div></div></div>',

	init: function(){
		if(auth && authUser.role == 'customer'){
			return false;
		}
		
		if(auth && authUser.username == user.username){
			// check if unverified
			if(authUser.is_verified == false){
				alertify.warning(`${authUser.username} verify your account to increse profile rating`);
			}
			// check if profile is updated
			if(authUser.path == 'user.png' && authUser.phone == ''){
				alertify.warning(`Hi ${authUser.fullname.toUpperCase()}, your profile has not been updated. Update your profile to get hired by clients, place priority on your profile and increse your profile rating... <br><br><a class="text-dark" href="${Main.mainUrl}/settings"><i class="ion ion-settings"></i> Click here to update your profile</a>`);
			}
		}else{
			// check if blocked
			if(user.is_blocked == true){
				alertify.error('This profile is blocked');
			}

			// check if unverified
			if(user.is_verified == false){
				alertify.warning('This profile is unverified');
			}
		}
	},
	initGallery: function(){
		$('.profile__img').magnificPopup({
		    items: {
		      src: $('.profile__img').attr('data-url')
		    },
		    type: 'image' // this is default type
		});
	    $('.img-container').magnificPopup({
	        delegate: 'a',
	        type: 'image',
	        tLoading: 'Loading image #%curr%...',
	        mainClass: 'mfp-img-mobile',
	        gallery: {
	            enabled: true,
	            navigateByImgClick: true,
	            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
	        },
	        image: {
	        	markup: '<div class="mfp-figure">'+
		            '<div class="mfp-close"></div>'+
		            '<div class="mfp-img" style="max-height:auto;"></div>'+
		            '<div class="mfp-bottom-bar">'+
		              '<div class="mfp-counter"></div>'+
		            '</div>'+
		          '</div>',
	            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
	            titleSrc: function(item) {
	                return item.el.attr('title');
	            }
	        },
	        zoom: {
	            enabled: true,
	            duration: 300, // don't foget to change the duration also in CSS
	            opener: function (element) {
	                return element.find('img');
	            }
	        }
	    });
	},

	getGallery: function(){
		if(auth && authUser.role == 'customer'){
			$('.profile__img').magnificPopup({
			    items: {
			      src: $('.profile__img').attr('data-url')
			    },
			    type: 'image' // this is default type
			});
			return false;
		}

		var shell = $('.gallery--container');
		var url = shell.attr('data-url');

		shell.html(Profile.loaderPink);

		$.ajax({
            url: url,
            type: 'get',
            dataType: 'JSON',
            success: function(res){
                shell.html('');
                if(res.data.length){
                    post = `
                    	<ul class="img-container">
                    	${(() => {
                            $com = '';
                            $.each(res.data, function(index, value){
                                $com += `
                                <li><a href="${value.path}">
	                                <img src="${value.path}" alt="${user.username}"></a>
	                            </li>
                                `;
                            });
                            return $com
                        })()}
                        </ul>
                    `;
                    shell.html(post);
                    Profile.initGallery();
                }else{
                    shell.html('<small style="color:red;font-size: 12px;">No item to showcase</small>');
                }
            },
            error: function(err){
                if(typeof err.responseJSON.message == 'string'){
                    alertify.error(err.responseJSON.message);
                }else{
                    $.each(err.responseJSON.message, function(index, value){
                        alertify.error(value);
                    });
                }
                shell.find('.feed--dummy').remove();
            }
        });
	},

	triggergalleryFileInput: function(){
		$('.gallery--file--input').trigger('click');
	},

	galleryFilesPreview: function(input){
		var shell = $('.gallery--file--preview');
        shell.html(Profile.loaderPink);
        if(input.files && input.files[0]) {
        	shell.html('<i class="ion ion-close preview-delete-btn" onclick="Profile.clearMediaPreview(this)"></i>');
            $.each(input.files, function(index, value){
            	var reader = new FileReader();
	            reader.onload = function (e) {
	             shell.append(`
	                <img style="max-width: 400px;max-height: 500px;" src="${e.target.result}">
	             `);
	            };
            	reader.readAsDataURL(input.files[index]);
           	});
        }else{
        	$('.gallery--file--input').val('');
        	shell.html('<small style="color:red;font-size:13px">You did not select any file?</small>');
        }
	},

	clearMediaPreview: function(obj){
		var btn = $(obj);
		var shell = $('.gallery--file--preview');
		shell.html('');
		$('.gallery--file--input').val('');
		btn.remove();
	},

	saveGalleryImages: function(obj){
		if ($('.gallery--file--input').val() == '') {
			alertify.error('no image file was uploaded');
		}else{
			var btn = $(obj);
			var oldHtml = btn.html();
			var modal = $('#uploadModal');
			var form = modal.find('form');
			formData = new FormData(form [0]);
			var input = $('.gallery--file--input');

	        btn.attr('disabled', 'disabled');
            btn.html(Profile.loaderWhite);

	        $.ajax({
	            url: input.attr('data-url'),
	            type: 'post',
	            data: formData,
	            dataType: 'JSON',
	            contentType: false,
	            processData: false,
	            cache: false,
	            success: function(res){
	                btn.html(oldHtml);
	                btn.removeAttr('disabled');
	            	modal.modal('hide');
	            	Profile.clearMediaPreview();
	                alertify.success(res.message);
	                Profile.getGallery();
	            },
	            error: function(err){
	                btn.html(oldHtml);
	                btn.removeAttr('disabled');
	                if(typeof err.responseJSON.message == 'string'){
	                    alertify.error(err.responseJSON.message);
	                }else{
	                    $.each(err.responseJSON.message, function(index, value){
	                        alertify.error(value);
	                    });
	                }
	            }
	        });
		}
	},

	hireProfileRating: function(obj){
		var btn = $(obj);
		var url = btn.attr('data-url');

		$.ajax({
            url: url,
            type: 'get',
            success: function(res){
            	//
            }
        });
	}
}

$(function() {
	Profile.init();
	Profile.getGallery();
});
	