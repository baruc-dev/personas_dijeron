<?php 
session_start();
if(!isset($_SESSION['usuario']) || $_SESSION['usuario'] != 'admin')
    {
        header('Location: index.php');
    }

    
$conexion = new mysqli('db', 'root','root','game-database');


$statement = $conexion->prepare('SELECT * FROM categorias WHERE estatus = 1');
$statement->execute();
$categorias = array();
$resultado =$statement->get_result();
if($resultado->num_rows > 0)
    {
        while($fila = $resultado->fetch_assoc())
            {
                $categorias[] = $fila;
            }
    }





    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pregunta']) && isset($_POST['categoria']) && $_POST['pregunta'] != '' && $_POST['categoria'] != '')
        {
    
    
            $pregunta = $_POST['pregunta'];
            $categoria = $_POST['categoria'];
            $r1 = $_POST['r1'];
            $r2 = $_POST['r2'];
            $r3 = $_POST['r3'];
            $r4 = $_POST['r4'];
            $r5 = $_POST['r5'];

            $respuestas = [$r1, $r2, $r3, $r4 , $r5];


            $statement1 = $conexion->prepare('INSERT INTO preguntas (pregunta, categoria) VALUES (?,?)');
            $statement1->bind_param('si', $pregunta, $categoria);
            $statement1->execute();
            $idPregunta = $conexion->insert_id;
            $statement1->close();


            $statement2 = $conexion->prepare('INSERT INTO respuestas (pregunta, respuesta, valor) VALUES (?,?,40)');
            $statement2->bind_param('is',$idPregunta, $r1);
            $statement2->execute();


            $statement3 = $conexion->prepare('INSERT INTO respuestas (pregunta, respuesta, valor) VALUES (?,?,30)');
            $statement3->bind_param('is',$idPregunta, $r2);
            $statement3->execute();


            $statement4 = $conexion->prepare('INSERT INTO respuestas (pregunta, respuesta, valor) VALUES (?,?,15)');
            $statement4->bind_param('is',$idPregunta, $r3);
            $statement4->execute();

            $statement5 = $conexion->prepare('INSERT INTO respuestas (pregunta, respuesta, valor) VALUES (?,?,10)');
            $statement5->bind_param('is',$idPregunta, $r4);
            $statement5->execute();



            $statement6 = $conexion->prepare('INSERT INTO respuestas (pregunta, respuesta, valor) VALUES (?,?,5)');
            $statement6->bind_param('is',$idPregunta, $r5);
            $statement6->execute();



            header('Location: nueva-pregunta.php?success=200');







        }

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Crear Pregunta</title>

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

    width:800px;

    background:rgba(255,255,255,.10);
    backdrop-filter:blur(18px);

    border:1px solid rgba(255,255,255,.15);

    border-radius:25px;

    padding:35px;

    box-shadow:0 25px 45px rgba(0,0,0,.35);

}

h1{

    text-align:center;
    margin-bottom:10px;

}

.subtitle{

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
    padding:14px;

    border:none;
    outline:none;

    border-radius:12px;

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

table{

    width:100%;
    margin-top:15px;
    border-collapse:collapse;

}

th{

    padding:14px;
    background:rgba(255,255,255,.10);
    border-bottom:1px solid rgba(255,255,255,.15);

}

td{

    padding:10px;

}

td input{

    margin:0;

}

input[readonly]{

    background:rgba(255,255,255,.08);
    text-align:center;
    font-weight:bold;
    cursor:not-allowed;

}

button{

    margin-top:30px;

    width:100%;

    padding:16px;

    border:none;

    border-radius:14px;

    background:linear-gradient(135deg,#06b6d4,#8b5cf6);

    color:white;

    font-size:17px;
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

<h1>❓ Crear Pregunta</h1>

<p class="subtitle">
Capture la pregunta y sus cinco respuestas.
</p>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

<div class="form-group">

<label>Pregunta</label>

<input
required
name="pregunta"
type="text"
placeholder="Ej. Menciona un superhéroe que vuele">


</div>

<div class="form-group">

<label>Categoría</label>

<select required name="categoria">

<option  selected disabled required>Seleccione una categoría</option>

<?php foreach ($categorias as $categoria) : ?>


    <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['categoria']; ?></option>
<?php endforeach ?>

</select>

</div>

<label>Respuestas</label>

<table>

<thead>

<tr>

<th>#</th>
<th>Palabra / Respuesta</th>
<th>Puntos</th>

</tr>

</thead>

<tbody>

<tr>
<td>1</td>
<td><input type="text" placeholder="Respuesta 1"  name="r1" required></td>
<td><input type="text" value="40" readonly></td>
</tr>

<tr>
<td>2</td>
<td><input type="text" placeholder="Respuesta 2"  name="r2" required></td>
<td><input type="text" value="30" readonly></td>
</tr>

<tr>
<td>3</td>
<td><input type="text" placeholder="Respuesta 3"  name="r3" required></td>
<td><input type="text" value="15" readonly></td>
</tr>

<tr>
<td>4</td>
<td><input type="text" placeholder="Respuesta 4" name="r4" required></td>
<td><input type="text" value="10" readonly></td>
</tr>

<tr>
<td>5</td>
<td><input type="text" placeholder="Respuesta 5"  name="r5" required></td>
<td><input type="text" value="5" readonly></td>
</tr>

</tbody>

</table>

<button type="submit">
💾 Guardar Pregunta
</button>

</form>

</div>

<div class="version">
Versión 0.0.1
</div>



<?php if(isset($_GET['success']) && $_GET['success'] == '200') :  ?>

    <script>alert('¡Pregunta guardada correctamente!');</script>

    <?php endif ?>

</body>
</html>