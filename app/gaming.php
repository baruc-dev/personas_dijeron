<?php

session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id']) || $_GET['id'] === '') {
    header('Location: juegos.php');
    exit;
}


if(!isset($_GET['qu']))
    {
        header('Location: juegos.php');
    exit;
    }


    $conexion = new mysqli('db', 'root', 'root', 'game-database');



    switch ($_GET['qu']) {
        case 1:
            $statement1 = $conexion->prepare('SELECT j.pregunta1, p.pregunta FROM juegos j JOIN preguntas p ON j.pregunta1 = p.id WHERE j.id = ?');
            $statement1->bind_param('i',$_GET['id']);
            $statement1->execute();
            $resultado1 = $statement1->get_result();
            $fila = $resultado1->fetch_assoc();
            $pregunta = $fila['pregunta'];
            $preguntaBuscar = $fila['pregunta1'];
        break;


         case 2:
               $statement1 = $conexion->prepare('SELECT j.pregunta2, p.pregunta FROM juegos j JOIN preguntas p ON j.pregunta2 = p.id WHERE j.id = ?');
            $statement1->bind_param('i',$_GET['id']);
            $statement1->execute();
            $resultado1 = $statement1->get_result();
            $fila = $resultado1->fetch_assoc();
            $pregunta = $fila['pregunta'];
             $preguntaBuscar = $fila['pregunta2'];
            
        break;


         case 3:

               $statement1 = $conexion->prepare('SELECT j.pregunta3, p.pregunta FROM juegos j JOIN preguntas p ON j.pregunta3 = p.id WHERE j.id = ?');
            $statement1->bind_param('i',$_GET['id']);
            $statement1->execute();
            $resultado1 = $statement1->get_result();
            $fila = $resultado1->fetch_assoc();
            $pregunta = $fila['pregunta'];
             $preguntaBuscar = $fila['pregunta3'];
            
        break;


         case 4:

               $statement1 = $conexion->prepare('SELECT j.pregunta4, p.pregunta FROM juegos j JOIN preguntas p ON j.pregunta4 = p.id WHERE j.id = ?');
            $statement1->bind_param('i',$_GET['id']);
            $statement1->execute();
            $resultado1 = $statement1->get_result();
            $fila = $resultado1->fetch_assoc();
            $pregunta = $fila['pregunta'];
             $preguntaBuscar = $fila['pregunta4'];
            
        break;



        
        default:
           $pregunta = ' ';
        break;
    }


    if(isset($preguntaBuscar))
        {


            $statement2 = $conexion->prepare('SELECT * FROM respuestas WHERE pregunta = ? ORDER BY id');
            $statement2->bind_param('i', $preguntaBuscar);
            $statement2->execute();
            $respuestas = array();
            $result = $statement2->get_result();
            while($answer = $result->fetch_assoc())
                {
                    $respuestas[] = $answer['respuesta'];
                }





        }

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>100 Personas Dijeron</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Segoe UI",sans-serif;
}

