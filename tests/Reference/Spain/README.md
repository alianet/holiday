# Spanish regional calendars

The 19 regional providers cover **2008–2027**. Their source is the annual
national/autonomous-community annex published by the Dirección General de
Trabajo in BOE. Each year is represented explicitly: regional choices and
substitute dates are not extrapolated to unannounced years.

`Data/annexes.json` transcribes the source tables, preserving Spanish labels,
month/day dates, regional applicability and the original `*`, `**`, `***` and
`FR` markers. It was extracted from BOE HTML tables or, for 2008–2012 and
2016–2017, the PDF text obtained with `pdftotext -layout`. The earlier column
order places Valencia before Extremadura; since 2022 it follows La Rioja.
`source.json` records all 20 document URLs and downloaded-document SHA-256
hashes, along with available explanatory notes. The package’s MIT license applies to the provider and data-building code.

`names.json` maps Spanish source labels to library identifiers. Combined rows
have region-specific identities (for example Aragon and Castile and León on
23 April). `build_provider_data.py` builds the packaged PHP data **offline**:

```sh
python3 tests/Reference/Spain/build_provider_data.py
php vendor/bin/phpunit tests/Reference/Spain
```

PHP and normal library dependencies are sufficient at runtime. Neither Python,
network access nor test fixtures are required to calculate holidays.

The reference tests compare all **380 region/year calendars** with annex dates,
including duplicate detection through exact sorted-list comparison. Additional
cases assert holiday identities, classifications, regional differences, range
errors and provider reuse. This is data-driven implementation coverage: the PHP
data and expected dates share the BOE annex source; it is not a second,
independently implemented calendar algorithm.

## Scope

- Only dates marked for that region in the annual national/autonomous-community
  table are included. Municipal and island-specific calendars and the Aran
  substitutions in Catalonia are outside scope. This excludes local holidays
  even where they apply broadly, such as St Francis Xavier in Navarre and local
  patronal holidays in Ceuta/Melilla. Canary Islands calendars have 11 dates
  before their island-specific holiday.
- The published table includes recoverable holidays (hours must be made up),
  such as San Juan in Valencia in some years. Catalonia sometimes lists 13 days
  with one recoverable day to be selected from several candidates. All dates
  remain listed as `OFFICIAL | DAY_OFF`; the library does not model working-time
  recovery arrangements or an employer's choice of recoverable day.
- Selected substitute dates have `COMPENSATORY` and the identifier suffix
  `_compensatory`. The corresponding original date is not added unless the
  table lists it. Both Saturday holidays and regional decisions not to grant a
  Monday substitute are preserved.
- The scope is the **published annual BOE annex**. Later municipal/regional
  amendments are not automatically discovered. Review applicable amendments
  when updating a calendar; no universal historical or future rule is implied.

## Updates

Add a new published resolution to `source.json`, transcribe its annex preserving
column order, labels and markers, and extend `names.json` for new identities.
Review explanatory notes, especially recoverable days and territorial variants.
Run the offline builder, update the supported-year message, README and test
coverage, and run PHPUnit and PHPStan. Review the generated PHP diff against BOE
before accepting it. Changing source dates must be a deliberate reviewed change;
the library output must never be used to regenerate the source annex snapshot.
