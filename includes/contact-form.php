<?php

add_shortcode('contact_form', 'render_contact_form');
add_action('rest_api_init', 'create_rest_endpoint');

function render_contact_form() {

    ob_start();
    include MY_PLUGIN_DIR . '/includes/templates/contact-form.php';
    return ob_get_clean();
}

function create_rest_endpoint() {
    register_rest_route('contact-plugin/v1', '/submit', array(
        'methods' => 'POST',
        'callback' => 'handle_form_submission',
        'permission_callback' => '__return_true',
    ));
}

function handle_form_submission(WP_REST_Request $request) {

    try {

        $params = $request->get_params();

        if( !isset($params['_wpnonce']) || !wp_verify_nonce($params['_wpnonce'], 'wp_rest') ) {
            return new WP_REST_Response([
                'error' => 'Invalid nonce.'
            ], 400);
        }

        unset($params['_wpnonce']);
        unset($params['_wp_http_referer']);

        $name = sanitize_text_field($params['name'] ?? '');
        $email = sanitize_email($params['email'] ?? '');
        $message_text = sanitize_textarea_field($params['message'] ?? '');

        if (empty($name) || empty($email) || empty($message_text)) {
            return new WP_REST_Response([
                'error' => 'All fields are required.'
            ], 400);
        }

        $headers = [];
        $headers[] = "From: " . get_bloginfo('name') . " <" . get_bloginfo('admin_email') . ">";
        $headers[] = "Reply-To: " . $name . " <" . $email . ">";
        $headers[] = "Content-Type: text/html; charset=UTF-8";

        $message = '';
        $message .= 'Message has been sent from: ' . get_bloginfo('name') . '<br>';

        foreach ($params as $key => $value) {
            $message .= '<strong>' . ucfirst($key) . ':</strong> ' . nl2br(esc_html($value)) . '<br>';
        }

        $message .= $message_text;

        $sent = wp_mail(
            get_bloginfo('admin_email'),
            'New Contact Form Submission',
            $message,
            $headers
        );

        if ($sent) {

            return new WP_REST_Response([
                'success' => 'Your message has been sent.'
            ], 200);

        } else {

            global $phpmailer;

            return new WP_REST_Response([
                'error' => 'wp_mail failed',
                'phpmailer_error' => $phpmailer->ErrorInfo ?? 'No error info available'
            ], 500);
        }

    } catch (\Throwable $e) {

        return new WP_REST_Response([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ], 500);

    }

}