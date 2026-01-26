<?php
// app/Controllers/PageController.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Services/Mailer.php';

class PageController
{
    private function validateContact(string $email, string $title, string $message): array
    {
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide.";
        }
        if (trim($title) === '') {
            $errors[] = "Titre obligatoire.";
        }
        if (trim($message) === '') {
            $errors[] = "Message obligatoire.";
        }

        return $errors;
    }

    public function contact(): void
    {
        $errors = [];
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $message = trim($_POST['message'] ?? '');

            $errors = $this->validateContact($email, $title, $message);

            if (empty($errors)) {
                $mailer = new Mailer('log');
                $mailer->send(
                    "contact@vitegourmand.fr",
                    "Contact - " . $title,
                    "De: $email\n\n$message"
                );
                $success = true;
            }
        }

        require_once BASE_PATH . '/app/Views/pages/contact.php';
    }

    public function mentions(): void
    {
        require_once BASE_PATH . '/app/Views/pages/mentions.php';
    }

    public function cgv(): void
    {
        require_once BASE_PATH . '/app/Views/pages/cgv.php';
    }
}
