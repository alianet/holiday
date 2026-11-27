# Priorytety rozwoju kalendarzy

Stan planu: 27 listopada 2026 r. Punktem odniesienia jest zakres opisany w README
oraz istniejące providery i mapowania ISO.

Plan obejmuje nowe kraje, odrębne terytoria, kalendarze religijne oraz uzupełnianie
istniejących kalendarzy. Kolejność uwzględnia udokumentowane zapotrzebowanie, domykanie obecnej obsługi,
dostępność oficjalnych źródeł i koszt utrzymania. Gdy brak danych o popycie,
pozostaje on niezmierzony; nie wnioskujemy o popularności PHP z geografii.
Jest to robocza rekomendacja produktowa, nie statystyczny ranking.

## Kolejność implementacji

| Kolejność | Kraj lub terytorium | Rodzaj prac | Zakres pierwszego wdrożenia i uzasadnienie |
|---|---|---|---|
| 1 | Dependencje Korony — dalszy audyt | Częściowo wdrożone | JE, GG (tylko wyspa Guernsey), IM: osobne providery i ISO, reguły 2020–2027 i znane wyjątki. Pozyskać brakujące roczne listy GG/IM oraz pełny tekst koronacyjnego aktu GG; osobno opracować Alderney i Sark bez wymyślonych kodów ISO. Granice dowodów: `tests/Reference/CrownDependencies/README.md`. |
| 2 | Japonia (`JP`) | Nowy kraj | Święta ogólnokrajowe, dni zastępcze i wyjątki roczne. Najsilniej uzasadniona danymi PHP luka w obecnej liście krajów. |
| 3 | Hiszpania — Wyspy Kanaryjskie i Baleary | Uzupełnienie regionów | Dodać kalendarze poszczególnych wysp. Istniejące providery obejmują poziom wspólnot autonomicznych, a README jawnie wyklucza święta wysp. |
| 4 | Brazylia (`BR`) | Audyt i rozbudowa | Sprawdzić istniejące pokrycie; następnie uzupełnić brakujące stany oraz rozróżnienie świąt i dni fakultatywnych. Duży potencjalny zasięg. |
| 5 | Kanada (`CA`) | Audyt i poprawki | Zweryfikować prowincje, terytoria, dni zastępcze i różnice sektorowe. README wprost sygnalizuje niedoskonałości obsługi. |
| 6 | Indie (`IN`) | Nowy kraj, etapami | Najpierw wspólna baza, następnie stany. Wyraźnie dokumentować częściowe pokrycie; sam poziom ogólnokrajowy nie wystarczy do pełnego kalendarza regionalnego. |
| 7 | Chiny (`CN`) | Nowy kraj | Uwzględnić roczne harmonogramy dni wolnych i powiązane pracujące weekendy. Przed wdrożeniem ustalić sposób reprezentowania tych wyjątków w bibliotece. |
| 8 | Indonezja (`ID`) | Nowy kraj | Święta ogólnokrajowe oraz osobne oznaczenie dodatkowych dni wolnych ogłaszanych na dany rok. |
| 9 | Turcja (`TR`) | Uzupełnienie kraju | Uzupełnić święta islamskie, których brak README wymienia wprost. |
| 10 | Australia (`AU`) | Audyt i rozbudowa | Sprawdzić kompletność stanów, terytoriów i dni zastępczych; wdrażać wykryte braki. Sam wpis kraju w README nie potwierdza kompletności regionalnej. |
| 11 | Szkocja (`GB-SCT`) | Uzupełnienie lokalne | Ustalić zakres miejscowości i dodać wybrane kalendarze lokalne. README obecnie wyklucza święta lokalne. |
| 12 | Pozostałe terytoria francuskie | Audyt i nowe kalendarze | Zinwentaryzować brakujące terytoria i ich mapowania. Gwadelupa, Martynika, Reunion, Majotta, Saint-Barthélemy i Saint-Martin mają już providery. |
| 13 | Wyspy Owcze (`FO`), Wyspy Alandzkie (`AX`) | Nowe kalendarze | Rozszerzenie obecnej obsługi Danii i Finlandii. Grenlandia ma już osobny provider. |
| 14 | Filipiny (`PH`), Wietnam (`VN`), Malezja (`MY`) | Nowe kraje | Kolejna fala rozszerzenia Azji. Kolejność w grupie ustalić według zgłoszeń użytkowników i dostępności wiarygodnych źródeł. |
| 15 | Argentyna (`AR`), Kolumbia (`CO`), Chile (`CL`) | Nowe kraje | Rozszerzenie Ameryki Łacińskiej po dopracowaniu Brazylii. Kolejność w grupie pozostaje do ustalenia. |
| 16 | Brytyjskie terytoria zamorskie — osobna kolejka OT | Audyt zakończony, wdrożenia zaplanowane | Kolejno BM, KY, AI, FK, GI, VG, MS, SH-HL, SH-AC, GS, następnie zadania źródłowe TC, PN, SH-TA, IO. Szczegóły, lata i warunki poniżej; brak providerów i mapowań nie uległ zmianie. |

