<?php
namespace App\Services;

use App\Models\Company;

class FratureService
{
    public function getFeatureValue(Company $company, string $featureKey): ?string
    {
        return $company->latestSubscription?->subscriptionPlan
            ?->featureValues
            ?->firstWhere('feature.key', $featureKey)
            ?->value;
    }

    public function hasFeature(Company $company, string $featureKey, string|bool $expectedValue = true): bool
    {
        $value = $this->getFeatureValue($company, $featureKey);

        return is_bool($expectedValue)
            ? filter_var($value, FILTER_VALIDATE_BOOLEAN) === $expectedValue
            : strtolower($value) === strtolower($expectedValue);
    }

}
