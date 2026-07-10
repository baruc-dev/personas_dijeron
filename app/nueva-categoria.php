<?php 
session_start();
if(!isset($_SESSION['usuario']) || $_SESSION['usuario'] != 'admin')
    {
        header('Location: index.php');
    }


    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['categoria']))
        {
            
            $conexion = new mysqli('db', 'root','root','game-database');
            $statement = $conexion->prepare('INSERT INTO categorias (categoria) VALUES (?)');
            $statement->bind_param('s', $_POST['categoria']);
            $statement->execute();
            header('Location: nueva-categoria.php?success=200');
        }


?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Crear Categoría</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI,sans-serif;
}

body{

    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;

    background:
    radial-gradient(circle at top left,#7c3aed 0%,transparent 35%),
    radial-gradient(circle at bottom right,#06b6d4 0%,transparent 35%),
    linear-gradient(135deg,#0f172a,#1e1b4b,#312e81);

    color:white;

}

.card{

    width:420px;

    background:rgba(255,255,255,.10);
    backdrop-filter:blur(18px);

    border:1px solid rgba(255,255,255,.15);

    border-radius:25px;

    padding:35px;

    box-shadow:0 25px 45px rgba(0,0,0,.35);

}

.card h1{

    text-align:center;
    font-size:32px;
    margin-bottom:8px;

}

.card p{

    text-align:center;
    color:#cbd5e1;
    margin-bottom:30px;

}

.form-group{

    margin-bottom:25px;

}

label{

    display:block;
    margin-bottom:8px;
    font-weight:600;

}

input{

    width:100%;
    padding:14px 16px;

    border:none;
    outline:none;

    border-radius:14px;

    background:rgba(255,255,255,.15);

    color:white;

    font-size:15px;

}

input::placeholder{

    color:#d1d5db;

}

button{

    width:100%;
    padding:15px;

    border:none;

    border-radius:14px;

    background:linear-gradient(135deg,#06b6d4,#8b5cf6);

    color:white;

    font-size:16px;
    font-weight:bold;

    cursor:pointer;

    transition:.25s;

}

button:hover{

    transform:translateY(-2px);

    box-shadow:0 15px 35px rgba(139,92,246,.45);

}

.version{

    position:fixed;
    right:20px;
    bottom:15px;

    color:rgba(255,255,255,.6);

}

</style>

</head>
<body>

<div class="card">

    <h1>📂 Crear Categoría</h1>

    <p>Agrega una nueva categoría al sistema.</p>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

        <div class="form-group">

            <label>Categoría</label>

            <input
                type="text"
                placeholder="Ej. Baby Shower"
                name="categoria"
            >

        </div>

        <button type="submit">
            💾 Guardar Categoría
        </button>

    </form>

    <?php if(isset($_GET['success']) && $_GET['success'] == '200') : ?>
    <p>Categoria agregada exitosamente ✅</p>
    <?php endif ?>
</div>

<div class="version">
Versión 0.0.1
</div>

</body>
</html>