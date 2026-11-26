<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Opinie klientów</title>
    <link rel="stylesheet" href="styl3.css">
</head>
<body>
<?php $polaczenie = mysqli_connect('localhost', 'root', '', 'hurtownia'); ?>
    <header>
        <h1>Hurtownia spożywcza</h1>
    </header>
    <main>
        <h2>Opinie naszych klientów</h2>
<?php
// Skrypt 1: zapytanie 3
$wynik = mysqli_query($polaczenie, "SELECT klienci.zdjecie, klienci.imie, opinie.opinia FROM klienci JOIN opinie ON opinie.Klienci_id = klienci.id WHERE klienci.Typy_id IN (2, 3)");
while ($w = mysqli_fetch_row($wynik)) {
    echo "        <div class=\"opinia\">\n";
    echo "            <img src=\"$w[0]\" alt=\"klient\">\n";
    echo "            <blockquote>$w[2]</blockquote>\n";
    echo "            <h4>$w[1]</h4>\n";
    echo "        </div>\n";
}
?>
    </main>
    <footer>
        <section>
            <h3>Współpracują z nami</h3>
            <a href="http://sklep.pl/">Sklep 1</a>
        </section>
        <section>
            <h3>Nasi top klienci</h3>
            <ol>
<?php
// Skrypt 2: zapytanie 1
$wynik = mysqli_query($polaczenie, "SELECT imie, nazwisko, punkty FROM klienci ORDER BY punkty DESC LIMIT 3");
while ($w = mysqli_fetch_row($wynik)) {
    echo "                <li>$w[0] $w[1], $w[2] pkt.</li>\n";
}
mysqli_close($polaczenie);
?>
            </ol>
        </section>
        <section>
            <h3>Skontaktuj się</h3>
            <p>telefon: 111222333</p>
        </section>
        <section>
            <h3>Autor: 00000000000</h3>
        </section>
    </footer>
</body>
</html>
