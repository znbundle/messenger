<?php

namespace PhpBundle\Messenger\Symfony\Widgets;

use PhpLab\Core\Domain\Libs\Query;
use PhpBundle\Messenger\Domain\Entities\MessageEntity;
use PhpBundle\Messenger\Domain\Interfaces\Services\MessageServiceInterface;
use PhpLab\Web\Base\BaseWidget;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuWidget extends BaseWidget implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    public function render(): string
    {

        /** @var MessageServiceInterface $messageService */
        $messageService = $this->container->get(MessageServiceInterface::class);

        $query = new Query;
        //$query->with(['chat']);
        //$query->where('is_seen', 1);
        $query->limit(5);

        /** @var MessageEntity[] $messageCollection */
        $messageCollection = $messageService->all($query);

        $itemsHtml = [];
        foreach ($messageCollection as $messageEntity) {
            $itemsHtml[] = $this->generateItem($messageEntity);
        }

        return implode('<div class="dropdown-divider"></div>', $itemsHtml);
    }

    public function generateItem(MessageEntity $messageEntity)
    {
        return '<a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
                <img src="' . $messageEntity->getAuthor()->getLogo() . '" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                <div class="media-body">
                    <p class="dropdown-item-title">
                        ' . $messageEntity->getAuthor()->getUsername() . '
                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                    </p>
                    <p class="text-sm">' . $messageEntity->getText() . '</p>
                    <p class="text-sm text-muted">
                        <i class="far fa-clock mr-1"></i>
                        4 Hours Ago
                    </p>
                </div>
            </div>
            <!-- Message End -->
        </a>';
    }

}