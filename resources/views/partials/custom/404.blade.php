<div class="page-not-found">
    <div class="inner-child">
        <?php if ( have_rows( '404_page_not_found', 'option' ) ) : ?>
            <?php while ( have_rows( '404_page_not_found', 'option' ) ) : the_row(); ?>

                <h1 class="h1"><?php the_sub_field( 'title' ); ?></h1>
                <p class="h5 uppercase"><?php the_sub_field( 'description' ); ?></p>

            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>
