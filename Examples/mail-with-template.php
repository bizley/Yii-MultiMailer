<?php
/**
 * @author Pawel Bizley Brzozowski
 * @version 1.3
 * 
 * MultiMailer MAIL implementation with body template
 * This sets default MAIL method with the template option.
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
        ),
        // ...
    ),
    // ...
);

/**
 * -----------------------------------------------------------------------------
 * Email body template:
 * <Yii directory>/protected/views/mail/mail.php
 * -----------------------------------------------------------------------------
 */

?>

<h1>Hello <?php echo $user; ?></h1>
<p>
    This is test for email template <?php echo $template; ?>.<br>
    MultiMailer test.
</p>

<?php
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
        $emailViewName  = '//mail/mail';
        $emailBodyVars  = array(
            'user'      => 'Test User',
            'template'  => $emailViewName,
        );
        
        $mailer = Yii::app()->MultiMailer->to($recipientEmail, $recipientName);
        $mailer->subject($emailSubject);
        $mailer->body($emailBodyVars)->template($emailViewName);

        if ($mailer->send()) {
            $result = 'Test email has been sent successfully.';
        }
        else {
            $result = 'Test email sending error!<br>' . $mailer->getMultiError();
        }
        
        $this->render('index', array('result' => $result));
    }
}