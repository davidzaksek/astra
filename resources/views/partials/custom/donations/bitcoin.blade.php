<div id="bitcoin" class="bitcoin-form tab-content">
    <?php if ( have_rows( 'bitcoin_form' ) ) : ?>
        <?php while ( have_rows( 'bitcoin_form' ) ) : the_row(); ?>

            <h5 class="h5"><?php the_sub_field( 'address_label' ); ?></h5>
            
            <div class="copy-code-section form-inputs">
                <input id="qr-code" type="text" value="<?php the_sub_field( 'address_code' ); ?>" onclick="this.select();" onmouseup="return false;" spellcheck="false">
                <button class="copy-btn" onclick="copyToClipboard('qr-code', 'copy-text-hover')">
                    <span class="copy-text-hover"><?php the_sub_field( 'copy_code_text' ); ?></span>
                </button>
            </div>
            
            <h5 class="h5"><?php the_sub_field( 'qr_label' ); ?></h5>

            <?php $qr_image = get_sub_field( 'qr_image' ); ?>
            <?php if ( $qr_image ) { ?>
                <img class="bitcoin-qrcode-img" src="<?php echo $qr_image['url']; ?>" alt="<?php echo $qr_image['alt']; ?>" />
            <?php } ?>

        <?php endwhile; ?>
    <?php endif; ?>
</div>

<script>

    // Copy code functionality
    function copyToClipboard(inputElement, btnElement) {

        // Get input element
        var inputEl = document.getElementById(inputElement),
            // Get copy button element
            btnEl = document.getElementsByClassName(btnElement)[0],
            // Save current copy button value
            originalBtnText = btnEl.innerHTML;

        // Exit if button is already in copy timeout
        if (btnEl.classList.contains('active')) return;

        // Select all text in input field
        inputEl.select();

        // Copy text to clipboard
        document.execCommand("copy");

        // Update current btn text
        btnEl.innerHTML = 'Kopirano!';
        // Add active class to btn
        btnEl.classList.add('active');

        // Set timeout
        setTimeout(() => {

            // Show back original text
            btnEl.innerHTML = originalBtnText;
            // Remove active class from btn
            btnEl.classList.remove('active');
            // Remove focus from btnEl
            btnEl.blur();

        }, 1500);
    }

</script>