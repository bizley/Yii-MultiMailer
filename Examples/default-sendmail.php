<?php
/**
 * @author Pawel Bizley Brzozowski
 * @version 1.5
 * 
 * MultiMailer default SENDMAIL implementation
 * This sets default SENDMAIL method with minimum options.
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
            'class'          => 'ext.MultiMailer.MultiMailer',
            'setFromAddress' => 'example@example.com',
            'setFromName'    => 'Example',
            'setMethod'      => 'SENDMAIL',
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
            $result = 'Test email sending error!<br>' . print_r($mailer->getMultiError(), true);
        }
        
        $this->render('index', array('result' => $result));
    }
}