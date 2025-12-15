<?php
namespace App\Controller;

use App\Repositorys\ArbeiterRepository as RepositorysArbeiterRepository;
use App\View\ViewHelper;

class LoginController
{
    private $repository;
    private ViewHelper $viewHelper;

    public function __construct()
    {
        $this->repository = new RepositorysArbeiterRepository();
        $this->viewHelper = new ViewHelper();
    }

    public function index()
    {
        $acceptJson = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

        if (isset($_SESSION['user'])) {
            if ($acceptJson) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Already logged in']);
                exit;
            }
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->repository->findeNachUser($username);

            if ($user) {

                $hashedPassword = crypt($password, $username);

                if ($hashedPassword === $user['password']) {
                    $_SESSION['user'] = $user['id'];

                    if ($acceptJson) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Login successful']);
                        exit;
                    }

                    header('Location: /');
                    exit;
                }
            }

            if ($acceptJson) {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
                exit;
            }
        }
            return $this->viewHelper->render('index', [
            ]);
    } 
}
