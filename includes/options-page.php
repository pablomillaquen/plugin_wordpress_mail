<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use PHPMailer\PHPMailer\PHPMailer;

add_action('after_setup_theme', 'local_carbon_fields');
add_action('carbon_fields_register_fields', 'create_options_page');
add_action('phpmailer_init', 'configure_mailhog');

function local_carbon_fields() {
    // Initialize Carbon Fields
    \Carbon_Fields\Carbon_Fields::boot();
}

function create_options_page() {
    Container::make('theme_options', __( 'Contact Form Settings' ))
        ->set_icon('dashicons-email')
        ->add_fields(array(

            Field::make('text', 'contact_plugin_recipients', 'Recipient Email')
                ->set_attribute('placeholder', 'Enter recipient email address')
                ->set_help_text('Enter the email address where contact form submissions will be sent.'),
            
            Field::make('textarea', 'contact_plugin_message', 'Confirmation Message')
                ->set_attribute('placeholder', 'Enter confirmation message')
                ->set_help_text('Enter a message that will be displayed after a successful contact form submission.'),
        ));
}   

function configure_mailhog(PHPMailer $phpmailer) {

    $phpmailer->isSMTP();

    $phpmailer->Host = 'mailhog';
    $phpmailer->Port = 1025;

    $phpmailer->SMTPAuth = false;
    $phpmailer->SMTPSecure = false;

}