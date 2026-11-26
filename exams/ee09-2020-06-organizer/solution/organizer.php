<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Organizer</title>
    <link rel="stylesheet" href="styl6.css">
</head>
<body>
<?php
$polaczenie = mysqli_connect('localhost', 'root', '', 'egzamin6');
// Dane z formularza: zapytanie 4 z nowym wpisem, zawsze dla 2020-08-27
if (isset($_POST['wpis'])) {
    $wpis = mysqli_real_escape_string($polaczenie, $_POST['wpis']);
    mysqli_query($polaczenie, "UPDATE zadania SET wpis = '$wpis' WHERE dataZadania = '2020-08-27'");
}
?>
    <header id="baner1">
        <h2>MÓJ ORGANIZER</h2>
    </header>
    <section id="baner2">
        <form action="organizer.php" method="post">
            <label for="wpis">Wpis wydarzenia: </label>
            <input type="text" id="wpis" name="wpis">
            <button type="submit">ZAPISZ</button>
        </form>
    </section>
    <section id="baner3">
        <img src="logo2.png" alt="Mój organizer">
    </section>
    <main>
<?php
// Skrypt 1: zapytanie 1
$wynik = mysqli_query($polaczenie, "SELECT dataZadania, miesiac, wpis FROM zadania WHERE miesiac = 'sierpien'");
while ($w = mysqli_fetch_row($wynik)) {
    echo "        <section class=\"dzien\">\n            <h6>$w[0], $w[1]</h6>\n            <p>$w[2]</p>\n        </section>\n";
}
?>
    </main>
    <footer>
<?php
// Skrypt 2: zapytanie 2
$wynik = mysqli_query($polaczenie, "SELECT miesiac, rok FROM zadania WHERE dataZadania = '2020-08-01'");
while ($w = mysqli_fetch_row($wynik)) {
    echo "        <h1>miesiąc: $w[0], rok: $w[1]</h1>\n";
}
mysqli_close($polaczenie);
?>
        <p>Stronę wykonał: 00000000000</p>
    </footer>
</body>
</html>
