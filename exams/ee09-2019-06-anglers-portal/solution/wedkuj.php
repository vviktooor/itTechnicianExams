<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wędkujemy</title>
    <link rel="stylesheet" href="styl_1.css">
</head>
<body>
    <header>
        <h1>Portal dla wędkarzy</h1>
    </header>
    <section id="lewy">
        <h2>Ryby drapieżne naszych wód</h2>
        <ul>
<?php
$polaczenie = mysqli_connect('localhost', 'root', '', 'wedkowanie');
$wynik = mysqli_query($polaczenie, 'SELECT nazwa, wystepowanie FROM ryby WHERE styl_zycia = 1;');
while ($wiersz = mysqli_fetch_row($wynik)) {
    echo "            <li>$wiersz[0], występowanie: $wiersz[1]</li>\n";
}
mysqli_close($polaczenie);
?>
        </ul>
    </section>
    <section id="prawy">
        <img src="ryba1.jpg" alt="Sum">
        <br>
        <a href="../answer/kwerendy.txt">Pobierz kwerendy</a>
    </section>
    <footer>
        <p>Stronę wykonał: 00000000000</p>
    </footer>
</body>
</html>
