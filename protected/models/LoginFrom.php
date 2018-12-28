<?php
/**
 * Created by PhpStorm.
 * User: F1083
 * Date: 2018/12/26
 * Time: 17:21
 */
class LoginFrom extends CFormModel
{
    public $username;
    public $password;
    public $rememberMe = false;

    private $_identity;

    public function rules()
    {
        return array(
            array('username, password', 'required'),
            array('rememberMe', 'boolean'),
            array('password', 'authenticate'),
        );
    }

    public function authenticate($attribute,$params)
    {
        $this->_identity=new UserIdentity($this->username,$this->password);
        if(!$this->_identity->authenticate())
            $this->addError('password','错误的用户名或密码。');
    }
}