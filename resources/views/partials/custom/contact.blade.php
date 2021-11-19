<div class="inner-child clearfix">

	<?php if ( have_rows( 'contact' ) ) : ?>
        <?php while ( have_rows( 'contact' ) ) : the_row(); ?>
        
            <div class="form-section pull-left">
                <h1 class="h1"><?php the_sub_field( 'title' ); ?></h1>
                <p class="h5 uppercase"><?php the_sub_field( 'description' ); ?></p>
                <div class="form-inputs">
                    <?php echo do_shortcode(get_sub_field( 'form_shortcode' )); ?>
                </div>
            </div>
            
            <div class="social-section pull-right">
                <h3 class="s-title"><?php the_sub_field( 'connect_text' ); ?></h3>

                <div class="social-links">
                <?php if ( have_rows( 'connect_links' ) ) : ?>
                    <?php while ( have_rows( 'connect_links' ) ) : the_row(); ?>

                        <?php $link = get_sub_field( 'link' ); ?>
                        <?php $icon = get_sub_field( 'icon' ); ?>
                        
                        <?php if ( $link ) { ?>
                            <a href="<?php echo $link['url']; ?>" class="social-link" target="<?php echo $link['target']; ?>">
                                <img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                            </a>
                        <?php } ?>
                        
                    <?php endwhile; ?>
                <?php endif; ?>
                </div>

            </div>
            
		<?php endwhile; ?>
    <?php endif; ?>
    
</div>