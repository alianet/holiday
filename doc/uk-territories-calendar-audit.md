# Audyt kalendarzy wysp i terytoriów związanych z UK

Stan repozytorium i weryfikacji źródeł: **27 listopada 2026**. Ten dokument jest
planem prac, nie deklaracją obsługi. Zakresy lat poniżej oznaczają proponowane
pierwsze wydania; brak kompletnego źródła jest warunkiem wstrzymującym wydanie.

## Metoda i stan projektu

Przeczytano README, IMPLEMENTATION_PRIORITIES.md, IsoProviderMap, IsoResolver,
providery UnitedKingdom (England, Wales, Scotland, NorthernIreland), Guernsey
oraz audyty referencyjne GovUk i CrownDependencies. W katalogu projektu i jego
sprawdzonych katalogach nadrzędnych nie znaleziono AGENTS.md; katalogi .agents
oraz .codex nie zawierały plików instrukcji. Narzędzie prepare_context nie miało
podłączonego indeksu, dlatego wykonano bezpośredni odczyt wskazanych plików.

- **Żaden z obszarów AI, BM, VG, KY, FK, MS, PN, TC, SH (ani jego trzech części),
  GS, IO, GI nie ma providera ani wpisu ISO w obecnym kodzie.** Dotyczy to każdego
  wiersza inwentaryzacji poniżej. Nie występuje też lokalny provider wysp UK.
- GB jest kalendarzem Anglii i Walii, nie sumą jurysdykcji. GB-ENG/GB-WLS są
  jawnymi aliasami klas; GB-SCT i GB-NIR mają własne różnice. Pełne porównania
  rocznych list UK obejmują **2019–2028**, nie całą historię.
- JE, GG i IM są wdrożone osobno dla **2020–2027**. GG obejmuje tylko Guernsey,
  nie cały Bailiwick; Alderney i Sark nadal wymagają osobnych prac. Brakuje
  pełnych rocznych list GG/IM i pełnego aktu koronacyjnego GG. Zachować te luki
  i ograniczenia z [audytu dependencji](../tests/Reference/CrownDependencies/README.md).
  Dependencje Korony nie są terytoriami zamorskimi ani częściami UK.
- Resolver nieznanego `GB-…` wraca do GB, więc np. kod rady szkockiej może
  zwrócić Anglię i Walię. To nie dowód obsługi lokalnej. Nieznane AI itd. nie
  wracają do GB. Nowe kody wolno mapować dopiero z właściwym providerem.

## Status prawny i identyfikatory

