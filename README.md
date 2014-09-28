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
You can find more configuration examples in Examples folder.

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

Special case: emails in database
================================

You can use MultiMailer to save email in database instead of sending it immediately. Email will be prepared using the PHPMailer as well.

First the configuration:

    return array(
        'components' => array(
            'MultiMailer' => array(
                'class' => 'ext.MultiMailer.MultiMailer',
                'setFromAddress' => 'from@yourwebsite.com',
                'setFromName' => 'Your Website',
                'setMethod' => 'DB',
                'setDbModel' => 'Email',
            ),
        ),
    );

You need to prepare the database table for email storage with Active Record model class ('Email' in the example above). Default table columns are:
- 'email' 
- 'name'
- 'subject'
- 'body'
- 'alt'

See the documentation for information about adding or switching the columns off.

The usage in this case is the same as before but remember that method send() will not actually *send* the email but will save it in database.

Available methods
=================

- mail()
- SMTP
- Gmail
- POP before SMTP
- Sendmail
- qmail
