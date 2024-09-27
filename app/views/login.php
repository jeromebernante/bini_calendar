<?php $this->loadView("components/head", $data); ?>

<body class="bg-image">
  <?php $this->loadView("components/top-navbar", $data); ?>
  <div class="container d-flex flex-column align-items-center p-3">
    <div class="card w-100 p-4" style="max-width:400px;">
      <div class="card-body p-3">
        <div class="text-center">
          <h3>Log in to your account</h3>
          <p>You will receive an email with a login link.</p>
        </div>
        <?php if (!empty($data['email_form_success_message'])) : ?>
          <div id="alertPlaceholder">
            <div class="alert alert-success" role="alert">
              <div>
                <i class="bi bi-check-circle me-2"></i>
                <span><?= $data['email_form_success_message'] ?></span>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <?php if (!empty($data['email_form_errors_messages'])) : ?>
          <div id="alertPlaceholder">
            <div class="alert alert-danger" role="alert">
              <ul class="mb-0">
                <?php foreach ($data['email_form_errors_messages'] as $message): ?>
                  <li class="text-break"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        <?php endif; ?>
        <form action="login/send_email_process" method="POST">

          <div class="form-floating mb-3">
            <input
              id="inputEmail"
              type="email"
              class="form-control <?= $data['input_email_red_border'] ?>"
              placeholder=""
              value="<?= $data['input_email'] ?>"
              name="email"
              required>
            <label for="inputEmail">Email</label>
          </div>

          <div
            class="mb-3 d-flex justify-content-center border rounded p-3 <?= $data['checkbox_recaptcha_red_border'] ?>"
            style="background-image: url('assets/img/captcha_bg.gif');">
            <div class="g-recaptcha" data-sitekey="6Lfs0k0qAAAAAChTLR023tGAFt1yvkSaOrkudjfy"></div>
          </div>

          <div>
            <button id="buttonSend" type="submit" class="btn btn-primary btn-lg w-100">
              <span class="button-text text-white">Send</span>
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
  <?php $this->loadView("components/scripts"); ?>
  <!-- custom.js here -->
</body>

</html>