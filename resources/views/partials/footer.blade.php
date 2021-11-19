<footer class="footer">
    <div class="primary dark-bg">
        <div class="inner clearfix">

            <?php if ( have_rows( 'primary_footer_section', 'option' ) ) : ?>
                <?php while ( have_rows( 'primary_footer_section', 'option' ) ) : the_row(); ?>
                    
                    <div class="site-logo">
                        <?php $site_logo = get_sub_field( 'site_logo' ); ?>
                        <?php if ( $site_logo ) { ?>
                            <a href="{{ home_url('/') }}">
                                <img src="<?php echo $site_logo['url']; ?>" alt="<?php echo $site_logo['alt']; ?>" />
                            </a>
                        <?php } ?>
                    </div>

                    <div class="internal-links pull-right">
                        <?php if ( have_rows( 'internal_links' ) ) : ?>
                            <?php while ( have_rows( 'internal_links' ) ) : the_row(); ?>
                                <?php $link = get_sub_field( 'link' ); ?>
                                <?php if ( $link ) { ?>
                                    <a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>"><?php echo $link['title']; ?></a>
                                <?php } ?>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>

                    <div class="social-links pull-left">
                        <?php if ( have_rows( 'social_links' ) ) : ?>
                            <?php while ( have_rows( 'social_links' ) ) : the_row(); ?>
                                <?php $link = get_sub_field( 'link' ); ?>
                                <?php if ( $link ) { ?>
                                    <a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>"><?php echo $link['title']; ?></a>
                                <?php } ?>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>

                <?php endwhile; ?>
            <?php endif; ?>

        </div>
    </div>

    <div class="secondary blue-bg">
        <div class="inner clearfix">
            <?php if ( have_rows( 'secondary_footer_section', 'option' ) ) : ?>
                <?php while ( have_rows( 'secondary_footer_section', 'option' ) ) : the_row(); ?>

                    <div class="copyright pull-left"><?php the_sub_field( 'copyright' ); ?></div>

                    <div class="designed-by"><?php the_sub_field( 'designed_by' ); ?></div>

                    <div class="quote pull-right"><?php the_sub_field( 'quote' ); ?></div>

                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</footer>
