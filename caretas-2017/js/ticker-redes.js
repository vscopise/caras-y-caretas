jQuery(document).ready(function ($) {
    if ($(".nav-header-two")[0]) {
            $.bombnav(".nav-header-two", { indicator: true, speed: 200, verticalHandle: true });
    }
    if ($(".nav-nores")[0]) {
            $.bombnav(".nav-nores", { indicator: true, speed: 200, delay :100, verticalHandle: false });
    }
    if ($(".nav-res")[0]) {
            $.bombnav(".nav-res", { indicator: true, speed: 200, delay :100, verticalHandle: false, responsiveHandle: true });
    }
    //$.bombnav("bombnav");

    /* News Ticker */
    function tick(){
        $('#ticker li:first').slideUp(function(){
            $(this).appendTo($('#ticker')).slideDown();
        });
    };
    setInterval(function(){ tick();}, 4000);
});
// Menu
(function ($) {
    "use strict";
    $.bombnav = function (selector, options) {
        var settings = $.extend({
            indicator: true,
            speed: 300,
            delay: 0,
            hideClickOut: true,
            submenuTrigger: "hover",
            responsiveHandle: false,
            verticalHandle: false,
            mainIndicator: "<i class='icon-indicator'></i>",
            subIndicator: "<i class='icon-indicator'></i>"
        }, options);
        var menu = $(selector);
        if (settings.indicator === true) {
            if (settings.verticalHandle === true) {
                $(menu).children("li").children("a").each(function () {
                    if ($(this).siblings(".sub-menu, .megamenu").length > 0) {
                        $(this).append("<span class='indicator'>" + settings.mainIndicator + "</span>");
                    }
                });
            }
            $(menu).find(".sub-menu").children("li").children("a").each(function () {
                if ($(this).siblings(".sub-menu").length > 0) {
                    $(this).append("<span class='indicator'>" + settings.subIndicator + "</span>");
                }
            });
        }
        start();
        $(window).resize(function () {
            if (settings.verticalHandle === false) {
                if (settings.responsiveHandle === true) {
                    if ($(window).width() < 943) {
                        unbindEvents();
                        bindClick();
                        $(menu).addClass("bombver");
                    } else {
                        unbindEvents();
                        bindHover();
                        $(menu).removeClass("bombver");
                    }
                } else {
                    unbindEvents();
                    bindHover();
                }
            } else {
                unbindEvents();
                bindClick();
                $(menu).addClass("bombver");
            }
        });

        function start() {
            if (settings.verticalHandle === false) {
                if (settings.responsiveHandle === true) {
                    if ($(window).width() < 943) {
                        bindClick();
                        $(menu).addClass("bombver");
                    } else {
                        bindHover();
                        $(menu).removeClass("bombver");
                    }
                } else {
                    bindHover();
                }
            } else {
                bindClick();
                $(menu).addClass("bombver");
            }
        }

        function unbindEvents() {
            $(menu).find("li, a").unbind();
            $(document).unbind("click.menu touchstart.menu");
            $(menu).find(".sub-menu, .megamenu").hide(0);
        }

        function bindHover() {
            if (navigator.userAgent.match(/Mobi/i) || window.navigator.msMaxTouchPoints > 0 || settings.submenuTrigger === "click") {
                $(menu).find("a").on("click touchstart", function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                    $(this).parent("li").siblings("li").find(".sub-menu, .megamenu").stop(true, true).fadeOut(settings.speed);
                    if ($(this).siblings(".sub-menu, .megamenu").css("display") === "none") {
                        $(this).siblings(".sub-menu, .megamenu").stop(true, true).delay(settings.delay).slideDown(settings.speed);
                        return false;
                    } else {
                        $(this).siblings(".sub-menu, .megamenu").stop(true, true).fadeOut(settings.speed);
                        $(this).siblings(".sub-menu").find(".sub-menu").stop(true, true).fadeOut(settings.speed);
                    }
                    window.location.href = $(this).attr("href");
                });
                $(menu).find("li").bind("mouseleave", function () {
                    $(this).children(".sub-menu, .megamenu").stop(true, true).fadeOut(settings.speed);
                });
                if (settings.hideClickOut === true) {
                    $(document).bind("click.menu touchstart.menu", function (ev) {
                        if ($(ev.target).closest(menu).length === 0) {
                            $(menu).find(".sub-menu, .megamenu").fadeOut(settings.speed);
                        }
                    });
                }
            } else {
                $(menu).find("li").bind("mouseenter", function () {
                    $(this).children(".sub-menu, .megamenu").stop(true, true).delay(settings.delay).slideDown(settings.speed);
                }).bind("mouseleave", function () {
                    $(this).children(".sub-menu, .megamenu").stop(true, true).fadeOut(settings.speed);
                });
            }
        }

        function bindClick() {
            $(menu).find(".indicator").each(function () {
                if ($(this).parent("a").siblings(".sub-menu, .megamenu").length > 0) {
                    $(this).bind("click", function (e) {
                        if ($(this).parent().prop("tagName") === "A") {
                            e.preventDefault();
                        }
                        if ($(this).parent("a").siblings(".sub-menu, .megamenu").css("display") === "none") {
                            $(this).parent("a").siblings(".sub-menu, .megamenu").delay(settings.delay).slideDown(settings.speed);
                            $(this).parent("a").parent("li").siblings("li").find(".sub-menu, .megamenu").slideUp(settings.speed);
                        } else {
                            $(this).parent("a").siblings(".sub-menu, .megamenu").slideUp(settings.speed);
                        }
                    });
                }
            });
        }
    };
})(jQuery);
