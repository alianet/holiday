# Crown dependencies: provenance and audit limits

Checked **2026-11-27**. `observed-dates.json` is a manually maintained, offline
reference, independent of provider output. It contains civil dates, not copied
web pages. Do not regenerate it from the providers. Recheck official legislation
and annual announcements before extending the supported **2020–2027** interval.
Years outside that interval throw `InvalidArgumentException`.

## Scope and semantics

- JE: Jersey; GG: **island of Guernsey only**, not a Bailiwick-wide calendar;
  IM: Isle of Man. Each provider defines its own calendar, legal types, substitutions and annual
  exceptions; only Easter date calculations use the existing Christian trait.
  None inherits from a UK provider or a common Crown dependency calendar.
- Alderney and Sark are excluded, as are unverified extensions to Herm and Jethou.
  Alderney has a first-Monday-in-August holiday and Homecoming Day (15 December);
  Sark's liberation anniversary is 10 May, rather than Guernsey's 9 May.
  These require separate calendars and audits, including their own substitutions
  and royal exceptions. No invented `GG-ALD`/`GG-SRK` ISO codes are registered.
  The ISO map uses only JE, GG and IM; GG must not be interpreted as uniform
  coverage of everything geographically associated with that ISO code.
- `BANK` describes bank closure; `OFFICIAL` describes statutory holiday status.
  Neither establishes the library's `DAY_OFF` (work normally prohibited).
  Annual leave, pay and alternative leave benefits are separate employment rights.
  Good Friday and Christmas are customary general holidays in Jersey/Guernsey;
  they retain religious/traditional and bank status without automatically becoming
  statutory public holidays. IM designates them as bank holidays by order.
- Weekend originals remain as traditional entries; observed replacements carry
  `OFFICIAL | BANK | COMPENSATORY`. Liberation has **no automatic replacement**.
  Jersey's Sunday liberation anniversary is traditional only; Guernsey retains
  statutory status even on Sunday. Fixtures compare observed bank dates, omitting
  traditional weekend originals and Jersey's non-bank Sunday anniversary.
- Sundays, school terms, local office closures and employment-specific leave
  are outside these providers.

## Official sources

### Jersey

