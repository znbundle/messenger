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

    document.addEventListener('DOMContentLoaded', function () { // Аналог $(document).ready(function(){

        var socket = new WebSocket("ws://127.0.0.1:8001?userId=1");
        socket.onopen = function () {
            console.log("Соединение установлено.");
        };
        socket.onclose = function (event) {
            if (event.wasClean) {
                console.log('Соединение закрыто чисто');
            } else {
                console.log('Обрыв соединения'); // например, "убит" процесс сервера
            }
            console.log('Код: ' + event.code + ' причина: ' + event.reason);
        };
        socket.onmessage = function (event) {
            var data = JSON.parse(event.data);
            var eventName = data.name;
            var eventData = data.data;
            //console.log("New message " + data.name);
            if (eventName == 'sendMessage') {
                updateMessageList(eventData.chatId);
                console.log("New message " + eventData.chatId);
            }
            console.log("Получены данные " + event.data);
        };
        socket.onerror = function (error) {
            console.log("Ошибка " + error.message);
        };


        function sendMessage() {
            var formElement = $('#messageForm');
            var action = formElement.attr('action');
            var textElement = formElement.find('input[name=message]');
            var chatIdElement = formElement.find('input[name=chatId]');
            var chatId = chatIdElement.val();
            var text = textElement.val();

            $.ajax({
                type: 'POST',
                url: action,
                data: {
                    'text': text,
                    'chatId': chatId,
                },
                success: function (msg) {
                    textElement.val('');
                }
            });
        }

        function updateMessageList() {
            var formElement = $('#messageForm');
            var chatIdElement = formElement.find('input[name=chatId]');
            var chatId = chatIdElement.val();
            $.ajax({
                type: 'GET',
                url: '/messenger/message-list/?chatId=' + chatId,
                success: function (msg) {
                    setMessageList(msg);
                    scrollBottomMessageList();
                }
            });
        }

        function setMessageList(msg) {
            var messageList = $('.direct-chat-messages');
            messageList.html(msg);
        }

        function scrollBottomMessageList() {
            var messageList = $('.direct-chat-messages');
            messageList.scrollTop(messageList.prop("scrollHeight"));
        }

        //scrollBottomMessageList();

        var formElement = $('#messageForm');
        formElement.submit(function () {
            sendMessage();
            return false;
        });

        $('.direct-chat-messages').show(function () {
            scrollBottomMessageList();
            return false;
        });

    });

</script>
