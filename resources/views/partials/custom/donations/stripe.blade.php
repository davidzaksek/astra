<?php if ( have_rows( 'stripe_form' ) ) : ?>
    <?php while ( have_rows( 'stripe_form' ) ) : the_row(); ?>

        <div id="stripe" class="stripe-form tab-content form-inputs">

            <div class="h5"><?php the_sub_field( 'stripe_description' ); ?></div>
            <?php echo do_shortcode(get_sub_field( 'form_shortcode' )) ?>

        </div>

    <?php endwhile; ?>
<?php endif; ?>