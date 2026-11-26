<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sekretariat</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
<?php $polaczenie = mysqli_connect('localhost', 'root', '', 'firma'); ?>
    <section id="lewy">
        <h1>Akta Pracownicze</h1>
<?php
// Skrypt 1: zapytanie 1
$wynik = mysqli_query($polaczenie, "SELECT imie, nazwisko, adres, miasto, czyRODO, czyBadania FROM pracownicy WHERE id = 2");
while ($w = mysqli_fetch_row($wynik)) {
    $rodo = $w[4] == 1 ? 'podpisano' : 'niepodpisano';
    $badania = $w[5] == 1 ? 'aktualne' : 'nieaktualne';
    echo "        <h3>dane</h3>\n        <p>$w[0] $w[1]</p>\n        <hr>\n";
    echo "        <h3>adres</h3>\n        <p>$w[2]</p>\n        <p>$w[3]</p>\n        <hr>\n";
    echo "        <p>RODO: $rodo</p>\n        <p>Badania: $badania</p>\n";
}
?>
        <hr>
        <h3>Dokumenty pracownika</h3>
        <a href="../sheet/cv.txt">Pobierz</a>
        <h1>Liczba zatrudnionych pracowników</h1>
        <p>
<?php
// Skrypt 2: zapytanie 2
$wynik = mysqli_query($polaczenie, "SELECT COUNT(*) FROM pracownicy");
echo mysqli_fetch_row($wynik)[0];
?>
        </p>
    </section>
    <section id="prawy">
<?php
// Skrypt 3: zapytanie 3, potem zapytanie 4
$wynik = mysqli_query($polaczenie, "SELECT id, imie, nazwisko FROM pracownicy WHERE id = 2");
while ($w = mysqli_fetch_row($wynik)) {
    echo "        <img src=\"$w[0].jpg\" alt=\"pracownik\">\n        <h2>$w[1] $w[2]</h2>\n";
}
$wynik = mysqli_query($polaczenie, "SELECT pracownicy.id, stanowiska.nazwa, stanowiska.opis FROM pracownicy JOIN stanowiska ON pracownicy.stanowiska_id = stanowiska.id WHERE pracownicy.id = 2");
while ($w = mysqli_fetch_row($wynik)) {
    echo "        <h4>$w[1]</h4>\n        <h5>$w[2]</h5>\n";
}
mysqli_close($polaczenie);
?>
    </section>
    <footer>
        Autorem aplikacji jest: 00000000000
        <ul>
            <li>skontaktuj się</li>
            <li>poznaj naszą firmę</li>
        </ul>
    </footer>
</body>
</html>
