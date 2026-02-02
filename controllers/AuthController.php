<?php
/**
 * AuthController
 *
 * Handles authentication (login, logout)
 */

class AuthController
{
    /**
     * Show login form
     */
    public function login()
    {
        // Redirect to dashboard if already logged in
        if (Auth::check()) {
            Response::redirect('/dashboard');
        }

        Response::view('auth/login', [], null); // No layout for login page
    }

    /**
     * Process login
     */
    public function doLogin()
    {
        try {
            // Validate CSRF token
            Security::checkCsrfToken();

            // Validate input
            $validator = new Validator();
            $valid = $validator->validate($_POST, [
                'username' => 'required',
                'password' => 'required'
            ]);

            if (!$valid) {
                Session::flash('error', $validator->getFirstError('username') ?? $validator->getFirstError('password'));
                Response::redirect('/auth/login');
            }

            $username = Security::sanitize($_POST['username']);
            $password = $_POST['password']; // Don't sanitize password

            // Attempt login
            $result = Auth::attempt($username, $password);

            if ($result['success']) {
                Session::flash('success', 'Welcome back, ' . Auth::user()['full_name'] . '!');
                Response::redirect($result['redirect']);
            } else {
                Session::flash('error', $result['message']);
                Response::redirect('/auth/login');
            }
        } catch (Exception $e) {
            Logger::error('Login error: ' . $e->getMessage());
            Session::flash('error', 'An error occurred during login. Please try again.');
            Response::redirect('/auth/login');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        Auth::logout();
        Session::flash('success', 'You have been logged out successfully');
        Response::redirect('/auth/login');
    }
}
