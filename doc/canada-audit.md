# Canadian calendar audit

Reviewed 2026-11-27. The current provincial/territorial general-holiday dates were
checked against official employment-standards sources. Tests explicitly cover all
13 jurisdictions in 2026, selected historical transitions, and the federal sector.
This is not a comprehensive historical or public-service calendar audit.

## Calendar contracts

`CA` retains shared dates and marks Thanksgiving, Remembrance Day and (since 2021)
Truth and Reconciliation as sector-dependent. Subclasses promote entries to general
holidays where provincial/territorial standards apply. Existing optional entries
remain partial; filtering out `PARTIAL_ONLY` gives the general calendar, except
Quebec's Good Friday/Easter Monday choice, which requires employer input.

`Provider\Canada\Federal` represents general holidays under the Canada Labour Code.
It includes Victoria Day and Boxing Day and promotes the three sector-dependent
entries. It excludes days of mourning, which are not Labour Code general holidays.
Federal public-service agreements, Easter Monday and employer-specific leave are
outside this provider. No invented ISO code is assigned to this employment sector.

## Findings and sources

| Jurisdiction | Audit result and official source |
| --- | --- |
| Federal / CA | [Labour Code general holidays](https://www.canada.ca/en/services/jobs/workplace/federal-labour-standards/vacations-holidays.html): distinguish ten federal-sector dates from nationwide coverage. [2022 mourning notice](https://www.canada.ca/en/treasury-board-secretariat/services/information-notice/day-mourning-canada-honour-majesty-queen-elizabeth-ii.html): September 19, not September 22. |
| AB | [General holidays](https://www.alberta.ca/alberta-general-holidays): nine general holidays; retain Boxing Day and September 30 as partial entries. |
| BC | Family Day missing: [introduced on the second Monday in 2013](https://news.gov.bc.ca/releases/2012PREM0073-000755), [third Monday from 2019](https://news.gov.bc.ca/releases/2018PREM0018-000188). [September 30 is general from 2023](https://www.bclaws.gov.bc.ca/civix/document/id/hstats/hstats/468174704). |
| MB | [General holidays](https://gov.mb.ca/labour/standards/doc%2Cgen-holidays-after-april-30-07%2Cfactsheet.html), [first statutory Orange Shirt Day in 2024](https://news.gov.mb.ca/news/index.html?item=65301&posted=2024-09-30): remove partial status from 2024. Remembrance Day is governed separately and remains outside this provider's general-day list. |
| NB | [Eight paid holidays](https://www.gnb.ca/en/topic/jobs-workplaces/labour-market-workforce/employment-standards/holiday-vacation.html); [days of rest](https://www2.gnb.ca/content/gnb/en/departments/elg/local_government/content/governance/content/days_of_rest_act/faq.html) distinguish shop closures from paid leave for Victoria Day, Thanksgiving and Boxing Day. |
| NL | [Labour Standards Act, section 14](https://www.assembly.nl.ca/Legislation/sr/statutes/l02.htm/en-en/): six general dates. July 1 carries both Memorial Day and Canada Day identities, but counts as one date. [Public-service holidays](https://www.gov.nl.ca/exec/tbs/policies/working-hours/holidays/) have a broader scope. |
| NT | [Employment standards](https://www.ece.gov.nt.ca/en/services/employment-standards/frequently-asked-questions): eleven dates; [September 30 from 2022](https://www.gov.nt.ca/en/newsroom/national-day-truth-and-reconciliation-declared-statutory-holiday-northwest-territories). Easter Monday retains its government-only classification. |
| NS | [Holiday pay](https://novascotia.ca/lae/employmentrights/holidaypay.asp): six general dates. Remembrance Day has separate legislation; retained as partial, as is Thanksgiving. |
| NU | [Nunavut Day became general in 2020](https://www.gov.nu.ca/sites/default/files/media/imp/2020-06_psa_jus_nunavut_day_general_holiday_-_eng.pdf); [2022 amendment adds September 30](https://www.nunavutlegislation.ca/en/file-download/download/public/7172). [Employment standards](https://www.gov.nu.ca/en/employment-training-and-career-development/labour-and-employment-standards-nunavut) confirm that August Civic Holiday is a general holiday. [Public-service calendar](https://www.gov.nu.ca/en/staff-resources/public-service-holidays) is broader than general employment standards. |
| ON | [Public holidays](https://www.ontario.ca/document/your-guide-employment-standards-act-0/public-holidays): nine dates; Victoria Day is general, so remove partial status. |
| PE | [Paid holidays](https://www.princeedwardisland.ca/en/information/workforce-and-advanced-learning/paid-holidays): eight dates. Correct Islander Day's identity; retain its second-Monday date in 2009 and third-Monday dates subsequently. |
| QC | [CNESST holiday rules](https://www.services.cnt.gouv.qc.ca/bulletin_paye/en/aide/_Template.jsp?aide=21): Good Friday or Easter Monday, and paid May/June holidays. Both Easter alternatives now have consistent partial flags; Patriots' Day and June 24 gain `DAY_OFF`. |
| SK | [Ten public holidays](https://www.saskatchewan.ca/business/employment-standards/public-statutory-holidays/list-of-saskatchewan-public-holidays): September 30 remains partial. Sunday shifts for some dates depend on whether the establishment normally closes on Sundays. |
| YT | [Statutory holidays](https://yukon.ca/en/find-yukon-statutory-holiday/): eleven dates; [September 30 general from 2023](https://open.yukon.ca/information/2c8404aa-b40c-489e-837c-fd89be5f2c0d/resource/39ad5ebf-8436-34d0-b60c-d864556965ca/download/cs-2023-spring-sessionbrief.pdf). Boxing Day remains partial, outside the general list. |

## Substitute days and remaining work

The [Holidays Act](https://laws-lois.justice.gc.ca/eng/acts/H-5/FullText.html)
makes July 2 the legal Canada Day when July 1 is Sunday. The calculator adds a
compensatory entry alongside the original date. No Saturday replacement is implied.
Direct `calculateHolidaysForYear()` calls return original dates; use `HolidayCalculator`
to apply compensatory strategies, as with other countries.

A universal next-Monday rule would misrepresent employment standards. Federal rules
allow a preceding or following working day; Ontario allows a scheduled substitute
within a statutory period or an agreed payment alternative. Yukon uses the next
working day, which cannot be inferred without a work schedule. These substitutes
are deliberately left to the caller. Quebec's conditional June 24/25 observance
and Saskatchewan's establishment-dependent Sunday rules also need a richer policy.

Further work: explicit work-schedule/sector policies for these substitutions,
complete provincial public-service calendars, retail closure rules (including
Manitoba/NS Remembrance Day), older holiday introduction dates and one-off provincial
mourning entitlements. The 1952 mourning entry and pre-modern rules remain inherited
behavior, not newly verified history. Azure's existing shared-identity checks do not
establish the completeness of any provincial calendar.
