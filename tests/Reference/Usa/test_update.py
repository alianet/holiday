"""Run with the same interpreter and dependencies as update.py."""

import copy
import json
import tempfile
import unittest
from pathlib import Path

from update import encode, export, update


class ExportTest(unittest.TestCase):
    def setUp(self):
        self.manifest = json.loads(Path(__file__).with_name("source.json").read_text())

    def test_all_states_and_observed_year_boundary(self):
        self.manifest["years"] = [2021]
        calendars = export(self.manifest)
        self.assertEqual(51, len(calendars))
        california = next(c for c in calendars if c["subdivision"] == "CA")
        self.assertTrue(any(h["date"] == "2021-03-31" for h in california["actual"]))
        texas = next(c for c in calendars if c["subdivision"] == "TX")
        self.assertTrue(any(h["date"] == "2021-03-02" for h in texas["actual"]))
        new_york = next(c for c in calendars if c["subdivision"] == "NY")
        self.assertTrue(any(h["date"] == "2021-12-31" for h in new_york["withObserved"]))
        self.assertFalse(any(h["date"] == "2021-12-31" for h in new_york["actual"]))
        self.assertEqual(encode(calendars), encode(export(self.manifest)))

    def test_invalid_configuration_does_not_replace_snapshot(self):
        for key, value in (("version", "0.0"), ("subdivisions", ["CA"]),
                           ("years", []), ("years", [2021, 2021]), ("years", [True]),
                           ("country", "CA"), ("categories", ["bank"])):
            with self.subTest(key=key, value=value), tempfile.TemporaryDirectory() as tmp:
                directory = Path(tmp)
                (directory / "Data").mkdir()
                manifest = copy.deepcopy(self.manifest)
                manifest[key] = value
                before = encode(manifest)
                (directory / "source.json").write_bytes(before)
                (directory / "Data/holidays.json").write_text("original")
                with self.assertRaises(ValueError):
                    update(directory)
                self.assertEqual(before, (directory / "source.json").read_bytes())
                self.assertEqual("original", (directory / "Data/holidays.json").read_text())


if __name__ == "__main__":
    unittest.main()
