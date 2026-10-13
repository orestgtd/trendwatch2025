<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Casts;

use App\Domain\Security\ValueObjects\Description;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\Security\ValueObjects\Variations\{
    NoVariations,
    Variations,
    VariationsInterface,
};

// TODO: add test coverage for variations deserialization from persisted JSON

/** @implements CastsAttributes<VariationsInterface, string> */
final class VariationsCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): VariationsInterface
    {
        return $this->decode($value);
    }

    /**
     * @param VariationsInterface|string|null $value
     * @return array<string, string|null>
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        return $this->encode($key, $value);
    }

    private function decode(?string $value): VariationsInterface
    {
        if (is_null($value)) {
            return NoVariations::create();
        }

        $decoded = json_decode($value, true);

        return is_array($decoded)
            ? Variations::fromStrings($decoded)
            : NoVariations::create();
    }

    /** @return array<string, string|null> */
    private function encode(string $key, VariationsInterface $variations): array
    {
        return match (true) {
            ($variations instanceof NoVariations) => [$key => null],
            default => [$key => json_encode(
                array_map(
                    fn (Description $variation) => (string) $variation,
                    $variations->all())
                )],
        };
    }
}