Pozycje obejmujące grupę terytoriów można dzielić na osobne wdrożenia.
Kolejność należy aktualizować po audycie zakresu prac oraz na podstawie zgłoszeń
użytkowników. Wysoki priorytet dependencji Korony wynika z domknięcia istniejącej
obsługi, a nie z udokumentowanej liczebności lokalnej społeczności PHP.

## Priorytety kalendarzy religijnych

Obecnie istnieją `ChristianHolidays` i `ChristianOrthodoxHolidays` oraz powiązane
traity. Poniższa kolejność jest osobną ścieżką rozwoju, powiązaną z wdrażaniem
krajów. Proponowane nazwy providerów i zakresy wymagają doprecyzowania przed
implementacją; nie oznaczają pełnego pokrycia wszystkich tradycji danej religii.

| Priorytet | Religia | Proponowany provider | Początkowy zakres i uzasadnienie |
|---|---|---|---|
| R1 | Islam | `IslamicHolidays` | Eid al-Fitr, Eid al-Adha, początek ramadanu, islamski Nowy Rok, Aszura, Mawlid. Najpierw wspólne mechanizmy datowania i integracja z Turcją; następnie wykorzystanie przy wdrażaniu Indonezji i Malezji. |
| R2 | Judaizm | `JewishHolidays` | Rosz ha-Szana, Jom Kipur, Pesach, Szawuot, Sukot, Szemini Aceret, Simchat Tora, Chanuka, Purim. Uwzględnić warianty Izraela i diaspory. Przydatny również do planowania pracy niezależnie od kalendarza kraju. |
| R3 | Hinduizm | `HinduHolidays` | Diwali, Holi, Mahaśiwaratri, Janmasztami, Nawaratri/Dasara, Ganeśa Ćaturthi. Rozwijać razem z Indiami; od początku określić wariant regionalny i reguły datowania. |
| R4 | Buddyzm | `BuddhistHolidays` z wariantami tradycji | Vesak, Magha Puja, Asalha Puja, Bodhi. Wybierać zakres zgodnie z obsługiwanymi krajami i tradycjami, bez zakładania jednego uniwersalnego kalendarza. |
| R5 | Sikhizm | `SikhHolidays` | Narodziny Guru Nanaka, Vaisakhi, Bandi Chhor Divas, wybrane pozostałe gurpuraby. Kolejny etap obsługi Indii i potrzeb diaspory. |
| R6 | Dżinizm | `JainHolidays` | Mahavir Jayanti, Paryushana, Samvatsari, Diwali. Jawnie określić obsługiwane tradycje i wielodniowe obchody. |
| R7 | Wiara bahaicka | `BahaiHolidays` | Naw-Rúz, Ridván, narodziny Bába i Bahá’u’lláha oraz pozostałe główne święta. Dalsze rozszerzenie zakresu międzyreligijnego. |

### Powiązania z planem krajów

- R1 rozpocząć przed uzupełnieniem Turcji i wdrożeniem Indonezji. Wspólny
  mechanizm nie zastępuje oficjalnych harmonogramów właściwych dla danego kraju.
- R2 realizować jako niezależny etap po R1; obsługa judaizmu nie wymaga
  jednoczesnego wdrożenia kalendarza państwowego Izraela.
- R3 realizować razem z pierwszym etapem Indii. R5 i R6 rozwijać przy dalszym
  poszerzaniu zakresu tego kraju.
- R4 wiązać z konkretnymi potrzebami kolejnych krajów azjatyckich. Kalendarz
  religijny nie jest warunkiem wdrożenia całego kalendarza państwowego Japonii.