- [Public Holidays and Bank Holidays Act 2010](https://www.jerseylaw.je/laws/current/ro_96_2010):
  continuing rules, weekday New Year, Easter, May/August and Christmas replacements;
  Liberation on weekdays/Saturday only. Historical amendments are listed there:
  R&O.87/2019, 50/2021, 23/2022, 84/2022, 14/2023 and 36/2024.
- [Government annual dates](https://www.gov.je/Leisure/Events/WhatsOn/pages/bankholidaydates.aspx):
  direct annual-list comparison for **2025–2027**. The page lists 9 May 2027
  despite its Sunday status; the Act determines the type in this library.
- [2020 Council minutes](https://www.gov.je/Freedom%20of%20Information%20library/ID%20FOI%202019-06-12%20COM%20%28A%20%29%2020191112.pdf),
  [May 2020 operational notice](https://www.gov.je/News/2020/Pages/wastereopens.aspx):
  4 May retained, 8 May additionally designated; no borrowed GB cancellation.
- [2021 Gazette](https://www.gov.je/gazette/Pages/RecentEnactments.aspx):
  27 September 2021, Code of 1771 anniversary, once only.
- [2022 decision](https://www.gov.je/government/planningperformance/pages/ministerialdecisions.aspx?docid=5B19BA2C-49CB-4D78-A19C-B8E3C889FEBA):
  30 May moved to 2 June, additional 3 June; enactment recorded in the Act history.
- [2022 historical Act](https://www.jerseylaw.je/laws/current/ro_96_2010_20230213)
  confirms jubilee and funeral dates; [2023 historical Act](https://www.jerseylaw.je/laws/current/ro_96_2010_20240701)
  confirms 8 May coronation.
- [2024 historical Act](https://www.jerseylaw.je/laws/current/ro_96_2010_20241230):
  royal visit 15 July; spent provisions disappear from the current consolidation.

### Guernsey

- [Public Holidays Ordinance 2009, pp. 80–81](https://parliament.gg/parliamentary-business/billets/billet-i-2010):
  statutory recurring rules and explicit Christmas collision provisions.
- [2019 resolution](https://parliament.gg/parliamentary-business/billets/billet-xv-2019):
  replace 4 May 2020 by 8 May (unlike Jersey).
- [2022 and beyond](https://parliament.gg/parliamentary-business/assets/propositions/p2021-107),
  [2022 Ordinance](https://parliament.gg/parliamentary-business/propositions/p2022-3):
  June jubilee changes and retention of 9 May even at weekends.
- [Funeral regulations](https://parliament.gg/parliamentary-business/billets/billet-xx-2022):
  19 September 2022.
- [Coronation regulations register](https://parliament.gg/parliamentary-business/billets/billet-iv-2023),
  [Royal visit regulations register](https://parliament.gg/parliamentary-business/billets/billet-xiii-2024).
- [Jersey royal visit decision](https://www.gov.je/Government/PlanningPerformance/Pages/MinisterialDecisions.aspx?docid=DAAB30E3-0377-48E4-95CA-9B05C6FE4940)
  also identifies Guernsey's 16 July 2024 date.
- [Alderney visitor information](https://www.visitalderney.com/media/1525/alderney-brochure-2019.pdf)
  and [Alderney school](https://www.stannesschool.gg/page/?pid=89&title=Term+Dates)
  confirm the different August/Homecoming observances. School closure is not used
  to infer universal holiday status. Sark makes separate decisions: see its
  [July 2024 Hansard](https://img1.wsimg.com/blobby/go/5ff82f30-38d7-4ae5-98f1-e93b4ee65820/downloads/2024%2007%2003%20%20Hansard%20of%20Midsummer%20Chief%20Pleas.pdf).

### Isle of Man

- [Tynwald research PP 2024/0108, Bank Holidays](https://tynwald.org.im/index.php/spfile?file=%2Feducation%2Fresearch%2FDocuments%2F2024-PP-0108.pdf),
  pp. 4–8: continuing Bank Holidays Order 2022 rules, Tynwald weekend moves,
  TT on the second Friday after the last Monday in May; TT moved to 28 August
  2020 and 27 August 2021. Includes 2022 jubilee/funeral and 2023 coronation,
  and explicitly distinguishes bank holidays from employment leave entitlement.
- [2026 Treasury announcement](https://www.gov.im/categories/home-and-neighbourhood/news/?altTemplate=ViewCategorisedNews&id=193144).

## Verification limits and reuse

All 24 jurisdiction/year combinations have independent handwritten expectations.
This does **not** mean 24 official annual lists were retrieved. JE 2025–2027 has
annual-list evidence; other entries apply continuing legislation and the identified
exceptions. The current gov.gg holidaydates and gov.im bank-holidays pages returned
403 during this audit; archive attempts also failed. GG annual lists for 2020–2027
and IM complete annual lists remain an independent audit gap, especially future
one-off proclamations. The GG coronation register identifies the instrument but
not its full text. Do not describe this as a complete annual-schedule audit.
No additional anniversary holidays or unpublished future exceptions are inferred.

Only manually selected factual dates and original code/documentation are stored.
Official pages/PDFs remain subject to their publishers' copyright and site terms;
no blanket OGL, public-domain or MIT licence for those source documents is claimed.
Links and attribution are retained. Redistribution of full documents, logos or
substantial extracts requires a separate check of the respective site's terms.

Jersey legislation on JLIB's current/enacted/pending legislation pages is offered
under [OSAL-J v1.0](https://www.statesassembly.je/open-licence), as explained in
the [JLIB copyright policy](https://www.jerseylaw.je/Pages/Copyright.aspx).
Contains States Assembly information licensed under the Open States Assembly
Licence – Jersey v1.0. This does not extend to the Law Review or other publishers.
The licence requires attribution and does not license official logos or imply endorsement.
