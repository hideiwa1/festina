    $(function () {
        //フッター
        var $ftr = $("footer"),
            $win = $(window),
            winHeight = 0,
            ftrHeight = 0,
            timer = null;

        function ftrFixed() {
            if (winHeight > ftrHeight) {
                $ftr.attr({
                    'style': 'position:fixed; bottom: 0px;'
                });
            } else {
                $ftr.removeAttr('style');
            }
        }

        function updateHeight() {
            $ftr.removeAttr('style');
            winHeight = window.innerHeight;
            ftrHeight = $ftr.offset().top + $ftr.outerHeight();
        }
        updateHeight();
        ftrFixed();

        $win.on('resize', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                updateHeight();
                ftrFixed();
            }, 300);
        });

        //スライドショー
        var imgTimer = null,
            lastImg = parseInt($(".slide li").length - 1);
        i = 0;

        $(".slide .s-img").attr({
            "style": "display:none;"
        });

        $(".slide .s-img").eq(i).attr({
            "style": "display:block;"
        });
        $(".slide-bar li").eq(i).addClass("active");

        function imgFade() {
            $(".slide .s-img").fadeOut(1000);
            $(".slide-bar .s-button").removeClass("active");
            $(".slide-bar .s-button").eq(i).addClass("active");
            $(".slide .s-img").eq(i).fadeIn(1000);
        }

        function imgSlide() {
            imgTimer = setInterval(function () {
                if (i === lastImg) {
                    i = 0;
                    imgFade();
                } else {
                    i++;
                    imgFade();
                }
            }, 5000);
        }
        imgSlide();

        $(".slide-bar .s-button").on('click', function () {
            clearInterval(imgTimer);
            imgSlide();
            i = $(".slide-bar .s-button").index(this);
            imgFade();
        });

        //画像切り替え
        var $imgindex = $(".index-img li"),
            $imgbar = $(".img-bar li");
        i = 0;

        $imgindex.attr({
            "style": "display:none;"
        });
        $imgindex.eq(i).attr({
            "style": "display:block;"
        });
        $imgbar.eq(i).addClass("active");

        function imgchange() {
            $imgindex.fadeOut('fast');
            $imgbar.removeClass("active");
            $imgbar.eq(i).addClass("active");
            $imgindex.eq(i).fadeIn('fast');
        }
        $imgbar.on('click', function () {
            i = $imgbar.index(this);
            imgchange();
        });


        //テキストカウント
        var $text = $(".js-text"),
            $count = $(".js-count");
        $text.on('keyup', function () {
            var count = $(this).val().length;
            console.log(count);
            $count.text(count);
        });

        /*コメント
        var $reply = $(".reply"),
            $comment = $("#comment"),
            $reset = $(".reset");
        $reply.on('click', function () {
            var msgId = '#' + $(this).parent("div").attr("class") + 'への返信';
            $comment.find("span").text(msgId);
            $comment.siblings("input").attr("value", "返信");
        });
        $reset.on('click', function () {
            var msgId = 'コメント';
            $comment.find("span").text(msgId);
            $comment.siblings("input").attr("value", "発言する");
            $comment.find("textarea").val('');
        });
        */

        //画像ライブプレビュー
        var $pic = $(".pic"),
            $input = $(".input");
        $pic.on('dragover', function (e) {
            $(this).css("border", "none");
        });
        $pic.on('dragleave', function (e) {
            $(this).css("border", "1px solid black");
        });
        $input.on('change', function (e) {
            $(this).parent($pic).css("border", "1px solid black");
            var file = this.files[0],
                $img = $(this).siblings("img"),
                filereader = new FileReader();
            $(this).siblings('.drop').attr({
                'style': 'display:none;'
            });
            filereader.onload = function (event) {
                $img.attr('src', event.target.result).show();

            };
            filereader.readAsDataURL(file);
        });

        //ウインドウ開閉
        var $open = $(".open"),
            $close = $(".close");
        $open.on('click', function () {
            $(this).attr("style", "display:none;");
            $(this).siblings($close).removeAttr("style");
            $(this).next(".lesson").removeAttr("style");
        });
        $close.on('click', function () {
            $(this).attr("style", "display:none;");
            $(this).siblings($open).removeAttr("style");
            $(this).next(".lesson").attr("style", "display:none;");
        });
        //済マーク
        //var $lesson = $(".lesson li"),
        //    $comp = $(".comp");
        //$lesson.on('click', function () {
        //    $(this).find($comp).toggle();
        //})

        //モーダルウインドウ
        var $open = $(".js-modalopen"),
            $over = $("#js-overlay"),
            $modal = $("#js-modal");

        $open.on('click', function () {
            var target = '#' + $(this).data("target");
            $(this).blur();
            if ($over[0]) {
                $over.remove();
            }
            $("body").append('<div id="js-overlay"></div>');
            $("#js-overlay").fadeIn('slow');
            $(target).fadeIn('slow');
            modalcenter();

            function modalcenter() {
                var w = $(window).width(),
                    h = $(window).height(),
                    mw = $(target).outerWidth(),
                    mh = $(target).outerHeight();
                $(target).css({
                    "position": "fixed",
                    "top": ((h - mh) / 2) + "px",
                    "left": ((w - mw) / 2) + "px"
                });
            }

            $("#js-overlay, .back").on('click', function () {
                $(target).fadeOut('slow');
                $("#js-overlay").fadeOut('slow', function () {
                    $("#js-overlay").remove();
                });
            });
        })

        //済みマーク
        var $comp = $(".comp") || null;
        $comp.on('click', function () {
            var $this = $(this),
                compClub = $this.data("compclub"),
                compLesson = $this.data("complesson");

            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    'c_club': compClub,
                    'c_lesson': compLesson
                }
            }).done(function (data) {
                var result = $this.find('img').toggleClass('stamp');
                console.log($this);
                console.log('成功');
            }).fail(function (msg) {
                console.log('Ajax Error');
            });
        });

        //lessonコメント
        var $button = $(".memo") || null;
        $button.on('click', function () {
            var $this = $(this),
                textClub = $this.data("textclub");
            textLesson = $this.data("textlesson");

            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    'c_club': textClub,
                    'c_lesson': textLesson,
                    'comment': $this.siblings('textarea').val()
                }
            }).done(function (data) {
                console.log('成功');
            }).fail(function (msg) {
                console.log('Ajax Error');
            });
        });

        //リストの偶数
        $('.side li:nth-child(even)').addClass('even');

        //タブ
        $('.tab').on('click', function () {
            var target = '#' + $(this).data('target') + '-content';
            $('.tab-content').removeClass('selected');
            $(target).addClass('selected');
        })

    });
