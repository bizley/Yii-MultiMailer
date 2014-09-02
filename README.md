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
