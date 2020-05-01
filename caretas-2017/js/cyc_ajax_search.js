jQuery(document).ready(function ($) {
    $('.cyc_ajax_search input').keyup(function(e){
        e.preventDefault();
        var input = $(this);
        var nonce = $(this).parent().children('input[name=cyc_ajax_search_nonce]').val();
        var ajax_result = $(this).parent().children('.cyc_ajax_result');
        if(input.val().length>3){
            ajax_result.addClass('loading');
            ajax_result.html('');
            $.ajax({
                type: 'POST',
                url:cyc_ajax_search.ajaxurl,
                data: {
                    action: 'cyc_ajax_search',
                    input: input.val(),
                    nonce : nonce
                },
                success: function(result) {
                    ajax_result.html('');
                    ajax_result.removeClass('loading');
                    input.addClass('remove');
                    var search_result = $.parseJSON( result );
                    if(search_result[0].count===0){
                        item = '<p>No hubo resultados...</p>'
                        ajax_result.append(item);
                    } else {
                        $.each(search_result,function(index,result){
                            var title = result.title;
                            var link = result.link;
                            var item = '<h3 class="cp-title-small"><a href="'+link+'">'+title+'</a></h3>';
                            ajax_result.append(item);
                        });
                    }
                }
            });
        } else {
            ajax_result.html('');
        }
    });
});