#!/usr/bin/env python3
"""Build packaged Spanish regional calendars from the reviewed BOE snapshot, offline."""

import collections
import datetime
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parents[3]
HERE = Path(__file__).resolve().parent
REGIONS = set('AN AR AS CB CE CL CM CN CT EX GA IB MC MD ML NC PV RI VC'.split())
NOMINAL_DATES = {
    'new_year': '01-01', 'epiphany': '01-06', 'labor_day': '05-01',
    'assumption_day': '08-15', 'spanish_national_day': '10-12',
    'all_saints_day': '11-01', 'constitution_day': '12-06',
    'immaculate_conception': '12-08', 'christmas_day': '12-25',
    'second_christmas_day': '12-26', 'saint_josephs_day': '03-19',
    'saint_james_day': '07-25', 'galician_national_day': '07-25',
    'andalusia_day': '02-28', 'aragon_day': '04-23', 'asturias_day': '09-08',
    'balearic_islands_day': '03-01', 'canary_islands_day': '05-30',
    'castile_and_leon_day': '04-23', 'castilla_la_mancha_day': '05-31',
    'extremadura_day': '09-08', 'la_rioja_day': '06-09',
    'murcia_day': '06-09', 'madrid_day': '05-02',
}
RELIGIOUS = {
    'epiphany', 'immaculate_conception', 'christmas_day', 'second_christmas_day',
    'assumption_day', 'all_saints_day', 'maundy_thursday', 'good_friday',
    'whit_monday', 'easter_monday', 'corpus_christi', 'saint_josephs_day',
    'saint_johns_day', 'saint_james_day', 'eid_al_adha', 'eid_al_fitr',
    'our_lady_of_bien_aparecida', 'our_lady_of_africa',
}


def build_records(annexes, mapping):
    records = {}
    for year, rows in annexes.items():
        records[year] = []
        dates_by_region = {code: set() for code in REGIONS}
        for row in rows:
            datetime.date.fromisoformat(f"{year}-{row['date']}")
            label = row['label']
            name = mapping[label]  # Unmapped source identities must be reviewed.
            groups = collections.defaultdict(list)
            for code, marker in row['regions'].items():
                if code not in REGIONS or marker not in ('*', '**', '***', '***FR'):
                    raise ValueError(f'Unknown region or holiday marker: {row}')
                if row['date'] in dates_by_region[code]:
                    raise ValueError(f'Duplicate calendar date: {year}/{code}/{row}')
                dates_by_region[code].add(row['date'])
                identity = name
                # Combined BOE rows describe different regional identities.
                if name == 'aragon_day' and code == 'CL':
                    identity = 'castile_and_leon_day'
                if name == 'saint_james_day' and code == 'GA':
                    identity = 'galician_national_day'
                if label == 'Lunes siguiente a la Natividad del Señor – San Esteban' and code in ('IB', 'CT'):
                    identity = 'second_christmas_day'
                if label == 'Lunes siguiente a la Fiesta del Trabajo- Fiesta de la Comunidad de Madrid' and code == 'MD':
                    identity = 'labor_and_madrid_day'
                compensatory = identity in NOMINAL_DATES and row['date'] != NOMINAL_DATES[identity]
                if identity == 'labor_and_madrid_day':
                    compensatory = True
                types = 'HolidayType::OFFICIAL | HolidayType::DAY_OFF'
                if name in RELIGIOUS:
                    types += ' | HolidayType::RELIGIOUS'
                if compensatory:
                    identity += '_compensatory'
                    types += ' | HolidayType::COMPENSATORY'
                groups[identity, types].append(code)
            for (identity, types), regions in groups.items():
                records[year].append((row['date'], identity, types, ' '.join(regions)))
        if not all(11 <= len(dates) <= 13 for dates in dates_by_region.values()):
            raise ValueError(f'Incomplete or unexpected annex for {year}')
    return records


def main():
    annexes = json.loads((HERE / 'Data/annexes.json').read_text())
    mapping = json.loads((HERE / 'names.json').read_text())
    records = build_records(annexes, mapping)
    header = (ROOT / 'src/Provider/Spain/Region.php').read_text().split('namespace')[0]
    content = header + r'''namespace Umulmrum\Holiday\Provider\Spain\Internal;

use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;

/**
 * @internal Generated from published BOE annexes by tests/Reference/Spain/build_provider_data.py.
 * See tests/Reference/Spain/source.json for provenance and scope.
 */
final class CalendarData
{
    /** @var array<int, list<array{string, string, int, string}>> */
    public const YEARS = [
'''
    for year, rows in records.items():
        content += f'        {year} => [\n'
        for date, identity, types, regions in rows:
            content += f"            ['{date}', HolidayName::{identity.upper()}, {types}, '{regions}'],\n"
        content += '        ],\n'
    content += '    ];\n}\n'
    (ROOT / 'src/Provider/Spain/Internal/CalendarData.php').write_text(content)


if __name__ == '__main__':
    main()
