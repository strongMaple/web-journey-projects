<!-- PHP CODE here 👇👇-->
 <?php
 session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <link rel="stylesheet" href="../style/style.css" />
    <link rel="stylesheet" href="../font/fontawesome-free-6.6.0-web/css/all.min.css"/>
    <title>Loruki || Features</title>
  </head>
  <body>
    <!-- NavBar -->
    <div class="navbar">
      <div class="container flex">
        <h1 class="logo">Loruki</h1>
        <nav>
          <ul>
            <li>
              <a href="../../index.php">Home</a>
            </li>
            <li class="current">
              <a href="features.php">Features</a>
            </li>
            <li>
              <a href="docs.php">Docs</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <!-- HEAD -->
    <section class="features-head bg-primary py-3">
      <div class="container grid">
        <div>
          <h1 class="xl">Features</h1>
          <p class="lead">
            Check out the features of Loruki that separate us from the
            competition
          </p>
        </div>
        <img src="users/image/image.png" alt="" />
      </div>
    </section>

    <!-- SUB-HEAD -->
    <section class="features-sub-head bg-light py-3">
      <div class="container grid">
        <div>
          <h1 class="md">The Loruki Platform</h1>
          <p>
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsam
            harum molestiae nulla minima deleniti quidem aliquam sit laboriosam
            beatae illo laudantium quo nobis, quos rem quis dolorum repellendus
            neque maiores.
          </p>
        </div>
        <img src="users/image/image.png" alt="" />
      </div>
    </section>

    <section class="features-main my-2">
      <div class="container grid grid-3">
        <div class="card flex">
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Officiis
            quas neque similique. Voluptatibus repellat ipsam aut nam tenetur
            alias, consequuntur, impedit ullam magni fugit optio nihil omnis
            repudiandae nisi dignissimos.
          </p>
        </div>

        <div class="card flex">
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam,
            maxime.
          </p>
        </div>

        <div class="card flex">
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi eius
            quaerat reiciendis ex eos dolorem.
          </p>
        </div>

        <div class="card flex">
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Consequuntur corporis tempora neque minima necessitatibus quo
            laborum cumque ipsum itaque nihil.
          </p>
        </div>

        <div class="card flex">
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor vero
            amet, quisquam tempore suscipit et quo fuga!
          </p>
        </div>

        <div class="card flex">
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit.
            Voluptatibus?
          </p>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer bg-dark py-5">
      <div class="container grid grid-3">
        <div>
          <h1>Loruki</h1>
          <p>Copyright &copy; 2025</p>
        </div>
        <nav>
          <ul>
            <li><a href="../../index.php">Home</a></li>
            <li><a href="features.php">Features</a></li>
            <li><a href="docs.php">Docs</a></li>
          </ul>
        </nav>
      </div>
    </footer>

      <!-- User Info_Icon -->
    <div class="login">
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="user.php" title="User Profile"><i class="fas fa-user-circle"></i></a>
    <?php else: ?>
        <a href="login.php" title="Login"><i class="fas fa-user-circle"></i></a>
    <?php endif; ?>
</div>

  </body>
</html>
