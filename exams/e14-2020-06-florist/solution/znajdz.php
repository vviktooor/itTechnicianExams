<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Kwiaty</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <header>
        <h1>Moje kwiaty</h1>
    </header>
    <section id="lewy">
        <h3>Kwiaty dla Ciebie!</h3>
        <a href="https://www.swiatkwiatow.pl/">Rozpoznaj kwiaty</a><br>
        <a href="znajdz.php">Znajdź kwiaciarnię</a><br>
        <img src="gozdzik.jpg" alt="Goździk">
    </section>
    <section id="prawy">
        <img src="gerbera.jpg" alt="gerbera">
        <img src="gozdzik.jpg" alt="goździk">
        <img src="roza.jpg" alt="róża">
        <p>Podaj miejscowość, w której poszukujesz kwiaciarni:</p>
        <form action="znajdz.php" method="post">
            <input type="text" name="miasto">
            <button type="submit">SPRAWDŹ</button>
        </form>
<?php
if (isset($_POST['miasto'])) {
    $polaczenie = mysqli_connect('localhost', 'root', '', 'kwiaciarnia');
    $miasto = mysqli_real_escape_string($polaczenie, $_POST['miasto']);
    $wynik = mysqli_query($polaczenie, "SELECT nazwa, ulica FROM kwiaciarnie WHERE miasto = '$miasto';");
    while ($wiersz = mysqli_fetch_row($wynik)) {
        echo "        $wiersz[0], $wiersz[1]<br>\n";
    }
    mysqli_close($polaczenie);
}
?>
    </section>
    <footer>
        <h3>Stronę opracował: 00000000000</h3>
    </footer>
</body>
</html>
