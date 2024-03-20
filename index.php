<?php
// Ukoliko nam se errori ne prikazuju uopste, potrebno je otkomentarisati narednu liniju koda
// error_reporting(E_ALL);

session_start();

// Ako neko dodje na ovu stranicu kao ulogovani korisnik, odmah ga prebacujemo na home stranicu
if (isset($_SESSION['currentUserEmail'])) {
    header('Location: home.php');
}

?>

<html>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include('header.php'); ?>

    <section>
        <h1>Welcome</h1>
    </section>

    <?php include('footer.php'); ?>
</body>

</html>