"""Export configured Azure snapshot rows without calculating holiday dates."""

import json
import sys
from pathlib import Path

import pyarrow.parquet as parquet


def export(manifest_path, parquet_path):
    manifest = json.loads(Path(manifest_path).read_text(encoding="utf-8"))
    rows = []
    coverage = set()
    for row in parquet.read_table(parquet_path).to_pylist():
        country = row["countryRegionCode"]
        date = row["date"]
        if country not in manifest["countries"] or date.year not in manifest["years"]:
            continue
        coverage.add((country, date.year))
        row["date"] = date.date().isoformat()
        rows.append(row)
    required = {(c, y) for c in manifest["countries"] for y in manifest["years"]}
    if not required or coverage != required:
        raise ValueError("Azure snapshot does not cover all configured countries and years")
    rows.sort(key=lambda row: (row["countryRegionCode"], row["date"], row["holidayName"]))
    return rows


if __name__ == "__main__":
    json.dump(export(sys.argv[1], sys.argv[2]), sys.stdout, ensure_ascii=False, indent=4)
    sys.stdout.write("\n")
