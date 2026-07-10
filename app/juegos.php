<?php 
session_start();
if(!isset($_SESSION['usuario']) || $_SESSION['usuario'] != 'admin')
    {
        header('Location: index.php');
    }


    
$conexion = new mysqli('db', 'root','root','game-database');
$statement= $conexion->prepare('SELECT * FROM juegos WHERE estatus = 1 order by id desc');
$statement->execute();
$juegos = array();
$resultado = $statement->get_result();
if($resultado->num_rows>0)
    {
        while($fila = $resultado->fetch_assoc())
            {
                $juegos[] = $fila;
            }
    }


?>



<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lista de Juegos</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI,sans-serif;
}

body{
    min-height:100vh;
    padding:40px;

    background:
    radial-gradient(circle at top left,#7c3aed 0%,transparent 35%),
    radial-gradient(circle at bottom right,#06b6d4 0%,transparent 35%),
    linear-gradient(135deg,#0f172a,#1e1b4b,#312e81);

    color:white;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.header h1{
    font-size:36px;
}

.btnNuevo{

    padding:12px 20px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#06b6d4,#8b5cf6);
    color:white;
    font-weight:bold;
    cursor:pointer;
    transition:.25s;
}

.btnNuevo:hover{
    transform:translateY(-2px);
}

.table-container{

    background:rgba(255,255,255,.10);
    backdrop-filter:blur(18px);

    border:1px solid rgba(255,255,255,.15);

    border-radius:20px;
    overflow:hidden;

    box-shadow:0 20px 40px rgba(0,0,0,.3);

}

table{

    width:100%;
    border-collapse:collapse;

}

thead{

    background:rgba(255,255,255,.12);

}

th{

    padding:18px;
    text-align:left;
    font-size:15px;
    color:#FFF;

}

td{

    padding:18px;
    border-top:1px solid rgba(255,255,255,.08);

}

tbody tr{

    transition:.25s;

}

tbody tr:hover{

    background:rgba(255,255,255,.08);

}

.btn{

    border:none;
    padding:10px 18px;
    border-radius:10px;
    color:white;
    font-weight:600;
    cursor:pointer;
    transition:.25s;

}

.btnPantalla{

    background:#2563eb;

}

.btnPantalla:hover{

    background:#1d4ed8;

}

.btnAdmin{

    background:#8b5cf6;

}

.btnAdmin:hover{

    background:#7c3aed;

}

.version{

    position:fixed;
    right:20px;
    bottom:15px;
    color:rgba(255,255,255,.6);
}

.btnEliminar{

    background:#dc2626;

}

.btnEliminar:hover{

    background:#b91c1c;

}

</style>

</head>
<body>

<div class="header">

    <h1>🎮 Lista de Juegos</h1>

    <button class="btnNuevo" id="btnNuevo">
        + Nuevo Juego
    </button>

</div>

<div class="table-container">

<table>

<thead>

<tr>

<th>ID</th>
<th>Juego</th>
<th>Puntos A</th>
<th>Puntos B</th>
<th>Pantalla</th>
<th>Elegir Preguntas</th>
<th>Administrador</th>
<th>Eliminar</th>

</tr>

</thead>

<tbody>


<?php foreach ($juegos as $juego) : ?>

<tr>
<td><?php echo htmlspecialchars($juego['id']); ?></td>
<td><?php echo htmlspecialchars($juego['evento']); ?></td>
<td><?php  echo htmlspecialchars($juego['puntosA']); ?></td>
<td><?php  echo htmlspecialchars($juego['puntosB']); ?></td>
<td><a href="gaming.php?id=<?php echo htmlspecialchars($juego['id']).'&qu=1'; ?>"><button class="btn btnPantalla">Pantalla</button></a></td>
<td><a href="game_admin.php?id=<?php echo htmlspecialchars($juego['id']); ?>"><button class="btn btnAdmin">Elegir Preguntas</button></a></td>
<td><a href="score.php?id=<?php echo htmlspecialchars($juego['id']); ?>"><button class="btn btnAdmin">Administrador</button></a></td>
<td><button class="btn btnEliminar">🗑 Eliminar</button></td>
</tr>


<?php endforeach ?>


</tbody>

</table>

</div>

<div class="version">
Versión 0.0.1
</div>


<script>

    const btnNuevo = document.getElementById('btnNuevo');
    btnNuevo.addEventListener('click', ()=> window.location.href = 'nuevo-juego.php');

</script>


<?php if(isset($_GET['success']) && $_GET['success'] == '200') :  ?>

    <script>alert('Juego guardado correctamente!');</script>

    <?php endif ?>


</body>


</html>