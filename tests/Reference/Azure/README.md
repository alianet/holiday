# Azure Open Datasets reference calendars

This suite covers all 24 country codes shared by the Azure snapshot and this
library for every year from 2000 through 2020 (504 country/year cases) with Microsoft's public
[Public Holidays dataset](https://learn.microsoft.com/en-us/azure/open-datasets/dataset-public-holidays).
The source combines the Python `holidays` package and Wikipedia. It is independent
of this PHP library, but is a third-party computed calendar rather than an official
government record. It is not independent of other datasets using those same inputs.

**This dataset contains current-year dates, but it is not a current calendar feed.**
Its advertised range is 1970–2099; the pinned Parquet blob was last modified on
**6 December 2020**. Dates for later years are projections based on rules captured
in that snapshot. They may omit subsequent legal changes and exceptional holidays,
such as Poland's Christmas Eve holiday introduced in 2025 or later one-off UK
bank holidays. A date existing in the dataset does not establish that it is accurate
under current rules.

The tests deliberately cover **2000–2020**. Extending them into later years would
compare providers with old projections, so disagreement alone would not prove a
provider defect. Use OpenHolidays and current official sources such as GOV.UK for
recent calendar validation.

`test:reference:azure:update` downloads the same configured snapshot again and
refreshes the local export and retrieval metadata. **A new retrieval date does not
mean the underlying holiday rules were updated.** The command does not discover or
switch to a newer dataset automatically.

## Data and comparison

`source.json` records the exact public blob URL, retrieval time, countries, years,
original Parquet hash and exported JSON hash. No account, key or Azure SDK is needed.
`export.py` selects the configured rows, preserves all source columns, converts
timestamps to ISO dates and sorts rows. It does not compute, remove duplicates,
translate or correct holidays. `names.json` maps source names to lists of PHP identifiers. Empty lists are permitted
only with an explicit explanation in `scope.json`. They represent source entries
outside this suite's declared scope, not a claim that a provider is correct.
Azure sometimes combines coinciding holidays in one row, for example Ascension and
Labour Day in Germany and Austria (2008), or Portugal Day and Corpus Christi (2004).
Explicit mappings expand these rows into both identifiers while preserving the
source date. Neither provider output nor date rules are used to split them.
The broader years also cover Germany's nationwide Reformation Day in 2017,
Poland's Epiphany change in 2011, Czech Good Friday from 2016, and Portugal's
suspension of four holidays during 2013–2015.

The original combined dataset and the filtered, reformatted JSON extract are
licensed under [CC BY-SA 3.0](https://creativecommons.org/licenses/by-sa/3.0/),
separately from this project's PHP code. Attribution: Microsoft Azure Open Datasets,
Python `holidays` contributors and Wikipedia contributors. The transformations
are the filtering, date serialization and sorting described above.

## What the tests establish

There are two comparison modes, declared in `scope.json`:

- **Full calendar**: AT, CZ, DE, PL, PT. Compare full DAY_OFF lists, excluding
  PARTIAL_ONLY, with the existing adaptations below.
- **Shared holiday identities**: the other 19 countries. Compare all dates and
  multiplicity for explicitly mapped holidays represented by both systems, including
  observed-day suffixes where applicable. Classification bits are not validated.
  Extra provider identities not represented in Azure are outside this comparison;
  this mode does not establish the completeness of the provider's entire calendar.

Both modes preserve duplicates. Unknown source names, empty country/year coverage,
changed dates, missing or extra occurrences of a compared identity fail agreement.
The nullable source `isPaidTimeOff` is not used as proof of DAY_OFF classification.

| Provider | Comparison mode | Cases with documented disagreement |
|---|---|---:|
| AT | Full calendar | 0 |
| AU | Shared identities | 0 |
| BE | Shared identities | 0 |
| BR | Shared identities | 0 |
| BY | Shared identities | 1 |
| CA | Shared identities | 0 |
| CH | Shared identities | 0 |
| CZ | Full calendar | 0 |
| DE | Full calendar | 0 |
| DK | Shared identities | 0 |
| ES | Shared identities | 0 |
| FI | Shared identities | 0 |
| FR | Shared identities | 4 |
| GB | Shared identities | 1 |
| IE | Shared identities | 0 |
| IT | Shared identities | 1 |
| MX | Shared identities | 21 |
| NL | Shared identities | 0 |
| NO | Shared identities | 0 |
| PL | Full calendar | 0 |
| PT | Full calendar | 0 |
| SE | Shared identities | 5 |
| UA | Shared identities | 0 |
| US | Shared identities | 0 |

### Representation and scope adaptations

- PL: include both Christmas entries by name despite missing DAY_OFF flags; exclude
  the additional Armed Forces Day commemoration on Assumption Day.
- CZ: compare restoration of Czech statehood on 1 January, omitting the additional
  New Year entry on that date. The latter is not independently verified here.
- BY/UA: the library uses `christmas_day` for both Christmas traditions. Both dates
  are compared under that identifier. UA Labour Day and Victory Day aliases are
  normalized to preserve dates while ignoring naming changes.
- NO/SE: ordinary weekly Sundays are omitted; named holidays coinciding with Sunday
  are retained. This does not verify the provider's regular Sunday coverage.
- GB: Azure contains a union of UK regional entries. This suite tests the GB base
  provider (England/Wales); Scottish and Northern Irish-only entries are excluded.
  The combined Scottish 2 January / observed New Year record retains the New Year
  component applying to England/Wales. Regional UK coverage remains in GOV.UK tests.
- CH: exclude source holidays absent from the base provider, not asserted nationwide
  for all cantons; compare DAY_OFF entries to avoid the provider's reused
  `swiss_national_day` identifier on religious Bettag.
- CA: omit provincial/broader entries missing from the base provider (Family Day,
  Civic Holiday, Victoria Day, Boxing Day). Observed records are outside this suite
  because the base CA provider has no compensation implementation. This does not
  confirm that the provider's regional or replacement-day coverage is correct.
- BR: Easter Sunday and Corpus Christi are absent from the provider and excluded
  from the shared subset. DK Palm Sunday, ES Immaculate Conception and MX federal
  inauguration likewise remain unsupported identities, explicitly excluded here.
- NL: Liberation Day dates are checked in years divisible by five, matching Azure's
  coverage. Annual observances in other years and its DAY_OFF classification are
  outside this date comparison.

`scope.json` lists reasons for excluded source names and partial-row comparisons.
These exclusions are scope limitations, not fixes to reference data or providers.
No regional providers are inferred by falling back to country-level data.

### Agreement versus known disagreement

**471 country/year cases agree within the declared scope. Another 33 cases have
unresolved differences**, recorded in `known-differences.json` with exact
reference-only and provider-only date/identifier entries and a reason:

- BY 2020: missing 2 January; the provider inserts New Year twice on 1 January.
- FR 2004–2007: Whit Monday is present in Azure and absent from the provider.
- GB 2002: both jubilee-related dates disagree; neither calendar is considered
  authoritative without an official historical source.
- IT 2000: Republic Day is 2 June in Azure and the first Sunday in June in the provider.
- MX 2000–2020: the pre-2006 Benito Juarez date differs, with further differences in
  observed dates and in-place replacement versus anniversary-plus-observed records.
- SE 2000–2004: Azure retains Whit Monday and introduces National Day later; the
  provider instead includes National Day throughout these years and omits Whit Monday.

`testPublicHolidayDates` checks agreement cases. Separately named
`testDocumentedReferenceDiscrepancy` tests freeze the **exact unresolved difference**
and common entries: they fail on new differences or on a correction requiring
review of the register. They do **not** certify the recorded provider dates as
correct. A green PHPUnit run therefore does not mean all 504 calendars agree.
The updater never regenerates this register from calculator output. Resolve entries
against an independent source, fix the appropriate side, and remove the register
entry so that the case joins the agreement tests.

This historical secondary reference can still share omissions with a provider;
agreement is not a guarantee of legal completeness.

## Run and update

PHPUnit reads the committed JSON; it requires neither Python nor network access:

```bash
php vendor/bin/phpunit tests/Reference/Azure
```

For updates without Docker, install Python 3.10+ and dependencies in a virtual
environment, then select its interpreter for the PHP command:

```bash
python3 -m venv /tmp/holiday-azure-venv
/tmp/holiday-azure-venv/bin/pip install -r tests/Reference/Azure/requirements.txt
AZURE_REFERENCE_PYTHON=/tmp/holiday-azure-venv/bin/python \
  php tests/console test:reference:azure:update
```

The command downloads the pinned public Parquet blob, invokes the exporter, validates
the records and coverage, and updates JSON plus provenance. A download, conversion
or validation failure leaves reference files unchanged. Review the diff and run
the tests before committing. Changing the blob URL or coverage is a deliberate edit
to `source.json`; updating does not automatically select a different snapshot.

The shared `php tests/console test:reference:update` command includes this source.
It requires Python/PyArrow even if the other two sources only need PHP.

## Docker

The [main README](../../../README.md#reference-data-with-docker) describes image
building and dependency installation. Use the `reference-updater` target, which
extends the PHP development image with Python and pinned PyArrow dependencies:

```bash
docker build --file docker/php/Dockerfile --target reference-updater \
  --build-arg PHP_VERSION=8.5 --tag holiday-php:8.5 .

docker run --rm --user "$(id -u):$(id -g)" \
  --volume "$PWD:/app" --workdir /app holiday-php:8.5 \
  php tests/console test:reference:azure:update

docker run --rm --network none --user "$(id -u):$(id -g)" \
  --volume "$PWD:/app" --workdir /app holiday-php:8.5 \
  php vendor/bin/phpunit tests/Reference/Azure --do-not-cache-result
```

The ordinary `development` and `qa` targets remain PHP-only; existing CI test runs
use committed JSON and do not install Python.

The UK/Ireland audit of 2026-11-27 resolved the provider's incorrect 2002
Jubilee date using the royal proclamation. The remaining Azure Spring-date
error is recorded exactly. IE observed-day differences are also recorded exactly:
WRC public holiday rules do not establish a national Monday substitute.
See [Irish source notes](../Ireland/README.md). Source data remains unchanged.
