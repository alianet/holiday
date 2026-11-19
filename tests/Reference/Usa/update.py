"""Export US state calendars from a pinned external implementation, never PHP providers."""

import hashlib
import json
import os
import sys
import tempfile
from datetime import datetime, timezone
from importlib.metadata import version
from pathlib import Path

import holidays

STATES = set("AL AK AZ AR CA CO CT DE FL GA HI ID IL IN IA KS KY LA ME MD MA MI MN MS MO MT NE NV NH NJ NM NY NC ND OH OK OR PA RI SC SD TN TX UT VT VA WA WV WI WY DC".split())


def encode(value):
    return (json.dumps(value, ensure_ascii=False, indent=4) + "\n").encode("utf-8")


def export(manifest):
    if manifest.get("package") != "holidays" or manifest.get("version") != version("holidays"):
        raise ValueError("Install the pinned Usa/requirements.txt dependencies first")
    subdivisions = manifest.get("subdivisions", [])
    years = manifest.get("years", [])
    if (not isinstance(subdivisions, list) or any(not isinstance(s, str) for s in subdivisions)
            or len(subdivisions) != 51 or set(subdivisions) != STATES
            or not set(subdivisions) <= set(holidays.US.subdivisions)):
        raise ValueError("Expected all 50 states and DC")
    if (not isinstance(years, list) or not years or any(type(y) is not int or not 1777 <= y <= 9998 for y in years)
            or len(set(years)) != len(years)):
        raise ValueError("Expected unique supported integer years")
    if (manifest.get("country") != "US" or manifest.get("language") != "en_US"
            or manifest.get("categories") != ["public"]):
        raise ValueError("Unsupported calendar scope")
    calendars = []
    for subdivision in sorted(subdivisions):
        for year in sorted(years):
            record = {"subdivision": subdivision, "year": year}
            for key, observed in (("actual", False), ("withObserved", True)):
                # Adjacent years include substitute days crossing the calendar-year boundary.
                calendar = holidays.US(subdiv=subdivision, years=[year - 1, year, year + 1],
                                       observed=observed, expand=False, language="en_US", categories="public")
                dates = [{"date": date.isoformat(), "name": name}
                         for date, name in sorted(calendar.items()) if date.year == year]
                if not dates or any(not row["name"] for row in dates):
                    raise ValueError(f"Empty calendar: {subdivision}/{year}/{key}")
                record[key] = dates
            calendars.append(record)
    return calendars


def replace(path, content):
    temporary = None
    try:
        with tempfile.NamedTemporaryFile(dir=path.parent, prefix=".reference-", delete=False) as stream:
            temporary = stream.name
            stream.write(content)
            os.fchmod(stream.fileno(), 0o644)
        os.replace(temporary, path)
    finally:
        if temporary and os.path.exists(temporary):
            os.unlink(temporary)


def update(directory):
    manifest_path = directory / "source.json"
    manifest = json.loads(manifest_path.read_text(encoding="utf-8"))
    content = encode(export(manifest))
    manifest["sha256"] = hashlib.sha256(content).hexdigest()
    manifest["generatedAt"] = datetime.now(timezone.utc).isoformat()
    metadata = encode(manifest)
    # Finish generation and validation before replacing any files.
    replace(directory / "Data" / "holidays.json", content)
    replace(manifest_path, metadata)


if __name__ == "__main__":
    update(Path(sys.argv[1]) if len(sys.argv) > 1 else Path(__file__).parent)
