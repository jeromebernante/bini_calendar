<?php
class LoginController extends Controller
{

  public function __construct()
  {
    if (isset($_SESSION['bini_logged_in'])) {
      if (isset($_SESSION['user_id'])) {
        header("Location: " . PAGE . "dashboard");
        exit();
      }
    }
  }
  function index()
  {
    $data['current_page'] = "Login";
    $data['email_form_success_message'] = $_SESSION['email_form_success_message'] ?? null;
    $data['email_form_errors_messages'] = $_SESSION['email_form_errors_messages'] ?? null;
    $data['input_email_red_border'] = (!empty($data['email_form_errors_messages']) && (in_array("Email is invalid.", $_SESSION['email_form_errors_messages']) || in_array("Email is required.", $_SESSION['email_form_errors_messages']))) ? 'is-invalid' : '';
    $data['checkbox_recaptcha_red_border'] = !empty($data['email_form_errors_messages']) && in_array("reCAPTCHA verification failed. Please try again.", $_SESSION['email_form_errors_messages']) ? 'border-danger' : '';

    $data['input_email'] = $_SESSION['input_email'] ?? '';

    $this->loadView("login", $data);

    unset($_SESSION['email_form_errors_messages']);
    unset($_SESSION['input_email']);
    unset($_SESSION['email_form_success_message']);
  }

  function send_email_process()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $_SESSION['email_form_errors_messages'] = [];
      $_SESSION['input_email'] = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');

      if (empty($_SESSION['input_email'])) {
        $_SESSION['email_form_errors_messages'][] = "Email is required.";
      } else {
        // Validate email format
        if (!filter_var($_SESSION['input_email'], FILTER_VALIDATE_EMAIL)) {
          $_SESSION['email_form_errors_messages'][] = "Email is invalid.";
        }
      }

      // If there are validation errors, redirect back to the login/email page
      if (!empty($_SESSION['email_form_errors_messages'])) {
        header('Location: ' . PAGE . 'login');
        exit();
      }

      // reCAPTCHA verification
      $recaptcha_secret = "6Lfs0k0qAAAAAFIuoQqvm42oDbI84cT789i8D0cq";
      $recaptcha_response = $_POST['g-recaptcha-response'];
      $verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
      $response_data = json_decode($verify_response);

      if (!$response_data->success) {
        // reCAPTCHA validation failed
        $_SESSION['email_form_errors_messages'][] = "reCAPTCHA verification failed. Please try again.";
        header('Location: ' . PAGE . 'login');
        exit();
      }


      // Proceed authentication
      $USER = $this->loadModel("UserModel");
      $returnResult = $USER->selectUser(['email' => $_SESSION['input_email']]);

      if ($returnResult) {
        $USER->insertLoginAttempt($_SESSION['input_email'], true); // true is valid email

        // Prepare email content
        $recipient = $_SESSION['input_email'];
        $subject = "Secure Login Link";
        $body = "login link";
        $emailResult = send_email($recipient, $subject, $body);
        if ($emailResult !== true) {
          // Handle error in sending email
          $_SESSION['email_form_errors_messages'][] = $emailResult;
          header('Location: ' . PAGE . 'login');
          exit();
        }
      }

      $_SESSION['email_form_success_message'] = "If your email is authorized, we've sent you a secure login link to access your account.";
      unset($_SESSION['input_email']);
      header('Location: ' . PAGE . 'login');
      exit();
    } else {
      // Invalid request method
      header("Location: " . PAGE . "invalid_page");
      exit();
    }
  }





  function invalid_page() //invalid the page if the method if doesn't exist
  {
    $data['current_page'] = "Invalid Page";
    $this->loadView("404", $data);
  }
}
