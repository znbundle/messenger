<?php

namespace ZnBundle\Messenger\Domain\Filters;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use ZnCore\Domain\Interfaces\Entity\ValidateEntityByMetadataInterface;
use Symfony\Component\Validator\Constraints as Assert;

class MessageFilter implements ValidateEntityByMetadataInterface
{

    private $chatId = null;
    
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('chatId', new Assert\NotBlank());
        $metadata->addPropertyConstraint('chatId', new Assert\Positive());
    }

    public function getChatId()
    {
        return $this->chatId;
    }

    public function setChatId($chatId): void
    {
        $this->chatId = $chatId;
    }
    
}