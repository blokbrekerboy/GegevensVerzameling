<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datcable</title>
    <link rel="shortcut icon" href="images/favicon.svg">
    <link rel="stylesheet" href="style.css">
</head>
<?php
// Bepaal het type van de HTTP-methode (GET, POST, etc.)
$methodType = $_SERVER["REQUEST_METHOD"];
// Initialiseer foutmeldingen voor gebruikersnaam en e-mail
$gebruikersnaamFout = $emailFout = "";
// Initialiseer gebruikersnaam en e-mail
$gebruikersnaam = $email = "";

// Controleer of de HTTP-methode POST is en of de gebruikersnaam is ingesteld
if (($methodType == "POST") && (isset($_POST["gebruikersnaam"]))) {
    // Haal de gebruikersnaam en e-mail op uit de POST-gegevens
    $gebruikersnaam = $_POST["gebruikersnaam"];
    $email = $_POST["email"];
    try {
        // Stel de databaseverbinding parameters in
        $host = "localhost";
        $user = "root";
        $pass = "";
        $database = "gegevensverzameling";

        // Maak een nieuwe databaseverbinding
        $connectie = new mysqli($host, $user, $pass, $database);

        // Controleer of er een fout is opgetreden bij het verbinden met de database
        if ($connectie->error) {
            throw new Exception($connectie->connect_error);
        }

        // Stel de SQL-query in om te controleren of de gebruikersnaam of e-mail al bestaat
        $query = "SELECT * FROM gebruikers WHERE gebruikersnaam = ? OR email = ?";

        // Bereid de SQL-query voor
        $statement = $connectie->prepare($query);
        // Bind de parameters aan de SQL-query
        $statement->bind_param("ss", $gebruikersnaam, $email);
        // Voer de SQL-query uit
        $statement->execute();
        // Haal het resultaat van de SQL-query op
        $result = $statement->get_result();

        // Controleer of er rijen zijn geretourneerd door de SQL-query
        if ($result->num_rows > 0) {
            // Loop door elke rij die is geretourneerd door de SQL-query
            while ($row = $result->fetch_assoc()) {
                // Controleer of de gebruikersnaam al bestaat
                if ($row['gebruikersnaam'] == $gebruikersnaam) {
                    $gebruikersnaamFout = "Gebruikersnaam bestaat al.";
                }
                // Controleer of de e-mail al bestaat
                if ($row['email'] == $email) {
                    $emailFout = "Email bestaat al.";
                }
            }
        } else {
            // Stel de SQL-query in om een nieuwe gebruiker in te voegen
            $query = "INSERT INTO gebruikers(gebruikersnaam,email,wachtwoord) VALUES (?,?,?)";

            // Bereid de SQL-query voor
            $statement = $connectie->prepare($query);

            // Haal het wachtwoord op uit de POST-gegevens
            $wachtwoord = $_POST["wachtwoord"];

            // Maak de gebruikersnaam, e-mail en wachtwoord veilig voor invoer in de database
            $veiligGebruikersnaam = htmlspecialchars($gebruikersnaam);
            $veiligEmail = htmlspecialchars($email);
            $veiligWachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);

            // Bind de parameters aan de SQL-query
            $statement->bind_param("sss", $veiligGebruikersnaam, $veiligEmail, $veiligWachtwoord);

            // Controleer of de SQL-query succesvol is uitgevoerd
            if (!$statement->execute()) {
                throw new Exception($connectie->error);
            }

            // Stuur de gebruiker door naar de login-pagina
            header("location: login.php");
        }
    } catch (Exception $e) {
        // Toon de foutmelding als er een uitzondering is opgetreden
        echo "Fout was: " . $e->getMessage();
    } finally {
        // Sluit het statement en de verbinding als ze bestaan
        if ($statement) {
            $statement->close();
        }

        if ($connectie) {
            $connectie->close();
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
            <label for="gebruikersnaam">Gebruikersnaam</label>
            <input type="text" name="gebruikersnaam" id="gebruikersnaam"
                value="<?php echo htmlspecialchars($gebruikersnaam); ?>" required><br>
            <?php if ($gebruikersnaamFout): ?>
                <span class="error"><?php echo $gebruikersnaamFout; ?></span>
            <?php endif; ?>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required><br>
            <?php if ($emailFout): ?>
                <span class="error"><?php echo $emailFout; ?></span>
            <?php endif; ?>
            <label for="wachtwoord">Wachtwoord</label>
            <input type="password" name="wachtwoord" id="wachtwoord" required><br>
            <input type="submit" value="Doorgaan">
        </form>
        <a class="login" href="login.php">Heb je al een account?</a>
    </main>
</body>

</html>