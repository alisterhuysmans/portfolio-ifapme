<?php
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start(); // Start the session

// Function to validate the CAPTCHA
function validateCaptcha($userCaptcha, $sessionCaptcha) {
    return ($userCaptcha === $sessionCaptcha);
}

// Define variables and set to empty values
$firstName = $lastName = $email = $message = $captcha = "";
$captchaError = "";

// Generate CAPTCHA image and session value if not already set
if (!isset($_SESSION["captcha"])) {
    $captchaChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $captcha = substr(str_shuffle($captchaChars), 0, 6); // Generate a random 6-character CAPTCHA
    $_SESSION["captcha"] = $captcha; // Store the CAPTCHA in session
    $image = imagecreate(100, 40);
    $bgColor = imagecolorallocate($image, 255, 255, 255); // White background
    $textColor = imagecolorallocate($image, 0, 0, 0); // Black text color
    imagestring($image, 5, 10, 10, $captcha, $textColor);
    imagejpeg($image, "captcha.jpg"); // Save CAPTCHA as an image
    imagedestroy($image);
}

// Process the form when it's submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = testInput($_POST["firstName"]);
    $lastName = testInput($_POST["lastName"]);
    $email = testInput($_POST["email"]);
    $message = testInput($_POST["message"]);
    $captcha = testInput($_POST["captcha"]);

    // CAPTCHA validation
    $sessionCaptcha = $_SESSION["captcha"];
    if (!validateCaptcha($captcha, $sessionCaptcha)) {
        $captchaError = "CAPTCHA Invalide. Veuillez réessayer.";
        echo "<div id='message-container' style='position: fixed; width:100vw; height:100vh; background-color: hsla(206, 15%, 9%, 0.85); z-index: 5;'>
        <div style='display: flex; height: 100vh; justify-content: center; align-items: center; flex-direction: column;'>
            <h2 style='margin:0;'>CAPTCHA Invalide. Veuillez réessayer.</h2>
        </div>
    </div>
    <script>
        var messageContainer = document.getElementById('message-container');
        messageContainer.addEventListener('click', function() {
        messageContainer.style.display = 'none';
        });
    </script>";
    }

    // If there are no errors, process the form
    if (empty($captchaError)) {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Configure SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'alister.huysmans@gmail.com'; // Replace with your Elastic Email SMTP username
            $mail->Password = 'otgbqlguqwghoyzr'; // Replace with your Elastic Email SMTP password
            $mail->Port = 587; // Replace with your Elastic Email SMTP port

            // Enable TLS encryption
            $mail->SMTPSecure = 'tls';

            // Set email parameters
            $mail->setFrom($email);
            $mail->addAddress('alister.huysmans@gmail.com'); // Replace with your recipient email address
            $mail->Subject = 'Contact Form Submission';
            $mail->Body = "Name: $firstName $lastName\nEmail: $email\nMessage: $message";

            // Send the email
            $mail->send();

            // Display a success message
            echo "
            <div id='message-container' style='position: fixed; width:100vw; height:100vh; background-color: hsla(206, 15%, 9%, 0.85); z-index: 5;'>
                <div style='display: flex; height: 100vh; justify-content: center; align-items: center; flex-direction: column;'>
                    <h2 style='margin:0;'>Merci pour votre soumission!</h2>
                    <p>Votre message a été envoyé avec succès.</p>
                </div>
            </div>
            <script>
                var messageContainer = document.getElementById('message-container');
                messageContainer.addEventListener('click', function() {
                messageContainer.style.display = 'none';
                });
            </script>";
        } catch (Exception $e) {
            // Display an error message
            echo "Votre message n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
        }
    } else {
        // Display the CAPTCHA error message
        echo $captchaError;
    }
}

