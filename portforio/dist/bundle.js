(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
$(function () {
    $('.toptitle').fadeIn(3000);
    var profile = $('#profile').offset().top,
        works = $('#works').offset().top,
        contact = $('#contact').offset().top,
        profileFlg = false,
        profileTitleFlg = false,
        worksFlg = false,
        worksTitleFlg = false,
        contactFlg = false,
        contactTitleFlg = false,
        beforeScroll = 0;

    $(window).on('scroll', function () {
        var heigh = $(this).scrollTop();
        if (heigh + profile > profile) {
            if (!profileFlg) {
                profileFlg = true;
                $('#profile').fadeIn(2000);
            }
        }
        if (heigh + profile > profile + 100) {
            if (!profileTitleFlg) {
                profileTitleFlg = true;
                $('#profile').find('.title').animate({
                    'font-size': '5vh'
                }, 2000);

                $('.user').fadeIn(2000);

            }
            $('.user tr').each(function (i) {
                $(this).find('th, td').delay(300 * i).animate({
                    'left': '0',
                    'opacity': '1'
                }, 1000);
            });
        }

        if (heigh + profile > works) {
            if (!worksFlg) {
                worksFlg = true;
                $('#works').fadeIn(2000);
            }
        }

        if (heigh + profile > works + 100) {
            if (!worksTitleFlg) {
                worksTitleFlg = true;
                $('#works').find('.title').animate({
                    'font-size': '5vh'
                }, 2000);
            }
            $('.panel').each(function (i) {
                $(this).find('.frame').delay(500 * i).animate({
                    'top': '0',
                    'opacity': '1'
                }, 1000);
            });
        }

        if (heigh + profile > contact) {
            if (!contactFlg) {
                contactFlg = true;
                $('#contact').fadeIn(2000);
            }
        }

        if (heigh + profile > contact + 100) {
            if (!contactTitleFlg) {
                contactTitleFlg = true;
                $('#contact').find('.title').animate({
                    'font-size': '5vh'
                }, 2000);
            }
        }

        if (heigh >= beforeScroll) {
            if (heigh > 200) {
                $('.navmanu').addClass('hide');
            }
        } else {
            $('.navmanu').removeClass('hide');
        }
        beforeScroll = $(this).scrollTop();

    });

    $('.panel').on('click', function () {
        var target = '#' + $(this).data('target'),
            panelTop = $(this).offset().top;
        $(target).css({
            'top': panelTop - works + 'px',
            'left': '-100vw',
            'display': 'block',
            'opacity': '0'
        });
        $(target).animate({
            'left': '0',
            'opacity': '1'
        }, 1000);

        $(target).on('click', function () {
            $(this).animate({
                'left': '-100vw',
                'opacity': '0'
            }, 1000);
        });

    });

    const MSG01 = '入力必須です';
    const MSG02 = 'E-mail形式で入力してください';
    $('.submit').attr('disabled', true);

    $('.name').on('keyup', function () {
        if ($(this).val().length === 0) {
            $(this).closest('.form-g').removeClass('success').addClass('error');
            $(this).siblings('.err-msg').text(MSG01);
        } else {
            $(this).closest('.form-g').removeClass('error').addClass('success');
            $(this).siblings('.err-msg').text('');
        }
        var flg = $('.form-g').hasClass('error');
        if (flg === true) {
            $('.submit').attr('disabled', true);
        } else {
            $('.submit').removeAttr('disabled');
        }
    });

    $('.email').on('keyup', function () {
        if ($(this).val().length === 0) {
            $(this).closest('.form-g').removeClass('success').addClass('error');
            $(this).siblings('.err-msg').text(MSG01);
        } else if (!$(this).val().match(/^([a-zA-Z0-9])+([a-zA-Z0-9¥._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9¥._-]+)+$/)) {
            $(this).closest('.form-g').removeClass('success').addClass('error');
            $(this).siblings('.err-msg').text(MSG02);
        } else {
            $(this).closest('.form-g').removeClass('error').addClass('success');
            $(this).siblings('.err-msg').text('');
        }
        var flg = $('.form-g').hasClass('error');
        if (flg === true) {
            $('.submit').attr('disabled', true);
        } else {
            $('.submit').removeAttr('disabled');
        }
    });

    $('.comment').on('keyup', function () {
        if ($(this).val().length === 0) {
            $(this).closest('.form-g').removeClass('success').addClass('error');
            $(this).siblings('.err-msg').text(MSG01);
        } else {
            $(this).closest('.form-g').removeClass('error').addClass('success');
            $(this).siblings('.err-msg').text('');
        }
        var flg = $('.form-g').hasClass('error');
        if (flg === true) {
            $('.submit').attr('disabled', true);
        } else {
            $('.submit').removeAttr('disabled');
        }
    });

});

},{}]},{},[1]);
