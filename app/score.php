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

$idJuego = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$idJuego) {
    header('Location: juegos.php');
    exit;
}

$conexion = new mysqli('db', 'root', 'root', 'game-database');

if ($conexion->connect_errno) {
    die('Error al conectar con la base de datos.');
}

$conexion->set_charset('utf8mb4');

$statement = $conexion->prepare('SELECT * FROM juegos WHERE id = ?');
$statement->bind_param('i', $idJuego);
$statement->execute();

$resultado = $statement->get_result();

if ($resultado->num_rows === 0) {
    header('Location: juegos.php');
    exit;
}

$juego = $resultado->fetch_assoc();



$question1 = $juego['pregunta1'];
$question2 = $juego['pregunta2'];
$question3 = $juego['pregunta3'];
$question4 = $juego['pregunta4'];




$statement3 = $conexion->prepare('SELECT p.pregunta as question, r.* FROM preguntas p join respuestas r on r.pregunta = p.id where p.id = ?');
$statement3->bind_param('i', $question1);
$statement3->execute();
$respuestasQ1 = array();
$resultado3 = $statement3->get_result();
while($fila3 = $resultado3->fetch_assoc())
    {
        $respuestasQ1[] = $fila3['respuesta'];
        $preguntaQ1 = $fila3['question'];
    }

$statement3->close();






$statement4 = $conexion->prepare('SELECT p.pregunta as question, r.* FROM preguntas p join respuestas r on r.pregunta = p.id where p.id = ?');
$statement4->bind_param('i', $question2);
$statement4->execute();
$respuestasQ2 = array();
$resultado4 = $statement4->get_result();
while($fila4 = $resultado4->fetch_assoc())
    {
        $respuestasQ2[] = $fila4['respuesta'];
        $preguntaQ2 = $fila4['question'];
    }

$statement4->close();






$statement5 = $conexion->prepare('SELECT p.pregunta as question, r.* FROM preguntas p join respuestas r on r.pregunta = p.id where p.id = ?');
$statement5->bind_param('i', $question3);
$statement5->execute();
$respuestasQ3 = array();
$resultado5 = $statement5->get_result();
while($fila5 = $resultado5->fetch_assoc())
    {
        $respuestasQ3[] = $fila5['respuesta'];
        $preguntaQ3 = $fila5['question'];
    }

$statement5->close();






$statement6 = $conexion->prepare('SELECT p.pregunta as question, r.* FROM preguntas p join respuestas r on r.pregunta = p.id where p.id = ?');
$statement6->bind_param('i', $question4);
$statement6->execute();
$respuestasQ4 = array();
$resultado6 = $statement6->get_result();
while($fila6 = $resultado6->fetch_assoc())
    {
        $respuestasQ4[] = $fila6['respuesta'];
        $preguntaQ4 = $fila6['question'];
    }

$statement6->close();

    