Aktualna [lista FCDO z września 2026](https://www.gov.uk/csv-preview/6aa9384ff1f8d2a39605f801/FCDO_UK_Overseas_Territories_-_11_Sep_2026.csv)
potwierdza wszystkie wymienione niżej terytoria i ich kody dwuliterowe.
[Cabinet Office, Appendix H](https://www.gov.uk/government/publications/the-central-government-s-concept-of-operations/the-amber-book-appendices-html)
potwierdza ich odrębność konstytucyjną od UK i od siebie. Właściwą jurysdykcją
świąt jest dane terytorium i jego ustawodawca/gubernator, nie parlament lub
kalendarz bank holidays GB. Lista ma 14 pozycji: British Antarctic Territory
oraz Akrotiri i Dhekelia pozostają poza zakresem tego zlecenia.

| Obszar | ISO 3166-1 | Jurysdykcja do audytu świąt |
|---|---|---|
| Anguilla | AI | Anguilla; decyzje władz lokalnych i gubernatora |
| Bermudy | BM | Bermuda; Public Holidays Act 1947 i proklamacje |
| Brytyjskie Wyspy Dziewicze | VG | Virgin Islands (oficjalna nazwa); nie amerykańskie VI |
| Kajmany | KY | Cayman Islands, wspólne władze trzech wysp |
| Falklandy | FK | Falkland Islands; lokalne akty i Executive Council |
| Montserrat | MS | Montserrat; Public Holidays Act i SRO gubernatora |
| Pitcairn, Henderson, Ducie i Oeno | PN | Rząd Pitcairn i gubernator; nie Nowa Zelandia |
| Turks i Caicos | TC | Turks and Caicos Islands; miejscowa ustawa i decyzje |
| Święta Helena, Ascension, Tristan da Cunha | SH | Jedno terytorium, trzy odrębne części i reżimy lokalne |
| Georgia Południowa i Sandwich Południowy | GS | Osobne terytorium, Commissioner; nie FK |
| Brytyjskie Terytorium Oceanu Indyjskiego | IO | Administracja BIOT; wymaga ponownego sprawdzenia przed wdrożeniem |
| Gibraltar (osobno, poza grupą wysp) | GI | Gibraltar; lokalne bank/public holidays |

Dla SH istnieją rzeczywiste ISO 3166-2: **SH-HL, SH-AC, SH-TA**, potwierdzone
przez [ISO, newsletter II-3](https://www.iso.org/files/live/sites/isoorg/files/archive/pdf/en/iso_3166-2_newsletter_ii-3_2011-12-13.pdf).
Nie używać AC i TA jako zwykłych przydzielonych kodów ISO 3166-1. Nie tworzyć
fikcyjnych kodów dla mniejszych wysp. SH nie powinno automatycznie oznaczać
kalendarza Świętej Heleny: decyzję o ewentualnym wspólnym interfejsie zostawić
po audycie trzech części; suma ich świąt nie jest wspólnym dniem wolnym.

**BIOT:** [administracja](https://www.biot.gov.io/) nadal opisuje BIOT jako
terytorium zamorskie zarządzane z Londynu. [FCDO](https://www.gov.uk/world/organisations/british-indian-ocean-territory)
odsyła do umowy UK–Mauritius podpisanej 22 maja 2025. Sam podpis nie dowodzi
wejścia w życie przeniesienia suwerenności; w sprawdzonych źródłach nie
potwierdzono skutecznego transferu na datę audytu. Nie przepinać IO do Mauritiusa
ani nie obiecywać przyszłej jurysdykcji. Sprawdzić wejście w życie umowy, akty
wykonawcze oraz aktualny rejestr ISO ponownie przed jakimkolwiek wydaniem.
Opis administracji UK nie rozstrzyga sporów suwerenności dotyczących BIOT,
Falklandów, GS czy Gibraltaru; nie wyprowadzać kalendarza z roszczeń innego państwa.

## Inwentaryzacja źródeł i zakresów

„Nie znaleziono różnic” oznacza wynik tego audytu, nie dowód ich nieistnienia.
Roczniki to zidentyfikowane publikacje, nie zapewnienie kompletnej historii.
Public holiday, zamknięcie urzędu, prawo pracownika do świadczenia i zakaz pracy
są różnymi kategoriami. `DAY_OFF` wymaga osobnego uzasadnienia prawnego.

### Anguilla — AI

Odrębny kalendarz publiczny dla terytorium: m.in. Webster Day, Anguilla Day,
sierpniowe obchody i Heroes and Heroines. [Rządowa lista 2026](https://www.gov.ai/service/public-holidays-for-2026)
podaje faktyczne dni obchodów, w tym 18 grudnia. Nie zastępować jej uniwersalną
regułą następnego poniedziałku. Nie zidentyfikowano odrębnych list dla przybrzeżnych
wysepek; lista dotyczy terytorium. Pełny rocznik potwierdzony: **2026**.
Zakres średni: własne nazwy, przesunięcia, coroczne decyzje dotyczące monarchy;
utrzymanie coroczne, bez dziedziczenia UK. Przed typowaniem praw pracowniczych
pozyskać Public Holidays Act i właściwe przepisy zatrudnienia.

### Bermudy — BM

Odrębny kalendarz publiczny archipelagu, bez wykazanego podziału między wyspami.
[Gazette GN0334/2024](https://www.gov.bm/theofficialgazette/notices/gn0334/2024)
zawiera **2026 i 2027**, odniesienie do Public Holidays Act 1947 oraz grudniowe
zastępstwa. Własne obchody emancypacji, Mary Prince i Bermuda Day. Zakres
średni, utrzymanie niskie/średnie: reguły plus kontrola proklamacji raz w roku.
Zamknięcia szkół nie zastępują listy public holidays; dla pracowników zweryfikować
odrębnie ustawę zatrudnienia i wyjątki pracy w święto.

### Brytyjskie Wyspy Dziewicze — VG

[Cabinet, lista 2026](https://www.gov.vg/news/cabinet-approves-2026-public-holidays)
i [proklamacja SI 94/2025](https://laws.gov.vg/sites/default/files/2025-12/SI%20No%2094%20of%202025-Proclamation%20declaring%20Public%20Holidays%20in%202026.pdf)
na podstawie Public Holidays Act Cap.199 dotyczą całego terytorium. Rocznik
**2026** obejmuje jednorazowy 30 czerwca związany z Long Look — lokalne miejsce
wydarzenia nie oznacza ograniczenia święta do jednej wyspy. Nie stwierdzono
osobnych kalendarzy Tortoli, Virgin Gorda, Anegady czy Jost Van Dyke. Zakres
średni, utrzymanie średnie/wysokie: proklamacje, święta emancypacji i wyjątki;
nie używać kalendarza regulatora obejmującego także biuro w Hongkongu jako listy VG.

### Kajmany — KY

[Pełna lista rządu 2026](https://otp.gov.ky/web/govky/w/cayman-islands-2026-public-holidays)
oraz [portal kalendarza](https://gov.ky/public-holidays) określają obchody jako
„All Islands”: Grand Cayman, Cayman Brac i Little Cayman. Thanksgiving jest
obchodem, a nie public holiday. Własne Heroes, Emancipation, Discovery i Constitution
Day; nie kopiować GB. Potwierdzony rocznik **2026**. Zakres średni, utrzymanie
średnie: lokalne zmiany ustawy, urodziny monarchy i wyjątki. Formalny status i
uprawnienia pracownicze sprawdzić w aktualnym Public Holidays Act i Labour Act.

### Falklandy — FK

[Rządowa tabela 2026–2029](https://www.gov.fk/wp-content/uploads/2026/01/Falkland-Islands-Public-Holidays-2026-2029.pdf)
wyraźnie oddziela public holidays od dwóch dodatkowych dni zamknięcia administracji
(z wyjątkiem służb niezbędnych). Ani Thatcher Day, ani Falkland Day nie są tu
public holidays. Zakres obejmuje East/West Falkland i pozostałe wyspy; nie
stwierdzono podziału lokalnego. Własne Liberation, Battle i Peat Cutting Monday,
trzeci dzień świąteczny oraz kolizje grudniowe. Zakres średni, utrzymanie
niskie/średnie dzięki czteroletniej tabeli. Oddzielić typ administracyjny od
publicznego, zweryfikować podstawę ustawową; nie obejmować GS przez FK.

### Montserrat — MS

[Lista 2026](https://www.gov.ms/wp-content/uploads/2026/04/Public-Holidays-Montserrat-2026.pdf)
początkowo pozostawia King's Birthday nieustalone. [Rejestr SRO](https://www.gov.ms/government/legal-department/attorney-generals-chambers/sros/)
zawiera późniejsze SRO 22/2026 wskazujące 13 czerwca oraz proklamacje świąt
wielkanocnych i Whit Monday. Dostępne są też wpisy roczników **2022 i 2023** w
[archiwum ODG](https://www.gov.ms/publicationcategory/odg-publications/), których
kompletności tu nie audytowano. Publiczny kalendarz całej jurysdykcji, bez
wykazanych różnic wyspowych; St Patrick's Day, Prayer & Thanksgiving i Festival
Day wymagają lokalnych reguł. Koszt średni/wysoki: uzgadnianie tabeli z kolejnymi
proklamacjami i zasadami otwarcia sklepów; nie wystarczy wyliczenie Wielkanocy.

### Turks i Caicos — TC

Odrębny reżim Public Holidays Act. Oficjalny [rejestr aktów 2025](https://gov.tc/agc/component/content/article/2025-ordinance?Itemid=114&catid=9)
zawiera Amendment Act 16/2025; [Attorney General, sesja 2026](https://www.treasury.gov.tc/agc/latest-speeches/speech-by-the-hon-attorney-general-on-the-opening-of-the-supreme-court-of-the-turks-and-caicos-islands-for-the-2026-session)
potwierdza wprowadzenie Constitution Day. **Nie pozyskano pełnej urzędowej listy
2026 ani pełnego tekstu nowelizacji**: nie ustalać jeszcze dat i zasad Thanksgiving
na podstawie portali turystycznych. Nie wykazano odrębnych reżimów Turks/Caicos,
ale trzeba potwierdzić terytorialny zakres aktu i ewentualnych proklamacji.
Koszt wysoki na starcie, później średni: zmiana prawa na granicy 2025/2026,
święta lokalne, zastępstwa, King's Birthday. Najpierw zadanie źródłowe.

### Święta Helena — SH-HL

[Ogłoszenie 2026](https://www.sainthelena.gov.sh/app/uploads/2025/11/Public-Notice-Public-Holidays-2026.pdf)
odróżnia public i government holidays; np. New Year, Whit Monday i August Bank
Holiday oznacza jako government. [Gazette](https://www.sainthelena.gov.sh/gazette/)
indeksuje także **2027** (treści tego rocznika tu nie uzgodniono). [Commencement
Order 2024](https://www.sainthelena.gov.sh/app/uploads/gazette/LN-9-Public-Holidays-Commencement-Order-2024.pdf)
wskazuje zmianę prawa od 31 maja 2024. Osobny kalendarz wyspy, nie Ascension i
Tristanu; adresatem ogłoszenia zamknięcia są urzędy, status publiczny części dni
wymaga ustawy. Koszt średni/wysoki ze względu na typy i lokalne proklamacje.

### Wyspa Wniebowstąpienia — SH-AC

Własny [Public Holidays (Ascension) Ordinance](https://www.sainthelena.gov.sh/documents/Public-Holidays-Asc-Ordinance.pdf),
a nie lista SH-HL. [Protokół rady z 29 października 2025](https://www.ascension.gov.ac/wp-content/uploads/2025/11/Formal-AI-Council-Minutes-29-October-2025-Final-1.1.pdf)
dokumentuje decyzję o obchodzie Ascension Day 15 maja **2026**. To źródło decyzji,
nie pełna roczna lista: pozyskać ogłoszenie i instrument prawny. Kalendarz
publiczny wyspy; harmonogramy baz/pracodawców mogą się różnić i nie stają się
kalendarzem całej jurysdykcji. Koszt średni/wysoki, osobne zastępstwa świąteczne.

### Tristan da Cunha — SH-TA

[Konstytucja z 2009](https://www.legislation.gov.uk/uksi/2009/1751)
wyodrębnia administrację Tristanu, podobnie jak Ascension. [Oficjalny serwis
społeczności](https://www.tristandc.com/community.php) opisuje lokalne obchody,
ale nie dostarcza zweryfikowanej pełnej listy public holidays z rocznikiem.
**Brak potwierdzonego rocznika**. Nie przenosić SH-HL. Ustalić lokalne przepisy,
dni zamknięcia administracji i status zwyczajowych obchodów; osobno zbadać zakres
Gough i pozostałych wysp grupy (harmonogram stacji nie jest prawem powszechnym).
Koszt wysoki pozyskania dowodów; implementacja odroczona do potwierdzenia listy.

### Pitcairn — PN

[Portal rządu](https://www.government.pn/government/policies-annexes-and-forms)
indeksuje „Public Holidays & Commemoration Days”, plik oznaczony June 2024.
Próba pobrania PDF przez narzędzie web zakończyła się błędem przekierowania;
**nie potwierdzono treści ani pełnego rocznika**. Jest to dowód istnienia lokalnej
polityki, nie dowód wszystkich dat lub powszechnego zakazu pracy. Zbadać reguły
administracji i mieszkańców Pitcairn; Henderson, Ducie i Oeno nie powinny dostać
osobnych kalendarzy bez dowodu różnicy i odbiorcy. Koszt wysoki źródłowo, później
średni; nie kopiować świąt Nowej Zelandii ani GB.

### Georgia Południowa i Sandwich Południowy — GS

[Gazette 1/2026, s. 2](https://laws.gov.gs/wp-content/uploads/2026/01/SGSSI-Gazette-No-1-dated-27-January-2026.pdf)
zawiera deklarację Commissioner dla całego GS na podstawie s.73 Interpretation
and General Clauses Ordinance 1977. [Gazette 1/2025](https://laws.gov.gs/wp-content/uploads/2025/01/SGSSI-Gazette-No-1-dated-30-January-2025.pdf)
zawiera również rocznik **2025**. Potwierdzony odrębny kalendarz publiczny,
m.in. Possession, Liberation, Shackleton, Mid-winter i Environment Day; nie FK.
Nie stwierdzono osobnej listy South Sandwich. Adresat praktyczny: administracja,
operatorzy i personel stacji; harmonogram kontraktowy pracy wymaga osobnego
źródła. Koszt średni, coroczna deklaracja; priorytet ogranicza brak danych popytu,
a nie brak oficjalnego kalendarza.

### Brytyjskie Terytorium Oceanu Indyjskiego — IO

Źródła statusu podano wyżej. **Nie pozyskano urzędowego rocznika public holidays**
ani podstawy, by uznać zamknięcia biura w Londynie lub święta personelu bazy
Diego Garcia za jednolity kalendarz archipelagu. Istnienie i krąg adresatów
odrębnego kalendarza pozostają nieustalone. Rozdzielić administrację, wojsko,
kontraktorów i inne wyspy; zmiany statusu sprawdzić również pod kątem skutków
historycznych. Koszt wysoki i niepewny, zadanie badawcze bez rekomendacji
wydania providera na obecnych dowodach.

### Gibraltar — GI (osobno)

Terytorium zamorskie poza grupą wysp, własny kalendarz bank/public holidays.
[Listy 2026](https://www.gibraltar.gov.gi/press-releases/bank-holidays-2026-11299)
i [2027](https://www.gibraltar.gov.gi/press-releases/bank-and-public-holidays-2027-12410)
obejmują m.in. Winter Midterm, Workers' Memorial i National Day. **2027 jest
niekompletny: King's Birthday pozostaje do potwierdzenia**, a 2026 także ma
zastrzeżenie zmiany tej daty. Brak podziału wyspowego. Koszt średni i coroczny;
nie używać hiszpańskiego ani GB providera. Zakres pierwszego wydania ograniczyć
do 2026 po sprawdzeniu finalnego obwieszczenia; 2027 odblokować osobno.

## Wyspy będące częściami UK

[Banking and Financial Dealings Act 1971](https://www.legislation.gov.uk/ukpga/1971/80/section/1)
ustanawia poziomy England/Wales, Scotland i Northern Ireland. Sama wyspiarskość
nie tworzy dodatkowego kalendarza bankowego.

| Grupa | Obecna obsługa / ISO lokalne | Wniosek i oficjalne dowody |
|---|---|---|
| Isle of Wight, Scilly, Lindisfarne i pozostałe wyspy Anglii | GB-ENG; brak osobnych providerów; GB-IOW, GB-IOS to kody administracyjne | Nie wykazano osobnego powszechnego kalendarza. [Proklamacja 2026](https://privycouncil.independent.gov.uk/wp-content/uploads/2025/07/2025-07-09-Proclamation-England-Wales-and-Northern-Ireland-Bank-Holidays.pdf) dotyczy całej Anglii; [Scilly library](https://www.scilly.gov.uk/learning-leisure/welcome-st-marys-library) zamyka się w bank holidays, co nie dowodzi dodatkowych świąt. |
| Anglesey, Holy Island i pozostałe wyspy Walii | GB-WLS; GB-AGY dla rady Anglesey | Brak wykazanej potrzeby osobnego publicznego providera; [rada Anglesey](https://www.anglesey.gov.wales/en/Residents/Schools-and-learning/Schools/School-term-dates.aspx) publikuje daty szkół, nie odrębne powszechne dni wolne. |
| Orkady | GB-SCT; GB-ORK nie jest obecnie mapowany lokalnie | [Rada](https://www.orkney.gov.uk/a-to-z/?AccessLetter=P) opisuje własne zamknięcia; [kalendarz](https://www.orkney.gov.uk/meetings-and-events/) oddziela public holidays od szkół. Potrzebny audyt kalendarza rady 2026–2027, nie zmiana GB-SCT. |
| Szetlandy | GB-SCT; GB-ZET bez lokalnego providera | [Decyzja rady 25.02.2026](https://www.shetland.gov.uk/downloads/file/13282/25-february-2026-sic-decision-note) odmawia dodatkowego bank holiday z uwagi na koszty i promy. [Zamknięcie świąteczne urzędów](https://www.shetland.gov.uk/festive-arrangements) jest kalendarzem usług/pracowników, nie całej ludności. Wykazana potrzeba oddzielnej analizy lokalnej. |
| Hebrydy Zewnętrzne, Lewis/Harris, Uist, Barra | GB-SCT; GB-ELS administracyjne | [Rada, szkoły 2026–2027](https://www.cne-siar.gov.uk/education-and-learning/schools/school-holiday-and-term-dates) nie wystarcza do ustalenia public holidays. Potrzebne źródła pracownicze i lokalne, bez założenia jednolitości archipelagu. |
| Skye, Hebrydy Wewnętrzne, Arran, Bute, inne wyspy Szkocji | GB-SCT; rady obejmują także inne obszary | [Rząd Szkocji](https://www.gov.scot/publications/bank-holidays/) rozróżnia lokalne public holidays. Zbadać miejscowości i sektor, nie budować jednego providera wszystkich wysp ani utożsamiać rady z wyspą. |
| Rathlin i inne wyspy Irlandii Północnej | GB-NIR | [nidirect](https://www.nidirect.gov.uk/articles/bank-holidays) kieruje do wspólnego kalendarza NI; nie wykazano osobnego lokalnego reżimu. IE pozostaje niezależną Irlandią. |

Kody rad nie są dowodem identycznego zasięgu świąt. Dla lokalnych providerów
wymagane są aktualne sprawdzenie ISO i jawna nazwa klasy; obecny fallback GB
nie zastępuje GB-SCT. Nie dodawać aliasów wyspiarskich, dopóki jedyną różnicą
są szkolne wakacje lub godziny otwarcia pojedynczej instytucji.

## Kolejka wykonalnych zadań

Nie znaleziono porównywalnych danych o zapotrzebowaniu na te kalendarze wśród
użytkowników biblioteki. Popyt ma status **niezmierzony dla każdego terytorium**;
nie użyto założenia popularności PHP ani liczby turystów jako jego zamiennika.
Kolejność robocza preferuje pełne źródła, ograniczony koszt i domknięcie znanych
luk. Potwierdzone zgłoszenie z wymaganymi latami może przesunąć zadanie wyżej.
Przed planowaniem wydania zebrać zgłoszenia/integracje i oczekiwany sektor.

Wspólne kryteria ukończenia **K** każdego wdrożenia: lokalna podstawa prawna i
kompletna lista każdego deklarowanego roku; ręcznie przygotowane oczekiwania
niepochodzące z providera; porównanie pełnych list dat/nazw/liczności, testy typów,
weekendów, kolizji i wyjątków; odrzucanie lat spoza zakresu; właściwy ISO (jeżeli
obejmuje zadeklarowany zakres), tłumaczenia, README, opis źródeł i procedura
corocznej kontroli proklamacji. Nie kopiować automatycznie wyjątków królewskich
UK ani `DAY_OFF`. Dla brakujących układów weekendu użyć testu reguły wynikającej
z ustawy, bez deklarowania dodatkowych niezweryfikowanych roczników.

| Kolejność / ID | Osobne zadanie i lata pierwszego wydania | Dodatkowe kryterium ukończenia / zależność | Koszt wdrożenia / utrzymania |
|---|---|---|---|
| 1 OT-BM | Bermuda 2026–2027 | K; Act 1947, oba pełne roczniki, kolizja 2027 | średni / niski–średni |
| 2 OT-KY | Cayman Islands 2026 | K; potwierdzić All Islands, wyłączyć Thanksgiving z public holidays | średni / średni |
| 3 OT-AI | Anguilla 2026 | K; wyjaśnić grudniowe przesunięcie na piątek w lokalnym prawie | średni / średni |
| 4 OT-FK | Falkland Islands 2026–2029 | K; oddzielić dwa dni administracji, brak Falkland Day, test grudniowych kolizji | średni / niski–średni |
| 5 OT-GI | Gibraltar 2026; 2027 jako kolejne rozszerzenie | K; finalna data monarchy dla każdego roku przed jego wydaniem | średni / średni |
| 6 OT-VG | Virgin Islands 2026 | K; SI 94/2025 i jednorazowy 30 czerwca, bez przenoszenia do innych lat | średni / średni–wysoki |
| 7 OT-MS | Montserrat 2026 | K; uzgodnić SRO 22 i pozostałe proklamacje z tabelą, status soboty i zastępstw | średni / średni–wysoki |
| 8 OT-HL | St Helena 2026 | K; SH-HL, osobne government holidays, aktualna ustawa po zmianie 2024; bez mapowania całego SH | średni / średni–wysoki |
| 9 OT-AC | Ascension 2026 | K; pozyskać pełną listę i akt wykonujący decyzję Ascension Day, SH-AC | średni–wysoki / średni |
| 10 OT-GS | South Georgia and South Sandwich Islands 2025–2026 | K; dwie deklaracje Commissioner, własne nazwy i zakres personelu, niezależność od FK | średni / średni |
| 11 OT-TC | Turks i Caicos, docelowo 2026 | Najpierw pełny tekst Act 16/2025 i lista/proklamacje 2026; dopiero potem K i provider TC | wysoki / średni |
| 12 OT-PN | Pitcairn, docelowo 2026 | Najpierw dostępna polityka June 2024, jej aktualność, adresaci i wyjątki 2026; potem K | wysoki źródłowo / średni |
| 13 OT-TA | Tristan, docelowo 2026 | Najpierw prawo i kompletna lista potwierdzona przez administrację, zasięg grupy; potem K, SH-TA | wysoki źródłowo / nieustalony |
| 14 OT-IO | BIOT: badanie 2026, bez wdrożenia na obecnych dowodach | Raport o wejściu w życie umowy i ISO, źródłach dat, odbiorcach i różnicach baz/wysp; decyzja wdrożyć albo jawnie odroczyć z uzasadnieniem | wysoki / nieustalony |

Osobna ścieżka lokalna UK (nie blokuje OT-BM):

- **UK-ISL-ORK**, audyt **2026–2027** i ewentualny provider rady Orkadów:
  pozyskać obie pełne listy, ustalić sektory i zasięg wysp, porównać z GB-SCT;
  ukończenie: udokumentowana różnica, K dla wdrażanych lat albo uzasadniona
  decyzja o braku nowego publicznego providera.
- **UK-ISL-ZET**, audyt **2026–2027** Szetlandów: jak wyżej, dodatkowo uchwała
  dotycząca 15 czerwca 2026 i zakres jej adresatów; kalendarz rady oznaczyć jako
  taki, bez zmiany krajowych bank holidays.
- **UK-ISL-ELS**, audyt **2026–2027** Hebrydów Zewnętrznych: uzyskać źródła inne
  niż szkolne, sprawdzić różnice między wyspami/miejscowościami; ukończenie jak
  ORK, z jawnym identyfikatorem każdego faktycznego zakresu.
- **UK-ISL-OTHER**, inwentaryzacja **2026–2027** Skye, Mull, Islay, Arran i Bute:
  tabela właściwych rad/miejscowości i źródeł public/trade holidays, lista różnic
  i osobne zadania dopiero po ustaleniu odbiorcy. Bez zbiorczego providera wysp.

Domknięcie GG/IM i osobne audyty Alderney/Sark pozostają przed nową ścieżką;
nie powtarza się tutaj ich implementacji ani nie poszerza zakresu GG.

## Utrzymanie i granice audytu

Każde zadanie wymaga ponownej weryfikacji przed kodowaniem: strony roczne są
zmienne, niektóre źródła nadal mają daty warunkowe. Roczna kontrola po publikacji
harmonogramu oraz po nadzwyczajnych proklamacjach, przegląd diffu i aktualizacja
metadanych są częścią kosztu. Nie deklarować przyszłych lat na podstawie reguł GB.

Ten audyt nie obejmuje pełnego przeglądu prawa pracy wszystkich terytoriów,
archiwum każdej gazety ani potwierdzenia braku każdej możliwej lokalnej różnicy.
Jawne luki TC/PN/TA/IO i niepełne listy AC/GI stanowią zadania źródłowe, a nie
kalendarze gotowe do publikacji. W repo zapisywać własne opracowanie faktów,
adresy i daty kontroli; nie zakładać wspólnej licencji dla wszystkich urzędowych
PDF i nie kopiować ich pełnych treści bez sprawdzenia warunków.
