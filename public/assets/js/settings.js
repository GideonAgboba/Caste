const Setting = {
	loaderWhite: '<div class="feed--dummy quick_loader_white"><div></div><div></div><div></div><div></div></div>',
	loaderPink: '<div class="feed--dummy quick_loader_pink"><div></div><div></div><div></div><div></div></div>',

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
		            '<a onclick="Setting.deleteGalleryImage(this);" class="mfp-title"></a>'+
		              '<div class="mfp-counter"></div>'+
		            '</div>'+
		          '</div>',
	            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
	            titleSrc: function(item) {
	                return item.el.attr('title')+' <i class="fa fa-trash"></i>';
	            }
	        },
	        zoom: {
	            enabled: true,
	            duration: 300, // don't foget to change the duration also in CSS
	            opener: function (element) {
	                return element.find('img');
	            }
	        },
	        callbacks: {
	        	open: function(){
	        		var src = this.content.find('img').attr('src');
	        		// get the image with the same src
	        		var a = $('.img-container').find(`a[href='${src}']`);
	        		var id = a.attr('data-id');
	        		this.content.find('.mfp-title').attr('data-id', id);

	        		console.log(`${src}, ${a}, ${id}`)
	        	}
	        }
	    });
	},

	getGallery: function(){
		var shell = $('.gallery--container');
		var url = shell.attr('data-url');

		shell.html(Setting.loaderPink);

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
                                <li><a href="${value.path}" title="Delete Image" data-id="${value.id}">
	                                <img src="${value.path}" alt="${user.username}"></a>
	                            </li>
                                `;
                            });
                            return $com
                        })()}
                        </ul>
                    `;
                    shell.html(post);
                    Setting.initGallery();
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
        shell.html(Setting.loaderPink);
        if(input.files && input.files[0]) {
        	shell.html('<i class="ion ion-close preview-delete-btn" onclick="Setting.clearMediaPreview(this)"></i>');
            $.each(input.files, function(index, value){
            	var reader = new FileReader();
	            reader.onload = function (e) {
	             shell.append(`
	                <img style="max-width: 80%;max-height: 500px;" src="${e.target.result}">
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
            btn.html(Setting.loaderWhite);

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
	            	Setting.clearMediaPreview();
	                alertify.success(res.message);
	                $('.profile-update').load(document.URL +  ' .profile-update', function(resp, status, xhr) {

		            });
                    $('.auth-shell').load(document.URL +  ' .auth-shell', function(resp, status, xhr) {

                    });
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

	deleteGalleryImage: function(obj){
		var btn = $(obj);
		var oldHtml = btn.html();
		var id = btn.attr('data-id');
		var url = $('.gallery--container').attr('data-delete-url');

		btn.css('pointer-events', 'none');
        btn.html(Setting.loaderWhite);

        $.ajax({
            url: url,
            type: 'post',
            data: {id:id, _token: Main.token},
            success: function(res){
                btn.html('Deleted');
            	Setting.getGallery();
            },
            error: function(err){
                btn.html(oldHtml);
                btn.css('pointer-events', 'all');
                if(typeof err.responseJSON.message == 'string'){
                    alertify.error(err.responseJSON.message);
                }else{
                    $.each(err.responseJSON.message, function(index, value){
                        alertify.error(value);
                    });
                }
            }
        });
	},

	updateProfile: function(obj){
		var form = $(obj);
        var data = form.serialize();
        var btn = form.find('button[type=submit]');
        var url = form.attr('action');
        var oldHtml = btn.html();

        btn.attr('disabled', 'disabled');
        btn.html(Setting.loaderWhite);

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'JSON',
            success: function(res){
                btn.removeAttr('disabled');
                btn.html(oldHtml);
                alertify.success(res.message);
               	$('.profile__update__form').load(document.URL +  ' .profile__update__form', function(resp, status, xhr) {

		        });
            },
            error: function(err){
                btn.removeAttr('disabled');
                btn.html(oldHtml);
                if(err.responseJSON.message != undefined && err.responseJSON.message != '' && err.responseJSON.message != null){
                    if(typeof err.responseJSON.message == 'string'){
                        alertify.error(err.responseJSON.message);
                    }else{
                        $.each(err.responseJSON.message, function(index, value){
                            alertify.error(value[0]);
                        });
                    }
                }else{
                    alertify.error('Opps, something went wrong');
                }
            }
        });
	},

	addSocialLink: function(obj){
		var form = $(obj);
        var data = form.serialize();
        var btn = form.find('button[type=submit]');
        var url = form.attr('action');
        var oldHtml = btn.html();

        if(form.find('input[name=url]').val().includes('https://') || form.find('input[name=url]').val().includes('http://')){

        }else{
        	alertify.warning('enter valid url into the Url field');
        	return false;
        }

        btn.attr('disabled', 'disabled');
        btn.html(Setting.loaderWhite);

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'JSON',
            success: function(res){
                btn.removeAttr('disabled');
                btn.html(oldHtml);
                alertify.success(res.message);
               	$('.social__link__form').load(document.URL +  ' .social__link__form', function(resp, status, xhr) {

		        });
            },
            error: function(err){
                btn.removeAttr('disabled');
                btn.html(oldHtml);
                if(err.responseJSON.message != undefined && err.responseJSON.message != '' && err.responseJSON.message != null){
                    if(typeof err.responseJSON.message == 'string'){
                        alertify.error(err.responseJSON.message);
                    }else{
                        $.each(err.responseJSON.message, function(index, value){
                            alertify.error(value[0]);
                        });
                    }
                }else{
                    alertify.error('Opps, something went wrong');
                }
            }
        });
	},

	deleteSocialLink: function(id, obj){
		var btn = $(obj);
		var oldHtml = btn.html();
		var url = btn.attr('data-url');

		btn.css('pointer-events', 'none');
        btn.html(Setting.loaderPink);

        $.ajax({
            url: url,
            type: 'post',
            data: {id:id, _token: Main.token},
            success: function(res){
            	btn.remove();
            },
            error: function(err){
                btn.html(oldHtml);
                btn.css('pointer-events', 'all');
                if(typeof err.responseJSON.message == 'string'){
                    alertify.error(err.responseJSON.message);
                }else{
                    $.each(err.responseJSON.message, function(index, value){
                        alertify.error(value);
                    });
                }
            }
        });
	},

    populateCrewCategoryInfo: function(obj){
        var select = $(obj);
        var value = select.val();
        var info  = $('select[name=crew_type_info]');
        var old = select.attr('data-old');

        if(value == 'production'){
            info.html(`
                <option value="">Select Crew Category Info</option>
                <option ${(() => { if(old == 'producer'){ return 'selected'; } })()} value="producer">Producer</option>
                <option ${(() => { if(old == 'director'){ return 'selected'; } })()} value="director">Director</option>
                <option ${(() => { if(old == 'script writer'){ return 'selected'; } })()} value="script writer">Script Writer</option>
                <option ${(() => { if(old == 'editor'){ return 'selected'; } })()} value="editor">Editor</option>
            `);
        }else if(value == 'technical'){
            info.html(`
                <option value="">Select Crew Category Info</option>
                <option ${(() => { if(old == 'sound engineer'){ return 'selected'; } })()} value="sound engineer">Sound Engineer</option>
                <option ${(() => { if(old == 'light'){ return 'selected'; } })()} value="light">Light</option>
                <option ${(() => { if(old == 'oop'){ return 'selected'; } })()} value="oop">OOP</option>
                <option ${(() => { if(old == 'continuity'){ return 'selected'; } })()} value="continuity">Continuity</option>
                <option ${(() => { if(old == 'makeup '){ return 'selected'; } })()} value="make up">Make Up</option>
                <option ${(() => { if(old == 'costume'){ return 'selected'; } })()} value="costume">Costume</option>
            `);
        }else{
            info.html(`
                <option value="">Select Crew Category Info</option>
            `);
        }
    }
}

$(function() {
	Setting.getGallery();
});











/*\
|*| ========================================================================
|*| Bootstrap Toggle: bootstrap4-toggle.js v3.6.1
|*| https://gitbrent.github.io/bootstrap4-toggle/
|*| ========================================================================
|*| Copyright 2018-2019 Brent Ely
|*| Licensed under MIT
|*| ========================================================================
\*/
!function(a){"use strict";function l(t,e){this.$element=a(t),this.options=a.extend({},this.defaults(),e),this.render()}l.VERSION="3.6.0",l.DEFAULTS={on:"On",off:"Off",onstyle:"primary",offstyle:"light",size:"normal",style:"",width:null,height:null},l.prototype.defaults=function(){return{on:this.$element.attr("data-on")||l.DEFAULTS.on,off:this.$element.attr("data-off")||l.DEFAULTS.off,onstyle:this.$element.attr("data-onstyle")||l.DEFAULTS.onstyle,offstyle:this.$element.attr("data-offstyle")||l.DEFAULTS.offstyle,size:this.$element.attr("data-size")||l.DEFAULTS.size,style:this.$element.attr("data-style")||l.DEFAULTS.style,width:this.$element.attr("data-width")||l.DEFAULTS.width,height:this.$element.attr("data-height")||l.DEFAULTS.height}},l.prototype.render=function(){this._onstyle="btn-"+this.options.onstyle,this._offstyle="btn-"+this.options.offstyle;var t="large"===this.options.size||"lg"===this.options.size?"btn-lg":"small"===this.options.size||"sm"===this.options.size?"btn-sm":"mini"===this.options.size||"xs"===this.options.size?"btn-xs":"",e=a('<label for="'+this.$element.prop("id")+'" class="btn">').html(this.options.on).addClass(this._onstyle+" "+t),s=a('<label for="'+this.$element.prop("id")+'" class="btn">').html(this.options.off).addClass(this._offstyle+" "+t),o=a('<span class="toggle-handle btn btn-light">').addClass(t),i=a('<div class="toggle-group">').append(e,s,o),l=a('<div class="toggle btn" data-toggle="toggle" role="button">').addClass(this.$element.prop("checked")?this._onstyle:this._offstyle+" off").addClass(t).addClass(this.options.style);this.$element.wrap(l),a.extend(this,{$toggle:this.$element.parent(),$toggleOn:e,$toggleOff:s,$toggleGroup:i}),this.$toggle.append(i);var n=this.options.width||Math.max(e.outerWidth(),s.outerWidth())+o.outerWidth()/2,h=this.options.height||Math.max(e.outerHeight(),s.outerHeight());e.addClass("toggle-on"),s.addClass("toggle-off"),this.$toggle.css({width:n,height:h}),this.options.height&&(e.css("line-height",e.height()+"px"),s.css("line-height",s.height()+"px")),this.update(!0),this.trigger(!0)},l.prototype.toggle=function(){this.$element.prop("checked")?this.off():this.on()},l.prototype.on=function(t){if(this.$element.prop("disabled"))return!1;this.$toggle.removeClass(this._offstyle+" off").addClass(this._onstyle),this.$element.prop("checked",!0),t||this.trigger()},l.prototype.off=function(t){if(this.$element.prop("disabled"))return!1;this.$toggle.removeClass(this._onstyle).addClass(this._offstyle+" off"),this.$element.prop("checked",!1),t||this.trigger()},l.prototype.enable=function(){this.$toggle.removeClass("disabled"),this.$toggle.removeAttr("disabled"),this.$element.prop("disabled",!1)},l.prototype.disable=function(){this.$toggle.addClass("disabled"),this.$toggle.attr("disabled","disabled"),this.$element.prop("disabled",!0)},l.prototype.update=function(t){this.$element.prop("disabled")?this.disable():this.enable(),this.$element.prop("checked")?this.on(t):this.off(t)},l.prototype.trigger=function(t){this.$element.off("change.bs.toggle"),t||this.$element.change(),this.$element.on("change.bs.toggle",a.proxy(function(){this.update()},this))},l.prototype.destroy=function(){this.$element.off("change.bs.toggle"),this.$toggleGroup.remove(),this.$element.removeData("bs.toggle"),this.$element.unwrap()};var t=a.fn.bootstrapToggle;a.fn.bootstrapToggle=function(o){var i=Array.prototype.slice.call(arguments,1)[0];return this.each(function(){var t=a(this),e=t.data("bs.toggle"),s="object"==typeof o&&o;e||t.data("bs.toggle",e=new l(this,s)),"string"==typeof o&&e[o]&&"boolean"==typeof i?e[o](i):"string"==typeof o&&e[o]&&e[o]()})},a.fn.bootstrapToggle.Constructor=l,a.fn.toggle.noConflict=function(){return a.fn.bootstrapToggle=t,this},a(function(){a("input[type=checkbox][data-toggle^=toggle]").bootstrapToggle()}),a(document).on("click.bs.toggle","div[data-toggle^=toggle]",function(t){a(this).find("input[type=checkbox]").bootstrapToggle("toggle"),t.preventDefault()})}(jQuery);
//# sourceMappingURL=bootstrap4-toggle.min.js.map