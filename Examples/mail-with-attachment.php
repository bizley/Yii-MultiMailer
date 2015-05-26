<?php
/**
 * @author Pawel Bizley Brzozowski
 * @version 1.5
 * 
 * MultiMailer MAIL implementation with attachment
 * This sets default MAIL method with the attachment file.
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

        $mailer = Yii::app()->MultiMailer->to($recipientEmail, $recipientName);
        $mailer->subject($emailSubject);
        $mailer->body('This is test email.');
        $isAttached = $mailer->attachment('PATH_TO_FILE_TO_ATTACH');

        if ($isAttached && $mailer->send()) {
            $result = 'Test email has been sent successfully.';
        }
        else {
            $result = 'Test email sending error!<br>' . print_r($mailer->getMultiError(), true);
        }
        
        $this->render('index', array('result' => $result));
    }
}
