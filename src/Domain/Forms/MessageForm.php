<?php

namespace ZnBundle\Messenger\Domain\Forms;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Domain\Interfaces\Entity\ValidateEntityByMetadataInterface;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\BuildFormInterface;

class MessageForm implements ValidateEntityByMetadataInterface, BuildFormInterface
{

    private $chatId;
    private $text;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('chatId', new Assert\NotBlank);
        $metadata->addPropertyConstraint('text', new Assert\NotBlank);
    }

    public function buildForm(FormBuilderInterface $formBuilder)
    {
        $formBuilder
            ->add('chatId', TextType::class, [
                //'label' => I18Next::t('user', 'auth.attribute.login')
            ])
            ->add('text', HiddenType::class, [
                //'label' => I18Next::t('user', 'auth.attribute.login')
            ])
            ->add('save', SubmitType::class, [
                'label' => I18Next::t('user', 'auth.login_action')
            ]);
    }

    /**
     * @return mixed
     */
    public function getChatId()
    {
        return $this->chatId;
    }

    /**
     * @param mixed $chatId
     */
    public function setChatId($chatId): void
    {
        $this->chatId = $chatId;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

}