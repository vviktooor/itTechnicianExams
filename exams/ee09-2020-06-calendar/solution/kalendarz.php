<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Mój kalendarz</title>
    <link rel="stylesheet" href="styl5.css">
</head>
<body>
<?php
$polaczenie = mysqli_connect('localhost', 'root', '', 'egzamin5');
// Dane z formularza: zapytanie 4 z nowym wpisem, zawsze dla 2020-07-13 (przed wyświetleniem bloków)
if (isset($_POST['wpis'])) {
    $wpis = mysqli_real_escape_string($polaczenie, $_POST['wpis']);
    mysqli_query($polaczenie, "UPDATE zadania SET wpis = '$wpis' WHERE dataZadania = '2020-07-13'");
}
?>
    <header>
        <section id="baner1">
            <img src="logo1.png" alt="Mój kalendarz">
        </section>
        <section id="baner2">
            <h1>KALENDARZ</h1>
<?php
// Skrypt 1: zapytanie 1
$wynik = mysqli_query($polaczenie, "SELECT miesiac, rok FROM zadania WHERE dataZadania = '2020-07-01'");
while ($w = mysqli_fetch_row($wynik)) {
    echo "            <h3>miesiąc: $w[0], rok: $w[1]</h3>\n";
}
?>
        </section>
    </header>
    <main>
<?php
// Skrypt 2: zapytanie 2
$wynik = mysqli_query($polaczenie, "SELECT dataZadania, wpis FROM zadania WHERE miesiac = 'lipiec'");
while ($w = mysqli_fetch_row($wynik)) {
    echo "        <section class=\"dzien\">\n            <h5>$w[0]</h5>\n            <p>$w[1]</p>\n        </section>\n";
}
mysqli_close($polaczenie);
?>
    </main>
    <footer>
        <form action="kalendarz.php" method="post">
            <label for="wpis">dodaj wpis: </label>
            <input type="text" id="wpis" name="wpis">
            <button type="submit">DODAJ</button>
        </form>
        <p>Stronę wykonał: 00000000000</p>
    </footer>
</body>
</html>
