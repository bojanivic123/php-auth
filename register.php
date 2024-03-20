<?php
// Ukoliko nam se errori ne prikazuju uopste, potrebno je otkomentarisati narednu liniju koda
// error_reporting(E_ALL);

session_start();

// Ako neko dodje na ovu stranicu kao ulogovani korisnik, odmah ga prebacujemo na home stranicu
if (isset($_SESSION['currentUserEmail'])) {
    header('Location: home.php');
}

// U slucaju kada se prvi put pristupi index stranici nemamo listu korisnika u sesiji, pa je potrebno da postavimo prazan niz u sesiju za index 'users'
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [];
}

/**
 * Funkcija koja na osnovu datog parametra `email` pronalazi korisnika u listi svih korisnika koju cuvamo u sesiji
 * @return bool Ukoliko je korisnik pronadjen povratna vrednost je `true`, a u suprotnom je `false`.
 */
function doesUserWithGivenEmailExists(string $email): bool
{
    foreach ($_SESSION['users'] as $user) {
        if ($user['email'] === $email) {
            return true;
        }
    }

    return false;
}

/**
 * Funkcija koja izvrsava proces registracije korisnika sa datim nizom podataka
 * @param array $data Niz podataka koji su vezani za korisnika kog je potrebno registrovati.
 */
function register(array $data)
{
    // Dodajemo novog korisnika u listu postojecih korisnika koju cuvamo u sesiji
    $_SESSION['users'][] = $data;

    // Registrovanog korisnika odmah zelimo da ulogujemo, dakle logujemo korisnika tako sto smestamo uneti email u sesiju pod indeksom 'currentUserEmail'
    $_SESSION['currentUserEmail'] = $data['email'];

    // Preusmeravamo korisnika na home stranicu
    header('Location: home.php');
}

// Ukoliko je stigao POST zahtev, prelazimo na proces registracije korisnika
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (doesUserWithGivenEmailExists($_POST['email'])) {
        // Korisnik ostaje na stranici za registraciju zato sto vec postoji neki korisnik sa tom email adresom
        // i prikazuje mu se validaciona poruka
        $errorMessage = "User with entered email already exists!";
    } else {
        // Korisnik sa unetom email adresom ne postoji, dakle mozemo ga registrovati
        register($_POST);
    }
}

?>
<html>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include('header.php'); ?>
    <section>
        <h1>Register</h1>
        <form action="register.php" method="post">
            <div class="form-fields">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <label for="lastname">Lastname:</label>
                    <label for="email">Email:</label>
                    <label for="password">Password:</label>
                </div>
                <div class="form-group">
                    <input type="text" name="name" placeholder="Your first name" required />
                    <input type="text" name="lastname" placeholder="Your last name" required />
                    <input type="email" name="email" placeholder="Your email address" required />
                    <input type="password" name="password" placeholder="Secret secret" required />
                </div>
            </div>
            <button class="btn btn-primary">Register</button>
        </form>
        <p class="error-message">
            <?php
            if (isset($errorMessage)) {
                echo $errorMessage;
            }
            ?>
        </p>
    </section>
    <?php include('footer.php'); ?>
</body>

</html>