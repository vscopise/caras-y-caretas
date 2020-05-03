jQuery(document).ready(function ($) {
    var ticker_container = '<div class="header-sub clearfix"><section id="news-ticker"><span class="ticker-title">Ultimas Noticias</span></section></div>';
    $('.mh-header').append(ticker_container);
    $.ajax({
        url: theme_object.ajaxurl,
        data: {
            action: 'cyc_header_ticker',
        },
        success: function (result) {
            var ticker_posts = $.parseJSON(result);
            var ticker_content = '<ul class="ticker-content">';
            $.each(ticker_posts, function (index, post) {
                var title = post.title;
                var link = post.link;
                var ticker_content_item = '<li><a href="' + link + '">' + title + '</a></li>';
                ticker_content += ticker_content_item;
            });
            ticker_content += '</ul>'
            $('#news-ticker').append(ticker_content);
        }
    });
    function tick() {
        $('#news-ticker li:first').slideUp(function () {
            $(this).appendTo($('#news-ticker .ticker-content')).slideDown();
        });
    };
    setInterval(function () { tick(); }, 4000);
});