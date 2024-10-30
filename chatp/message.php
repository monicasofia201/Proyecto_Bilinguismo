<?php
// Conectando a la base de datos
$conn = mysqli_connect("localhost", "root", "", "bilinguismo") or die("Database Error");

// Obteniendo el mensaje del usuario a través de AJAX
$getMesg = mysqli_real_escape_string($conn, $_POST['text']);

// Comprobando la consulta del usuario a la consulta de la base de datos
$check_data = "SELECT replies FROM chatbot WHERE queries LIKE '%$getMesg%'";
$run_query = mysqli_query($conn, $check_data) or die("Error");

// Si la consulta del usuario coincide con la consulta de la base de datos, mostraremos la respuesta; de lo contrario, irá a otra declaración
if (mysqli_num_rows($run_query) > 0) {
    // Recuperando la respuesta de la base de datos de acuerdo con la consulta del usuario
    $fetch_data = mysqli_fetch_assoc($run_query);
    // Almacenando la respuesta en una variable que enviaremos a AJAX
    $replay = $fetch_data['replies'];
    echo $replay;
} else {
    echo "¡Lo siento, no puedo ayudarte con este inconveniente! Favor comunícate con el administrador en el siguiente enlace:<br/><a href='https://zajuna.sena.edu.co'></a>";
}
?>