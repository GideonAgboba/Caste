function Utils() {

}

Utils.prototype = {
    constructor: Utils,
    isElementInView: function (element, fullyInView) {
        var pageTop = $(window).scrollTop();
        var pageBottom = pageTop + $(window).height();
        var elementTop = $(element).offset().top;
        var elementBottom = elementTop + $(element).height();

        if (fullyInView === true) {
            return ((pageTop < elementTop) && (pageBottom > elementBottom));
        } else {
            return ((elementTop <= pageBottom) && (elementBottom >= pageTop));
        }
    }
};

var Utils = new Utils();
    /* ==============================================
/*  PRE LOADING
  =============================================== */
'use strict';
$(window).load(function() {
    $('.loader').delay(500).fadeOut('slow');
});


$(function() {

    'use strict';
    /* ==============================================
     /*   wow
      =============================================== */
    var wow = new WOW(
        {
            animateClass: 'animated',
            offset: 10,
            mobile: true
        }
    );
    wow.init();

    /* ==============================================
      Sidebar show and hide
       =============================================== */
    $(".menu-btn").on('click',function(i){
        $("body").toggleClass("sidebar_closed");
    });


    /* --------------------------------------------------------
     COUNTER JS
     ----------------------------------------------------------- */

    $('.counter').counterUp({
        delay: 5,
        time: 3000
    });

    /* ==============================================
     OWL CAROUSEL
     =============================================== */
    $('.testimonial_carousel').slick({
        dots: true,
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: false
    });


    /* ------------------------------------- */
    /* Animated progress bars
     /* ------------------------------------- */
    'use strict';

    var waypoints = $('.progress_container').waypoint(function() {
        $('.progress .progress-bar').progressbar({
            transition_delay: 1000
        });
    },{
        offset: '50%'
    });

});

