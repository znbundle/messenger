<?php

use ZnBundle\User\Symfony4\Web\Controllers\AuthController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use ZnBundle\Messenger\Symfony4\Web\Controllers\MessageController;

return function (RoutingConfigurator $routes) {
    $routes
        ->add('web_messenger_chat_index', '/messenger')
        ->controller([MessageController::class, 'index'])
        ->methods(['GET']);
    $routes
        ->add('messenger/send-message', '/messenger/send-message')
        ->controller([MessageController::class, 'send'])
        ->methods(['POST']);
    $routes
        ->add('messenger/messageList', '/messenger/message-list')
        ->controller([MessageController::class, 'messageList'])
        ->methods(['GET']);

    /*$routes
        ->add('web_messenger_chat_list', '/chat-list')
        ->controller([MessageController::class, 'chatList'])
        ->methods(['GET', 'POST']);
    $routes
        ->add('web_messenger_message_message-list', '/message-list/{chatId}')
        ->controller([MessageController::class, 'messageList'])
        ->methods(['GET', 'POST']);
    $routes
        ->add('web_messenger_message_create', '/messenger/{chatId}')
        ->controller([MessageController::class, 'create'])
        ->methods(['GET', 'POST']);*/
    
};
