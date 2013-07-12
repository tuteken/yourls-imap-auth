# Yourls IMAP Authenication

A plugin for the Open Source URL-shortener [YOURLS](http://yourls.org/) (Your Own URL Shortener) to allow admins to login with a valid IMAP email account.

## Requirements

- YOURLS version 1.7+

## Installation

1. In /user/plugins, create a new folder named yourls-imap-auth
1. Unzip the contents of this package into your user/plugins/yourls-imap-auth folder
2. Activate plugin in the admin console
3. Start logging-in with an IMAP email account

## Configuration

1. Add the following lines to your user/config.php file

    // IMAP Configuration
    
    define('IMAP_SERVER', "http://www.example.com");
    
    define('IMAP_PORT', 143);
    
    define('IMAP_OPTIONS', '/imap/readonly');
    
    // End IMAP Configuration

2. Change the default IMAP values to your server
