# Możliwości rozszerzenia testów referencyjnych

Data analizy: 2026-11-19.

## Wnioski

Największy zysk da wykorzystanie już zapisanych danych OpenHolidays oraz
rozszerzenie pokrycia regionalnego. Obecna baza jest dobra: oczekiwane daty
pochodzą spoza biblioteki, porównania uwzględniają nazwy i duplikaty,
a znane rozbieżności Azure są jawnie rejestrowane.

## Proponowane rozszerzenia

| Priorytet | Rozszerzenie | Uzasadnienie |
| --- | --- | --- |
| 1 | OpenHolidays: lata 2020–2025 zamiast 2024–2025 | Dane już są w repozytorium. Dla obecnych pięciu providerów oznacza to wzrost z 10 do 30 przypadków, bez pobierania danych. |
| 2 | Kolejne kraje OpenHolidays | Zapisano dane dla 36 krajów, ale testowane są cztery. Kandydatami są kraje mające już providery, np. Belgia, Francja, Włochy, Portugalia i Holandia. Potrzebne będą jawne mapowania nazw i ustalenie zakresu porównania. |
| 3 | Regiony | Poza Berlinem niemieckie landy nie mają tego porównania referencyjnego. Warto też objąć austriackie kraje związkowe oraz dostępne regiony Francji i Portugalii, po sprawdzeniu zgodności zakresu danych. |
| 4 | Integralność zapisanych danych | USA sprawdza hash, kompletność kombinacji stan–rok i poprawność dat. Analogicznych kontroli samych zapisanych plików brakuje w testach OpenHolidays, GOV.UK i Azure. Testy updaterów nie zastępują tej kontroli. |
| 5 | Klasyfikacja świąt | Obecne porównania koncentrują się na datach i nazwach. Osobne przypadki powinny sprawdzać DAY_OFF, PARTIAL_ONLY i COMPENSATORY, tam gdzie źródło pozwala jednoznacznie ustalić klasyfikację. |

## Miejsca wymagające uwagi

### OpenHolidays

[OpenHolidaysReferenceTest.php](../tests/Reference/OpenHoliday/OpenHolidaysReferenceTest.php)
uruchamia porównania dla PL, DE, DE-BE, CZ i AT wyłącznie w latach 2024–2025.
Zapisane dane obejmują 36 krajów i lata 2020–2025.

Rozszerzenie listy przypadków wymaga sprawdzenia semantyki danych regionalnych,
świąt częściowych i wielodniowych oraz uzupełnienia mapowania nazw. Rozbieżność
powinna prowadzić do analizy obu stron, a nie automatycznego dostosowania
oczekiwanych danych do wyniku biblioteki.

### Polska

Testy OpenHolidays i Azure jawnie obchodzą brak DAY_OFF dla obu świąt Bożego
Narodzenia. Daty i obecność tych świąt są chronione, ale luka klasyfikacji
pozostaje. Dodatkowe upamiętnienie Święta Wojska Polskiego w dniu Wniebowzięcia
jest wyłączane z porównania.

### Azure

Pełny kalendarz porównywany jest tylko dla pięciu krajów: AT, CZ, DE, PL i PT.
W pozostałych sprawdzany jest wspólny zestaw nazw, więc dodatkowe święto
providera spoza mapowania może pozostać niezauważone. Rozszerzanie pełnego
porównania ma większą wartość niż samo zwiększanie liczby lat.

Zakres określa [scope.json](../tests/Reference/Azure/scope.json).
Znane rozbieżności zapisano w
[known-differences.json](../tests/Reference/Azure/known-differences.json).
Testy zamrażające te rozbieżności nie potwierdzają poprawności kalendarza.

Azure warto pozostawić w historycznym zakresie 2000–2020. Późniejsze daty
w tym snapshotcie są projekcjami dawnych reguł i nie stanowią potwierdzenia
aktualności kalendarza.

### GOV.UK

[GovUkReferenceTest.php](../tests/Reference/GovUk/GovUkReferenceTest.php)
obejmuje trzy kalendarze regionalne w latach 2019–2028. Normalizacja szkockich
świąt noworocznych sprawdza daty, ale zaciera różnice nazw i oznaczenia dnia
zastępczego. Warto uzupełnić ją osobnym testem tych oznaczeń.

### USA

[StateProvidersReferenceTest.php](../tests/Reference/Usa/StateProvidersReferenceTest.php)
zapewnia już 714 porównań: 51 jurysdykcji × 7 lat (2020–2026) × dwa tryby
(actual i withObserved).

Kolejny krok to wybrane lata graniczne zmian reguł poza tym zakresem oraz
niezależne, urzędowe przykłady. Implementacja i referencja opierające się na
tych samych regułach upstream mogą współdzielić błąd.

Istnieją już testy jednostkowe m.in. klasyfikacji, reguł dni zastępczych,
przejścia przez granicę roku oraz cykli wyborczych. Nowe testy powinny
uzupełniać ich zakres, zamiast powielać te same przypadki.

## Zalecana kolejność prac

1. Rozszerzyć obecne przypadki OpenHolidays na lata 2020–2025.
2. Dodać niemieckie landy po sprawdzeniu zakresu danych regionalnych.
3. Dodać kontrolę integralności wszystkich zapisanych zestawów referencyjnych.
4. Stopniowo dodawać kolejne kraje z istniejących danych OpenHolidays.
5. Uzupełnić niezależne kontrole klasyfikacji i historycznych zmian reguł.

## Ograniczenia analizy

Analiza była statyczna i dotyczyła stanu katalogu roboczego, w tym lokalnych
zmian dotyczących USA. Nie zmieniano testów ani danych referencyjnych.
Nie potwierdzono wyników uruchomieniem testów: lokalnie brakuje PHP,
a próba dostępu do demona Dockera zakończyła się odmową dostępu.
