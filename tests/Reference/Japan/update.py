"""Download Cabinet Office holidays independently of the PHP provider (Python 3)."""

import csv
import hashlib
import io
import json
import os
import sys
import tempfile
import urllib.request
from datetime import date, datetime, timezone
from pathlib import Path

DIRECTORY = Path(__file__).resolve().parent
URL = "https://www8.cao.go.jp/chosei/shukujitsu/syukujitsu.csv"


def normalize(raw):
    rows = list(csv.reader(io.StringIO(raw.decode("cp932"))))
    if not rows or rows[0] != ["国民の祝日・休日月日", "国民の祝日・休日名称"]:
        raise ValueError("Unexpected Cabinet Office CSV header")
    records = []
    seen = set()
    for row in rows[1:]:
        if len(row) != 2 or not row[1].strip():
            raise ValueError("Invalid holiday record")
        year, month, day = map(int, row[0].split("/"))
        value = date(year, month, day).isoformat()
        if value in seen:
            raise ValueError("Duplicate date: " + value)
        seen.add(value)
        if 1980 <= year <= 2099:
            records.append({"date": value, "name": row[1]})
    records.sort(key=lambda record: record["date"])
    years = sorted({int(record["date"][:4]) for record in records})
    if not years or years != list(range(1980, years[-1] + 1)) or years[-1] < 2027:
        raise ValueError("Missing reference years")
    for year in years:
        dates = {record["date"][5:] for record in records if record["date"].startswith(str(year))}
        if len(dates) < 12 or not {"01-01", "02-11", "04-29", "05-03", "05-05", "11-03", "11-23"} <= dates:
            raise ValueError("Incomplete calendar: " + str(year))
    return records, years


def encode(value):
    return (json.dumps(value, ensure_ascii=False, indent=4) + "\n").encode()


def replace(path, content):
    with tempfile.NamedTemporaryFile(dir=path.parent, delete=False) as file:
        temporary = Path(file.name)
        file.write(content)
    try:
        os.replace(temporary, path)
    finally:
        temporary.unlink(missing_ok=True)


def main():
    # An optional local CSV allows reproducible imports without network access.
    raw = Path(sys.argv[1]).read_bytes() if len(sys.argv) == 2 else urllib.request.urlopen(URL, timeout=30).read()
    records, years = normalize(raw)
    old = DIRECTORY / "source.json"
    if old.exists() and not set(json.loads(old.read_text())["years"]) <= set(years):
        raise ValueError("Refusing to shrink reference coverage")
    data = encode(records)
    manifest = {
        "publisher": "Cabinet Office, Government of Japan",
        "url": URL,
        "landingPage": "https://www8.cao.go.jp/chosei/shukujitsu/gaiyou.html",
        "retrievedAt": datetime.now(timezone.utc).isoformat(),
        "rawSha256": hashlib.sha256(raw).hexdigest(),
        "sha256": hashlib.sha256(data).hexdigest(),
        "normalization": "CP932 CSV to UTF-8 JSON; dates normalized to ISO; filtered to supported years 1980–2099; sorted by date. Names unchanged.",
        "years": years,
    }
    replace(DIRECTORY / "Data/holidays.json", data)
    replace(DIRECTORY / "source.json", encode(manifest))
    print(f"Saved {len(records)} holidays for {years[0]}–{years[-1]}. Review diff and run reference tests.")


if __name__ == "__main__":
    main()
