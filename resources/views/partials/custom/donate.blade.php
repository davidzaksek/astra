<div class="inner-child clearfix">
    <?php if ( have_rows( 'donate' ) ) : ?>
        <?php while ( have_rows( 'donate' ) ) : the_row(); ?>

            <div class="donation-block less-padding">
                <?php if ( have_rows( 'main_content' ) ) : ?>
                    <?php while ( have_rows( 'main_content' ) ) : the_row(); ?>

                        <div class="clearfix">
                            <div class="about-section">

                                <h1 class="h1"><u><?php the_sub_field( 'title' ); ?></u></h1>
                                <div class="desc"><?php the_sub_field( 'description' ); ?></div>

                                <?php if ( have_rows( 'donation', 'option' ) ) : ?>
                                    <?php while ( have_rows( 'donation', 'option' ) ) : the_row(); ?>

                                        {{-- Signature --}}
                                        @include('partials/custom/donations/signature')

                                    <?php endwhile; ?>
                                <?php endif; ?>

                            </div>

                            <div class="donate-section pull-right">

                                {{-- Full Donation Form --}}
                                @include('partials/custom/donations/_form')

                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
            
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<div class="thank-you-section dark-bg">
    <div class="inner-child">

        <?php if ( have_rows( 'donate' ) ) : ?>
            <?php while ( have_rows( 'donate' ) ) : the_row(); ?>
            
                <?php if ( have_rows( 'wall_of_gratefulness' ) ) : ?>
                    <?php while ( have_rows( 'wall_of_gratefulness' ) ) : the_row(); ?>

                        <h2 class="lh2 light">{!! strip_tags(get_sub_field( 'title' ), '<u>') !!}</h2>
                        <p class="h5 light uppercase"><?php the_sub_field( 'subtitle' ); ?></p>
                            
                        <div class="donators">
                            <?php if ( have_rows( 'donators' ) ) : ?>
                                <?php while ( have_rows( 'donators' ) ) : the_row(); ?>
                                    <div class="donator"><?php the_sub_field( 'full_name' ); ?></div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>

                        <a class="btn box blue hide-m" href="#top-scroll">
                            <span class="full-arrow is-top white"></span>
                            <span class="text"><?php the_sub_field( 'scroll_top_text' ); ?></span>
                        </a>
                        <a class="btn box blue small hide-d" href="#top-scroll">
                            <span class="full-arrow is-top white"></span>
                            <span class="text"><?php the_sub_field( 'scroll_top_text' ); ?></span>
                        </a>

                    <?php endwhile; ?>
                <?php endif; ?>

            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>