<?php 
session_start();
if(!isset($_SESSION['usuario']) || $_SESSION['usuario'] != 'admin')
    {
        header('Location: index.php');
    }

 


?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel de Control</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI, sans-serif;
}

body{
    min-height:100vh;
    background:
    radial-gradient(circle at top left,#7c3aed 0%,transparent 35%),
    radial-gradient(circle at bottom right,#06b6d4 0%,transparent 35%),
    linear-gradient(135deg,#0f172a,#1e1b4b,#312e81);

    display:flex;
    justify-content:center;
    align-items:center;
    color:white;
}

.container{
    width:90%;
    max-width:1100px;
}

.header{
    margin-bottom:40px;
    text-align:center;
}

.header h1{
    font-size:42px;
    font-weight:700;
}

.header p{
    margin-top:10px;
    color:#d1d5db;
}

.grid{

    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:25px;

}

.card{

    height:180px;

    background:rgba(255,255,255,.12);
    backdrop-filter:blur(18px);

    border:1px solid rgba(255,255,255,.15);

    border-radius:25px;

    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;

    text-decoration:none;
    color:white;

    transition:.3s;

    box-shadow:0 20px 45px rgba(0,0,0,.25);

}

.card:hover{

    transform:translateY(-8px) scale(1.02);

    background:rgba(255,255,255,.18);

    box-shadow:0 30px 55px rgba(139,92,246,.35);

}

.icon{

    font-size:60px;
    margin-bottom:18px;

}

.title{

    font-size:22px;
    font-weight:600;

}

.version{

    position:fixed;
    right:20px;
    bottom:15px;
    color:rgba(255,255,255,.65);
    font-size:14px;

}

</style>

</head>
<body>

<div class="container">

    <div class="header">
        <h1>100 Personas Dijeron</h1>
        <p>Panel de Administración</p>
    </div>

    <div class="grid">

        <a href="juegos.php" class="card">
            <div class="icon">🎮</div>
            <div class="title">Lista de Juegos</div>
        </a>

        <a href="nuevo-juego.php" class="card">
            <div class="icon">➕</div>
            <div class="title">Crear Juego Nuevo</div>
        </a>

        <a href="nueva-categoria.php" class="card">
            <div class="icon">📂</div>
            <div class="title">Crear Categoría</div>
        </a>

        <a href="nueva-pregunta.php" class="card">
            <div class="icon">❓</div>
            <div class="title">Crear Pregunta</div>
        </a>

    </div>

</div>

<div class="version">
    Versión 0.0.1
</div>

</body>
</html>