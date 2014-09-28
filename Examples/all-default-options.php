<?php
/**
 * @author Pawel Bizley Brzozowski
 * @version 1.1
 * 
 * MultiMailer list of all default configuration options here so you can see 
 * what is unnecessary to set.
 */

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
            'setMethod'             => 'MAIL',
            'setContentType'        => 'html',
            'setFromAddress'        => '',
            'setFromName'           => '',
            'setReplyAddress'       => '',
            'setReplyName'          => '',
            'setSameReply'          => true,
            'setLogging'            => true,
            'setDbModel'            => '',
            'setDbModelColumns'     => array(
                'email'     => 'email',
                'name'      => 'name',
                'subject'   => 'subject',
                'body'      => 'body',
                'alt'       => 'alt',
            ),
            'setExternalExceptions' => true,
            'setOptions'            => array(
                'CharSet'       => 'UTF-8',
                'SMTPDebug'     => 0,
                'Debugoutput'   => 'html',
                'Host'          => 'mail.example.com',
                'Port'          => 25,
                'SMTPAuth'      => true,
                'Username'      => 'yourname@example.com',
                'Password'      => 'yourpassword',
            ),
            'setPopOptions'         => array(
                'Port'          => false,
                'Timeout'       => false,
                'Username'      => '',
                'Password'      => '',
                'Debug'         => 0,
            ),
        ),
        // ...
    ),
    // ...
);