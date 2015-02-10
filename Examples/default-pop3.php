<?php
/**
 * @author Pawel Bizley Brzozowski
 * @version 1.3
 * 
 * MultiMailer default POP before SMTP implementation
 * This sets POP3 method with minimum options.
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
            'class'             => 'ext.MultiMailer.MultiMailer',
            'setFromAddress'    => 'example@example.com',
            'setFromName'       => 'Example',
            'setMethod'         => 'POP3',
            'setPopOptions'     => array(
                'Host'      => 'pop3.example.com',
                'Username'  => 'pop3username@example.com',
                'Password'  => 'pop3password',
            ),
            'setOptions'        => array(
                'Host'      => 'smtp.example.com',
            ),
        ),
        // ...
    ),
    // ...
);

/**
 * -----------------------------------------------------------------------------
 * Usage:
 * -----------------------------------------------------------------------------
 */

class ExampleController extends Controller
{
    public function actionIndex()
    {
        $recipientEmail = 'recipient@example.com';
        $recipientName  = 'Example Name';
        $emailSubject   = 'Example email subject';
        $emailBody      = '<h1>Hello</h1><p>This is test.<br>MultiMailer test.</p>';
        
        $mailer = Yii::app()->MultiMailer->to($recipientEmail, $recipientName);
        $mailer->subject($emailSubject);
        $mailer->body($emailBody);

        if ($mailer->send()) {
            $result = 'Test email has been sent successfully.';
        }
        else {
            $result = 'Test email sending error!<br>' . $mailer->getMultiError();
        }
        
        $this->render('index', array('result' => $result));
    }
}