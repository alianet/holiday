<?php

declare(strict_types=1);

/*
 * This file is part of the alianet/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Resolver;

use Umulmrum\Holiday\Provider\HolidayProviderInterface;

use function preg_match;
use function strlen;

/**
 * IsoResolver resolves holiday providers that are given by their ISO-3166-1 ALPHA-2 or ISO-3166-2 code, see IsoProviderMap
 * for the list of supported countries and regions. If an ISO-3166-2 region (code with a dash) that is not implemented
 * is given, a fallback to the ISO-3166 country is performed. There can be different reasons for the fallback and you
 * should check if this behaviour is correct for your use case:
 * - The region's holidays do not differ from the country.
 * - The region's holidays are not yet implemented.
 * - The given code is invalid but the part before the dash is valid.
 */
final class IsoResolver implements ProviderResolverInterface
{
    private const MAX_CODE_LENGTH = 7;

    public function resolveHolidayProvider(string $identifier): ?HolidayProviderInterface
    {
        if (strlen($identifier) > self::MAX_CODE_LENGTH) {
            return null;
        }
        $providerClass = IsoProviderMap::MAP[$identifier] ?? null;
        if ($providerClass !== null) {
            return new $providerClass();
        }

        if (preg_match('#^([A-Z]{2})-#', $identifier, $matches)) {
            return $this->resolveHolidayProvider($matches[1]);
        }

        return null;
    }
}
