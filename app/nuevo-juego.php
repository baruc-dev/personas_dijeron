<?php 

session_start();

$conexion = new mysqli('db', 'root','root','game-database');

$statement = $conexion->prepare('SELECT * FROM categorias WHERE estatus = 1');
$statement->execute();
$resultado = $statement->get_result();
$categorias = array();
if($resultado->num_rows>0)
    {

        while($fila = $resultado->fetch_assoc())
            {
                $categorias[] = $fila;
            }


    }


if(!isset($_SESSION['usuario']) || $_SESSION['usuario'] != 'admin')
    {
        header('Location: index.php');
    }



    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['evento']) && isset($_POST['categoria']))
        {
            $conexion = new mysqli('db', 'root','root','game-database');
            $statement1 = $conexion->prepare('INSERT INTO juegos (evento, categoria, puntosA, puntosB) VALUES (?,?,0,0)');
            $statement1->bind_param('si', $_POST['evento'], $_POST['categoria']);
            $statement1->execute();
            header('Location: juegos.php');
        }





?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Crear Juego</title>

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

    width:450px;

    background:rgba(255,255,255,.10);
    backdrop-filter:blur(18px);

    border:1px solid rgba(255,255,255,.15);

    border-radius:25px;

    padding:35px;

    box-shadow:0 25px 45px rgba(0,0,0,.35);

}

.card h1{

    text-align:center;
    margin-bottom:8px;
    font-size:32px;

}

.card p{

    text-align:center;
    color:#cbd5e1;
    margin-bottom:30px;

}

.form-group{

    margin-bottom:22px;

}

label{

    display:block;
    margin-bottom:8px;
    font-weight:600;

}

input,
select{

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

select option{

    color:#111827;

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

    <h1>🎮 Crear Juego</h1>

    <p>Complete la información del nuevo juego.</p>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

        <div class="form-group">

            <label>Evento (Juego)</label>

            <input
                type="text"
                placeholder="Ej. Fiesta Juanito #3"
                name="evento"
            >

        </div>

        <div class="form-group">

            <label>Categoría</label>

            <select name="categoria">

                <option selected disabled>Seleccione una categoría</option>

                <?php foreach ($categorias as $categoria) : ?>

                <option value="<?php echo $categoria['id']; ?>"><?php echo htmlspecialchars($categoria['categoria']); ?></option>

                <?php endforeach ?>
               

            </select>

        </div>

        <button type="submit">
            💾 Guardar Juego
        </button>

    </form>

</div>

<div class="version">
Versión 0.0.1
</div>

</body>
</html>