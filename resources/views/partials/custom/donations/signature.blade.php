<div class="signature">
    <?php if ( have_rows( 'signature_section' ) ) : ?>
        <?php while ( have_rows( 'signature_section' ) ) : the_row(); ?>

            <?php $profile_picture = get_sub_field( 'profile_picture' ); ?>
            <?php if ( $profile_picture ) { ?>
                <figure class="profile-pic pull-left">
                    <img src="<?php echo $profile_picture['url']; ?>" alt="<?php echo $profile_picture['alt']; ?>" />
                </figure>
            <?php } ?>
            <h6 class="h6 full-name"><?php the_sub_field( 'full_name' ); ?></h6>
            <p class="para opac title"><?php the_sub_field( 'title' ); ?></p>
            
            <?php $signature_image = get_sub_field( 'signature_image' ); ?>
            <?php if ( $signature_image ) { ?>
                <img class="signature-img" src="<?php echo $signature_image['url']; ?>" alt="<?php echo $signature_image['alt']; ?>" />
            <?php } ?>

        <?php endwhile; ?>
    <?php endif; ?>
</div>