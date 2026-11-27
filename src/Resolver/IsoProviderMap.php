<?php

declare(strict_types=1);

/*
 * This file is part of the alianet/holiday package.
 *
 * (c) Stefan Kruppa
 * (c) Mariusz Kaczanowski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Resolver;

use Umulmrum\Holiday\Provider;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;

/**
 * See https://www.iso.org/obp/ui/#search for country and region data.
 */
final class IsoProviderMap
{
    /** @var array<string, class-string<HolidayProviderInterface>> */
    public const MAP = [
        // Crown dependencies: GG is scoped to the island of Guernsey, not the Bailiwick.
        'JE' => Provider\Jersey\Jersey::class,
        'GG' => Provider\Guernsey\Guernsey::class,
        'IM' => Provider\IsleOfMan\IsleOfMan::class,

        // Australia
        'AU' => Provider\Australia\Australia::class,
        'AU-ACT' => Provider\Australia\AustralianCapitalTerritory::class,
        'AU-NSW' => Provider\Australia\NewSouthWales::class,
        'AU-NT' => Provider\Australia\NorthernTerritory::class,
        'AU-QLD' => Provider\Australia\Queensland::class,
        'AU-SA' => Provider\Australia\SouthAustralia::class,
        'AU-TAS' => Provider\Australia\Tasmania::class,
        'AU-VIC' => Provider\Australia\Victoria::class,
        'AU-WA' => Provider\Australia\WesternAustralia::class,

        // Austria
        'AT' => Provider\Austria\Austria::class,
        'AT-1' => Provider\Austria\Burgenland::class,
        'AT-2' => Provider\Austria\Carinthia::class,
        'AT-3' => Provider\Austria\LowerAustria::class,
        'AT-4' => Provider\Austria\UpperAustria::class,
        'AT-5' => Provider\Austria\Salzburg::class,
        'AT-6' => Provider\Austria\Styria::class,
        'AT-7' => Provider\Austria\Tyrol::class,
        'AT-8' => Provider\Austria\Vorarlberg::class,
        'AT-9' => Provider\Austria\Vienna::class,

        // Belarus
        'BY' => Provider\Belarus\Belarus::class,

        // Belgium
        'BE' => Provider\Belgium\Belgium::class,

        // Brazil
        'BR' => Provider\Brazil\Brazil::class,

        // Bulgaria
        'BG' => Provider\Bulgaria\Bulgaria::class,

        // Canada
        'CA' => Provider\Canada\Canada::class,
        'CA-AB' => Provider\Canada\Alberta::class,
        'CA-BC' => Provider\Canada\BritishColumbia::class,
        'CA-MB' => Provider\Canada\Manitoba::class,
        'CA-NB' => Provider\Canada\NewBrunswick::class,
        'CA-NL' => Provider\Canada\NewFoundlandAndLabrador::class,
        'CA-NT' => Provider\Canada\NorthwestTerritories::class,
        'CA-NS' => Provider\Canada\NovaScotia::class,
        'CA-NU' => Provider\Canada\Nunavut::class,
        'CA-ON' => Provider\Canada\Ontario::class,
        'CA-PE' => Provider\Canada\PrinceEdwardIsland::class,
        'CA-QC' => Provider\Canada\Quebec::class,
        'CA-SK' => Provider\Canada\Saskatchewan::class,
        'CA-YT' => Provider\Canada\Yukon::class,

        // Czech Republic
        'CZ' => Provider\CzechRepublic\CzechRepublic::class,

        // Denmark
        'DK' => Provider\Denmark\Denmark::class,

        // Estonia
        'EE' => Provider\Estonia\Estonia::class,

        // Finland
        'FI' => Provider\Finland\Finland::class,

        // France
        'FR' => Provider\France\France::class,
        'FR-57' => Provider\France\Moselle::class,
        'FR-67' => Provider\France\BasRhin::class,
        'FR-68' => Provider\France\HautRhin::class,
        'FR-BL' => Provider\France\SaintBarthelemy::class,
        'FR-GF' => Provider\France\FrenchGuiana::class,
        'FR-GUA' => Provider\France\Guadeloupe::class,
        'FR-LRE' => Provider\France\Reunion::class,
        'FR-MF' => Provider\France\SaintMartin::class,
        'FR-MQ' => Provider\France\Martinique::class,
        'FR-YT' => Provider\France\Mayotte::class,

        // Germany
        'DE' => Provider\Germany\Germany::class,
        'DE-BB' => Provider\Germany\Brandenburg::class,
        'DE-BE' => Provider\Germany\Berlin::class,
        'DE-BW' => Provider\Germany\BadenWuerttemberg::class,
        'DE-BY' => Provider\Germany\Bavaria::class,
        'DE-HB' => Provider\Germany\Bremen::class,
        'DE-HE' => Provider\Germany\Hesse::class,
        'DE-HH' => Provider\Germany\Hamburg::class,
        'DE-MV' => Provider\Germany\MecklenburgVorpommern::class,
        'DE-NI' => Provider\Germany\LowerSaxony::class,
        'DE-NW' => Provider\Germany\NorthRhineWestphalia::class,
        'DE-RP' => Provider\Germany\RhinelandPalatinate::class,
        'DE-SH' => Provider\Germany\SchleswigHolstein::class,
        'DE-SL' => Provider\Germany\Saarland::class,
        'DE-SN' => Provider\Germany\Saxony::class,
        'DE-ST' => Provider\Germany\SaxonyAnhalt::class,
        'DE-TH' => Provider\Germany\Thuringia::class,

        // Greenland
        'GL' => Provider\Greenland\Greenland::class,

        // Iceland
        'IS' => Provider\Iceland\Iceland::class,

        // Ireland
        'IE' => Provider\Ireland\Ireland::class,

        // Italy
        'IT' => Provider\Italy\Italy::class,
        'IT-32' => Provider\Italy\SouthTyrol::class,

        // Latvia
        'LV' => Provider\Latvia\Latvia::class,

        // Liechtenstein
        'FL' => Provider\Liechtenstein\Liechtenstein::class,

        // Lithuania
        'LT' => Provider\Lithuania\Lithuania::class,

        // Luxembourg
        'LU' => Provider\Luxembourg\Luxembourg::class,

        // Mexico
        'MX' => Provider\Mexico\Mexico::class,

        // Netherlands
        'NL' => Provider\Netherlands\Netherlands::class,

        // Norway
        'NO' => Provider\Norway\Norway::class,

        // Poland
        'PL' => Provider\Poland\Poland::class,

        // Portugal
        'PT' => Provider\Portugal\Portugal::class,
        'PT-20' => Provider\Portugal\Azores::class,
        'PT-30' => Provider\Portugal\Madeira::class,

        // Russia
        'RU' => Provider\Russia\Russia::class,

        // Spain
        'ES' => Provider\Spain\Spain::class,
        'ES-AN' => Provider\Spain\Andalusia::class,
        'ES-AR' => Provider\Spain\Aragon::class,
        'ES-AS' => Provider\Spain\Asturias::class,
        'ES-CB' => Provider\Spain\Cantabria::class,
        'ES-CE' => Provider\Spain\Ceuta::class,
        'ES-CL' => Provider\Spain\CastileAndLeon::class,
        'ES-CM' => Provider\Spain\CastillaLaMancha::class,
        'ES-CN' => Provider\Spain\CanaryIslands::class,
        'ES-CT' => Provider\Spain\Catalonia::class,
        'ES-EX' => Provider\Spain\Extremadura::class,
        'ES-GA' => Provider\Spain\Galicia::class,
        'ES-IB' => Provider\Spain\BalearicIslands::class,
        'ES-MC' => Provider\Spain\Murcia::class,
        'ES-MD' => Provider\Spain\Madrid::class,
        'ES-ML' => Provider\Spain\Melilla::class,
        'ES-NC' => Provider\Spain\Navarre::class,
        'ES-PV' => Provider\Spain\BasqueCountry::class,
        'ES-RI' => Provider\Spain\LaRioja::class,
        'ES-VC' => Provider\Spain\ValencianCommunity::class,

        // Sweden
        'SE' => Provider\Sweden\Sweden::class,

        // Switzerland
        'CH' => Provider\Switzerland\Switzerland::class,
        'CH-AG' => Provider\Switzerland\Aargau::class,
        'CH-AI' => Provider\Switzerland\AppenzellInnerrhoden::class,
        'CH-AR' => Provider\Switzerland\AppenzellAusserrhoden::class,
        'CH-BE' => Provider\Switzerland\Bern::class,
        'CH-BL' => Provider\Switzerland\BaselLandschaft::class,
        'CH-BS' => Provider\Switzerland\BaselStadt::class,
        'CH-FR' => Provider\Switzerland\Fribourg::class,
        'CH-GE' => Provider\Switzerland\Geneva::class,
        'CH-GL' => Provider\Switzerland\Glarus::class,
        'CH-GR' => Provider\Switzerland\Grisons::class,
        'CH-JU' => Provider\Switzerland\Jura::class,
        'CH-LU' => Provider\Switzerland\Lucerne::class,
        'CH-NE' => Provider\Switzerland\Neuchatel::class,
        'CH-NW' => Provider\Switzerland\Nidwalden::class,
        'CH-OW' => Provider\Switzerland\Obwalden::class,
        'CH-SG' => Provider\Switzerland\StGallen::class,
        'CH-SH' => Provider\Switzerland\Schaffhausen::class,
        'CH-SO' => Provider\Switzerland\Solothurn::class,
        'CH-SZ' => Provider\Switzerland\Schwyz::class,
        'CH-TG' => Provider\Switzerland\Thurgau::class,
        'CH-TI' => Provider\Switzerland\Ticino::class,
        'CH-UR' => Provider\Switzerland\Uri::class,
        'CH-VD' => Provider\Switzerland\Vaud::class,
        'CH-VS' => Provider\Switzerland\Valais::class,
        'CH-ZG' => Provider\Switzerland\Zug::class,
        'CH-ZH' => Provider\Switzerland\Zuerich::class,

        // Turkey
        'TR' => Provider\Turkey\Turkey::class,

        // Ukraine
        'UA' => Provider\Ukraine\Ukraine::class,

        // United Kingdom
        'GB' => Provider\UnitedKingdom\UnitedKingdom::class,
        'GB-ENG' => Provider\UnitedKingdom\England::class,
        'GB-WLS' => Provider\UnitedKingdom\Wales::class,
        'GB-NIR' => Provider\UnitedKingdom\NorthernIreland::class,
        'GB-SCT' => Provider\UnitedKingdom\Scotland::class,

        // USA
        'US' => Provider\Usa\Usa::class,
        'US-AK' => Provider\Usa\Alaska::class,
        'US-AL' => Provider\Usa\Alabama::class,
        'US-AR' => Provider\Usa\Arkansas::class,
        'US-AZ' => Provider\Usa\Arizona::class,
        'US-CA' => Provider\Usa\California::class,
        'US-CO' => Provider\Usa\Colorado::class,
        'US-CT' => Provider\Usa\Connecticut::class,
        'US-DC' => Provider\Usa\DistrictOfColumbia::class,
        'US-DE' => Provider\Usa\Delaware::class,
        'US-FL' => Provider\Usa\Florida::class,
        'US-GA' => Provider\Usa\Georgia::class,
        'US-HI' => Provider\Usa\Hawaii::class,
        'US-IA' => Provider\Usa\Iowa::class,
        'US-ID' => Provider\Usa\Idaho::class,
        'US-IL' => Provider\Usa\Illinois::class,
        'US-IN' => Provider\Usa\Indiana::class,
        'US-KS' => Provider\Usa\Kansas::class,
        'US-KY' => Provider\Usa\Kentucky::class,
        'US-LA' => Provider\Usa\Louisiana::class,
        'US-MA' => Provider\Usa\Massachusetts::class,
        'US-MD' => Provider\Usa\Maryland::class,
        'US-ME' => Provider\Usa\Maine::class,
        'US-MI' => Provider\Usa\Michigan::class,
        'US-MN' => Provider\Usa\Minnesota::class,
        'US-MO' => Provider\Usa\Missouri::class,
        'US-MS' => Provider\Usa\Mississippi::class,
        'US-MT' => Provider\Usa\Montana::class,
        'US-NC' => Provider\Usa\NorthCarolina::class,
        'US-ND' => Provider\Usa\NorthDakota::class,
        'US-NE' => Provider\Usa\Nebraska::class,
        'US-NH' => Provider\Usa\NewHampshire::class,
        'US-NJ' => Provider\Usa\NewJersey::class,
        'US-NM' => Provider\Usa\NewMexico::class,
        'US-NV' => Provider\Usa\Nevada::class,
        'US-NY' => Provider\Usa\NewYork::class,
        'US-OH' => Provider\Usa\Ohio::class,
        'US-OK' => Provider\Usa\Oklahoma::class,
        'US-OR' => Provider\Usa\Oregon::class,
        'US-PA' => Provider\Usa\Pennsylvania::class,
        'US-RI' => Provider\Usa\RhodeIsland::class,
        'US-SC' => Provider\Usa\SouthCarolina::class,
        'US-SD' => Provider\Usa\SouthDakota::class,
        'US-TN' => Provider\Usa\Tennessee::class,
        'US-TX' => Provider\Usa\Texas::class,
        'US-UT' => Provider\Usa\Utah::class,
        'US-VA' => Provider\Usa\Virginia::class,
        'US-VT' => Provider\Usa\Vermont::class,
        'US-WA' => Provider\Usa\Washington::class,
        'US-WI' => Provider\Usa\Wisconsin::class,
        'US-WV' => Provider\Usa\WestVirginia::class,
        'US-WY' => Provider\Usa\Wyoming::class,
    ];
}
