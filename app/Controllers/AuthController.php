<?php
// app/Controllers/AuthController.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/Services/Mailer.php';


class AuthController
{
    private function passwordStrong(string $pwd): bool
    {
        // 10 caractères min, 1 maj, 1 min, 1 chiffre, 1 spécial
        if (strlen($pwd) < 10) return false;
        if (!preg_match('/[A-Z]/', $pwd)) return false;
        if (!preg_match('/[a-z]/', $pwd)) return false;
        if (!preg_match('/[0-9]/', $pwd)) return false;
        if (!preg_match('/[^A-Za-z0-9]/', $pwd)) return false;
        return true;
    }

    public function register(): void
    {
        $errors = [];
        $old = [
            'firstname' => '',
            'lastname'  => '',
            'email'     => '',
            'phone'     => '',
            'address'   => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = trim($_POST['firstname'] ?? '');
            $lastname  = trim($_POST['lastname'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $phone     = trim($_POST['phone'] ?? '');
            $address   = trim($_POST['address'] ?? '');
            $password  = (string)($_POST['password'] ?? '');
            $password2 = (string)($_POST['password2'] ?? '');

            $old = compact('firstname','lastname','email','phone','address');

            if ($firstname === '' || $lastname === '') $errors[] = "Nom et prénom sont obligatoires.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
            if ($phone === '') $errors[] = "Numéro de GSM obligatoire.";
            if ($address === '') $errors[] = "Adresse postale obligatoire.";

            if ($password !== $password2) $errors[] = "Les mots de passe ne correspondent pas.";
            if (!$this->passwordStrong($password)) {
                $errors[] = "Mot de passe trop faible (10 caractères min, 1 maj, 1 min, 1 chiffre, 1 spécial).";
            }

            if (User::isEmailTaken($email)) $errors[] = "Cet email est déjà utilisé.";

            if (empty($errors)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $userId = User::createUser([
                    'firstname' => $firstname,
                    'lastname'  => $lastname,
                    'email'     => $email,
                    'phone'     => $phone,
                    'address'   => $address,
                    'password'  => $hash
                ]);

                // Connexion auto après inscription 
                $_SESSION['user_id'] = $userId;
                $_SESSION['role'] = 'user';
                $mailer = new Mailer('log'); 
                $mailer->send(
                $email,
                "Bienvenue chez Vite & Gourmand",
                "Bonjour $firstname,\n\nBienvenue chez Vite & Gourmand !\nVous pouvez désormais consulter nos menus et passer commande.\n\nÀ bientôt,\nJulie & José"
);


                header('Location: ?r=home');
                exit;
            }
        }

        require_once BASE_PATH . '/app/Views/auth/register.php';
    }

    public function login(): void
    {
        $errors = [];
        $oldEmail = '';

        $redirect = $_GET['redirect'] ?? '';
        $id = $_GET['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = (string)($_POST['password'] ?? '');
            $oldEmail = $email;

            $user = User::findByEmail($email);

            if (!$user || !(bool)$user['is_active']) {
                $errors[] = "Identifiants invalides.";
            } else {
                if (!password_verify($password, $user['password'])) {
                    $errors[] = "Identifiants invalides.";
                }
            }

            if (empty($errors) && $user) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['role'] = $user['role_name'];

                // Redirection si on venait d’un “commander”
                if ($redirect === 'menu_show' && $id) {
                    header('Location: ?r=menu_show&id=' . (int)$id);
                    exit;
                }

                header('Location: ?r=home');
                exit;
            }
        }

        require_once BASE_PATH . '/app/Views/auth/login.php';
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"], $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header('Location: ?r=home');
        exit;
    }
}

