<?php

/**
 * @var $formView FormView|AbstractType[]
 * @var $dataProvider DataProvider
 * @var $baseUri string
 */

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use ZnCore\Base\Legacy\Yii\Helpers\Url;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Domain\Libs\DataProvider;
use ZnLib\Web\Widgets\Collection\CollectionWidget;

//dd($dataProvider->getCollection()->first()->getAuthor()->getLogo());

/** @var \ZnBundle\Messenger\Domain\Entities\MessageEntity[] $collection */
$collection = $dataProvider->getCollection();

$attributes = [
    [
        'label' => 'ID',
        'attributeName' => 'id',
    ],
    [
        'label' => 'Text',
        'attributeName' => 'text',
    ],
    [
        'label' => 'Author',
        'attributeName' => 'author.username',
    ],
//    [
//        'label' => I18Next::t('core', 'main.attribute.title'),
//        'attributeName' => 'title',
//        'sort' => true,
//        'formatter' => [
//            'class' => LinkFormatter::class,
//            'uri' => $baseUri . '/view',
//        ],
//    ],
//    [
//        'label' => 'Application',
//        'attributeName' => 'application.title',
//        'sort' => true,
//        /*'formatter' => [
//            'class' => LinkFormatter::class,
//            'uri' => $baseUri . '/view',
//        ],*/
//    ],
//    /*[
//        'label' => I18Next::t('core', 'main.attribute.name'),
//        'attributeName' => 'name',
//    ],*/
//    [
//        'formatter' => [
//            'class' => ActionFormatter::class,
//            'actions' => [
//                'update',
//                'delete',
//            ],
//            'baseUrl' => $baseUri,
//        ],
//    ],
];

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

            <?php foreach ($collection as $messageEntity): ?>

                <?php if ($messageEntity->getAuthorId() == $myId): ?>
                    <div class="direct-chat-msg">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-left"><?= $messageEntity->getAuthor()->getUsername() ?></span>
                            <span class="direct-chat-timestamp float-right"><?= $messageEntity->getCreatedAt()->format('Y-m-d H:i:s') ?></span>
                        </div>
                        <img class="direct-chat-img" src="<?= $messageEntity->getAuthor()->getLogo() ?>"
                             alt="message user image">
                        <div class="direct-chat-text">
                            <?= $messageEntity->getText() ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="direct-chat-msg right">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-right"><?= $messageEntity->getAuthor()->getUsername() ?></span>
                            <span class="direct-chat-timestamp float-left"><?= $messageEntity->getCreatedAt()->format('Y-m-d H:i:s') ?></span>
                        </div>
                        <img class="direct-chat-img" src="<?= $messageEntity->getAuthor()->getLogo() ?>"
                             alt="message user image">
                        <div class="direct-chat-text">
                            <?= $messageEntity->getText() ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>

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
        <form action="#" method="post">
            <div class="input-group">
                <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                <span class="input-group-append">
              <button type="button" class="btn btn-primary">Send</button>
            </span>
            </div>
        </form>
    </div>
</div>
