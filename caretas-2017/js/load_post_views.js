jQuery(document).ready(function($) {
    $.ajax({
        type: 'POST',
        url: ajax_post_views_object.ajaxurl,
        data:{
            action: 'set_post_views',
            post_id: ajax_post_views_object.post_id
        }
    });
});