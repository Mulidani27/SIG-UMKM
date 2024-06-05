<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login/Register Panel</title>
    <link
      href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  </head>
  <body>
    <div class="login-reg-panel">
      <div class="login-info-box">
        <h2>Sudah punya akun?</h2>
        <p>Jika sudah klik di bawah ini</p>
        <label id="label-register" for="log-reg-show">Masuk</label>
        <input
          type="radio"
          name="active-log-panel"
          id="log-reg-show"
          checked="checked"
        />
      </div>

      <div class="register-info-box">
        <h2>Belum punya akun?</h2>
        <p>Jika belum klik di bawah ini</p>
        <label id="label-login" for="log-login-show">Daftar</label>
        <input type="radio" name="active-log-panel" id="log-login-show" />
      </div>

      <div class="white-panel">
        <div class="login-show">
          <h2>MASUK</h2>
          <input type="text" placeholder="Email" />
          <input type="password" placeholder="Kata sandi" />
          <input type="button" value="Masuk" />
          <a href="">Lupa sandi?</a>
        </div>
        <div class="register-show">
          <h2>DAFTAR</h2>
          <input type="text" placeholder="Email" />
          <input type="password" placeholder="Kata sandi" />
          <input type="password" placeholder="Konfirmasi kata sandi" />
          <input type="button" value="Daftar" />
        </div>
      </div>
    </div>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
      $(document).ready(function () {
        $(".login-info-box").fadeOut();
        $(".login-show").addClass("show-log-panel");
      });

      $('.login-reg-panel input[type="radio"]').on("change", function () {
        if ($("#log-login-show").is(":checked")) {
          $(".register-info-box").fadeOut();
          $(".login-info-box").fadeIn();
          $(".white-panel").addClass("right-log");
          $(".register-show").addClass("show-log-panel");
          $(".login-show").removeClass("show-log-panel");
        } else if ($("#log-reg-show").is(":checked")) {
          $(".register-info-box").fadeIn();
          $(".login-info-box").fadeOut();
          $(".white-panel").removeClass("right-log");
          $(".login-show").addClass("show-log-panel");
          $(".register-show").removeClass("show-log-panel");
        }
      });
    </script>
  </body>
</html>