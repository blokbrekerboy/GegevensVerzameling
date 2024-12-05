<!DOCTYPE html>
<html>

<head>
    <title>Mijn Website</title>
    <style>

    </style>
    <link rel="stylesheet" href="style.css">
</head>

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
        <h2>Over</h2>
        <p>Dit is de thuispagina van mijn website. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam
            auctor, nisl at ultrices tincidunt, nunc nunc aliquet nunc, id lacinia nunc nunc ac nunc. Sed auctor, nunc
            id lacinia aliquet, nunc nunc aliquet nunc, id lacinia nunc nunc ac nunc.</p>

        <h2>Diensten</h2>
        <div class="services">
            <div class="service">
                <h3>Webdesign</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="service">
                <h3>Webontwikkeling</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
            <div class="service">
                <h3>Grafisch ontwerp</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
        </div>

        <h2>Contact</h2>
        <div class="contact-info">
            <div>
                <p>Email: thisisdefinitelyareal@email.com</p>
                <p>Phone: +31 6 53531861</p>
            </div>
            <div>
                <p>Adres: Kasteeldreef 122</p>
            </div>
        </div>
        <h2>Andere pagina's</h2>
        <div class="hrefs">
            <div>
                <a class="href" href="profielpagina.php">Profielpagina</a>
            </div>
        </div>
    </main>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> Mijn Website. Alle rechten voorbehouden.
    </div>
</body>

</html>