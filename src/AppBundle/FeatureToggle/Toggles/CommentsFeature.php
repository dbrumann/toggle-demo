<?php

namespace AppBundle\FeatureToggle\Toggles;

use AppBundle\FeatureToggle\ToggleInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CommentsFeature implements ToggleInterface
{
    use LoggerAwareTrait;

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
        $result = $this->isEnabled && (!$this->isRestricted || $this->authChecker->isGranted('ROLE_ADMIN'));
        try {
            $this->raiseException($result);
        } catch (\DomainException $e) {
            $this->logger->debug($e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }

        return $result;
    }

    private function raiseException(bool $result)
    {
        // Neat trick to get a stack trace to find out where the feature was called.
        throw new \DomainException(
            sprintf('CommentsToggle was called. Feature is %s.', $result ? 'active' : 'inactive')
        );
    }
}
