<?php
require_once 'app/config/database.php';
require_once 'app/models/AccountModel.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/config/oauth_config.php';

class AccountController
{
    private $accountModel;
    private $db;

    public function __construct()
    {
        SessionHelper::start();

        $this->db = (new Database())->getConnection();

        if (!$this->db) {
            die('Không kết nối được database. Hãy chạy file setup_database.php hoặc import database.sql trước.');
        }

        $this->accountModel = new AccountModel($this->db);
    }

    public function register()
    {
        include 'app/views/account/register.php';
    }

    public function login()
    {
        include 'app/views/account/login.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('Account/register'));
            exit();
        }

        $username = $_POST['username'] ?? '';
        $fullname = $_POST['fullname'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmpassword'] ?? '';
        $role = $_POST['role'] ?? 'user';
        $errors = [];

        if (trim($username) === '') {
            $errors['username'] = 'Vui lòng nhập username!';
        }

        if (trim($fullname) === '') {
            $errors['fullname'] = 'Vui lòng nhập họ tên!';
        }

        if ($password === '') {
            $errors['password'] = 'Vui lòng nhập mật khẩu!';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự!';
        }

        if ($password !== $confirmPassword) {
            $errors['confirmPass'] = 'Mật khẩu xác nhận chưa khớp!';
        }

        if (!in_array($role, ['admin', 'user'])) {
            $role = 'user';
        }

        if ($this->accountModel->getAccountByUsername($username)) {
            $errors['account'] = 'Tài khoản này đã được đăng ký!';
        }

        if (!empty($errors)) {
            include 'app/views/account/register.php';
            return;
        }

        $result = $this->accountModel->save($username, $fullname, $password, $role);

        if ($result) {
            header('Location: ' . url('Account/login'));
            exit();
        }

        $errors['system'] = 'Đăng ký thất bại. Vui lòng thử lại.';
        include 'app/views/account/register.php';
    }

