<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Nasz sklep komputerowy</title>
    <link rel="stylesheet" href="styl8.css">
</head>
<body>
    <nav id="menu">
        <a href="index.php">Główna</a>
        <a href="procesory.html">Procesory</a>
        <a href="ram.html">RAM</a>
        <a href="grafika.html">Grafika</a>
    </nav>
    <header id="logo">
        <h2>Podzespoły komputerowe</h2>
    </header>
    <main>
        <h1>Dzisiejsze promocje</h1>
        <table>
            <tr>
                <th>NUMER</th>
                <th>NAZWA PODZESPOŁU</th>
                <th>OPIS</th>
                <th>CENA</th>
            </tr>
<?php
$polaczenie = mysqli_connect('localhost', 'root', '', 'sklep');
$wynik = mysqli_query($polaczenie, 'SELECT id, nazwa, opis, cena FROM podzespoly WHERE cena < 1000;');
while ($wiersz = mysqli_fetch_row($wynik)) {
    echo "            <tr><td>$wiersz[0]</td><td>$wiersz[1]</td><td>$wiersz[2]</td><td>$wiersz[3]</td></tr>\n";
}
mysqli_close($polaczenie);
?>
        </table>
    </main>
    <footer>
        <section class="stopka">
            <img src="scalak.jpg" alt="promocje na procesory">
        </section>
        <section class="stopka">
            <h4>Nasz Sklep Komputerowy</h4>
            <p>Współpracujemy z hurtownią <a href="http://www.edata.pl/" target="_blank">edata</a></p>
        </section>
        <section class="stopka">
            <p>zadzwoń: 601 602 603</p>
        </section>
        <section class="stopka">
            <p>Stronę wykonał: 00000000000</p>
        </section>
    </footer>
</body>
</html>
