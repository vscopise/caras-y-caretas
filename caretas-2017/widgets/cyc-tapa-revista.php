<?php

/***** MH Tapa Revista Widget *****/

class cyc_tapa_revista extends WP_Widget {

	function Tapa_Revista() {
		// widget actual processes
		parent::WP_Widget(false, $name = 'Tapa Revista', array(
			'description' => 'Muestra la tapa de la última revista'
		));
	}
	function __construct() {
		parent::__construct(
			'mh_newsdesk_lite_tapa_revista', esc_html_x('MH Tapa Revista', 'widget name', 'mh-newsdesk-lite'),
			array('mh_newsdesk_lite_tapa_revista' => 'mh_affiliate', 'description' => esc_html__('Muestra la tapa de la última revista', 'mh-newsdesk-lite'))
		);
	}
	function widget( $args, $instance ) {
		extract( $args );
		$titulo = apply_filters('widget_titulo', empty($instance['titulo']) ? '' : $instance['titulo'], $instance, $this->id_base);
		$pagina = apply_filters('widget_pagina', empty($instance['pagina']) ? '' : $instance['pagina'], $instance, $this->id_base);
		$producto = apply_filters('widget_producto', empty($instance['producto']) ? '' : $instance['producto'], $instance, $this->id_base);

		echo $before_widget;
		if ( $titulo ) :
                    ?>
                        <h4 class="widget-title">
                            <span><?php echo $titulo; ?></span>
                        </h4>
                    <?php
		endif;
		echo '<div class="tapa-revista-widget clearfix">';
                $query_args = array(
                        'post_type' => 'revistas',
                        'posts_per_page' => 1,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'productos',
                                'field' => 'term_id',
                                'terms' => $producto
                            )
                        )
                );
                $revistas = new WP_Query( $query_args ); 
                if ( $revistas->have_posts() ) : while( $revistas->have_posts() ) : $revistas->the_post();
                        if ( has_post_thumbnail ) :
                        ?>
                            <div class="entry-image">
                                <a href="<?php echo get_page_link( $pagina ); ?>">
                                    <?php the_post_thumbnail( 'tapa-small' ); ?>
                                </a>
                            </div>
                        <?php
                        endif;
                endwhile; endif;
		echo '</div>';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['titulo'] = strip_tags($new_instance['titulo']);
		$instance['pagina'] = strip_tags($new_instance['pagina']);
		$instance['producto'] = strip_tags($new_instance['producto']);
		

		return $instance;
	}

	function form( $instance ) {

		$titulo = isset($instance['titulo']) ? esc_attr($instance['titulo']) : '';
		$pagina = isset($instance['pagina']) ? esc_attr($instance['pagina']) : '';
		$producto = isset($instance['producto']) ? esc_attr($instance['producto']) : '';
		
?>

		<!-- admin widget starts -->
		<div class="rd-widget-form">
			<p><label for="<?php echo $this->get_field_id('titulo'); ?>">Título</label>
			<input class="widefat" id="<?php echo $this->get_field_id('titulo'); ?>" name="<?php echo $this->get_field_name('titulo'); ?>" type="text" value="<?php echo esc_attr($titulo); ?>" /></p>
                        <p>
				<label for="pagina">Página</label>
				<select class="widefat" name="<?php echo $this->get_field_name('pagina'); ?>" id="<?php echo $this->get_field_id('pagina'); ?>">
                                        <?php
                                        if( $pages = get_pages() ){
                                            foreach( $pages as $page ){
                                                echo '<option value="' . $page->ID . '" ' . selected( $page->ID, $pagina ) . '>' . $page->post_title . '</option>';
                                            }
                                        }
                                        ?>
				</select>
			</p>
                        <p>
                            <label for="producto">Producto</label>
                                <select class="widefat" name="<?php echo $this->get_field_name('producto'); ?>" id="<?php echo $this->get_field_id('producto'); ?>">
                                        <?php
                                        $productos = get_terms( 'productos');
                                            foreach( $productos as $prod ){
                                                echo '<option value="' . $prod->term_id . '" ' . selected( $prod->term_id, $producto ) . '>' . $prod->name . '</option>';
                                            }
                                        ?>
				</select>
                        </p>
			 <p><b>Este widget muestra la tapa de la última revista con un link a la página que se establezca</b></p>
		</div>
		<!-- admin widget ends -->
<?php
	}
}


?>