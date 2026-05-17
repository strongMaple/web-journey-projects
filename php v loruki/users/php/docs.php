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
    <title>Loruki || Docs</title>
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
            <li>
              <a href="features.php">Features</a>
            </li>
            <li class="current">
              <a href="docs.php">Docs</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <!-- HEAD -->
    <section class="docs-head bg-primary py-3">
      <div class="container grid">
        <div>
          <h1 class="xl">Docs</h1>
          <p class="lead">Learn how to work with the loruki platform.</p>
        </div>
        <img src="users/image/download.jpg" alt="" />
      </div>
    </section>

    <!-- DOCS MAIN -->
    <section class="docs-main my-4">
      <div class="container grid">
        <div class="card bg-light py-3">
          <h3 class="my-2">Essentials</h3>
          <nav>
            <ul>
              <li><a class="text-primary" href="#">Introduction</a></li>
              <li><a href="#">About Loruki</a></li>
              <li><a href="#">Installation</a></li>
            </ul>
          </nav>

          <h3 class="my-2">Deployment</h3>
          <nav>
            <ul>
              <li><a href="#">Setting up a container</a></li>
              <li><a href="#">Using the CLI</a></li>
              <li><a href="#">Managing resources</a></li>
              <li><a href="#">Upgrade & downgrade</a></li>
            </ul>
          </nav>
        </div>

        <div class="card">
          <h2>Introduction</h2>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur
            temporibus et quod natus tenetur atque vitae autem fugiat animi
            exercitationem omnis quasi repudiandae, accusantium iusto voluptas
            architecto ducimus officia voluptate?
          </p>

          <div class="alert alert-success">
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quo, quia.
          </div>

          <h3>Lorem, ipsum dolor.</h3>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio
            eius voluptatum aperiam. Sapiente magnam harum explicabo tempore,
            quas iure neque.
          </p>
          <a href="#" class="btn btn-primary">Install CLI</a>

          <h3>Requirements</h3>
          <ul>
            <li>Windows 10, Mac OSX, Linux</li>
            <li>Node.js v12 or higher</li>
          </ul>

          <h3>Install</h3>
          <p>Mac (Homebrew)</p>
          <pre><code>$ brew install loruki-cli</code></pre>

          <p>NPM</p>
          <pre><code>$ npm install loruki-cli</code></pre>

          <p>Yarn</p>
          <pre><code>$ yarn install loruki-cli</code></pre>
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
