<?php if ( have_rows( 'paypal_form' ) ) : ?>
<?php while ( have_rows( 'paypal_form' ) ) : the_row(); ?>

<?php echo do_shortcode('[dntplgn item_name="Astra.si donacije"]'); ?>

<div id="paypal" class="paypal-form tab-content" style="display: block">

    <?php if ( have_rows( 'payment_recurrence' ) ) : ?>
        <?php while ( have_rows( 'payment_recurrence' ) ) : the_row(); ?>

            <h5 class="h5"><?php the_sub_field( 'label' ); ?></h5>

            <div class="recurring-options options">

                <?php 
                    $monthly = get_sub_field( 'monthly_text' );
                    $monthly_label = strip_string($monthly);
                ?>
                <input type="radio" name="recurring" class="monthly" id="{{$monthly_label}}" checked>
                <label for="{{$monthly_label}}">{{$monthly}}</label>
                
                <?php 
                    $once = get_sub_field( 'one_time_text' );
                    $once_label = strip_string($once);
                ?>
                <input type="radio" name="recurring" class="once" id="{{$once_label}}">
                <label for="{{$once_label}}">{{$once}}</label>

            </div>

        <?php endwhile; ?>
    <?php endif; ?>

    <?php if ( have_rows( 'payment_options' ) ) : ?>
        <?php while ( have_rows( 'payment_options' ) ) : the_row(); ?>

            <h5 class="h5"><?php the_sub_field( 'label' ); ?></h5>

            <div class="amount-options options ph-styles">

                <?php if ( have_rows( 'options' ) ) : ?>

                    <?php 
                        $count = 0;
                        while ( have_rows( 'options' ) ) : the_row();
                        $checked = ($count == 0) ? 'checked' : '';
                    ?>

                        <?php 
                            $amount = get_sub_field( 'amount' );
                            $amount_label = 'al-' . strip_string($amount);
                        ?>
                        <input type="radio" name="amount" id="{{$amount_label}}" value="{{$amount}}" {{$checked}}>
                        <label for="{{$amount_label}}">{{$amount}} €</label>

                    <?php $count++; endwhile; ?>
                <?php endif; ?>

                <?php $c_amount = get_sub_field( 'custom_option_text' ); ?>
                <input type="radio" name="amount" id="custom_amount" value="other">
                <label for="custom_amount" class="contains-input">
                    <input id="custom_donation_value" type="number" placeholder="{{$c_amount}}" onclick="document.getElementById('custom_amount').checked = true;">
                </label>

            </div>

        <?php endwhile; ?>
    <?php endif; ?>
    
    <button type="button" id="submit_paypal_form" class="btn yellow default load-btn"><?php the_sub_field( 'main_button_text' ); ?></button>

    <script>
        document.getElementById('submit_paypal_form').addEventListener('click', function() {

            // Start loading button
            document.getElementsByClassName('load-btn')[0].classList.add('loading');

            // Get recurring state | Monthly or Once
            var monthly = (document.querySelector('input[name=recurring].monthly:checked')) ? true : false;
            var amount = document.querySelector('input[name=amount]:checked').value;
            // Check if custom amount was inserted
            if (amount == "other") amount = document.getElementById('custom_donation_value').value;

            var paypal_plugin = document.getElementsByClassName('dntplgn_form_wrapper')[0];
            var monthly_form = paypal_plugin.querySelector('form.dntplgn_donate_monthly');
            var once_form = paypal_plugin.querySelector('form:not(.dntplgn_donate_monthly)');

            if (monthly) { // Monthly payment

                // Choose monthly option in plugin
                var monthly_amount = paypal_plugin.getElementsByClassName('dntplgn_monthly_other_sum')[0];

                // Select "other" option
                monthly_amount.classList.add('checked');

                // Check its input
                document.getElementById('fourth_button').checked = true;

                // Set amount
                monthly_form.querySelectorAll('input[ name="a3" ]').forEach(element => {
                    element.value = amount;
                });

                // Submit monthly form
                monthly_form.submit.click();

            } else { // One time payment

                // Choose once option in plugin
                document.getElementById('dntplgn_once_amount').value = amount;

                // Submit one time form
                once_form.submit.click();
            }
        });
    </script>

</div>

<?php if (!isset($hide_more_link)) { ?>

<?php $more_options_link = get_sub_field( 'more_options_link' ); ?>
<?php if ( $more_options_link ) { ?>
    <a class="link" href="<?php echo $more_options_link['url']; ?>" target="<?php echo $more_options_link['target']; ?>"><?php echo $more_options_link['title']; ?><div class="underline"></div></a>
<?php } ?>

<?php } ?>

<?php endwhile; ?>
<?php endif; ?>