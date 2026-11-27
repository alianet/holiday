#!/usr/bin/env python3
"""Build named island providers from the reviewed source snapshot, offline."""
import datetime
import json
from pathlib import Path

HERE = Path(__file__).resolve().parent
ROOT = HERE.parents[2]
records = json.loads((HERE / 'Data/islands.json').read_text())
header = (ROOT / 'src/Provider/Spain/CanaryIslands.php').read_text().split('final class')[0]
for island in records['2008']:
    rows = []
    for year, islands in records.items():
        entries = []
        for row in islands[island]:
            datetime.date.fromisoformat(f"{year}-{row['date']}")
            entries.append(f"['{row['date']}', HolidayName::{row['name'].upper()}]")
        rows.append(f"        {year} => [" + ', '.join(entries) + '],')
    content = header + 'use Umulmrum\\Holiday\\Constant\\HolidayName;\n\n'
    content += f"final class {island} extends Island\n{{\n"
    content += "    protected const REGION = '" + ('IB' if island == 'Formentera' else 'CN') + "';\n\n"
    content += '    protected const HOLIDAYS = [\n' + '\n'.join(rows) + '\n    ];\n}\n'
    (ROOT / f'src/Provider/Spain/{island}.php').write_text(content)
