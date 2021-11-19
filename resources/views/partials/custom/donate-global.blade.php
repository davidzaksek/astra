<div class="donation-block">
<div class="inner-child">

	<?php if ( have_rows( 'donation', 'option' ) ) : ?>
	    <?php while ( have_rows( 'donation', 'option' ) ) : the_row(); ?>

            <div class="about-section">

                <h2 class="lh2">{!! strip_tags(get_sub_field( 'title' ), '<u>') !!}</h2>
                <div class="desc"><?php the_sub_field( 'description' ); ?></div>
                
                @include('partials/custom/donations/signature')

            </div>

            <div class="donate-section pull-right">
                <?php if ( have_rows( 'form_section' ) ) : ?>
                    <?php while ( have_rows( 'form_section' ) ) : the_row(); ?>
                        
                        @include('partials/custom/donations/paypal')

                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

		<?php endwhile; ?>
    <?php endif; ?>
    
</div>
</div>