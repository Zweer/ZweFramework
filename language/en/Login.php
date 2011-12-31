<?php

return array(
    Zwe_Controller_Action_Login::LOGIN_NOTHING => 'Login completed successfully',
    Zwe_Controller_Action_Login::LOGIN_CREDENTIALS => 'Wrong username or password',
    Zwe_Controller_Action_Login::LOGIN_ALLOW => 'The user is not activated yet',

    Zwe_Controller_Action_Login::REGISTRATION_OK => 'Registration done successfully. Please check email for the confirmation code',
    Zwe_Controller_Action_Login::REGISTRATION_EMAIL_SUBJECT => 'New Registration',
    Zwe_Controller_Action_Login::REGISTRATION_EMAIL_TEXT => "Welcome to the %site% website.\n\nThis is the recap of your credentials:\nUsername: %username%\nPassword: %password%\nEmail: %email%\n\nPlease consider to save this email to remember the credentials.\n\nTo activate your account you have to click on this link: %link%",

    Zwe_Controller_Action_Login::ACTIVATE_OK => 'Activation completed successfully',
    Zwe_Controller_Action_Login::ACTIVATE_KO => 'Activation completed with errors. Please be sure to copy the entire link in the address bar',

    Zwe_Controller_Action_Login::RECOVER_EMAIL_SUBJECT => 'Password Recovery',
    Zwe_Controller_Action_Login::RECOVER_EMAIL_TEXT => "This message has been sent to you after a password recory request.\nIf you haven't done any request so, please, don't consider this email.\n\nOtherwise please visit %link% to change your password"
);