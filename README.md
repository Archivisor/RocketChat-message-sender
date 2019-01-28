# RocketChat simple messenger

This repository contains the source code for sendRocketMessage

# Prepare

You need to have technical account in RocketChat from whom you will send messages.

Once you will have credentials you can set them as settings in class.

    private $rocket_creds = array(
        'username' => '', // Please fill this parameter before use
        'password' => ''  // Please fill this parameter before use
    );

# Usage

If you correctly did the preparation you can start using this class 

    $sender = new sendRocketMessage;
    $sender->send('someoneWhoWillRecieveAMessage', 'Hello World!');

Usually you have something like @someone in RocketChat, '@' sign will be added automatically and you need to enter just 'someone'. 
