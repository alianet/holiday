"""Validate importer failures without network or PHP provider dependencies."""

import unittest
from update import normalize


class ImportTest(unittest.TestCase):
    @staticmethod
    def calendar():
        rows = ["国民の祝日・休日月日,国民の祝日・休日名称"]
        for year in range(1980, 2028):
            for month, day in [(1, 1), (1, 15), (2, 11), (3, 20), (4, 29), (5, 3), (5, 5), (9, 15), (9, 23), (10, 10), (11, 3), (11, 23)]:
                rows.append(f"{year}/{month}/{day},テスト祝日")
        return rows

    def test_normalizes_dates_and_preserves_names(self):
        records, years = normalize("\n".join(self.calendar()).encode("cp932"))
        self.assertEqual(list(range(1980, 2028)), years)
        self.assertEqual({"date": "1980-01-01", "name": "テスト祝日"}, records[0])
        self.assertEqual(48 * 12, len(records))

    def test_rejects_bad_header_duplicates_invalid_dates_and_missing_coverage(self):
        original = self.calendar()
        cases = [
            ["date,name"] + original[1:],
            original + [original[1]],
            original + ["2027/2/30,テスト祝日"],
            [row for row in original if not row.startswith("2000/")],
            original[:-1],
            original + ["2027/12/1,"],
        ]
        for rows in cases:
            with self.subTest(last_row=rows[-1]):
                with self.assertRaises(ValueError):
                    normalize("\n".join(rows).encode("cp932"))


if __name__ == "__main__":
    unittest.main()
