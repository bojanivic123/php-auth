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
 * Funkcija koja na osnovu datih parametara `email` i `password` pronalazi korisnika u listi svih korisnika koju cuvamo u sesiji.
 * @return bool Ukoliko je korisnik pronadjen povratna vrednost je `true`, a u suprotnom je `false`.
 */
function areCredentialsValid(string $email, string $password): bool
{
    foreach ($_SESSION['users'] as $user) {
        if ($user['email'] === $email && $user['password'] === $password) {
            return true;
        }
    }

    return false;
}

/**
 * Funkcija koja izvrsava proces logovanja korisnika sa datim email-om
 */
function login(string $email)
{
    // Logujemo korisnika tako sto smestamo uneti email u sesiju pod indeksom 'currentUserEmail'
    $_SESSION['currentUserEmail'] = $email;

    // Preusmeravamo korisnika na home stranicu
    header('Location: home.php');
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (areCredentialsValid($_POST['email'], $_POST['password'])) {
        // Posto smo pronasli korisnika sa unetim kredencijalima, mozemo ga ulogovati
        login($_POST['email']);
    } else {
        // Korisnik ostaje na login stranici zato sto nije uneo validne kredencijale i prikazuje mu se validaciona poruka
        $errorMessage = "Invalid credentials!";
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
        <h1>Login</h1>
        <form method="post" action="login.php">
            <div class="form-fields">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <label for="password">Password:</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email" />
                    <input type="password" name="password" />
                </div>
            </div>
            <button class="btn btn-primary">Login</button>
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