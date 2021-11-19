<?php if ( have_rows( 'donation', 'option' ) ) : ?>
	<?php while ( have_rows( 'donation', 'option' ) ) : the_row(); ?>

		<?php if ( have_rows( 'form_section' ) ) : ?>
            <?php while ( have_rows( 'form_section' ) ) : the_row(); ?>
            
				<?php if ( have_rows( 'payment_type' ) ) : ?>
                    <?php while ( have_rows( 'payment_type' ) ) : the_row(); ?>
                    
                        <h5 class="h5"><?php the_sub_field( 'label' ); ?></h5>
						
                        <div class="tabs">
                            <button class="tab-link active" onclick="showTab(event, 'paypal')"><?php the_sub_field( 'paypal_text' ); ?></button>
                            <button class="tab-link" onclick="showTab(event, 'stripe')"><?php the_sub_field( 'stripe_text' ); ?></button>
                            <button class="tab-link" onclick="showTab(event, 'bitcoin')"><?php the_sub_field( 'bitcoin_text' ); ?></button>
                            <button class="tab-link" onclick="showTab(event, 'trr')"><?php the_sub_field( 'trr_text' ); ?></button>
                        </div>

					<?php endwhile; ?>
                <?php endif; ?>
                
                {{-- PayPal Section --}}
                @include('partials/custom/donations/paypal', ['hide_more_link' => true])
                
                {{-- Stripe Section --}}
                @include('partials/custom/donations/stripe')
                
                {{-- Bitcoin Section --}}
                @include('partials/custom/donations/bitcoin')
                
                {{-- TRR Section --}}
                @include('partials/custom/donations/trr')

			<?php endwhile; ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php endif; ?>

<script>

     // Tab functionality
     function showTab(event, tab) {
        var i, tabcontent, tablinks;

        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        tablinks = document.getElementsByClassName("tab-link");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show selected tab
        document.getElementById(tab).style.display = "block";
        event.currentTarget.className += " active";
    }

</script>