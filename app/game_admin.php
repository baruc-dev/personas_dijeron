<?php 


session_start();
if(!isset($_SESSION['usuario']) || $_SESSION['usuario'] != 'admin')
    {
        header('Location: index.php');
    }


       if(!isset($_GET['id']) || $_GET['id'] == '')
        {
            header('Location: juegos.php');
        }


    $conexion = new mysqli('db', 'root','root','game-database');

    $statement = $conexion->prepare('SELECT j.*, c.categoria as cate_nombre FROM juegos j JOIN categorias c WHERE j.id = ?');
    $statement->bind_param('i',$_GET['id']);
    $statement->execute();
    $resultado = $statement->get_result();
    if($resultado->num_rows > 0)
        {
            $juego = $resultado->fetch_assoc();
        }

    $statement->close();



    $statement2 = $conexion->prepare('SELECT * FROM preguntas WHERE categoria = ?');
    $statement2->bind_param('i', $juego['categoria']);
    $statement2->execute();
    $preguntas = array();
    $resultadoP = $statement2->get_result();
    if($resultadoP->num_rows > 0)
        {
            while($fila = $resultadoP->fetch_assoc())
                {
                    $preguntas[] = $fila;
                }
        }



    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pregunta1']) && isset($_POST['pregunta2']) && isset($_POST['pregunta3']) && isset($_POST['pregunta4']))
        {

        $statement3 = $conexion->prepare('UPDATE juegos SET pregunta1 = ?, pregunta2 = ?, pregunta3 = ?, pregunta4 = ? WHERE id = ?');
        $statement3->bind_param('iiiii', $_POST['pregunta1'], $_POST['pregunta2'], $_POST['pregunta3'], $_POST['pregunta4'], $_GET['id']);
        $statement3->execute();
        header('Location: juegos.php?success=200');
        






        }    


?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Seleccionar Preguntas</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Segoe UI",sans-serif;
}

body{

    min-height:100vh;
    padding:40px 20px;

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

    width:100%;
    max-width:750px;

    padding:35px;

    background:rgba(255,255,255,.10);
    backdrop-filter:blur(18px);

    border:1px solid rgba(255,255,255,.15);
    border-radius:25px;

    box-shadow:0 25px 50px rgba(0,0,0,.35);

}

.header{

    text-align:center;
    margin-bottom:30px;

}

.header h1{

    font-size:32px;
    margin-bottom:8px;

}

.header p{

    color:#cbd5e1;

}

.game-info{

    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;

    padding:18px 20px;
    margin-bottom:30px;

    background:rgba(255,255,255,.10);
    border:1px solid rgba(255,255,255,.12);
    border-radius:16px;

}

.game-name{

    font-size:17px;
    font-weight:600;

}

