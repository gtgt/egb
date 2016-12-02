<?php

namespace Egb\UserBundle;

use Egb\UserBundle\DependencyInjection\UserExtension;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class UserBundle extends Bundle {
	public function getParent() {
		return 'FOSUserBundle';
	}
    public function getContainerExtension()
    {
        return new UserExtension();
		}
}
