# Island calendars

Named providers `ElHierro`, `Fuerteventura`, `GranCanaria`, `LaGomera`, `LaPalma`,
`Lanzarote`, `LaGraciosa`, `Tenerife` and `Formentera` cover **2008–2027** only.
They combine the existing regional BOE calendar with explicitly published island
dates, rejecting other years rather than extrapolating annual decisions.

Sources reviewed on 27 November 2026:

- [BOE 2026 calendar, explanatory Canary Islands note](https://www.boe.es/buscar/doc.php?id=BOE-A-2025-21667), citing Decreto 61/2025: El Hierro 24 September, Fuerteventura 18 September, Gran Canaria 8 September, La Gomera 5 October, La Palma 5 August, Lanzarote and La Graciosa 15 September, Tenerife 2 February.
- [Decreto 115/2026, annex for 2027](https://www.gobiernodecanarias.org/boc/2026/133/2334.html): Fuerteventura changes to 17 September and La Gomera to 4 October; the other island dates match 2026.
- [CAIB 2026 calendar, Formentera row](https://www.caib.es/sites/calendarilaboral/es/aao_2026/): 25 July (San Jaime) and 3 December (San Francisco Javier).
- [CAIB 2027 calendar](https://www.caib.es/sites/calendarilaboral/es/aao_2027/) and [November correction](https://www.caib.es/sites/calendarilaboral/f/574545): 5 August (Santa María) replaces 25 July; 3 December remains.

Canary providers exclude municipal holidays. Formentera is a single municipality,
so its two local holidays apply throughout the island. Mallorca, Menorca and
Ibiza have varying municipal/parish holidays: use `BalearicIslands` for their
shared regional calendar. Island celebration days are not automatically days off.
No artificial ISO identifiers are assigned to individual islands.

`IslandReferenceTest` compares all 180 island/year calendars against the regional
calendar plus the source dates above, including the corrected Formentera date,
and checks unsupported years and English name availability. To update, review
the annual island decree and municipal corrections, extend the provider tables
and tests, and update the supported-year message and documentation.

## Historical coverage, 2008–2027

`Data/islands.json` records all 180 island/year additions (dates, identities and
substitute flags). Canary dates come from the explanatory island notes in the
20 annual BOE resolutions already listed in `source.json`. The Formentera rows
come from the general/local CAIB calendars; links and document hashes are in
`island-sources.json`. For early CAIB pages follow their linked BOIB PDFs:
2008 `https://boib.caib.es/pdf/2007174/mp7.pdf`,
2009 `https://boib.caib.es/pdf/2008153/mp21.pdf`,
2010 `https://boib.caib.es/pdf/2009157/mp27.pdf`,
2011 `https://boib.caib.es/pdf/2010164/mp42.pdf`.
For 2024 the linked resolution is
`https://www.caib.es/eboibfront/pdf/es/2023/140/1146877`.

Before BOE began naming La Graciosa separately, its entries follow Lanzarote:
[La Graciosa forms part of the municipality of Teguise in Lanzarote](https://www.miteco.gob.es/es/parques-nacionales-oapn/centros-fincas/graciosa/ficha-tecnica.html). This is an
application of the territorial calendar, not an assertion that its patronal
festival is the same. Municipal/parish patronal days remain outside scope.

Historical exceptions include El Hierro's July Bajada, Saint Michael on La Palma
in 2012/2018 and the Bajada of Our Lady of Socorro on Tenerife in 2020. These are
separate identities, not substitutes for the usual patronal feast. Published
Monday substitutes use `COMPENSATORY`; other annually selected dates are retained
without imposing a general weekend substitution rule. The scope remains the
published annual calendar; cancellation of a religious event does not by itself
amend a statutory holiday.

Regenerate the PHP tables offline with:

```sh
python3 tests/Reference/Spain/build_island_data.py
```

The date tests and generated PHP tables share the reviewed snapshot. Literal
regression cases additionally check historical identities and classifications.