if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['puntosA'], $_POST['puntosB'])
) {
    $puntosA = filter_input(INPUT_POST, 'puntosA', FILTER_VALIDATE_INT);
    $puntosB = filter_input(INPUT_POST, 'puntosB', FILTER_VALIDATE_INT);

    if (
        $puntosA !== false
        && $puntosB !== false
        && $puntosA >= 0
        && $puntosB >= 0
    ) {
        $statement2 = $conexion->prepare(
            'UPDATE juegos SET puntosA = ?, puntosB = ? WHERE id = ?'
        );

        $statement2->bind_param(
            'iii',
            $puntosA,
            $puntosB,
            $idJuego
        );

        $statement2->execute();

        header('Location: score.php?id=' . $idJuego);
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Preguntas de ejemplo
|--------------------------------------------------------------------------
| Después puedes reemplazar este arreglo con los datos de tu base de datos.
*/






$preguntas = [
    [
        'pregunta' => $preguntaQ1,
        'respuestas' => $respuestasQ1
    ],
    [
       'pregunta' => $preguntaQ2,
        'respuestas' => $respuestasQ2
    ],
    [
        'pregunta' => $preguntaQ3,
        'respuestas' => $respuestasQ3
    ],
    [
        'pregunta' => $preguntaQ4,
        'respuestas' => $respuestasQ4
    ]
];

?>
<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Panel del Juego</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", sans-serif;
}

body {
    min-height: 100vh;
    padding: 40px 20px;

    display: flex;
    justify-content: center;
    align-items: center;

    background:
        radial-gradient(circle at top left, #7c3aed 0%, transparent 35%),
        radial-gradient(circle at bottom right, #06b6d4 0%, transparent 35%),
        linear-gradient(135deg, #0f172a, #1e1b4b, #312e81);

    color: white;
}

.dashboard {
    width: 100%;
    max-width: 1350px;
}

.page-header {
    margin-bottom: 28px;
    text-align: center;
}

.page-header h1 {
    margin-bottom: 8px;
    font-size: 36px;
}

.page-header p {
    color: #cbd5e1;
}

.game-info {
    margin-bottom: 25px;
    padding: 17px 20px;

    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;

    background: rgba(255, 255, 255, .10);
    backdrop-filter: blur(18px);

    border: 1px solid rgba(255, 255, 255, .15);
    border-radius: 18px;

    box-shadow: 0 20px 40px rgba(0, 0, 0, .25);
}

.game-info strong {
    display: block;
    margin-bottom: 4px;
    font-size: 20px;
}

.game-info small {
    color: #cbd5e1;
}

.game-id {
    padding: 9px 15px;

    border-radius: 50px;
    background: linear-gradient(135deg, #06b6d4, #8b5cf6);

    font-size: 14px;
    font-weight: 700;
}

.content-grid {
    display: grid;
    grid-template-columns: minmax(320px, .75fr) minmax(600px, 1.7fr);
    gap: 25px;
    align-items: start;
}

.card {
    padding: 30px;

    background: rgba(255, 255, 255, .10);
    backdrop-filter: blur(18px);

    border: 1px solid rgba(255, 255, 255, .15);
    border-radius: 24px;

    box-shadow: 0 25px 50px rgba(0, 0, 0, .35);
}

.card-title {
    margin-bottom: 7px;
    font-size: 25px;
}

.card-subtitle {
    margin-bottom: 26px;
    color: #cbd5e1;
    font-size: 14px;
}

/* Puntajes */

.form-group {
    margin-bottom: 22px;
}

label {
    display: block;
    margin-bottom: 9px;

    font-weight: 700;
    color: #f8fafc;
}

.team-label {
    display: flex;
    align-items: center;
    gap: 9px;
}

.team-badge {
    width: 32px;
    height: 32px;

    display: inline-flex;
    justify-content: center;
    align-items: center;

    border-radius: 9px;
    background: rgba(255, 255, 255, .15);

    font-weight: 800;
}

.score-input {
    width: 100%;
    padding: 19px 16px;

    border: none;
    outline: none;
    border-radius: 14px;

    background: rgba(255, 255, 255, .15);
    color: white;

    font-size: 28px;
    font-weight: 800;
    text-align: center;
}

.score-input:focus {
    box-shadow: 0 0 0 3px rgba(34, 211, 238, .25);
}

.score-input::-webkit-outer-spin-button,
.score-input::-webkit-inner-spin-button {
    appearance: none;
    margin: 0;
}

/* Botones */

.actions {
    display: flex;
    gap: 12px;
    margin-top: 10px;
}

.btn {
    flex: 1;
    padding: 14px 16px;

    border: none;
    border-radius: 13px;

    color: white;
    font-size: 15px;
    font-weight: 700;

    cursor: pointer;
    transition: .25s;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, .25);
}

.btn-save {
    background: linear-gradient(135deg, #06b6d4, #8b5cf6);
}

.btn-back {
    background: rgba(255, 255, 255, .13);
    border: 1px solid rgba(255, 255, 255, .15);
}

/* Preguntas */

.questions-list {
    display: grid;
    gap: 16px;
}

.question-item {
    padding: 18px;

    background: rgba(255, 255, 255, .08);
    border: 1px solid rgba(255, 255, 255, .11);
    border-radius: 16px;

    transition: .2s;
}

.question-item:hover {
    background: rgba(255, 255, 255, .12);
}

.question-title {
    margin-bottom: 14px;

    color: #67e8f9;
    font-size: 17px;
    font-weight: 800;
    line-height: 1.4;
}

.answers-inline {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.answer-item {
    padding: 8px 11px;

    background: rgba(255, 255, 255, .08);
    border-radius: 9px;

    color: #f8fafc;
    font-size: 14px;
    line-height: 1.4;
}

.answer-item strong {
    color: #c4b5fd;
}

.version {
    position: fixed;
    right: 20px;
    bottom: 15px;

    color: rgba(255, 255, 255, .6);
    font-size: 13px;
}

@media(max-width: 1000px) {

    body {
        align-items: flex-start;
    }

    .content-grid {
        grid-template-columns: 1fr;
    }

}

@media(max-width: 600px) {

    body {
        padding: 25px 14px 70px;
    }

    .card {
        padding: 23px 18px;
    }

    .game-info {
        flex-direction: column;
        align-items: flex-start;
    }

    .actions {
        flex-direction: column-reverse;
    }

    .answers-inline {
        flex-direction: column;
    }

}

</style>

</head>

<body>

<main class="dashboard">

    <header class="page-header">

        <h1>🎮 Panel del Juego</h1>

        <p>
            Administra los puntajes y consulta las preguntas asignadas.
        </p>

    </header>

    <section class="game-info">

        <div>

            <strong>
                <?php echo htmlspecialchars($juego['evento']); ?>
            </strong>

            <small>
                Juego seleccionado
            </small>

        </div>

        <div class="game-id">
            Juego #<?php echo $idJuego; ?>
        </div>

    </section>

    <section class="content-grid">

        <!-- Lado izquierdo: puntajes -->

        <div class="card">

            <h2 class="card-title">
                🏆 Puntajes
            </h2>

            <p class="card-subtitle">
                Modifica los puntos de cada equipo.
            </p>

            <form
                action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $idJuego; ?>"
                method="POST"
            >

                <div class="form-group">

                    <label for="puntosA" class="team-label">

                        <span class="team-badge">A</span>

                        Puntos Equipo A

                    </label>

                    <input
                        class="score-input"
                        type="number"
                        id="puntosA"
                        name="puntosA"
                        value="<?php echo htmlspecialchars($juego['puntosA']); ?>"
                        min="0"
                        step="1"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="puntosB" class="team-label">

                        <span class="team-badge">B</span>

                        Puntos Equipo B

                    </label>

                    <input
                        class="score-input"
                        type="number"
                        id="puntosB"
                        name="puntosB"
                        value="<?php echo htmlspecialchars($juego['puntosB']); ?>"
                        min="0"
                        step="1"
                        required
                    >

                </div>

                <div class="actions">

                    <button
                        type="button"
                        id="btnRegresar"
                        class="btn btn-back"
                    >
                        ← Regresar
                    </button>

                    <button
                        type="submit"
                        class="btn btn-save"
                    >
                        💾 Guardar Puntajes
                    </button>

                </div>

            </form>

        </div>

        <!-- Lado derecho: preguntas y respuestas -->

        <div class="card">

            <h2 class="card-title">
                ❓ Preguntas del juego
            </h2>

            <p class="card-subtitle">
                Preguntas y respuestas asignadas a este juego.
            </p>

            <div class="questions-list">

                <?php foreach ($preguntas as $indice => $pregunta): ?>

                    <div class="question-item">

                        <div class="question-title">

                            Pregunta <?php echo $indice + 1; ?>:
                            <?php echo htmlspecialchars($pregunta['pregunta']); ?>

                        </div>

                        <div class="answers-inline">

                            <?php foreach ($pregunta['respuestas'] as $indiceRespuesta => $respuesta): ?>

                                <div class="answer-item">

                                    <strong>
                                        Respuesta <?php echo $indiceRespuesta + 1; ?>:
                                    </strong>

                                    <?php echo htmlspecialchars($respuesta); ?>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </section>

</main>

<div class="version">
    Versión 0.0.1
</div>

<script>

const btnRegresar = document.getElementById('btnRegresar');

btnRegresar.addEventListener('click', () => {
    window.location.href = 'juegos.php';
});

</script>

</body>
</html>
