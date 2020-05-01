var ds = ds || {};


( function( $ ) {
	var media;

	ds.media = media = {
		buttonId: '#open-media-modal',
		detailsTemplate: '#attachment-details-tmpl',

		frame: function() {
			if ( this._frame )
				return this._frame;

			this._frame = wp.media( {
				title: 'Elija las imágenes',
				button: {
					text: 'Elegir'
				},
				multiple: true,
				library: {
					type: 'image'
				}
			} );

			this._frame.on( 'ready', this.ready );

			this._frame.state( 'library' ).on( 'select', this.select );

			return this._frame;
		},

		ready: function() {
			$( '.media-modal' ).addClass( 'no-sidebar smaller' );
		},

		select: function() {
			var settings = wp.media.view.settings,
				selection = this.get( 'selection' );

			$( '.added' ).remove();
			selection.map( media.showAttachmentDetails );
			$( '#attachment-details-tmpl' ).remove();
		},

		showAttachmentDetails: function( attachment ) {
                        var id_image = attachment.attributes.id;
                        var img_item_id = '<input type="hidden" name="cyc_home_image_gallery[]" value="'+id_image+'" />';
                        
                        var src_image = attachment.attributes.sizes.thumbnail.url;
                        var img_item_src = '<img src="'+src_image+'" width="100" height="100" /><span class="remove_item dashicons dashicons-no"></span>'
                        
                        var image_item = '<div class="img_item" id="img_item_' + id_image + '">' + img_item_id + img_item_src + '</div>';
                        $('#cyc_home_gallery_images').prepend(image_item);
		},

		init: function() {
			$( media.buttonId ).on( 'click', function( e ) {
				e.preventDefault();

				media.frame().open();
			});
		}
	};

	$( media.init );
} )( jQuery );

( function( $ ) {
    $(document).on('click','.remove_item',function(){
        $(this).parent().remove();
    });
} )( jQuery );
