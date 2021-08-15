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

<?= \ZnLib\Web\Widgets\RequireJs\RequireJsWidget::require('/rjs/pages/messenger2/service/messageService.js') ?>