    public function checkLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('Account/login'));
            exit();
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $account = $this->accountModel->getAccountByUsername($username);

        if ($account && password_verify($password, $account->password)) {
            $this->setLoginSession($account);
            header('Location: ' . url('Product'));
            exit();
        }

        $error = $account ? 'Mật khẩu không đúng!' : 'Không tìm thấy tài khoản!';
        include 'app/views/account/login.php';
    }

    public function logout()
    {
        unset(
            $_SESSION['username'],
            $_SESSION['fullname'],
            $_SESSION['role'],
            $_SESSION['email'],
            $_SESSION['provider']
        );

        header('Location: ' . url('Product'));
        exit();
    }

    // ================= GOOGLE LOGIN =================

    public function googleLogin()
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;

        $params = [
            'client_id' => GOOGLE_CLIENT_ID,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'access_type' => 'online',
            'prompt' => 'select_account'
        ];

        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);

        header('Location: ' . $authUrl);
        exit();
    }

    public function googleCallback()
    {
        if (!isset($_GET['code'])) {
            $error = 'Google không trả về mã xác thực.';
            include 'app/views/account/login.php';
            return;
        }

        if (!isset($_GET['state']) || !isset($_SESSION['oauth_state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
            $error = 'Phiên đăng nhập Google không hợp lệ.';
            include 'app/views/account/login.php';
            return;
        }

        unset($_SESSION['oauth_state']);

        $tokenData = $this->httpPost('https://oauth2.googleapis.com/token', [
            'code' => $_GET['code'],
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'grant_type' => 'authorization_code'
        ]);

        if (empty($tokenData['access_token'])) {
            $error = 'Không lấy được access token từ Google.';
            include 'app/views/account/login.php';
            return;
        }

        $userInfo = $this->httpGet('https://www.googleapis.com/oauth2/v2/userinfo', [
            'Authorization: Bearer ' . $tokenData['access_token']
        ]);

        if (empty($userInfo['id']) || empty($userInfo['email'])) {
            $error = 'Không lấy được thông tin tài khoản Google.';
            include 'app/views/account/login.php';
            return;
        }

        $providerId = $userInfo['id'];
        $email = $userInfo['email'];
        $fullname = $userInfo['name'] ?? $email;

        $account = $this->findOrCreateSocialAccount('google', $providerId, $email, $fullname);

        $this->setLoginSession($account);

        header('Location: ' . url('Product'));
        exit();
    }

    // ================= GITHUB LOGIN =================

    public function githubLogin()
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;

        $params = [
            'client_id' => GITHUB_CLIENT_ID,
            'redirect_uri' => GITHUB_REDIRECT_URI,
            'scope' => 'read:user user:email',
            'state' => $state
        ];

        $authUrl = 'https://github.com/login/oauth/authorize?' . http_build_query($params);

        header('Location: ' . $authUrl);
        exit();
    }

    public function githubCallback()
    {
        if (!isset($_GET['code'])) {
            $error = 'GitHub không trả về mã xác thực.';
            include 'app/views/account/login.php';
            return;
        }

        if (!isset($_GET['state']) || !isset($_SESSION['oauth_state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
            $error = 'Phiên đăng nhập GitHub không hợp lệ.';
            include 'app/views/account/login.php';
            return;
        }

        unset($_SESSION['oauth_state']);

        $tokenData = $this->httpPost('https://github.com/login/oauth/access_token', [
            'client_id' => GITHUB_CLIENT_ID,
            'client_secret' => GITHUB_CLIENT_SECRET,
            'code' => $_GET['code'],
            'redirect_uri' => GITHUB_REDIRECT_URI
        ], [
            'Accept: application/json'
        ]);

        if (empty($tokenData['access_token'])) {
            $error = 'Không lấy được access token từ GitHub.';
            include 'app/views/account/login.php';
            return;
        }

        $userInfo = $this->httpGet('https://api.github.com/user', [
            'Authorization: Bearer ' . $tokenData['access_token'],
            'User-Agent: THPTPMNM-PHP-App',
            'Accept: application/vnd.github+json'
        ]);

        if (empty($userInfo['id'])) {
            $error = 'Không lấy được thông tin tài khoản GitHub.';
            include 'app/views/account/login.php';
            return;
        }

        $email = $userInfo['email'] ?? '';

        if ($email === '') {
            $emails = $this->httpGet('https://api.github.com/user/emails', [
                'Authorization: Bearer ' . $tokenData['access_token'],
                'User-Agent: THPTPMNM-PHP-App',
                'Accept: application/vnd.github+json'
            ]);

            if (is_array($emails)) {
                foreach ($emails as $item) {
                    if (!empty($item['primary']) && !empty($item['verified']) && !empty($item['email'])) {
                        $email = $item['email'];
                        break;
                    }
                }
            }
        }

        if ($email === '') {
            $email = 'github_' . $userInfo['id'] . '@github.local';
        }

        $providerId = (string)$userInfo['id'];
        $fullname = $userInfo['name'] ?? $userInfo['login'] ?? $email;

        $account = $this->findOrCreateSocialAccount('github', $providerId, $email, $fullname);

        $this->setLoginSession($account);

        header('Location: ' . url('Product'));
        exit();
    }

    // ================= SOCIAL ACCOUNT HELPERS =================

    private function findOrCreateSocialAccount($provider, $providerId, $email, $fullname)
    {
        $account = $this->getAccountByProvider($provider, $providerId);

        if ($account) {
            return $account;
        }

        $account = $this->getAccountByEmail($email);

        if ($account) {
            $query = "UPDATE account 
                      SET provider = :provider, provider_id = :provider_id 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':provider', $provider);
            $stmt->bindParam(':provider_id', $providerId);
            $stmt->bindParam(':id', $account->id);
            $stmt->execute();

            return $this->getAccountByProvider($provider, $providerId);
        }

        $username = $this->createUniqueUsername($provider, $email);
        $password = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
        $role = 'user';

        $query = "INSERT INTO account (username, fullname, email, password, role, provider, provider_id)
                  VALUES (:username, :fullname, :email, :password, :role, :provider, :provider_id)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':provider', $provider);
        $stmt->bindParam(':provider_id', $providerId);
        $stmt->execute();

        return $this->getAccountByProvider($provider, $providerId);
    }

    private function getAccountByProvider($provider, $providerId)
    {
        $query = "SELECT * FROM account 
                  WHERE provider = :provider AND provider_id = :provider_id 
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':provider', $provider);
        $stmt->bindParam(':provider_id', $providerId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    private function getAccountByEmail($email)
    {
        $query = "SELECT * FROM account 
                  WHERE email = :email 
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    private function createUniqueUsername($provider, $email)
    {
        $base = explode('@', $email)[0];
        $base = preg_replace('/[^a-zA-Z0-9_]/', '', $base);

        if ($base === '') {
            $base = $provider . '_user';
        }

        $username = $base;
        $count = 1;

        while ($this->accountModel->getAccountByUsername($username)) {
            $username = $base . '_' . $count;
            $count++;
        }

        return $username;
    }

    private function setLoginSession($account)
    {
        $_SESSION['username'] = $account->username;
        $_SESSION['fullname'] = $account->fullname;
        $_SESSION['role'] = $account->role ?? 'user';
        $_SESSION['email'] = $account->email ?? '';
        $_SESSION['provider'] = $account->provider ?? 'local';
    }

    // ================= HTTP HELPERS =================

    private function httpPost($url, $data, $headers = [])
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return [];
        }

        curl_close($ch);

        $decoded = json_decode($response, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function httpGet($url, $headers = [])
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return [];
        }

        curl_close($ch);

        $decoded = json_decode($response, true);

        return is_array($decoded) ? $decoded : [];
    }
}