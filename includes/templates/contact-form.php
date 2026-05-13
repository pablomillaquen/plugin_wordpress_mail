    <form id="contact-form" method="post">
        <div class="form-group">
            <?php wp_nonce_field( 'wp_rest' ); ?>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required class="form-control">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea id="message" name="message" required class="form-control"></textarea>
        </div>
        <br />
        <div class="form-group">
            <button type="submit" class="btn btn-primary w-100">Send</button>
        </div>
    </form>

    <script>

        jQuery(document).ready(function($) {
            $('#contact-form').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "<?php echo get_rest_url(null, 'contact-plugin/v1/submit'); ?>",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response.success || 'Your message has been sent.');
                        $('#contact-form')[0].reset();
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred. Please try again.';
                        alert(errorMsg);
                    }
                });
            });
        });
    </script>

    