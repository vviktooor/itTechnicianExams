<?php
$polaczenie = mysqli_connect('localhost', 'root', '', 'sklep');
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sklep papierniczy</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <header>
        <h1>W naszym sklepie internetowym kupisz najtaniej</h1>
    </header>
    <section id="lewy">
        <h3>Promocja 15% obejmuje artykuły:</h3>
        <ul>
<?php
$wynik = mysqli_query($polaczenie, 'SELECT nazwa FROM towary WHERE promocja = 1;');
while ($wiersz = mysqli_fetch_row($wynik)) {
    echo "            <li>$wiersz[0]</li>\n";
}
?>
        </ul>
    </section>
    <main id="srodkowy">
        <h3>Cena wybranego artykułu w promocji</h3>
        <form action="index.php" method="post">
            <select name="towar">
                <option>Gumka do mazania</option>
                <option>Cienkopis</option>
                <option>Pisaki 60 szt.</option>
                <option>Markery 4 szt.</option>
            </select>
            <button type="submit">WYBIERZ</button>
        </form>
<?php
if (isset($_POST['towar'])) {
    $towar = mysqli_real_escape_string($polaczenie, $_POST['towar']);
    $wynik = mysqli_query($polaczenie, "SELECT cena FROM towary WHERE nazwa = '$towar';");
    $wiersz = mysqli_fetch_row($wynik);
    if ($wiersz) {
        echo '        ' . round($wiersz[0] * 0.85, 2) . "\n";
    }
}
mysqli_close($polaczenie);
?>
    </main>
    <section id="prawy">
        <h3>Kontakt</h3>
        <p>telefon: 123123123<br>e-mail: <a href="mailto:bok@sklep.pl">bok@sklep.pl</a></p>
        <img src="promocja2.png" alt="promocja">
    </section>
    <footer>
        <h4>Autor strony 00000000000</h4>
    </footer>
</body>
</html>
