<?php

namespace AppBundle\FeatureToggle;

use Twig_Extension;
use Twig_SimpleFunction;

class TwigExtension extends Twig_Extension
{
    private $toggleRouter;

    public function __construct(ToggleRouter $toggleRouter)
    {
        $this->toggleRouter = $toggleRouter;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('is_active', [$this, 'isFeatureActive']),
        ];
    }

    public function isFeatureActive(string $featureName): bool
    {
        return $this->toggleRouter->isActive($featureName);
    }
}
