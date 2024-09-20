<?php
session_start(); // Start een nieuwe sessie of hervat een bestaande sessie
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php
$methodType = $_SERVER["REQUEST_METHOD"]; // Haal de methode van het huidige verzoek op
$loginFout = ""; // Initialiseer de variabele voor login foutmeldingen
$email = $wachtwoord = ""; // Initialiseer de variabelen voor email en wachtwoord

if (($methodType == "POST") && (isset($_POST["email"]))) { // Controleer of het verzoek een POST is en of de email is ingesteld
    $email = $_POST["email"]; // Haal de email uit het POST verzoek
    $wachtwoord = $_POST["wachtwoord"]; // Haal het wachtwoord uit het POST verzoek
    try {
        $host = "localhost"; // Stel de host in voor de database connectie
        $user = "root"; // Stel de gebruikersnaam in voor de database connectie
        $pass = ""; // Stel het wachtwoord in voor de database connectie
        $database = "gegevensverzameling"; // Stel de database naam in voor de connectie

        $connectie = new mysqli($host, $user, $pass, $database); // Maak een nieuwe database connectie

        if ($connectie->error) { // Controleer of er een fout is met de connectie
            throw new Exception($connectie->connect_error); // Gooi een uitzondering als er een fout is
        }

        $query = "SELECT * FROM gebruikers WHERE email = ?"; // Stel de SQL query op

        $statement = $connectie->prepare($query); // Bereid de SQL query voor
        $statement->bind_param("s", $email); // Bind de email parameter aan de query
        $statement->execute(); // Voer de query uit
        $result = $statement->get_result(); // Haal het resultaat van de query op

        if ($result->num_rows > 0) { // Controleer of er resultaten zijn
            $row = $result->fetch_assoc(); // Haal de eerste rij van het resultaat op
            if (password_verify($wachtwoord, $row['wachtwoord'])) { // Controleer of het wachtwoord overeenkomt met het gehashte wachtwoord in de database
                $_SESSION['gebruikerID'] = $row['gebruikerID']; // Sla de gebruiker ID op in de sessie
                $_SESSION['gebruikersnaam'] = $row['gebruikersnaam']; // Sla de gebruikersnaam op in de sessie
                $_SESSION['email'] = $email; // Sla de email op in de sessie
                header("location: dashboard.php"); // Stuur de gebruiker door naar het dashboard
            } else {
                $loginFout = "Ongeldige gebruikersnaam of wachtwoord."; // Stel de foutmelding in als de gebruikersnaam of het wachtwoord ongeldig is
            }
        } else {
            $loginFout = "Ongeldige gebruikersnaam of wachtwoord."; // Stel de foutmelding in als de gebruikersnaam of het wachtwoord ongeldig is
        }
    } catch (Exception $e) {
        echo "Fout was: " . $e->getMessage(); // Toon de foutmelding als er een uitzondering is gegooid
    } finally {
        if ($statement) {
            $statement->close(); // Sluit het statement als het bestaat
        }

        if ($connectie) {
            $connectie->close(); // Sluit de connectie als het bestaat
        }
    }
}
?>

<body>
    <div class="header">
        <a href="index.php" class="logohref"><img src="logo.svg" id="logo" alt="Logo"></a>
        <h1>DataTrail</h1>
        <div class="buttons">
            <a href="login.php" class="button">Inloggen</a>
            <a href="register.php" class="button">Registreren</a>
        </div>
    </div>
    <main>
        <form action="" method="post">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required><br>
            <label for="wachtwoord">Wachtwoord</label>
            <input type="password" name="wachtwoord" id="wachtwoord"><br>
            <?php if ($loginFout): ?>
                <span class="error"><?php echo $loginFout; ?></span>
            <?php endif; ?>
            <input type="submit" value="Inloggen">
        </form>
        <a class="registreer" href="register.php">Nog geen account? Registreer hier.</a>
    </main>
</body>

</html>