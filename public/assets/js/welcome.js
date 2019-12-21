const Home = {
	loaderWhite: '<div class="feed--dummy quick_loader_white"><div></div><div></div><div></div><div></div></div>',
	loaderPink: '<div class="feed--dummy quick_loader_pink"><div></div><div></div><div></div><div></div></div>',

	initFeed: function(){
		/* ==============================================
	     portfolio-filter
	     =============================================== */

	    // filter items on button click

	    var $grid = $('.grid').isotope({
	        // set itemSelector so .grid-sizer is not used in layout
	        itemSelector: '.grid-item',
	        percentPosition: true,
	        masonry: {
	            // use element for option
	            columnWidth: '.grid-sizer'
	        }
	    });

	    $grid.imagesLoaded().progress( function() {
	        $grid.isotope('layout');
	    });
	    $('#filtr-container').on( 'click', 'li', function(e) {
	        e.preventDefault();
	        $('#filtr-container li').removeClass('active');
	        $(this).closest('li').addClass('active');
	        var filterValue = $(this).attr('data-filter');
	        $grid.isotope({ filter: filterValue });
	    });

	    /* ==============================================
	     pop up
	     =============================================== */

	    // portfolio-pop up

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
		            '<div class="mfp-img"></div>'+
		            '<div class="mfp-bottom-bar">'+
		              '<a onclick="window.location = $(this).html();" class="mfp-title"></a>'+
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

    getLatestFeeds: function(){
        var shell,url,post,counter,dummy;
        shell = $('.wall--post--holder');
        url = shell.attr('data-url');
        counter = 0;
        dummy = Home.loaderPink;
        shell.html(dummy);
        if($('.fresh_________load').length){}else{
            $('html, body').animate({
                scrollTop: $('.feed--dummy').offset().top
            }, 1500);
        }
        $('.load__more__btn').remove();
        $('.fresh_________load').remove();
        $.ajax({
            url: url+'/'+0,
            type: 'get',
            dataType: 'JSON',
            success: function(res){
                shell.html('');
                if(res.data.length){
                    post = `
                    	<div class="grid img-container justify-content-center no-gutters">
			                <div class="grid-sizer col-sm-12 col-md-6 col-lg-3"></div>
			                ${(() => {
                                $com = '';
                                $.each(res.data, function(index, value){
                                	counter++
                                    $com += `
						                <div class="grid-item branding ${value.role} col-sm-12 col-md-6 col-lg-3">
						                	${(() => {
				                            if(value.is_booked == true)
					                            return `<div class="add--to--cart checked" data-id="${value.id}" data-type="user" data-url="${Main.mainUrl}/add-to-booking" onclick="Main.Booking.addItemToCart(this);"><i class="ion ion-checkmark"></i></div>`;
					                        else
					                        	return `<div class="add--to--cart" data-id="${value.id}" data-type="user" data-url="${Main.mainUrl}/add-to-booking" onclick="Main.Booking.addItemToCart(this);"><i class="ion ion-plus"></i></div>`;
					                        })()}
						                	${(() => {
				                            if(value.role == 'model' && value.path == 'user.png')
					                            return `<a href="${Main.elixir}model.png" title="${value.username}">`;
					                        else if(value.role == 'actor' && value.path == 'user.png')
					                        	return `<a href="${Main.elixir}actor.png" title="${value.username}">`;
					                        else
					                        	return `<a href="${Main.elixir}${value.path}" title="${value.username}">`;
					                        })()}
						                        <div class="project_box_one">
						                        	${(() => {
						                            if(value.role == 'model' && value.path == 'user.png')
							                            return `<img src="${Main.elixir}model.png" alt="${value.username}" />`;
							                        else if(value.role == 'actor' && value.path == 'user.png')
							                        	return `<img src="${Main.elixir}actor.png" alt="${value.username}" />`;
							                        else
							                        	return `<img src="${Main.elixir}${value.path}" alt="${value.username}" />`;
							                        })()}
						                            <div class="product_info">
						                                <div class="product_info_text">
						                                    <div class="product_info_text_inner text-center">
						                                    	${(() => {
					                                            if(value.gender == 'male')
					                                            	return '<i class="ion ion-male"></i>';
					                                            else if(value.gender == 'female')
					                                            	return '<i class="ion ion-female"></i>';
					                                            else
					                                            	return '<i class="fa fa-question"></i>';
					                                        	})()}
						                                        <h4>${value.fullname}</h4>
						                                        <small>${value.role}</small>
						                                        <div class="rating">
						                                        	${(() => {
						                                            if(value.rating >= 20)
						                                            	return '<span class=""><i class="text-warning fa fa-star"></i></span>';
						                                            else
						                                            	return '<span class=""><i class="text-muted fa fa-star"></i></span>';
						                                        	})()}
						                                        	${(() => {
						                                            if(value.rating >= 40)
						                                            	return '<span class=""><i class="text-warning fa fa-star"></i></span>';
						                                            else
						                                            	return '<span class=""><i class="text-muted fa fa-star"></i></span>';
						                                        	})()}
						                                        	${(() => {
						                                            if(value.rating >= 60)
						                                            	return '<span class=""><i class="text-warning fa fa-star"></i></span>';
						                                            else
						                                            	return '<span class=""><i class="text-muted fa fa-star"></i></span>';
						                                        	})()}
						                                        	${(() => {
						                                            if(value.rating >= 80)
						                                            	return '<span class=""><i class="text-warning fa fa-star"></i></span>';
						                                            else
						                                            	return '<span class=""><i class="text-muted fa fa-star"></i></span>';
						                                        	})()}
						                                        	${(() => {
						                                            if(value.rating >= 100)
						                                            	return '<span class=""><i class="text-warning fa fa-star"></i></span>';
						                                            else
						                                            	return '<span class=""><i class="text-muted fa fa-star"></i></span>';
						                                        	})()}
						                                        </div>
						                                    </div>
						                                </div>
						                            </div>
						                        </div>
						                    </a>
						                </div>
                                    `;
                                });
                                return $com
                            })()}
			            </div>
                    `;
                    shell.append(post);
                    if(counter >= 10){
                        // append load more
                        shell.parent().append(`<button class="mt-3 btn btn-primary load__more__btn" onclick="Home.loadMorelatestFeeds(this)">Load More</button>`)
                    }
                    Home.initFeed();
                    localStorage.setItem('users_latest_count', 0);
                }else{
                    shell.html('<small style="color:red;">No user to showcase</small>');
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

    loadMorelatestFeeds: function(obj){
        var btn,oldHtml,dummy,shell,url,post,oldCount,newCount,counter;
        btn = $(obj);
        oldHtml = btn.html();
        shell = $('.wall--post--holder');
        url = shell.attr('data-url');
        counter = 0;
        oldCount = localStorage.getItem('users_latest_count');
        newCount = parseInt(oldCount) + 10;
        dummy = Home.loaderWhite;

        btn.attr('disabled', 'disabled');
        btn.html(dummy);
        $.ajax({
            url: `${url}/${newCount}`,
            type: 'get',
            dataType: 'JSON',
            success: function(res){
                post = `
                	<div class="grid img-container justify-content-center no-gutters">
		                <div class="grid-sizer col-sm-12 col-md-6 col-lg-3"></div>
		                ${(() => {
                            $com = '';
                            $.each(res.data, function(index, value){
                            	counter++
                                $com += `
					                <div class="grid-item branding ${value.role} col-sm-12 col-md-6 col-lg-3">
					                	${(() => {
			                            if(value.is_booked == true)
				                            return `<div class="add--to--cart checked" data-id="${value.id}" data-type="user" data-url="${Main.mainUrl}/add-to-booking" onclick="Main.Booking.addItemToCart(this);"><i class="ion ion-checkmark"></i></div>`;
				                        else
				                        	return `<div class="add--to--cart" data-id="${value.id}" data-type="user" data-url="${Main.mainUrl}/add-to-booking" onclick="Main.Booking.addItemToCart(this);"><i class="ion ion-plus"></i></div>`;
				                        })()}
					                	${(() => {
			                            if(value.role == 'model' && value.path == 'user.png')
				                            return `<a href="${Main.elixir}model.png" title="${value.username}">`;
				                        else if(value.role == 'actor' && value.path == 'user.png')
				                        	return `<a href="${Main.elixir}actor.png" title="${value.username}">`;
				                        else
				                        	return `<a href="${Main.elixir}${value.path}" title="${value.username}">`;
				                        })()}
					                        <div class="project_box_one">
					                        	${(() => {
					                            if(value.role == 'model' && value.path == 'user.png')
						                            return `<img src="${Main.elixir}model.png" alt="${value.username}" />`;
						                        else if(value.role == 'actor' && value.path == 'user.png')
						                        	return `<img src="${Main.elixir}actor.png" alt="${value.username}" />`;
						                        else
						                        	return `<img src="${Main.elixir}${value.path}" alt="${value.username}" />`;
						                        })()}
					                            <div class="product_info">
					                                <div class="product_info_text">
					                                    <div class="product_info_text_inner text-center">
					                                    	${(() => {
				                                            if(value.gender == 'male')
				                                            	return '<i class="ion ion-male"></i>';
				                                            else if(value.gender == 'female')
				                                            	return '<i class="ion ion-female"></i>';
				                                            else
				                                            	return '<i class="fa fa-question"></i>';
				                                        	})()}
					                                        <h4>${value.fullname}</h4>
					                                        <small>${value.role}</small>
					                                        <div class="rating">
					                                        	${(() => {
					                                            if(value.rating >= 20)
					                                            	return '<span class=""><i class="text-warning fa fa-star"></i></span>';
					                                            else
					                                            	return '<span class=""><i class="text-muted fa fa-star"></i></span>';
					                                        	})()}
					                                        	${(() => {
					                                            if(value.rating >= 40)
					                                            	return '<span class=""><i class="text-warning fa fa-star"></i></span>';
					                                            else
					                                            	return '<span class=""><i class="text-muted fa fa-star"></i></span>';
					                                        	})()}
					                                        	${(() => {
					                                            if(value.rating >= 60)
					                                            	return '<span class=""><i class="text-warning fa fa-star"></i></span>';
					                                            else
					                                            	return '<span class=""><i class="text-muted fa fa-star"></i></span>';
					                                        	})()}
					                                        	${(() => {
					                                            if(value.rating >= 80)
					                                            	return '<span class=""><i class="text-warning fa fa-star"></i></span>';
					                                            else
					                                            	return '<span class=""><i class="text-muted fa fa-star"></i></span>';
					                                        	})()}
					                                        	${(() => {
					                                            if(value.rating >= 100)
					                                            	return '<span class=""><i class="text-warning fa fa-star"></i></span>';
					                                            else
					                                            	return '<span class=""><i class="text-muted fa fa-star"></i></span>';
					                                        	})()}
					                                        </div>
					                                    </div>
					                                </div>
					                            </div>
					                        </div>
					                    </a>
					                </div>
                                `;
                            });
                            return $com
                        })()}
		            </div>
                `;
                shell.append(post);
                if(counter >= 10){
                    // append load more
                    btn.removeAttr('disabled');
                	btn.html(oldHtml);
                }else{
                	btn.remove();
                }
                Home.initFeed();
                localStorage.setItem('users_latest_count', newCount);
            },
            error: function(err){
                if(typeof err.responseJSON.message == 'string'){
                    alertify.error(err.responseJSON.message);
                }else{
                    $.each(err.responseJSON.message, function(index, value){
                        alertify.error(value);
                    });
                }
                btn.removeAttr('disabled');
                btn.html(oldHtml);
            }
        });
    }
}

$(function() {
	Home.getLatestFeeds();
	$(window).scroll(function() {
        searchBox = $('.main-header .search');
        var isElementInView = Utils.isElementInView(searchBox, false);
        if (isElementInView) {
            $('.fixed-search').fadeOut('fast');
        } else {
            $('.fixed-search').fadeIn('fast');
        }
    });
});