// Function to sanitize input values
function testInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/ff4f8ab1ed.js" crossorigin="anonymous"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <title>Alister Huysmans - Portfolio</title>
</head>
<body>
    <header>
        <nav>
            <a class="logo" href="#">Alister Huysmans</a>
            <ul>
                <li>
                    <a class="nav-link" href="#profile">Profil</a>
                </li>
                <li>
                    <a class="nav-link" href="#skills">Compétences</a>
                </li>
                <li>
                    <a class="nav-link" href="#education">Formation</a>
                </li>
                <li>
                    <a class="nav-link" href="#portfolio">Portfolio</a>
                </li>
                <li>
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
            </ul>
            <ul>
                <li>
                    <a href="https://www.linkedin.com/in/alisterhuysmans/">
                        <i class="fa-brands fa-lg fa-linkedin"></i>
                    </a>
                </li>
                <li>
                    <a href="https://github.com/alisterhuysmans">
                        <i class="fa-brands fa-lg fa-github"></i>
                    </a>
                </li>
                <li>
                    <a href="https://codepen.io/alisterhuysmans">
                        <i class="fa-brands fa-lg fa-codepen"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <section id="profile">
        <div class="profile-container">
            <img src="./images/profile_pic.jpg" alt="Profile Picture">
            <div>
                <h1>Développeur Web Front-end</h1>
                <p>Habité par une passion pour la programmation, le web design et le développement de web apps, je suis prêt à relever tous les défis pour mener à bien différents types de projets.</p>
                <a class="cta" href="#contact">Parlons de votre projet</a>
            </div>
        </div>
    </section>
    <section id="skills">
        <h1>Compétences</h1>
        <div class="skills-container">
            <div class="skills-card">
                <h2>Front-end</h2>
                <ul>
                    <li>
                        <div class="language">
                            HTML
                            <div class="progress-bar">
                                <div class="progress html">95% </div>
                            </div>
                        </div> 
                    </li>
                    <li>
                        <div class="language">
                            CSS
                            <div class="progress-bar">
                                <div class="progress css">75% </div>
                            </div>
                        </div> 
                    </li>
                    <li>
                        <div class="language">
                            JavaScript
                            <div class="progress-bar">
                                <div class="progress js">50% </div>
                            </div>
                        </div> 
                    </li>
                </ul>
            </div>
            <div class="skills-card">
                <h2>Back-end</h2>
                <ul>
                    <li>
                        <div class="language">
                            PHP
                            <div class="progress-bar">
                                <div class="progress php">25% </div>
                            </div>
                        </div> 
                    </li>
                    <li>
                        <div class="language">
                            MySQL
                            <div class="progress-bar">
                                <div class="progress mysql">60% </div>
                            </div>
                        </div> 
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <section id="education">
        <h1>Formation</h1>
        <div class="education-container">
            <h2>Mon parcours en quelques points</h2>
            <ul>
                <li>
                    <span>2022 - ...</span>
                    <div>
                        <h3>Centre IFAPME</h3>
                        <p>Formation en alternance - Développeur Web Front-end</p>
                    </div> 
                </li>
                <li>
                    <span>2015 - 2019</span>
                    <div>
                       <h3>Université de Liège</h3>
                        <p>Bachelier - Histoire</p> 
                    </div>
                </li>
            </ul>
        </div>
    </section>
    <section id="portfolio">
        <h1>Portfolio</h1>
        <div class="carousel">
            <div class="slider">
                <div class="slide">
                    <a href="https://github.com/alisterhuysmans/djm-digital-homepage" target="_blank">
                        <img src="./images/djm.png" alt="Homepage Djm">
                        <div class="caption">Reproduction du design de la Homepage du site djmdigital.be
                            <p>Technologies utilisées : HTML, Sass, JavaScript</p>
                        </div>
                    </a>
                </div>
                <div class="slide">
                    <a href="https://github.com/alisterhuysmans/djm-digital-homepage" target="_blank">
                        <img src="./images/djm.png" alt="Homepage Djm">
                        <div class="caption">Reproduction du design de la Homepage du site djmdigital.be
                            <p>Technologies utilisées : HTML, Sass, JavaScript</p>
                        </div>
                    </a>
                </div>
                <div class="slide">
                    <a href="https://github.com/alisterhuysmans/djm-digital-homepage" target="_blank">
                        <img src="./images/djm.png" alt="Homepage Djm">
                        <div class="caption">Reproduction du design de la Homepage du site djmdigital.be
                            <p>Technologies utilisées : HTML, Sass, JavaScript</p>
                        </div>
                    </a>
                </div>
                <div class="slide">
                    <a href="https://github.com/alisterhuysmans/djm-digital-homepage" target="_blank">
                        <img src="./images/djm.png" alt="Homepage Djm">
                        <div class="caption">Reproduction du design de la Homepage du site djmdigital.be
                            <p>Technologies utilisées : HTML, Sass, JavaScript</p>
                        </div>
                    </a>
                </div>
                <div class="slide">
                    <a href="https://github.com/alisterhuysmans/djm-digital-homepage" target="_blank">
                        <img src="./images/djm.png" alt="Homepage Djm">
                        <div class="caption">Reproduction du design de la Homepage du site djmdigital.be
                            <p>Technologies utilisées : HTML, Sass, JavaScript</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="slider-action">
            <button class="prev">
                <i class="fa-solid fa-2x fa-arrow-left"></i>
            </button>
            <button class="next">
                <i class="fa-solid fa-2x fa-arrow-right"></i>
            </button>
        </div>
    </section>
    <section id="contact">
        <div class="contact-container">
            <form id="contact-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <h1>Contact</h1>
                <p>Un projet ou une simple question ? Discutons-en !</p>
                <label for="firstName">Prénom</label>
                <input type="text" name="firstName" required>
    
                <label for="lastName">Nom</label>
                <input type="text" name="lastName" required>

                <label for="email">Email</label>
                <input type="email" name="email" required>

                <label for="message">Message</label>
                <textarea name="message" rows="5" required></textarea>

                <label for="captcha">CAPTCHA</label>
                <input type="text" name="captcha" required>
                <small>
                    <?php echo $captchaError; ?>
                </small>

                <!-- Display CAPTCHA image -->
                <img style="width:150px; margin-bottom: 1rem;" src="captcha.jpg" alt="CAPTCHA">

                <input class="cta" type="submit" value="Envoyer">
            </form>
        </div>
    </section>
    <footer>
        <p>&copy; Copyright - Alister Huysmans 2023</p>
    </footer>
    <script src="./script.js"></script>
</body>
</html>