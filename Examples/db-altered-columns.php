<?php
/**
 * @author Pawel Bizley Brzozowski
 * @version 1.5
 * 
 * MultiMailer DB implementation
 * This sets DB method with altered columns.
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
            'setMethod'         => 'DB',
            'setDbModel'        => 'Email',
            'setDbModelColumns' => array(
                'alt'   => null,
                'body'  => 'content',
                'email' => 'address',
            ),
        ),
        // ...
    ),
    // ...
);

/**
 * -----------------------------------------------------------------------------
 * Database table, i.e. MySQL
 * -----------------------------------------------------------------------------
 * 
 * CREATE TABLE IF NOT EXISTS `emails` (
 *  `id` INT NOT NULL AUTO_INCREMENT,
 *  `address` VARCHAR(255) NOT NULL,
 *  `name` VARCHAR(255) NOT NULL,
 *  `subject` VARCHAR(255) NOT NULL,
 *  `content` TEXT NOT NULL,
 *  `status` TINYINT(1) NOT NULL,
 *  PRIMARY KEY (`id`)
 * ) ENGINE=InnoDB;
 */

/**
 * -----------------------------------------------------------------------------
 * Active Record model:
 * -----------------------------------------------------------------------------
 */

class Email extends CActiveRecord
{
    public function tableName()
    {
        return 'emails';
    }

    public function rules()
    {
        return array(
            array('address, name, subject, content', 'required'),
            array('address, name, subject', 'length', 'max' => 255),
            array('address', 'email'),
            array('address, name, subject', 'safe', 'on' => 'search'),
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}

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
        $mailer->db('status', 1);

        if ($mailer->send()) {
            $result = 'Test email has been saved in database successfully.';
        }
        else {
            $result = 'Test email saving in database error!<br>' . print_r($mailer->getMultiError(), true);
        }
        
        $this->render('index', array('result' => $result));
    }
}