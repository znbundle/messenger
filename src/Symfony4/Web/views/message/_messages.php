<?php

//
///** @var \ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface $authService */
//$authService = \ZnCore\Base\Libs\App\Helpers\ContainerHelper::getContainer()->get(\ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface::class);
//$myId = $authService->getIdentity()->getId();

$collection = $collection->reverse();

?>

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