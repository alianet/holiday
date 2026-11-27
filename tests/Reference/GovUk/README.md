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

## Jurisdiction audit (2026-11-27)

England (`GB-ENG`, `England`) and Wales (`GB-WLS`, `Wales`) inherit the existing
`UnitedKingdom` rules. All three codes now run against the same independent feed
for 2019–2028, and a separate equality test compares dates, names and type masks
including original weekend entries. GB keeps its England-and-Wales meaning.
The existing feed and its original retrieval metadata are retained.

Jurisdiction-specific corroboration:

- [nidirect](https://www.nidirect.gov.uk/articles/bank-holidays): Northern Ireland directs readers to the GOV.UK calendar. GB-NIR includes St Patrick's Day and Battle of the Boyne, with weekend substitutes.
- [Scottish Government](https://www.gov.scot/publications/bank-holidays/): confirms Scotland-wide dates, including 15 June 2026; local holidays and actual bank closures are different calendars.
- [Hansard, 27 January 1977](https://hansard.parliament.uk/Commons/1977-01-27/debates/d431b4e2-f65f-48cc-9367-dac0fefdc28d/MayDay1978): Scottish last-Monday-in-May holiday starts in 1978. Earlier inherited Whit Monday/Spring entries removed.
- [Hansard, 25 January 1978](https://hansard.parliament.uk/lords/1978-01-25/debates/f597ec92-84ba-4023-ada8-7be83c64b245/WrittenAnswers): Scottish 1977 Silver Jubilee on 6 June.
- [Hansard, 14 November 1979](https://hansard.parliament.uk/commons/1979-11-14/debates/68a02849-b498-4564-84b7-035751fbf645/RoyalVisit%281977%29): 7 June 1977 additional holiday in Northern Ireland.
- [Royal proclamation for 2002](https://www.thegazette.co.uk/notice/L-56043-1001): 3 June additional Jubilee holiday, 4 June replacing 27 May in England, Wales and Northern Ireland. Corrected 2 June to 3 June.

Full annual verification is limited to 2019–2028. Historical fixes are targeted;
legacy rules outside that window are not an exhaustive historical calendar.
Future extraordinary proclamations require maintenance. New Year collisions
(2022/2023/2028), Christmas/Boxing Day collisions (2021/2022/2027), NI weekend
holidays, 2020 May movement, 2022 Jubilee/funeral, 2023 coronation and Scotland's
2026 exception are covered by complete independent lists.
