<?php

namespace AndyTruong\Bundle\CommonBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AndyTruongCommonBundle extends Bundle
{

    public function ___getParent()
    {
        return 'FOSUserBundle';
    }

}
