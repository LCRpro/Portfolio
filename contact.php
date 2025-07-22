<?php
$destinataire = "cariou.liam@orange.fr";
$sujet = "Nouveau message depuis le portfolio";
$fromDomain = $_SERVER['SERVER_NAME'] ?? 'localhost';
$headers = "Content-Type: text/plain; charset=UTF-8\r\n" .
           "From: Formulaire <no-reply@$fromDomain>\r\n" .
           "Reply-To: %s\r\n";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  exit("Méthode non autorisée");
}

$nom     = trim($_POST["nameContact"] ?? "");
$email   = trim($_POST["emailContact"] ?? "");
$message = trim($_POST["messageContact"] ?? "");

if ($nom === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === "") {
  http_response_code(400);
  exit("Champs invalides ou incomplets.");
}

$corps  = "Vous avez reçu un message via le formulaire de contact :\n\n";
$corps .= "Nom : $nom\n";
$corps .= "Email : $email\n";
$corps .= "Message :\n$message\n";

$sent = mail(
  $destinataire,
  $sujet,
  $corps,
  sprintf($headers, $email)
);

if ($sent) {
  header("Location: contact.html?sent=1");
  exit;
} else {
  http_response_code(500);
  exit("Erreur lors de l'envoi de l'e-mail.");
}
?>
