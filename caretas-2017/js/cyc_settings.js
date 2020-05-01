jQuery(document).ready( function( $ ) {

    var myplugin_media_upload;

    $('#myplugin-change-image').click(function(e) {

        e.preventDefault();

        // If the uploader object has already been created, reopen the dialog
        if( myplugin_media_upload ) {

            myplugin_media_upload.open();
            return;

        }

        // Extend the wp.media object
        myplugin_media_upload = wp.media.frames.file_frame = wp.media({

            //button_text set by wp_localize_script()
            title: button_text.title,
            button: { text: button_text.button },
            multiple: true //allowing for multiple image selection

        });

        /**
         *THE KEY BUSINESS
         *When multiple images are selected, get the multiple attachment objects
         *and convert them into a usable array of attachments
         */
        myplugin_media_upload.on( 'select', function(){

            var attachments = myplugin_media_upload.state().get('selection').map( 

                function( attachment ) {

                    attachment.toJSON();
                    return attachment;

            });

            //loop through the array and do things with each attachment

            var i;

            for (i = 0; i < attachments.length; ++i) {

                //sample function 1: add image preview
                $('#myplugin-placeholder').after(
                    '<div class="myplugin-image-preview"><img src="' + 
                    attachments[i].attributes.url + '" ></div>'
                    );

                //sample function 2: add hidden input for each image
                $('#myplugin-placeholder').after(
                    '<input id="myplugin-image-input' + 
                    attachments[i].id+'" type="hidden" \n\
                    name="myplugin_attachment_id_array[]" value="' + 
                    attachments[i].id + '" />'
                    );
/* 
 * $('#myplugin-placeholder').after(
                    '<input id="myplugin-image-input' +
                    attachments[i].id '" type="hidden" 
                    name="myplugin_attachment_id_array[]"  value="' + 
                    attachments[i].id + '">'
                    );
 * */
             }

        });

    myplugin_media_upload.open();

    });

});