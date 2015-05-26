<?php
/**
 * @author Pawel Bizley Brzozowski
 * @version 1.5
 * 
 * MultiMailer MAIL implementation with body template to be sent with 
 * command line (Yii::CConsoleCommand()) 
 * This sets default MAIL method with the template option.
 */

/**
 * -----------------------------------------------------------------------------
 * Configuration:
 * <Yii directory>/protected/config/console.php
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
 * Email body template:
 * <Yii directory>/protected/views/mail/cli.php
 * -----------------------------------------------------------------------------
 */

?>

<h1>Hello <?php echo $user ?></h1>
<p>
    This is test for CLI email with template.<br>
    MultiMailer test.
</p>

<?php
/**
 * -----------------------------------------------------------------------------
 * Usage:
 * -----------------------------------------------------------------------------
 */

class ExampleCommand extends CConsoleCommand
{
    public function actionRun()
    {
        $recipientEmail = 'recipient@example.com';
        $recipientName  = 'Example Name';
        $emailSubject   = 'Example email subject';
        $emailBodyVars  = array('user' => 'Test User');
        
        $mailer = Yii::app()->MultiMailer->to($recipientEmail, $recipientName);
        $mailer->subject($emailSubject);
        $mailer->body($emailBodyVars)->template(Yii::app()->basePath . '/views/mail/cli.php');

        if ($mailer->send()) {
            $result = 'Test email has been sent successfully.';
        }
        else {
            $result = 'Test email sending error!<br>' . print_r($mailer->getMultiError(), true);
        }
        
        $this->render('index', array('result' => $result));
    }
}