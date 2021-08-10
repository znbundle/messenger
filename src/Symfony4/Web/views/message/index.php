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

use ZnCore\Base\Legacy\Yii\Helpers\Url;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Domain\Libs\DataProvider;
use ZnLib\Web\Widgets\Collection\CollectionWidget;

/** @var \ZnBundle\Messenger\Domain\Entities\MessageEntity[] $collection */
$collection = $dataProvider->getCollection();

/** @var \ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface $authService */
$authService = \ZnCore\Base\Libs\App\Helpers\ContainerHelper::getContainer()->get(\ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface::class);
$myId = $authService->getIdentity()->getId();


?>

<div class="card card-primary card-outline direct-chat direct-chat-primary" style="width: 400px;">
    <div class="card-header">
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
    </div>
    <div class="card-body">
        <div class="direct-chat-messages">
            <?= $this->renderFile(__DIR__ . '/_messages.php', [
                    'collection' => $collection,
                    'myId' => $myId,
            ]) ?>
        </div>
        <!--/.direct-chat-messages-->
        <div class="direct-chat-contacts">
            <ul class="contacts-list">
                <li>
                    <a href="#">
                        <img class="contacts-list-img" src="https://adminlte.io/docs/3.0/assets/img/user1-128x128.jpg">
                        <div class="contacts-list-info">
                  <span class="contacts-list-name">
                    Count Dracula
                    <small class="contacts-list-date float-right">2/28/2015</small>
                  </span>
                            <span class="contacts-list-msg">How have you been? I was...</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img class="contacts-list-img" src="https://adminlte.io/docs/3.0/assets/img/user7-128x128.jpg">
                        <div class="contacts-list-info">
                  <span class="contacts-list-name">
                    Sarah Doe
                    <small class="contacts-list-date float-right">2/23/2015</small>
                  </span>
                            <span class="contacts-list-msg">I will be waiting for...</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img class="contacts-list-img" src="https://adminlte.io/docs/3.0/assets/img/user3-128x128.jpg">
                        <div class="contacts-list-info">
                  <span class="contacts-list-name">
                    Nadia Jolie
                    <small class="contacts-list-date float-right">2/20/2015</small>
                  </span>
                            <span class="contacts-list-msg">I'll call you back at...</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img class="contacts-list-img" src="https://adminlte.io/docs/3.0/assets/img/user5-128x128.jpg">
                        <div class="contacts-list-info">
                  <span class="contacts-list-name">
                    Nora S. Vans
                    <small class="contacts-list-date float-right">2/10/2015</small>
                  </span>
                            <span class="contacts-list-msg">Where is your new...</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img class="contacts-list-img" src="https://adminlte.io/docs/3.0/assets/img/user6-128x128.jpg">
                        <div class="contacts-list-info">
                  <span class="contacts-list-name">
                    John K.
                    <small class="contacts-list-date float-right">1/27/2015</small>
                  </span>
                            <span class="contacts-list-msg">Can I take a look at...</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img class="contacts-list-img" src="https://adminlte.io/docs/3.0/assets/img/user8-128x128.jpg">
                        <div class="contacts-list-info">
                  <span class="contacts-list-name">
                    Kenneth M.
                    <small class="contacts-list-date float-right">1/4/2015</small>
                  </span>
                            <span class="contacts-list-msg">Never mind I found...</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-footer">
        <form id="messageForm" action="/messenger/send-message" method="post" onsubmit="sendMessage(this); return false;">
            <div class="input-group">
                <input type="hidden" name="chatId" value="<?= $formView->vars['value']->getChatId() ?>">
                <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                <span class="input-group-append">
              <button type="button" class="btn btn-primary" name="submit">Send</button>
            </span>
            </div>
        </form>
    </div>
</div>

<script>

    var socket = new WebSocket("ws://127.0.0.1:8001?userId=1");
    socket.onopen = function() {
        console.log("Соединение установлено.");
    };
    socket.onclose = function(event) {
        if (event.wasClean) {
            console.log('Соединение закрыто чисто');
        } else {
            console.log('Обрыв соединения'); // например, "убит" процесс сервера
        }
        console.log('Код: ' + event.code + ' причина: ' + event.reason);
    };
    socket.onmessage = function(event) {
        var data = JSON.parse(event.data);
        var eventName = data.name;
        var eventData = data.data;
        //console.log("New message " + data.name);
        if(eventName == 'sendMessage') {
            updateMessageList(eventData.chatId);
            console.log("New message " + eventData.chatId);
        }
        console.log("Получены данные " + event.data);
    };
    socket.onerror = function(error) {
        console.log("Ошибка " + error.message);
    };


    function sendMessage() {
        // var formElement = $(form);
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
            success: function(msg) {
                textElement.val('');
                // updateMessageList();
                //alert('wow' + msg);
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
            success: function(msg) {
                var messageList = $('.direct-chat-messages');
                messageList.html(msg);
                messageList.scrollTop(messageList.prop("scrollHeight"));
            }
        });
    }

</script>