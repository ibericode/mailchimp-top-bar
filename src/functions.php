<?php

function mctb_get_options()
{
    static $options = null;
    if (is_array($options)) {
        return $options;
    }

    $defaults = array(
        'list' => '',
        'enabled' => 1,
        'show_to_administrators' => 1,
        'cookie_length' => 365,
        'color_bar' => '#ffcc00',
        'color_text' => '#222222',
        'color_button' => '#222222',
        'color_button_text' => '#ffffff',
        'disable_after_use' => 1,
        'size' => 'medium',
        'sticky' => 1,
        'text_email_placeholder' => __( 'Your email address..', 'mailchimp-top-bar' ),
        'text_bar' => __( 'Sign-up now - don\'t miss the fun!', 'mailchimp-top-bar' ),
        'text_button' => __( 'Subscribe', 'mailchimp-top-bar' ),
        'redirect' => '',
        'position' => 'top',
        'double_optin' => 1,
        'send_welcome' => 0,
        'update_existing' => 0,
        'text_subscribed' => __( "Thanks, you're in! Please check your email inbox for a confirmation.", 'mailchimp-top-bar' ),
        'text_error' => __( "Oops. Something went wrong.", 'mailchimp-top-bar' ),
        'text_invalid_email' => __( 'That email seems to be invalid.', 'mailchimp-top-bar' ),
        'text_already_subscribed' => __( "You are already subscribed. Thank you!", 'mailchimp-top-bar' ),
        'disable_on_pages' => '',
    );

    $options = (array) get_option('mailchimp_top_bar', array());
    $options = array_merge( $defaults, $options );

    // for BC with MailChimp Top Bar v1.2.3, always fill text option keys
    $text_keys = array(
        'text_subscribed',
        'text_error',
        'text_invalid_email',
        'text_already_subscribed'
    );

    foreach( $text_keys as $text_key ) {
        if( empty( $options[ $text_key ] ) && ! empty( $defaults[ $text_key ] ) ) {
            $options[ $text_key ] = $defaults[ $text_key ];
        }
    }

    return $options;
}