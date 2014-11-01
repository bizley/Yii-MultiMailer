<?php
/**
 * @author Pawel Bizley Brzozowski
 * @version 1.2
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
            'setContentType'        => 'html',
            'setDbModel'            => '',
            'setDbModelColumns'     => array(
                'alt'       => 'alt',
                'body'      => 'body',
                'email'     => 'email',
                'name'      => 'name',
                'subject'   => 'subject',              
            ),
            'setExternalExceptions' => true,
            'setFromAddress'        => '',
            'setFromName'           => '',
            'setMethod'             => 'MAIL',
            'setOptions'            => array(
                'action_function'       => '',
                'AllowEmpty'            => false,
                'AuthType'              => '',
                'CharSet'               => 'UTF-8',
                'ConfirmReadingTo'      => '',
                'ContentType'           => 'text/html',
                'Debugoutput'           => 'html',
                'DKIM_domain'           => '',
                'DKIM_identity'         => '',
                'DKIM_passphrase'       => '',
                'DKIM_private'          => '',
                'DKIM_selector'         => '',             
                'do_verp'               => false,
                'Encoding'              => '8bit',
                'ErrorInfo'             => '',
                'Helo'                  => '',
                'Host'                  => 'mail.example.com',
                'Hostname'              => '',
                'Ical'                  => '',
                'LE'                    => '
',
                'Mailer'                => 'mail',
                'MessageDate'           => '',
                'MessageID'             => '',
                'Password'              => 'yourpassword',
                'PluginDir'             => '',
                'Port'                  => 25,
                'Priority'              => 3,
                'Realm'                 => '',
                'ReturnPath'            => '',
                'Sendmail'              => '/usr/sbin/sendmail',
                'SingleTo'              => false,
                'SingleToArray'         => array(),
                'SMTPAuth'              => true,
                'SMTPDebug'             => 0,
                'SMTPKeepAlive'         => false,
                'SMTPSecure'            => '',
                'Timeout'               => 10,
                'Username'              => 'yourname@example.com',
                'UseSendmailOptions'    => true,
                'WordWrap'              => 0,
                'Workstation'           => '',
                'XMailer'               => '',
            ),
            'setPopOptions'         => array(
                'Debug'         => 0,
                'Password'      => '',
                'Port'          => false,
                'Timeout'       => false,
                'Username'      => '',              
            ),
            'setReplyAddress'       => '',
            'setReplyName'          => '',
            'setSameReply'          => true,
            'setTranslation'        => true,
        ),
        // ...
    ),
    // ...
);