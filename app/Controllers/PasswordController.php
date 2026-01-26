<?php
// app/Controllers/PasswordController.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Models/User.php';
require_once BASE_PATH . '/app/Services/Mailer.php';

class PasswordController
{
    private function passwordStrong(string $pwd): bool
    {
        if (strlen($pwd) < 10) return false;
        if (!preg_match('/[A-Z]/', $pwd)) return false;
        if (!preg_match('/[a-z]/', $pwd)) return false;
        if (!preg_match('/[0-9]/', $pwd)) return false;
        if (!preg_match('/[^A-Za-z0-9]/', $pwd)) return false;
        return true;
    }

    public function forgot(): void
    {
        $errors = [];
        $success = false;
        $oldEmail = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $oldEmail = $email;

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email invalide.";
            } else {
                // Sécurité : on ne révèle pas si l'email existe ou non
                $user = User::findByEmail($email);

                if ($user && (bool)$user['is_active']) {
                    $token = bin2hex(random_bytes(32));
                    $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1h

                    User::setResetToken((int)$user['id'], $token, $expiresAt);

                    $resetLink = "http://localhost:8000/?r=reset&token=" . urlencode($token);

                    $mailer = new Mailer('log'); 
                    $mailer->send(
                        $email,
                        "Réinitialisation de votre mot de passe",
                        "Bonjour,\n\nPour réinitialiser votre mot de passe, cliquez sur ce lien (valide 1 heure) :\n$resetLink\n\nSi vous n'êtes pas à l'origine de cette demande, ignorez ce message."
                    );
                }

                $success = true;
            }
        }

        require_once BASE_PATH . '/app/Views/auth/forgot.php';
    }

    public function reset(): void
    {
        $errors = [];
        $success = false;

        $token = trim($_GET['token'] ?? '');
        if ($token === '') {
            http_response_code(400);
            echo "Token manquant.";
            return;
        }

        $user = User::findByResetToken($token);
        if (!$user) {
            http_response_code(404);
            echo "Lien invalide.";
            return;
        }

        // Vérifie expiration
        $expiresAt = $user['reset_expires_at'] ?? null;
        if (!$expiresAt || strtotime($expiresAt) < time()) {
            $errors[] = "Lien expiré. Merci de refaire une demande.";
            require_once BASE_PATH . '/app/Views/auth/reset.php';
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password  = (string)($_POST['password'] ?? '');
            $password2 = (string)($_POST['password2'] ?? '');

            if ($password !== $password2) $errors[] = "Les mots de passe ne correspondent pas.";
            if (!$this->passwordStrong($password)) {
                $errors[] = "Mot de passe trop faible (10 caractères min, 1 maj, 1 min, 1 chiffre, 1 spécial).";
            }

            if (empty($errors)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                User::updatePasswordAndClearReset((int)$user['id'], $hash);
                $success = true;
            }
        }

        require_once BASE_PATH . '/app/Views/auth/reset.php';
    }
}
