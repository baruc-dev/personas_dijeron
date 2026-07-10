<?php 
 session_start();
 if(isset($_SESSION['usuario']) || $_SESSION['usuario'] == 'admin')
    {
        header('Location: panel.php');
    }


if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    if(isset($_POST['user']) && isset($_POST['password']) && $_POST['user'] == 'admin' && $_POST['password'] == '12345678')
      {
       $_SESSION['usuario'] = 'admin';
       header('Location: panel.php');       
      }
  }



?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>100 personas dijeron</title>

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: "Segoe UI", sans-serif;
    }

    body {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background:
        radial-gradient(circle at top left, #7c3aed, transparent 35%),
        radial-gradient(circle at bottom right, #06b6d4, transparent 35%),
        linear-gradient(135deg, #0f172a, #1e1b4b, #312e81);
      color: white;
      overflow: hidden;
    }

    .login-card {
      width: 380px;
      padding: 40px 35px;
      border-radius: 24px;
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(18px);
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
      border: 1px solid rgba(255, 255, 255, 0.2);
      text-align: center;
    }

    .login-card h1 {
      font-size: 30px;
      margin-bottom: 10px;
      font-weight: 800;
    }

    .login-card p {
      margin-bottom: 30px;
      color: #dbeafe;
      font-size: 15px;
    }

    .input-group {
      text-align: left;
      margin-bottom: 18px;
    }

    .input-group label {
      display: block;
      margin-bottom: 7px;
      font-size: 14px;
      color: #e0f2fe;
    }

    .input-group input {
      width: 100%;
      padding: 14px 16px;
      border: none;
      border-radius: 14px;
      outline: none;
      background: rgba(255, 255, 255, 0.18);
      color: white;
      font-size: 15px;
    }

    .input-group input::placeholder {
      color: #cbd5e1;
    }

    .login-btn {
      width: 100%;
      margin-top: 12px;
      padding: 15px;
      border: none;
      border-radius: 16px;
      background: linear-gradient(135deg, #22d3ee, #8b5cf6, #ec4899);
      color: white;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: 0.25s ease;
      box-shadow: 0 12px 30px rgba(139, 92, 246, 0.45);
    }

    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 16px 40px rgba(236, 72, 153, 0.45);
    }

    .footer-text {
      margin-top: 22px;
      font-size: 13px;
      color: #c7d2fe;
    }

    .version {
      position: fixed;
      right: 18px;
      bottom: 14px;
      font-size: 13px;
      color: rgba(255, 255, 255, 0.7);
    }
  </style>
</head>
<body>

  <div class="login-card">
    <h1>100 personas dijeron</h1>
    <p>Inicia sesión para comenzar</p>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
      <div class="input-group">
        <label>Usuario</label>
        <input type="text" name="user" placeholder="Ingresa tu usuario" />
      </div>

      <div class="input-group">
        <label>Contraseña</label>
        <input type="password" name="password" placeholder="Ingresa tu contraseña" />
      </div>

      <button class="login-btn" type="submit">Entrar</button>
    </form>

    <div class="footer-text">
      Panel de acceso
    </div>
  </div>

  <div class="version">version 0.0.1</div>

</body>
</html>