### Założenia implementacyjne

- Rozdzielić wyznaczanie dat religijnych od ich statusu prawnego. Provider
  religijny oznacza charakter religijny; provider kraju lub regionu określa,
  czy święto jest oficjalne i czy jest dniem wolnym. Nie nadawać automatycznie
  `DAY_OFF` wszystkim świętom religijnym.
- Dla islamu jawnie wybrać metodę obliczeniową lub źródło dat. Kalendarz
  tablicowy nie gwarantuje zgodności z lokalną obserwacją Księżyca ani z datami
  ogłoszonymi przez władze. Rozróżniać daty obliczone i potwierdzone.
- Dla judaizmu określić wariant Izrael/diaspora, długość obchodów oraz konwencję
  dat dziennych dla świąt zaczynających się poprzedniego wieczoru.
- Dla hinduizmu, buddyzmu, sikhizmu i dżinizmu dokumentować tradycję, region
  i przyjęty kalendarz; tam, gdzie obliczenia tego wymagają, również lokalizację
  i strefę czasową.
- Określić sposób reprezentacji obchodów wielodniowych i okresów takich jak
  ramadan. Początek okresu nie oznacza, że wszystkie jego dni są świętami wolnymi
  od pracy.
- Współdzielić mechanizmy datowania tam, gdzie reguły są wspólne. Zachować
  odrębne znaczenia obchodów: np. Diwali występuje w kilku religiach.
- API nadal powinno przyjmować rok gregoriański i zwracać daty przypadające
  w tym roku. Uwzględnić możliwość kilku wystąpień tego samego święta w jednym
  roku gregoriańskim w kalendarzach księżycowych.
- Udokumentować wspierane lata i źródła; nie deklarować zakresu 1–9999,
  jeśli użyte tablice lub algorytmy go nie zapewniają. Dodać testy konwersji,
  granic roku, wariantów tradycji i porównania z niezależnymi źródłami.

### Źródła do analizy kalendarzy religijnych