const Main = {
    mainUrl: $('#app').attr('data-url'),
    token: $('#app').attr('data-token'),
    elixir: $('#app').attr('data-elixir'),
    loader: '<div class="quick_loader_white"><div></div><div></div><div></div><div></div></div>',

    Register: {
        toggleAntiBot(obj){
            var bot = $(obj);
            $('.form-check-input').trigger('click');
            if($('.form-check-input').prop('checked'))
                bot.html('&#10003;');
            else
                bot.html('');
        },
        antiBot: function(token, obj){
            var checkbox = $(obj);
            var form = checkbox.parents('form').first();
            if(checkbox.prop('checked')){
                // insert token
                form.append(`<input type="hidden" name="bot_token" value="${token}" class="hidden__bot__token" />`);
            }else{
                form.find('.hidden__bot__token').remove();
            }
        },
        submit: function(obj){
            var form = $(obj);
            var data = form.serialize();
            var btn = form.find('button[type=submit]');
            var url = form.attr('action');
            var oldHtml = btn.html();
            btn.attr('disabled', 'disabled');
            btn.html(Main.loader);

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                dataType: 'JSON',
                success: function(res){
                    btn.removeAttr('disabled');
                    btn.html(oldHtml);
                    alertify.success(res.message);
                    setTimeout(function(){
                        alertify.warning('redirecting, please wait...');
                    }, 1000);
                    setTimeout(function(){
                        window.location = '/'+res.user.username;
                    }, 3000);
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

                    console.log(err.responseJSON.message);
                }
            });
        }
    },

    Login: {
        submit: function(obj){
            var form = $(obj);
            var data = form.serialize();
            var btn = form.find('button[type=submit]');
            var url = form.attr('action');
            var oldHtml = btn.html();
            btn.attr('disabled', 'disabled');
            btn.html(Main.loader);

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                dataType: 'JSON',
                success: function(res){
                    btn.removeAttr('disabled');
                    btn.html(oldHtml);
                    alertify.success(res.message);
                    setTimeout(function(){
                        alertify.warning('redirecting, please wait...');
                    }, 1000);
                    setTimeout(function(){
                        window.location = $('.redirect-to').attr('data-url');
                    }, 3000);
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

                    console.log(err.responseJSON.message);
                }
            });
        }
    },

    Booking: {
        addItemToCart: function(obj){
            var btn = $(obj);
            var oldHtml = btn.html();
            var url = btn.attr('data-url');
            var type = btn.attr('data-type');
            var id = btn.attr('data-id');

            if(auth){
                if(authUser.role != 'customer'){
                    alertify.error(`Opps! you need to login as a customer to book ${type} <br><a class="text-info" href="${Main.mainUrl}/logout">Click to logout this account</a>`);
                    return false;
                }

                btn.css('pointer-events', 'none');
                btn.css('filter', 'brightness(0.5)');

                $.ajax({
                    url: url,
                    type: 'post',
                    data: {id:id, type:type, _token: Main.token},
                    success: function(res){
                        btn.html('<i class="ion ion-checkmark"></i>');
                        btn.css('pointer-events', 'all');
                        btn.css('filter', 'brightness(1)');
                        $('.booking-shell').load(document.URL +  ' .booking-shell', function(resp, status, xhr) {

                        });
                    },
                    error: function(err){
                        btn.html(oldHtml);
                        btn.css('pointer-events', 'all');
                        btn.css('filter', 'brightness(1)');
                        if(typeof err.responseJSON.message == 'string'){
                            alertify.error(err.responseJSON.message);
                        }else{
                            $.each(err.responseJSON.message, function(index, value){
                                alertify.error(value);
                            });
                        }
                    }
                });
            }else{
                alertify.error(`Opps! you need to login as a customer to book ${type} <br>Have a customer account already? <a class="text-info" href="${Main.mainUrl}/login">Click to login</a> or <a class="text-info" href="${Main.mainUrl}/register">Create a customer account</a>`);
            }
        },

        deleteBookingItem: function(obj){
            var btn = $(obj);
            var oldHtml = btn.html();
            var url = btn.attr('data-url');
            var id = btn.attr('data-id');
            var bookingUserID = btn.attr('data-booking-user-id');
            var postBtn = $(`.add--to--cart[data-id=${bookingUserID}]`);

            btn.css('pointer-events', 'none');
            btn.html(`<div style="padding: 10px 20px;">${Main.loader}</div>`);

            $.ajax({
                url: url,
                type: 'post',
                data: {id:id, _token: Main.token},
                success: function(res){
                    btn.remove();
                    postBtn.find('i').toggleClass('ion-checkmark');
                    postBtn.find('i').toggleClass('ion-plus');
                    postBtn.removeClass('checked');
                    $('.booking--counter').html(res.count);
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

        deleteAllBookingItem: function(obj){
            var btn = $(obj);
            var oldHtml = btn.html();
            var url = btn.attr('data-url');

            btn.css('pointer-events', 'none');
            btn.html(Main.loader);

            $.ajax({
                url: url,
                type: 'post',
                data: {_token: Main.token},
                success: function(res){
                    $('.booking-shell').load(document.URL +  ' .booking-shell', function(resp, status, xhr) {
                        
                    });
                    $('.add--to--cart').each(function(){
                        $(this).find('i').toggleClass('ion-checkmark');
                        $(this).find('i').toggleClass('ion-plus');
                        $(this).removeClass('checked');
                    });
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

        checkout: function(obj){
            var btn = $(obj);
            var oldHtml = btn.html();
            var url = btn.attr('data-url');

            btn.css('pointer-events', 'none');
            btn.html(Main.loader);

            $.ajax({
                url: url,
                type: 'post',
                data: {_token: Main.token},
                success: function(res){
                    $('.booking-shell').load(document.URL +  ' .booking-shell', function(resp, status, xhr) {
                        alertify.success(res.message);
                    });
                    $('.add--to--cart').each(function(){
                        $(this).find('i').toggleClass('ion-checkmark');
                        $(this).find('i').toggleClass('ion-plus');
                        $(this).removeClass('checked');
                    });
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
        }
    }
}