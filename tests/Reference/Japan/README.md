# Japan: Cabinet Office reference calendar

Independent source: [Cabinet Office, Government of Japan](https://www8.cao.go.jp/chosei/shukujitsu/gaiyou.html),
[official CSV](https://www8.cao.go.jp/chosei/shukujitsu/syukujitsu.csv).
The downloaded CSV covers 1955–2027. `Data/holidays.json` preserves its Japanese
names and all dates within the provider's supported range: 1980–2027 (789 entries).
It is created directly from the government feed, never from the PHP provider.
`source.json` records the URL, download time, raw CSV hash, JSON hash and years.

Tests run offline and compare complete annual date lists, including original
weekend holidays, substitutes, bridge days and one-off imperial holidays.
They check national holiday identities and OFFICIAL/DAY_OFF classification.
The feed labels both bridge and substitute days simply `休日`, so their exact
identity and individual COMPENSATORY flags cannot be independently verified from
this source. Provider unit tests cover that distinction. The two 2019 imperial
holidays are both labelled `休日（祝日扱い）`; their names are disambiguated by date.
No replacement dates or equinox dates are calculated in the reference tests.

Run with development dependencies installed:

```bash
php vendor/bin/phpunit tests/Reference/Japan --do-not-cache-result
python3 -m unittest discover -s tests/Reference/Japan -p 'test_*.py'
```

Refresh with Python 3 (standard library only; network required):

```bash
python3 tests/Reference/Japan/update.py
# Or import an already downloaded original CP932 CSV:
python3 tests/Reference/Japan/update.py /path/to/syukujitsu.csv
```

Review the diff and run the reference tests after refreshing. The importer rejects
invalid headers, dates, empty names, duplicates, gaps in years, incomplete calendars
and shrinking coverage before writing. Coverage in the PHP tests is deliberately
pinned to 1980–2027: when a new official year is published, extend both explicit
ranges there after reviewing the downloaded data. This standalone updater is not
part of `test:reference:update`.

These tests do not independently verify 2028–2099: future equinox dates remain
predictions and exceptional holidays can require provider updates.
