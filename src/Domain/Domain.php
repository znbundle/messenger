<?php

namespace ZnBundle\Messenger\Domain;

use ZnCore\Base\Domain\Interfaces\DomainInterface;

class Domain implements DomainInterface
{

    public function getName()
    {
        return 'messenger';
    }

}