body{
    min-height:100vh;
    padding:35px;

    display:flex;
    justify-content:center;
    align-items:center;

    overflow:hidden;

    background:
        radial-gradient(circle at top left,#7c3aed 0%,transparent 35%),
        radial-gradient(circle at bottom right,#06b6d4 0%,transparent 35%),
        linear-gradient(135deg,#0f172a,#1e1b4b,#312e81);

    color:white;
}

.game-container{
    width:100%;
    max-width:1100px;
}

.question{
    width:100%;
    margin-bottom:30px;
    padding:25px 30px;

    text-align:center;

    background:rgba(255,255,255,.12);
    backdrop-filter:blur(18px);

    border:1px solid rgba(255,255,255,.18);
    border-radius:24px;

    box-shadow:0 20px 50px rgba(0,0,0,.30);
}

.question h1{
    font-size:clamp(28px,4vw,50px);
    line-height:1.25;
}

.answers{
    display:grid;
    gap:16px;
}

.answer{
    min-height:85px;
    padding:14px 20px;

    display:grid;
    grid-template-columns:70px 1fr 100px;
    align-items:center;

    background:rgba(255,255,255,.10);
    backdrop-filter:blur(18px);

    border:1px solid rgba(255,255,255,.15);
    border-radius:18px;

    box-shadow:0 12px 30px rgba(0,0,0,.20);

    overflow:hidden;
    transition:.35s ease;
}

.answer-number{
    width:48px;
    height:48px;

    display:flex;
    justify-content:center;
    align-items:center;

    border-radius:14px;

    background:linear-gradient(135deg,#06b6d4,#8b5cf6);

    font-size:24px;
    font-weight:800;
}

.answer-text{
    padding:0 20px;

    font-size:clamp(20px,2.5vw,32px);
    font-weight:700;
    letter-spacing:.3px;

    opacity:0;
    transform:translateY(12px);
    transition:.35s ease;
}

.answer-points{
    padding:12px;

    text-align:center;

    border-radius:12px;
    background:rgba(255,255,255,.14);

    font-size:28px;
    font-weight:800;

    opacity:0;
    transform:scale(.8);
    transition:.35s ease;
}

.answer.revealed{
    background:rgba(255,255,255,.19);
    box-shadow:0 15px 35px rgba(34,211,238,.15);
}

.answer.revealed .answer-text,
.answer.revealed .answer-points{
    opacity:1;
    transform:none;
}

.hidden-line{
    height:8px;
    width:75%;
    margin-left:20px;

    border-radius:50px;

    background:rgba(255,255,255,.20);
    transition:.25s;
}

.answer.revealed .hidden-line{
    display:none;
}

/* X grande en el centro */

.big-x{
    position:fixed;
    inset:0;
    z-index:100;

    display:flex;
    justify-content:center;
    align-items:center;

    pointer-events:none;

    background:rgba(0,0,0,.25);

    font-size:min(55vw,520px);
    font-weight:900;
    line-height:1;

    color:#ef4444;

    text-shadow:
        0 0 20px rgba(239,68,68,.8),
        0 0 60px rgba(239,68,68,.55);

    opacity:0;
    visibility:hidden;
    transform:scale(.5);

    transition:
        opacity .18s ease,
        transform .18s ease,
        visibility .18s;
}

.big-x.show{
    opacity:1;
    visibility:visible;
    transform:scale(1);
}

/* Contador inferior de X */

.mistakes{
    position:fixed;
    left:50%;
    bottom:18px;
    z-index:50;

    min-height:65px;

    display:flex;
    justify-content:center;
    align-items:center;
    gap:15px;

    transform:translateX(-50%);

    font-size:62px;
    font-weight:900;
    color:#ef4444;

    text-shadow:0 0 18px rgba(239,68,68,.65);
}

/* Juego finalizado */

.finished-message{
    position:fixed;
    inset:0;
    z-index:200;

    display:flex;
    justify-content:center;
    align-items:center;

    padding:30px;

    background:
        radial-gradient(circle at center,#7c3aed 0%,#1e1b4b 45%,#0f172a 100%);

    opacity:0;
    visibility:hidden;
    transform:scale(1.1);

    transition:.4s ease;
}

.finished-message.show{
    opacity:1;
    visibility:visible;
    transform:scale(1);
}

.finished-content{
    width:100%;
    max-width:950px;

    padding:55px 40px;

    text-align:center;

    border-radius:30px;

    background:rgba(255,255,255,.12);
    border:1px solid rgba(255,255,255,.20);
    backdrop-filter:blur(20px);

    box-shadow:0 30px 80px rgba(0,0,0,.40);
}

.finished-content h2{
    font-size:clamp(50px,9vw,110px);
    line-height:1;
    margin-bottom:20px;

    background:linear-gradient(135deg,#22d3ee,#a78bfa,#f472b6);
    background-clip:text;
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.finished-content p{
    color:#dbeafe;
    font-size:clamp(20px,3vw,32px);
}

.version{
    position:fixed;
    right:18px;
    bottom:12px;

    color:rgba(255,255,255,.55);
    font-size:13px;
}

@media(max-width:700px){

    body{
        padding:20px 14px 80px;
        overflow:auto;
    }

    .question{
        padding:20px 15px;
    }

    .answer{
        min-height:75px;
        padding:12px;
        grid-template-columns:55px 1fr 65px;
    }

    .answer-number{
        width:42px;
        height:42px;
        font-size:20px;
    }

    .answer-text{
        padding:0 10px;
        font-size:18px;
    }

    .answer-points{
        padding:10px 5px;
        font-size:22px;
    }

}

</style>
</head>

<body>

<main class="game-container">

    <section class="question">
        <h1>
           <?php echo htmlspecialchars($pregunta); ?>
        </h1>
    </section>

    <section class="answers">

        <div class="answer" id="answer1">
            <div class="answer-number">1</div>

            <div>
                <div class="hidden-line"></div>
                <div class="answer-text"><?php echo htmlspecialchars($respuestas[0]); ?></div>
            </div>

            <div class="answer-points">40</div>
        </div>

        <div class="answer" id="answer2">
            <div class="answer-number">2</div>

            <div>
                <div class="hidden-line"></div>
                <div class="answer-text"><?php echo htmlspecialchars($respuestas[1]); ?></div>
            </div>

            <div class="answer-points">30</div>
        </div>

        <div class="answer" id="answer3">
            <div class="answer-number">3</div>

            <div>
                <div class="hidden-line"></div>
                <div class="answer-text"><?php echo htmlspecialchars($respuestas[2]); ?></div>
            </div>

            <div class="answer-points">15</div>
        </div>

        <div class="answer" id="answer4">
            <div class="answer-number">4</div>

            <div>
                <div class="hidden-line"></div>
                <div class="answer-text"><?php echo htmlspecialchars($respuestas[3]); ?></div>
            </div>

            <div class="answer-points">10</div>
        </div>

        <div class="answer" id="answer5">
            <div class="answer-number">5</div>

            <div>
                <div class="hidden-line"></div>
                <div class="answer-text"><?php echo htmlspecialchars($respuestas[4]); ?></div>
            </div>

            <div class="answer-points">5</div>
        </div>

    </section>

</main>

<!-- X grande temporal -->

<div class="big-x" id="bigX">
    X
</div>

<!-- X acumuladas; solo se muestran una o dos -->

<div class="mistakes" id="mistakes"></div>

<!-- Mensaje de juego finalizado -->

<div class="finished-message" id="finishedMessage">

    <div class="finished-content">

        <h2>JUEGO FINALIZADO</h2>

        <p>¡Gracias por participar!</p>

    </div>

</div>

<div class="version">
    Versión 0.0.1
</div>

<script>

const bigX = document.getElementById('bigX');
const mistakes = document.getElementById('mistakes');
const finishedMessage = document.getElementById('finishedMessage');

let mistakeCount = 0;
let bigXTimeout = null;

/**
 * Muestra una respuesta de acuerdo con el número presionado.
 */
function revealAnswer(number){

    const answer = document.getElementById(`answer${number}`);

    if(answer){
        answer.classList.add('revealed');
    }

}

/**
 * Muestra una X grande durante dos segundos.
 * En la parte inferior se conserva un máximo de dos X.
 * Al llegar al tercer error, el contador inferior se limpia.
 */
function showMistake(){

    mistakeCount++;

    bigX.classList.add('show');

    clearTimeout(bigXTimeout);

    bigXTimeout = setTimeout(() => {
        bigX.classList.remove('show');
    }, 2000);

    if(mistakeCount === 1){
        mistakes.textContent = 'X';
    }
    else if(mistakeCount === 2){
        mistakes.textContent = 'X X';
    }
    else{
        mistakes.textContent = '';
        mistakeCount = 0;
    }

}

/**
 * Muestra la pantalla de juego finalizado.
 */
function finishGame(){

    finishedMessage.classList.add('show');

}

document.addEventListener('keydown', function(event){

    /*
     * Evita ejecutar varias veces la misma acción
     * cuando una tecla permanece presionada.
     */
    if(event.repeat){
        return;
    }

    const key = event.key.toLowerCase();

    if(['1','2','3','4','5'].includes(key)){
        revealAnswer(key);
        return;
    }

    if(key === 'x'){
        showMistake();
        return;
    }

    if(key === 'f'){
        finishGame();
        return;
    }

    if(event.key === 'ArrowRight'){
        window.location.href = 'gaming.php?id=<?php echo $_GET['id'].'&qu='.$_GET['qu']+1 ?>';
        return;
    }

    if(event.key === 'ArrowLeft'){
         window.location.href = 'gaming.php?id=<?php echo $_GET['id'].'&qu='.$_GET['qu']-1 ?>';
    }

});

</script>

</body>
</html>

