<?php
/**
 * @author Paweł Bizley Brzozowski
 * @version 1.5
 * @license BSD 2-Clause License
 * @see LICENSE file
 * 
 * This is PHPMailer subclass that allows to set options that are not 
 * modifiable directly in PHPMailer.
 */

Yii::import('ext.MultiMailer.*');
require_once 'PHPMailer-5.2.10/PHPMailerAutoload.php';

class ProxyPHPMailer extends PHPMailer
{
    
    /**
     * @var string selector for the validation pattern to use.
     * Options:
     * 'auto' Pick strictest one automatically (default);
     * 'pcre8' Use the squiloople.com pattern, requires PCRE > 8.0, PHP >= 5.3.2, 5.2.14;
     * 'pcre' Use old PCRE implementation;
     * 'php' Use PHP built-in FILTER_VALIDATE_EMAIL; same as pcre8 but does not allow 'dotless' domains;
     * 'html5' Use the pattern given by the HTML5 spec for 'email' type form input elements.
     * 'noregex' Don't use a regex: super fast, really dumb.
     */
    public static $patternSelect = 'auto';
    
    /**
     * @inheritdoc
     */
    public function __construct($exceptions = false)
    {
        parent::__construct($exceptions);
    }
    
    /**
     * @inheritdoc
     */
    public static function validateAddress($address, $patternselect = null)
    {
        if ($patternselect === null) {
            $patternselect = self::$patternSelect;
        }

        return parent::validateAddress($address, $patternselect);
    }
}