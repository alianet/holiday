<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 * See https://www.iso.org/obp/ui/#search for country and region data
 */
return [
    // Australia
    'AU' => \Umulmrum\Holiday\Provider\Australia\Australia::class,
    'AU-ACT' => \Umulmrum\Holiday\Provider\Australia\AustralianCapitalTerritory::class,
    'AU-NSW' => \Umulmrum\Holiday\Provider\Australia\NewSouthWales::class,
    'AU-NT' => \Umulmrum\Holiday\Provider\Australia\NorthernTerritory::class,
    'AU-QLD' => \Umulmrum\Holiday\Provider\Australia\Queensland::class,
    'AU-SA' => \Umulmrum\Holiday\Provider\Australia\SouthAustralia::class,
    'AU-TAS' => \Umulmrum\Holiday\Provider\Australia\Tasmania::class,
    'AU-VIC' => \Umulmrum\Holiday\Provider\Australia\Victoria::class,
    'AU-WA' => \Umulmrum\Holiday\Provider\Australia\WesternAustralia::class,

    // Austria
    'AT' => \Umulmrum\Holiday\Provider\Austria\Austria::class,
    'AT-1' => \Umulmrum\Holiday\Provider\Austria\Burgenland::class,
    'AT-2' => \Umulmrum\Holiday\Provider\Austria\Carinthia::class,
    'AT-3' => \Umulmrum\Holiday\Provider\Austria\LowerAustria::class,
    'AT-4' => \Umulmrum\Holiday\Provider\Austria\UpperAustria::class,
    'AT-5' => \Umulmrum\Holiday\Provider\Austria\Salzburg::class,
    'AT-6' => \Umulmrum\Holiday\Provider\Austria\Styria::class,
    'AT-7' => \Umulmrum\Holiday\Provider\Austria\Tyrol::class,
    'AT-8' => \Umulmrum\Holiday\Provider\Austria\Vorarlberg::class,
    'AT-9' => \Umulmrum\Holiday\Provider\Austria\Vienna::class,

    // Belarus
    'BY' => \Umulmrum\Holiday\Provider\Belarus\Belarus::class,

    // Belgium
    'BE' => \Umulmrum\Holiday\Provider\Belgium\Belgium::class,

    // Brazil
    'BR' => \Umulmrum\Holiday\Provider\Brazil\Brazil::class,

    // Bulgaria
    'BG' => \Umulmrum\Holiday\Provider\Bulgaria\Bulgaria::class,

    // Canada
    'CA' => \Umulmrum\Holiday\Provider\Canada\Canada::class,
    'CA-AB' => \Umulmrum\Holiday\Provider\Canada\Alberta::class,
    'CA-BC' => \Umulmrum\Holiday\Provider\Canada\BritishColumbia::class,
    'CA-MB' => \Umulmrum\Holiday\Provider\Canada\Manitoba::class,
    'CA-NB' => \Umulmrum\Holiday\Provider\Canada\NewBrunswick::class,
    'CA-NL' => \Umulmrum\Holiday\Provider\Canada\NewFoundlandAndLabrador::class,
    'CA-NT' => \Umulmrum\Holiday\Provider\Canada\NorthwestTerritories::class,
    'CA-NS' => \Umulmrum\Holiday\Provider\Canada\NovaScotia::class,
    'CA-NU' => \Umulmrum\Holiday\Provider\Canada\Nunavut::class,
    'CA-ON' => \Umulmrum\Holiday\Provider\Canada\Ontario::class,
    'CA-PE' => \Umulmrum\Holiday\Provider\Canada\PrinceEdwardIsland::class,
    'CA-QC' => \Umulmrum\Holiday\Provider\Canada\Quebec::class,
    'CA-SK' => \Umulmrum\Holiday\Provider\Canada\Saskatchewan::class,
    'CA-YT' => \Umulmrum\Holiday\Provider\Canada\Yukon::class,

    // Czech Republic
    'CZ' => \Umulmrum\Holiday\Provider\CzechRepublic\CzechRepublic::class,

    // Denmark
    'DK' => \Umulmrum\Holiday\Provider\Denmark\Denmark::class,

    // Estonia
    'EE' => \Umulmrum\Holiday\Provider\Estonia\Estonia::class,

    // Finland
    'FI' => \Umulmrum\Holiday\Provider\Finland\Finland::class,

    // France
    'FR' => \Umulmrum\Holiday\Provider\France\France::class,
    'FR-57' => \Umulmrum\Holiday\Provider\France\Moselle::class,
    'FR-67' => \Umulmrum\Holiday\Provider\France\BasRhin::class,
    'FR-68' => \Umulmrum\Holiday\Provider\France\HautRhin::class,
    'FR-GF' => \Umulmrum\Holiday\Provider\France\FrenchGuiana::class,
    'FR-GUA' => \Umulmrum\Holiday\Provider\France\Guadeloupe::class,
    'FR-LRE' => \Umulmrum\Holiday\Provider\France\Reunion::class,
    'FR-MQ' => \Umulmrum\Holiday\Provider\France\Martinique::class,

    // Germany
    'DE' => \Umulmrum\Holiday\Provider\Germany\Germany::class,
    'DE-BB' => \Umulmrum\Holiday\Provider\Germany\Brandenburg::class,
    'DE-BE' => \Umulmrum\Holiday\Provider\Germany\Berlin::class,
    'DE-BW' => \Umulmrum\Holiday\Provider\Germany\BadenWuerttemberg::class,
    'DE-BY' => \Umulmrum\Holiday\Provider\Germany\Bavaria::class,
    'DE-HB' => \Umulmrum\Holiday\Provider\Germany\Bremen::class,
    'DE-HE' => \Umulmrum\Holiday\Provider\Germany\Hesse::class,
    'DE-HH' => \Umulmrum\Holiday\Provider\Germany\Hamburg::class,
    'DE-MV' => \Umulmrum\Holiday\Provider\Germany\MecklenburgVorpommern::class,
    'DE-NI' => \Umulmrum\Holiday\Provider\Germany\LowerSaxony::class,
    'DE-NW' => \Umulmrum\Holiday\Provider\Germany\NorthRhineWestphalia::class,
    'DE-RP' => \Umulmrum\Holiday\Provider\Germany\RhinelandPalatinate::class,
    'DE-SH' => \Umulmrum\Holiday\Provider\Germany\SchleswigHolstein::class,
    'DE-SL' => \Umulmrum\Holiday\Provider\Germany\Saarland::class,
    'DE-SN' => \Umulmrum\Holiday\Provider\Germany\Saxony::class,
    'DE-ST' => \Umulmrum\Holiday\Provider\Germany\SaxonyAnhalt::class,
    'DE-TH' => \Umulmrum\Holiday\Provider\Germany\Thuringia::class,

    // Greenland
    'GL' => \Umulmrum\Holiday\Provider\Greenland\Greenland::class,

    // Iceland
    'IS' => \Umulmrum\Holiday\Provider\Iceland\Iceland::class,

    // Ireland
    'IE' => \Umulmrum\Holiday\Provider\Ireland\Ireland::class,

    // Italy
    'IT' => \Umulmrum\Holiday\Provider\Italy\Italy::class,
    'IT-32' => \Umulmrum\Holiday\Provider\Italy\SouthTyrol::class,

    // Latvia
    'LV' => \Umulmrum\Holiday\Provider\Latvia\Latvia::class,

    // Liechtenstein
    'FL' => \Umulmrum\Holiday\Provider\Liechtenstein\Liechtenstein::class,

    // Lithuania
    'LT' => \Umulmrum\Holiday\Provider\Lithuania\Lithuania::class,

    // Luxembourg
    'LU' => \Umulmrum\Holiday\Provider\Luxembourg\Luxembourg::class,

    // Mexico
    'MX' => \Umulmrum\Holiday\Provider\Mexico\Mexico::class,

    // Netherlands
    'NL' => \Umulmrum\Holiday\Provider\Netherlands\Netherlands::class,

    // Norway
    'NO' => \Umulmrum\Holiday\Provider\Norway\Norway::class,

    // Poland
    'PL' => \Umulmrum\Holiday\Provider\Poland\Poland::class,

    // Portugal
    'PT' => \Umulmrum\Holiday\Provider\Portugal\Portugal::class,
    'PT-20' => \Umulmrum\Holiday\Provider\Portugal\Azores::class,
    'PT-30' => \Umulmrum\Holiday\Provider\Portugal\Madeira::class,

    // Russia
    'RU' => \Umulmrum\Holiday\Provider\Russia\Russia::class,

    // Spain
    'ES' => \Umulmrum\Holiday\Provider\Spain\Spain::class,

    // Sweden
    'SE' => \Umulmrum\Holiday\Provider\Sweden\Sweden::class,

    // Switzerland
    'CH' => \Umulmrum\Holiday\Provider\Switzerland\Switzerland::class,
    'CH-AG' => \Umulmrum\Holiday\Provider\Switzerland\Aargau::class,
    'CH-AI' => \Umulmrum\Holiday\Provider\Switzerland\AppenzellInnerrhoden::class,
    'CH-AR' => \Umulmrum\Holiday\Provider\Switzerland\AppenzellAusserrhoden::class,
    'CH-BE' => \Umulmrum\Holiday\Provider\Switzerland\Bern::class,
    'CH-BL' => \Umulmrum\Holiday\Provider\Switzerland\BaselLandschaft::class,
    'CH-BS' => \Umulmrum\Holiday\Provider\Switzerland\BaselStadt::class,
    'CH-FR' => \Umulmrum\Holiday\Provider\Switzerland\Fribourg::class,
    'CH-GE' => \Umulmrum\Holiday\Provider\Switzerland\Geneva::class,
    'CH-GL' => \Umulmrum\Holiday\Provider\Switzerland\Glarus::class,
    'CH-GR' => \Umulmrum\Holiday\Provider\Switzerland\Grisons::class,
    'CH-JU' => \Umulmrum\Holiday\Provider\Switzerland\Jura::class,
    'CH-LU' => \Umulmrum\Holiday\Provider\Switzerland\Lucerne::class,
    'CH-NE' => \Umulmrum\Holiday\Provider\Switzerland\Neuchatel::class,
    'CH-NW' => \Umulmrum\Holiday\Provider\Switzerland\Nidwalden::class,
    'CH-OW' => \Umulmrum\Holiday\Provider\Switzerland\Obwalden::class,
    'CH-SG' => \Umulmrum\Holiday\Provider\Switzerland\StGallen::class,
    'CH-SH' => \Umulmrum\Holiday\Provider\Switzerland\Schaffhausen::class,
    'CH-SO' => \Umulmrum\Holiday\Provider\Switzerland\Solothurn::class,
    'CH-SZ' => \Umulmrum\Holiday\Provider\Switzerland\Schwyz::class,
    'CH-TG' => \Umulmrum\Holiday\Provider\Switzerland\Thurgau::class,
    'CH-TI' => \Umulmrum\Holiday\Provider\Switzerland\Ticino::class,
    'CH-UR' => \Umulmrum\Holiday\Provider\Switzerland\Uri::class,
    'CH-VD' => \Umulmrum\Holiday\Provider\Switzerland\Vaud::class,
    'CH-VS' => \Umulmrum\Holiday\Provider\Switzerland\Valais::class,
    'CH-ZG' => \Umulmrum\Holiday\Provider\Switzerland\Zug::class,
    'CH-ZH' => \Umulmrum\Holiday\Provider\Switzerland\Zuerich::class,

    // Turkey
    'TR' => \Umulmrum\Holiday\Provider\Turkey\Turkey::class,

    // Ukraine
    'UA' => \Umulmrum\Holiday\Provider\Ukraine\Ukraine::class,

    // United Kingdom
    'GB' => \Umulmrum\Holiday\Provider\UnitedKingdom\UnitedKingdom::class,
    'GB-NIR' => \Umulmrum\Holiday\Provider\UnitedKingdom\NorthernIreland::class,
    'GB-SCT' => \Umulmrum\Holiday\Provider\UnitedKingdom\Scotland::class,

    // USA
    'US' => \Umulmrum\Holiday\Provider\Usa\Usa::class,
    'US-AK' => \Umulmrum\Holiday\Provider\Usa\Alaska::class,
    'US-AL' => \Umulmrum\Holiday\Provider\Usa\Alabama::class,
    'US-AR' => \Umulmrum\Holiday\Provider\Usa\Arkansas::class,
    'US-AZ' => \Umulmrum\Holiday\Provider\Usa\Arizona::class,
    'US-CA' => \Umulmrum\Holiday\Provider\Usa\California::class,
    'US-CO' => \Umulmrum\Holiday\Provider\Usa\Colorado::class,
    'US-CT' => \Umulmrum\Holiday\Provider\Usa\Connecticut::class,
    'US-DC' => \Umulmrum\Holiday\Provider\Usa\DistrictOfColumbia::class,
    'US-DE' => \Umulmrum\Holiday\Provider\Usa\Delaware::class,
    'US-FL' => \Umulmrum\Holiday\Provider\Usa\Florida::class,
    'US-GA' => \Umulmrum\Holiday\Provider\Usa\Georgia::class,
    'US-HI' => \Umulmrum\Holiday\Provider\Usa\Hawaii::class,
    'US-IA' => \Umulmrum\Holiday\Provider\Usa\Iowa::class,
    'US-ID' => \Umulmrum\Holiday\Provider\Usa\Idaho::class,
    'US-IL' => \Umulmrum\Holiday\Provider\Usa\Illinois::class,
    'US-IN' => \Umulmrum\Holiday\Provider\Usa\Indiana::class,
    'US-KS' => \Umulmrum\Holiday\Provider\Usa\Kansas::class,
    'US-KY' => \Umulmrum\Holiday\Provider\Usa\Kentucky::class,
    'US-LA' => \Umulmrum\Holiday\Provider\Usa\Louisiana::class,
    'US-MA' => \Umulmrum\Holiday\Provider\Usa\Massachusetts::class,
    'US-MD' => \Umulmrum\Holiday\Provider\Usa\Maryland::class,
    'US-ME' => \Umulmrum\Holiday\Provider\Usa\Maine::class,
    'US-MI' => \Umulmrum\Holiday\Provider\Usa\Michigan::class,
    'US-MN' => \Umulmrum\Holiday\Provider\Usa\Minnesota::class,
    'US-MO' => \Umulmrum\Holiday\Provider\Usa\Missouri::class,
    'US-MS' => \Umulmrum\Holiday\Provider\Usa\Mississippi::class,
    'US-MT' => \Umulmrum\Holiday\Provider\Usa\Montana::class,
    'US-NC' => \Umulmrum\Holiday\Provider\Usa\NorthCarolina::class,
    'US-ND' => \Umulmrum\Holiday\Provider\Usa\NorthDakota::class,
    'US-NE' => \Umulmrum\Holiday\Provider\Usa\Nebraska::class,
    'US-NH' => \Umulmrum\Holiday\Provider\Usa\NewHampshire::class,
    'US-NJ' => \Umulmrum\Holiday\Provider\Usa\NewJersey::class,
    'US-NM' => \Umulmrum\Holiday\Provider\Usa\NewMexico::class,
    'US-NV' => \Umulmrum\Holiday\Provider\Usa\Nevada::class,
    'US-NY' => \Umulmrum\Holiday\Provider\Usa\NewYork::class,
    'US-OH' => \Umulmrum\Holiday\Provider\Usa\Ohio::class,
    'US-OK' => \Umulmrum\Holiday\Provider\Usa\Oklahoma::class,
    'US-OR' => \Umulmrum\Holiday\Provider\Usa\Oregon::class,
    'US-PA' => \Umulmrum\Holiday\Provider\Usa\Pennsylvania::class,
    'US-RI' => \Umulmrum\Holiday\Provider\Usa\RhodeIsland::class,
    'US-SC' => \Umulmrum\Holiday\Provider\Usa\SouthCarolina::class,
    'US-SD' => \Umulmrum\Holiday\Provider\Usa\SouthDakota::class,
    'US-TN' => \Umulmrum\Holiday\Provider\Usa\Tennessee::class,
    'US-TX' => \Umulmrum\Holiday\Provider\Usa\Texas::class,
    'US-UT' => \Umulmrum\Holiday\Provider\Usa\Utah::class,
    'US-VA' => \Umulmrum\Holiday\Provider\Usa\Virginia::class,
    'US-VT' => \Umulmrum\Holiday\Provider\Usa\Vermont::class,
    'US-WA' => \Umulmrum\Holiday\Provider\Usa\Washington::class,
    'US-WI' => \Umulmrum\Holiday\Provider\Usa\Wisconsin::class,
    'US-WV' => \Umulmrum\Holiday\Provider\Usa\WestVirginia::class,
    'US-WY' => \Umulmrum\Holiday\Provider\Usa\Wyoming::class,
];