.category-badge{

    padding:9px 16px;

    background:linear-gradient(135deg,#06b6d4,#8b5cf6);

    border-radius:50px;

    font-size:14px;
    font-weight:700;
    white-space:nowrap;

}

.form-group{

    margin-bottom:20px;

}

label{

    display:block;
    margin-bottom:8px;

    font-weight:600;
    color:#f8fafc;

}

select{

    width:100%;
    padding:15px 16px;

    border:none;
    outline:none;

    border-radius:14px;

    background:rgba(255,255,255,.15);
    color:white;

    font-size:15px;
    cursor:pointer;

}

select:focus{

    box-shadow:0 0 0 3px rgba(34,211,238,.25);

}

select option{

    color:#111827;
    background:white;

}

.question-number{

    display:inline-flex;
    justify-content:center;
    align-items:center;

    width:28px;
    height:28px;

    margin-right:8px;

    border-radius:8px;

    background:rgba(255,255,255,.15);

}

.actions{

    display:flex;
    gap:15px;
    margin-top:30px;

}

.btn{

    flex:1;

    padding:15px;

    border:none;
    border-radius:14px;

    color:white;
    font-size:16px;
    font-weight:700;

    cursor:pointer;
    transition:.25s;

}

.btn-save{

    background:linear-gradient(135deg,#06b6d4,#8b5cf6);

}

.btn-back{

    background:rgba(255,255,255,.13);
    border:1px solid rgba(255,255,255,.15);

}

.btn:hover{

    transform:translateY(-2px);
    box-shadow:0 15px 30px rgba(0,0,0,.25);

}

.version{

    position:fixed;
    right:20px;
    bottom:15px;

    color:rgba(255,255,255,.6);
    font-size:13px;

}

@media(max-width:600px){

    .card{
        padding:25px 20px;
    }

    .game-info{
        flex-direction:column;
        align-items:flex-start;
    }

    .actions{
        flex-direction:column-reverse;
    }

}

</style>

</head>

<body>

<div class="card">

    <div class="header">

        <h1>🎯 Seleccionar Preguntas</h1>

        <p>
            Elige las cuatro preguntas que aparecerán durante el juego.
        </p>

    </div>

    <div class="game-info">

        <div>

            <div class="game-name">
                <?php echo htmlspecialchars($juego['evento']); ?>
            </div>

            <small style="color:#cbd5e1;">
                Juego seleccionado
            </small>

        </div>

        <div class="category-badge">
            Categoría: <?php echo htmlspecialchars($juego['cate_nombre']); ?>
        </div>

    </div>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>" method="POST">

        <input type="hidden" name="id_juego" value="14">

        <div class="form-group">

            <label for="pregunta1">

                <span class="question-number">1</span>
                Primera pregunta

            </label>

            <select id="pregunta1" name="pregunta1" required>

                <option value="" selected disabled>
                    Seleccione la primera pregunta
                </option>

               <?php foreach ($preguntas as $pregunta) :?>
               
               <option value="<?php echo htmlspecialchars($pregunta['id']); ?>">
                    <?php echo htmlspecialchars($pregunta['pregunta']); ?>
                </option>
               
                <?php endforeach ?>

            </select>

        </div>

        <div class="form-group">

            <label for="pregunta2">

                <span class="question-number">2</span>
                Segunda pregunta

            </label>

            <select id="pregunta2" name="pregunta2" required>

                <option value="" selected disabled>
                    Seleccione la segunda pregunta
                </option>

               <?php foreach ($preguntas as $pregunta) :?>
               
               <option value="<?php echo htmlspecialchars($pregunta['id']); ?>">
                    <?php echo htmlspecialchars($pregunta['pregunta']); ?>
                </option>
               
                <?php endforeach ?>

            </select>

        </div>

        <div class="form-group">

            <label for="pregunta3">

                <span class="question-number">3</span>
                Tercera pregunta

            </label>

            <select id="pregunta3" name="pregunta3" required>

                <option value="" selected disabled>
                    Seleccione la tercera pregunta
                </option>
<?php foreach ($preguntas as $pregunta) :?>
               
               <option value="<?php echo htmlspecialchars($pregunta['id']); ?>">
                    <?php echo htmlspecialchars($pregunta['pregunta']); ?>
                </option>
               
                <?php endforeach ?>

            </select>

        </div>

        <div class="form-group">

            <label for="pregunta4">

                <span class="question-number">4</span>
                Cuarta pregunta

            </label>

            <select id="pregunta4" name="pregunta4" required>

                <option value="" selected disabled>
                    Seleccione la cuarta pregunta
                </option>

              <?php foreach ($preguntas as $pregunta) :?>
               
               <option value="<?php echo htmlspecialchars($pregunta['id']); ?>">
                    <?php echo htmlspecialchars($pregunta['pregunta']); ?>
                </option>
               
                <?php endforeach ?>

            </select>

        </div>

        <div class="actions">

            <button type="button" id="btnRegresar" class="btn btn-back">
                ← Regresar
            </button>

            <button type="submit" class="btn btn-save">
                💾 Guardar Preguntas
            </button>

        </div>

    </form>

</div>

<div class="version">
    Versión 0.0.1
</div>

</body>


<script>

           
const  btnRegresar = document.getElementById('btnRegresar');
btnRegresar.addEventListener('click', ()=> window.location.href ='juegos.php');

</script>



</html>