- [US Naval Observatory — The Islamic Calendar](https://aa.usno.navy.mil/faq/islamic):
  różnica między obserwacją Księżyca a kalendarzem tablicowym.
- [Manitoba Education — Judaism: Times and Dates](https://www.edu.gov.mb.ca/k12/docs/support/world_religions/judaism/times-dates.pdf):
  kalendarz hebrajski i początek dnia o zachodzie słońca.
- [Stanford — Religious Calendar](https://orsl.stanford.edu/religious-calendar)
  oraz [Case Western Reserve University — Religious Holidays & Observances](https://case.edu/studentlife/dean/religious-holidays-observances-calendar):
  przykłady zakresu obchodów, tradycji i różnic w datach. Zestawienia są punktem
  wyjścia; implementacja wymaga weryfikacji reguł dla wybranego wariantu.

## Utrzymanie równoległe

- USA, Niemcy, Francja, Wielka Brytania i Rosja: aktualność istniejących reguł,
  wyjątki roczne oraz niezależna weryfikacja. Nowe regiony dodawać po wykazaniu luki.
- Hiszpania: aktualizować opublikowane kalendarze regionalne; obecny zakres README
  to lata 2008–2027. Nie ekstrapolować decyzji regionalnych na nieopublikowane lata.
- Azory i Madera: istnieją osobne providery i mapowania ISO; audytować kompletność.
  Święta miejskie pozostają poza zakresem deklarowanym w README.
- Wielka Brytania: jawne GB-ENG/England i GB-WLS/Wales wdrożone; GB/UnitedKingdom zachowuje kalendarz Anglii i Walii. GB-SCT obejmuje bank holidays całej Szkocji, GB-NIR Irlandię Północną. IE oznacza niezależną Irlandię. Audyt i granice lat: `tests/Reference/GovUk/README.md` i `tests/Reference/Ireland/README.md`.

## Warunki ukończenia kalendarza

- Określony zakres: kraj, region, wyspa lub miejscowość, kategorie świąt i lata.
- Oficjalne źródła reguł i wyjątków, z odnotowaną datą weryfikacji.
- Provider i odpowiednie mapowania ISO, jeśli istnieją właściwe kody.
- Poprawne oznaczenia typów świąt i reguły dni zastępczych.
- Testy obejmujące wyjątki oraz porównania z niezależnymi danymi referencyjnymi.
- Aktualizacja README: zakres obsługi, ograniczenia i sposób aktualizacji.

## Podstawa ustalenia priorytetów

- [README](README.md) i `src/Resolver/IsoProviderMap.php`: obecne pokrycie
  biblioteki oraz jawnie opisane ograniczenia. Brak szczegółów w README nie jest
  sam w sobie dowodem braku implementacji; pozycje oznaczone jako audyt wymagają
  sprawdzenia kodu przed ustaleniem konkretnych braków.
- [JetBrains — State of PHP 2025](https://blog.jetbrains.com/phpstorm/2025/10/state-of-php-2025/):
  1720 respondentów wskazujących PHP jako główny język; największe grupy pochodziły
  z Japonii, USA, Rosji, Chin i Francji. Badanie może być obciążone nadreprezentacją
  użytkowników produktów JetBrains; nie jest spisem światowej społeczności PHP.
- [GitHub — Octoverse 2026](https://github.blog/news-insights/octoverse/octoverse-commits-pushes-reach-new-highs-in-2026-as-developers-build-with-agents/):
  skala społeczności programistycznych, szczególnie Indii i Indonezji, jako
  pomocniczy wskaźnik potencjalnego zasięgu. Liczba użytkowników GitHub nie jest
  liczbą programistów PHP.

Pozycje dalszych fal są propozycjami rozwoju, a ich dokładna kolejność nie wynika
z porównywalnych danych o liczbie programistów PHP w każdym z tych krajów.

## Lokalne święta Szkocji — osobne zadania

Inwentaryzacja 27.11.2026: [mygov.scot](https://www.mygov.scot/scotland-bank-holidays)
i [gov.scot](https://www.gov.scot/publications/bank-holidays/) rozróżniają
jednolity kalendarz bank holidays od lokalnych decyzji rad. Nie dodawać poniższych
dat do GB-SCT. To początkowy rejestr potwierdzonych luk, nie pełny spis Szkocji.

- SCT-LOCAL-1: osobny kalendarz [Edynburga](https://www.edinburgh.gov.uk/council-democracy/public-holidays/1).
  W 2026 poza GB-SCT: 6 kwietnia (Easter Monday), 20 kwietnia (Spring),
  18 maja (Victoria Day), 21 września. Rada podaje też początek Trades Holidays
  4 lipca; przed implementacją ustalić znaczenie i długość, nie traktować
  automatycznie jako pojedynczego dnia wolnego.
- SCT-LOCAL-2: osobny kalendarz [Inverness](https://www.highland.gov.uk/contact-us/inverness-local-trade-holidays/2).
  Lokalne daty 2026 poza GB-SCT: 2 lutego, 2 marca, 6 kwietnia, 6 lipca,
  5 października i 2 listopada. Nie rozszerzać na cały Highland Council.
- SCT-LOCAL-3: zinwentaryzować pozostałe rady i miejscowości, zaczynając od
  Glasgow, Aberdeen i Dundee. Pozyskać oficjalne roczne listy i określić zasięg
  terytorialny oraz sektorowy. Osobno ustalić identyfikatory (ISO rady może być
  zbyt szerokie), zakres lat i niezależne fixture’y; nie utożsamiać kalendarza
  urzędu, szkoły lub NHS z public holidays całej miejscowości.
- UK-HISTORY: rozszerzyć niezależny audyt przed 2019, w szczególności początki
  New Year/2 January/Boxing Day oraz historyczne proklamacje. Obecne poprawki
  historyczne nie stanowią pełnej gwarancji pokrycia tych lat.


## Terytoria UK i wyspy lokalne — konkretna kolejka po audycie

[Audyt z 27.11.2026](doc/uk-territories-calendar-audit.md) zawiera stan kodu,
status jurysdykcji, oficjalne źródła, dostępne roczniki i koszty utrzymania.
Popyt dla każdego z poniższych obszarów jest **niezmierzony**. Kolejność wynika
obecnie z jakości źródeł i kosztu; zgłoszenia użytkowników z określonym sektorem
i latami mogą ją zmienić. Pozycja 16 w ogólnej kolejce oznacza tę ścieżkę,
nie zbiorcze wdrożenie wszystkich terytoriów. Domknięcie GG/IM i audyty
Alderney/Sark z pozycji 1 pozostają osobnymi pracami.

Każdy wiersz to osobne zadanie. Warunki **K** z audytu są obowiązkowe:
kompletne źródła dla deklarowanych lat i lokalna podstawa prawna, niezależne
oczekiwania roczne, testy dat/typów/zastępstw/wyjątków, właściwe ISO, ograniczenie
lat, tłumaczenia, README oraz procedura aktualizacji. Nie dziedziczyć kalendarza
GB ani automatycznych praw do dnia wolnego. Plan nie deklaruje nowej obsługi.

| Kolejność | Zadanie | Lata pierwszego wdrożenia | Szczególne kryterium ukończenia poza K |
|---|---|---|---|
| OT-1 | OT-BM — Bermudy | 2026–2027 | Uzgodnienie Act 1947 z GN0334/2024, oba roczniki i kolizje grudnia 2027. |
| OT-2 | OT-KY — Kajmany | 2026 | Potwierdzony wspólny zakres trzech wysp; Thanksgiving wyłączony z public holidays. |
| OT-3 | OT-AI — Anguilla | 2026 | Uzasadnione lokalnie przesunięcia, w tym piątek 18 grudnia; brak mechanicznej reguły GB. |
| OT-4 | OT-FK — Falklandy | 2026–2029 | Rozdzielenie dni publicznych i zamknięć administracji; kolizje trzech dni Bożego Narodzenia. |
| OT-5 | OT-GI — Gibraltar, poza grupą wysp | 2026; osobne rozszerzenie 2027 | Finalne potwierdzenie daty monarchy dla każdego wydawanego roku; 2027 pozostaje warunkowy. |
| OT-6 | OT-VG — Brytyjskie Wyspy Dziewicze | 2026 | Proklamacja SI 94/2025, jednorazowy 30 czerwca, potwierdzony zakres terytorialny. |
| OT-7 | OT-MS — Montserrat | 2026 | Roczna tabela uzgodniona z późniejszymi SRO, w tym 22/2026; typy i zastępstwa sobotnie. |
| OT-8 | OT-HL — Święta Helena | 2026 | SH-HL, rozróżnienie government/public holidays po zmianie 2024; bez ogólnego aliasu SH. |
| OT-9 | OT-AC — Ascension | 2026, po pozyskaniu pełnej listy | SH-AC, akt dotyczący Ascension Day i pełny rocznik, własne zastępstwa. |
| OT-10 | OT-GS — Georgia Południowa i Sandwich Południowy | 2025–2026 | Obie deklaracje Commissioner, własne nazwy i odbiorcy, niezależność od FK. |
| OT-11 | OT-TC — Turks i Caicos | Docelowo 2026 | Najpierw pełny Act 16/2025 i oficjalne daty/proklamacje; dopiero po ich uzgodnieniu provider. |
| OT-12 | OT-PN — Pitcairn | Docelowo 2026 | Najpierw odczyt polityki June 2024, potwierdzenie aktualności, odbiorców i pełnego 2026. |
| OT-13 | OT-TA — Tristan da Cunha | Docelowo 2026 | Najpierw lokalne prawo, kompletna lista administracji i zasięg grupy; dopiero potem SH-TA. |
| OT-14 | OT-IO — badanie BIOT | Audyt roku 2026; brak wystarczających dowodów do wdrożenia | Ustalenie skuteczności umowy UK–Mauritius, aktualnego ISO, kalendarza i adresatów; ukończenie przez udokumentowaną decyzję wdrożyć/odroczyć. |

Równoległa kolejka lokalnego audytu UK: **UK-ISL-ORK → UK-ISL-ZET →
UK-ISL-ELS → UK-ISL-OTHER**, każde dla **2026–2027**. Zakresy i kryteria
podaje audyt: Orkady (rada), Szetlandy (w tym decyzja o dodatkowym dniu 2026),
Hebrydy Zewnętrzne (różnice lokalne), następnie Skye/Mull/Islay/Arran/Bute.
Wdrożenie wymaga potwierdzonej różnicy i odbiorcy; sam kalendarz szkolny nie
wystarcza. Nie stwierdzono potrzeby nowych powszechnych providerów Isle of
Wight, Scilly, Anglesey ani Rathlin. Brak lokalnego mapowania rady szkockiej
może obecnie uruchomić fallback do GB, co nie oznacza poprawnej lokalnej obsługi.
