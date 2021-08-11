<?php

//use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\FormView;
//use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
//use ZnCore\Base\Libs\App\Helpers\ContainerHelper;
//use ZnLib\Web\Symfony4\MicroApp\Libs\FormRender;
//
///** @var CsrfTokenManagerInterface $tokenManager */
//$tokenManager = ContainerHelper::getContainer()->get(CsrfTokenManagerInterface::class);
//$formRender = new FormRender($formView, $tokenManager);
//$formRender->addFormOption('autocomplete', 'off');

/**
 * @var $formView FormView|AbstractType[]
 * @var $dataProvider DataProvider
 * @var $baseUri string
 * @var $this \ZnLib\Web\View\View
 */

use ZnCore\Domain\Libs\DataProvider;

/** @var \ZnBundle\Messenger\Domain\Entities\MessageEntity[] $collection */
$collection = $dataProvider->getCollection();

/** @var \ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface $authService */
$authService = \ZnCore\Base\Libs\App\Helpers\ContainerHelper::getContainer()->get(\ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface::class);
$myId = $authService->getIdentity()->getId();

$this->registerJs('

');

?>

<div class="card card-primary card-outline11 direct-chat direct-chat-primary" style="width: auto;">
    <!--<div class="card-header">
        <h3 class="card-title">Direct Chat</h3>
        <div class="card-tools">
            <span data-toggle="tooltip" title="3 New Messages" class="badge badge-light">3</span>
            <button type="button" class="btn btn-tool" data-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Contacts"
                    data-widget="chat-pane-toggle">
                <i class="fas fa-comments"></i>
            </button>
            <button type="button" class="btn btn-tool" data-widget="remove"><i class="fas fa-times"></i>
            </button>
        </div>
    </div>-->
    <div class="card-body">
        <div class="direct-chat-messages" style="height: 318px;">
            <?= $this->renderFile(__DIR__ . '/_messages.php', [
                'collection' => $collection,
                'myId' => $myId,
            ]) ?>
        </div>
        <!--/.direct-chat-messages-->
        <div class="direct-chat-contacts">
            <?= $this->renderFile(__DIR__ . '/_contacts.php') ?>
        </div>
    </div>
    <div class="card-footer">
        <?= $this->renderFile(__DIR__ . '/_form.php', [
            'formView' => $formView,
        ]) ?>
    </div>
</div>

<script>

    var container = {};

    document.addEventListener('DOMContentLoaded', function () {

        container.SocketService = {
            handlers: {},
            connect: function (url) {
                var socket = new WebSocket(url);
                socket.onopen = function () {
                    console.log("Соединение установлено.");
                };
                socket.onclose = function (event) {
                    if (event.wasClean) {
                        console.log('Соединение закрыто чисто');
                    } else {
                        console.log('Обрыв соединения');
                        // например, "убит" процесс сервера
                    }
                    console.log('Код: ' + event.code + ' причина: ' + event.reason);
                };
                socket.onmessage = function (event) {
                    var data = JSON.parse(event.data);
                    var eventName = data.name;
                    var eventData = data.data;
                    container.SocketService.trigger(data.name, data.data);
                    console.log("Получены данные " + event.data);
                };
                socket.onerror = function (error) {
                    console.log("Ошибка " + error.message);
                };
            },
            addHandler: function (eventName, handler) {
                if (this.handlers[eventName] == undefined) {
                    this.handlers[eventName] = [];
                }
                this.handlers[eventName].push(handler);
            },
            trigger: function (eventName, params) {
                //console.log(eventName, params);
                var handlers = this.handlers[eventName];
                for (var i in handlers) {
                    var handler = handlers[i];
                    handler.run(params);
                }
            },
        };

    });

    document.addEventListener('DOMContentLoaded', function () {

        var chatElement = {
            getFormElement: function () {
                return $('#messageForm');
            },
            getMessagesElement: function () {
                return $('.direct-chat-messages');
            },
            getUrl: function () {
                var action = chatElement.getFormElement().attr('action');
                return action;
            },
            clearMessageText: function () {
                var textElement = chatElement.getFormElement().find('input[name=message]');
                textElement.val('');
            },
            getMessageText: function () {
                var textElement = chatElement.getFormElement().find('input[name=message]');
                var text = textElement.val();
                return text;
            },
            getChatId: function () {
                var chatIdElement = chatElement.getFormElement().find('input[name=chatId]');
                var chatId = chatIdElement.val();
                return chatId;
            },
            setMessageList: function (msg) {
                chatElement.getMessagesElement().html(msg);
            },
            scrollBottomMessageList: function () {
                var messageList = chatElement.getMessagesElement();
                messageList.scrollTop(messageList.prop("scrollHeight"));
            },
        };

        var ApiDriver = {
            request: function (method, uri, data) {
                var promiseCallback = function (resolve, reject) {
                    $.ajax({
                        type: method,
                        url: uri,
                        data: data,
                        success: function (response) {
                            resolve(response);
                        },
                        error: function (error) {
                            reject(response);
                        }
                    });
                };
                return new Promise(promiseCallback);
            }
        };

        var ChatApiDriver = {
            sendMessage: function (chatId, messageText) {
                return ApiDriver.request('POST', '/messenger/send-message', {
                    'chatId': chatId,
                    'text': messageText,
                });
            },
            updateMessageList: function (chatId) {
                var uri = '/messenger/message-list/?chatId=' + chatId;
                return ApiDriver.request('GET', uri);
            },
        };

        container.chatService = {
            sendMessage: function (chatId, messageText) {
                ChatApiDriver
                    .sendMessage(chatId, messageText)
                    .then(function (msg) {
                        chatElement.clearMessageText();
                    });
            },
            updateMessageList: function () {
                ChatApiDriver
                    .updateMessageList(chatElement.getChatId())
                    .then(function (messageListHtml) {
                        chatElement.setMessageList(messageListHtml);
                        chatElement.scrollBottomMessageList();
                    });
            },
        };

        chatElement.getFormElement().submit(function () {
            var messageText = chatElement.getMessageText();
            var chatId = chatElement.getChatId();
            container.chatService.sendMessage(chatId, messageText);
            return false;
        });
        chatElement.getMessagesElement().show(function () {
            chatElement.scrollBottomMessageList();
            return false;
        });

    });

    document.addEventListener('DOMContentLoaded', function () {

        var messageHandler = {
            run: function (params) {
                container.chatService.updateMessageList(params.chatId);
            }
        };

        container.SocketService.addHandler('sendMessage', messageHandler);
        container.SocketService.connect("ws://127.0.0.1:8001?userId=1");

    });

</script>
