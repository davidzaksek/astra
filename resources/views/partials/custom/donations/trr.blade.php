<div id="trr" class="trr-form tab-content form-inputs">
    <?php if ( have_rows( 'trr_form' ) ) : ?>
        <?php while ( have_rows( 'trr_form' ) ) : the_row(); ?>

            <div class="para"><?php the_sub_field( 'description' ); ?></div>
            <?php echo do_shortcode(get_sub_field( 'form_shortcode' )); ?>

        <?php endwhile; ?>
    <?php endif; ?>
</div>