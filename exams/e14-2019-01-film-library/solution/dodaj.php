<?php
$polaczenie = mysqli_connect('localhost', 'root', '', 'dane');

$tytul = mysqli_real_escape_string($polaczenie, $_POST['tytul']);
$gatunek = (int) $_POST['gatunek'];
$rok = (int) $_POST['rok'];
$ocena = (int) $_POST['ocena'];

$zapytanie = "INSERT INTO filmy (gatunki_id, rezyserzy_id, tytul, rok, ocena) VALUES ($gatunek, 0, '$tytul', $rok, $ocena);";
if (mysqli_query($polaczenie, $zapytanie)) {
    echo 'Film ' . htmlspecialchars($_POST['tytul']) . ' został dodany do bazy';
}

mysqli_close($polaczenie);
