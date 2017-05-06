<?php

namespace AppBundle\FeatureToggle\Toggles;

use AppBundle\FeatureToggle\ToggleInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CommentsFeature implements ToggleInterface
{
    private $authChecker;
    private $isEnabled;
    private $isRestricted;

    public function __construct(AuthorizationCheckerInterface $authChecker, bool $isEnabled, bool $isRestricted)
    {
        $this->authChecker = $authChecker;
        $this->isEnabled = $isEnabled;
        $this->isRestricted = $isRestricted;
    }

    public function isActive(): bool
    {
        return $this->isEnabled && (!$this->isRestricted || $this->authChecker->isGranted('ROLE_ADMIN'));
    }
}
