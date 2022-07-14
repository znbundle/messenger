<?php

namespace ZnBundle\Messenger\Domain;

use ZnDomain\Domain\Interfaces\DomainInterface;

class Domain implements DomainInterface
{

    public function getName()
    {
        return 'messenger';
    }

}