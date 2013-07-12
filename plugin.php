<?php
/*
Plugin Name: YOURLS IMAP Authentication
Plugin URI: http://github.com/tuteken/yourls-imap-auth
Description: Add support for IMAP based authenication.
Version: 1.0
Author: tuteken
Author URI: None
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

// Options
if (!defined('IMAP_SERVER')) { define('IMAP_SERVER', "http://www.example.com"); }
if (!defined('IMAP_PORT')) { define('IMAP_PORT', 143); }
if (!defined('IMAP_OPTIONS')) { define('IMAP_OPTIONS', '/imap/readonly'); }

// Login
yourls_add_filter( 'shunt_is_valid_user', 'imap_is_valid_user' );

function imap_is_valid_user()
{
    global $yourls_user_passwords;
    session_start();

    // Logout request
    if( isset( $_GET['action'] ) && $_GET['action'] == 'logout' ) 
    {
        yourls_do_action( 'logout' );
        yourls_store_cookie( null );
        unset($_SESSION['IMAP_USER_AUTH']);
        session_destroy();
        return yourls__( 'Logged out successfully' );
    }

    if ( isset( $_SESSION['IMAP_AUTH_USER'] ) ) 
    {
        list($username, $hash) = explode(":", $_SESSION['IMAP_AUTH_USER'] );
        if ( hash('sha512', $username.YOURLS_COOKIEKEY) === $hash ) {
            yourls_set_user( $username );
            return true;
        }
        return null;
    }

    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    if ($username && $password) 
    {
        $imap = imap_open(
            "{".IMAP_SERVER.":".IMAP_PORT.IMAP_OPTIONS."}INBOX",
            $username,
            $password);

        if ($imap) 
        {
            imap_close($imap);

           $_SESSION['IMAP_AUTH_USER'] = $username.":".hash('sha512', $username.YOURLS_COOKIEKEY);

            // Notify various yourls stages
            yourls_do_action( 'pre_login' );
            yourls_do_action( 'pre_login_username_password' );
            yourls_do_action( 'login' );
            yourls_set_user( $username );
            if ( !yourls_is_API() ) 
            {
                // Satisfy yourls' cookie generation routine
                $yourls_user_passwords[$username] = $username;
                yourls_store_cookie( YOURLS_USER );
            }
            return true;
        }
    }
    return null;
}
?>
