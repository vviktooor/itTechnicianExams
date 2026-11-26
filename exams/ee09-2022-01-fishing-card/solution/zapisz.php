<?php
// Zapytanie 1 z danymi z formularza; data_zezwolenia i punkty: NULL
$polaczenie = mysqli_connect('localhost', 'root', '', 'wedkowanie');
$imie = mysqli_real_escape_string($polaczenie, $_POST['imie'] ?? '');
$nazwisko = mysqli_real_escape_string($polaczenie, $_POST['nazwisko'] ?? '');
$adres = mysqli_real_escape_string($polaczenie, $_POST['adres'] ?? '');
mysqli_query($polaczenie, "INSERT INTO karty_wedkarskie (imie, nazwisko, adres, data_zezwolenia, punkty) VALUES ('$imie', '$nazwisko', '$adres', NULL, NULL)");
mysqli_close($polaczenie);
