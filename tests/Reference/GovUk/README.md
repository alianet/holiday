# GOV.UK reference bank holidays

These offline tests use the official [GOV.UK bank holiday feed](https://www.gov.uk/bank-holidays.json).
The saved response covers 2019–2028 for England and Wales (`GB`), Scotland
(`GB-SCT`) and Northern Ireland (`GB-NIR`). `GB` is the library's base provider
for England and Wales; it does not represent the union of regional holidays.

The fixture is the feed response with indentation only. `source.json` records its
URL, retrieval time, SHA-256 hash, license and explicitly tested years.
`names.json` maps GOV.UK titles to library identifiers independently of providers.

Contains public sector information licensed under the
[Open Government Licence v3.0](https://www.nationalarchives.gov.uk/doc/open-government-licence/version/3/).
See [GOV.UK terms](https://www.gov.uk/help/terms-conditions).

Run from the project root with development dependencies installed:

```bash
php vendor/bin/phpunit tests/Reference/GovUk
php tests/console test:reference:govuk:update
```

`php tests/console test:reference:update` updates OpenHolidays, GOV.UK and Azure (Azure updates require Python/PyArrow).
The source-specific command above updates only GOV.UK.

The updater requires internet access and PHP HTTPS streams. It validates all three
divisions, non-empty event lists and coverage of every configured year before
writing data and metadata. Missing years cause an error: the live feed can change
its rolling window, so adjust coverage deliberately rather than silently dropping
tests. Review the diff and run the tests before committing updated fixtures.
Name mappings and configured years are preserved.

For Docker setup, see the [main README](../../../README.md#reference-data-with-docker).
Once the development image and dependencies exist:

```bash
docker run --rm --user "$(id -u):$(id -g)" \
  --volume "$PWD:/app" --workdir /app holiday-php:8.5 \
  php tests/console test:reference:govuk:update

docker run --rm --network none --user "$(id -u):$(id -g)" \
  --volume "$PWD:/app" --workdir /app holiday-php:8.5 \
  php vendor/bin/phpunit tests/Reference/GovUk --do-not-cache-result
```

## Comparison semantics

Tests compare complete sorted lists of observed dates and holiday identifiers,
including duplicates, exceptional holidays and substitute-day suffixes. They fail
on missing coverage, unknown titles or notes, missing holidays, unexpected holidays
and wrong dates. The library retains original weekend holidays alongside their
replacement days; originals are omitted only when a compensatory entry exists.
The test does not calculate replacement dates from the GOV.UK data or remove all
weekends blindly, so missing compensation is detected.

Scotland's two New Year bank holidays share one comparison identifier. When
1 January falls on Sunday (2023), GOV.UK labels 2 January as the substitute New Year
and 3 January as substitute 2 January, while the library preserves 2 January and
labels 3 January as substitute New Year. Both observed dates and their multiplicity
are checked; the individual names and compensation flags of this pair are not.
All other holiday identities, including substitute-day suffixes, are compared.

These tests validate the observed bank holiday calendar, not every classification
bit or historical dates outside the recorded coverage.
