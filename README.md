MultiMailer
===========

Yii extension for sending or saving emails.

What it does
============

This extension allows to send emails with the help of PHPMailer class or to store emails in database (to send them later using Java workers for example).

Installation
============

Place the MultiMailer folder inside your Yii /protected/extensions directory.
Modify the main.php config file. For example:

    return array(
        'components' => array(
            'MultiMailer' => array(
                'class' => 'ext.MultiMailer.MultiMailer',
                'setFromAddress' => 'from@yourwebsite.com',
                'setFromName' => 'Your Website',
            ),
        ),
    );
  
This sets MultiMailer to send email using PHP mail() function with sender 'Your Website <from@yourwebsite.com>'.
You can find more configuration examples in Examples folder (as soon as I add it).

How to use it
=============

    $mailer = Yii::app()->MultiMailer->to('example@server.com', 'Recipient');
    $mailer->subject('Example email subject');
    $mailer->body('<h1>Hello</h1><p>This is test.<br>MultiMailer test.</p>');

    if ($mailer->send()) {
        // success
    }
    else {
        // error
    }

You can chain the methods like:

    Yii::app()->MultiMailer->to('example@server.com', 'Recipient')->subject('Example email subject')->body('<h1>Hello</h1><p>This is test.<br>MultiMailer test.</p>')->send();
