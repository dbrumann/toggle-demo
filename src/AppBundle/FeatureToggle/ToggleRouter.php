<?php

namespace AppBundle\FeatureToggle;

class ToggleRouter
{
    /**
     * @var ToggleInterface[]
     */
    private $featureToggles = [];

    public function registerToggle(string $featureName, ToggleInterface $toggle): void
    {
        $this->featureToggles[$featureName] = $toggle;
    }

    public function isActive(string $featureName): bool
    {
        if (array_key_exists($featureName, $this->featureToggles)) {
            return $this->featureToggles[$featureName]->isActive();
        }

        return false;
    }
}
