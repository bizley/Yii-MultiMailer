With PHPMailer 5.2.10 you can get "preg_match(): Compilation failed: internal 
error: previously-checked referenced subpattern not found" error.

This is an issue with pcre8 validation pattern used by PHPMailer that appears 
with specific combination of PHP and PCRE library installed on the server.

To get rid of this error try to upgrade PHP and/or PCRE library and if it did 
not help or maybe it's somehow not possible for you to upgrade your server use 
MultiMailer 'setPattern' option set to something else than 'auto' or 'pcre8'.

<?php
/**
 * -----------------------------------------------------------------------------
 * Configuration:
 * <Yii directory>/protected/config/main.php
 * -----------------------------------------------------------------------------
 */

return array(
    // ...
    'components' => array(
        // ...
        'MultiMailer' => array(
            'class'          => 'ext.MultiMailer.MultiMailer',
            'setFromAddress' => 'example@example.com',
            'setFromName'    => 'Example',
            'setPattern'     => 'php', // <- or 'pcre' or 'html5'
        ),
        // ...
    ),
    // ...
);

?>

If you have any problem or question with MultiMailer please open an issue at 
GitHub https://github.com/bizley-code/Yii-MultiMailer/issues

If you have any problem with PHPMailer please let me know at GitHub as well so 
I'll try to help or ask PHPMailer's author directly at 
https://github.com/PHPMailer/PHPMailer/issues