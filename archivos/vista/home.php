<?php
// Incluir el archivo de configuración de conexión a la base de datos
include_once('../../include/conex.php');

// Establecer el tipo de contenido a HTML con el charset especificado en la configuración
header('Content-Type: text/html; charset=' . $charset);

// Iniciar la sesión con el nombre de sesión configurado
session_name($session_name);
session_start();

// Verificar si existe una sesión activa con el id_userprofile
if (isset($_SESSION['id_userprofile'])) {
?>
<!Doctype html>
<html lang="es">

<head>
    <?php include_once('cabecera.php');
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-lang-es="Sistema de biblioteca bilingüismo" data-lang-en="Bilingualism Library System" data-lang-fr="Système de bibliothèque bilinguisme">
        Sistema de biblioteca bilingüismo
    </title>

    <link rel="stylesheet" href="css/biblioteca.css">


    <link rel="stylesheet" href="../../herramientas/css/style.css">

</head>
<script>
    document.addEventListener("DOMContentLoaded", () => {
    const languageSelect = document.getElementById("language-select");

    languageSelect.addEventListener("change", (event) => {
        const selectedLanguage = event.target.value;
        setLanguage(selectedLanguage);
    });

    function setLanguage(language) {
        document.querySelectorAll("[data-lang-es]").forEach(element => {
            element.textContent = element.getAttribute(`data-lang-${language}`);
        });
    }

    // Set default language
    setLanguage(languageSelect.value);
});
</script>
<body>
    <div class="layout">
        <!-- Menú de navegación -->
        <aside class="layout__aside">
            <div class="aside__user-info">
                <?php
                    // Incluir el menú de navegación
                    include_once('menu.php');
                    ?>
            </div>
        </aside>
        <!-- Contenido principal -->
        <div class="container layout__content">
            <div class="content__page">
                <!-- Contenedor de la imagen de fondo -->
                <div class="background-image-container">
                    <div>
                    <div>
                        <select id="language-select">
                            <option value="es"><label for="language-select">Idioma:</label></option>
                            <option value="es">Español</option>
                            <option value="en">English</option>
                            <option value="fr">Français</option>
                        </select>
                        </div>
                        <div class="welcome-section">

                        <h1 class="title" 
                            data-lang-es="BIBLIOTECA MULTILINGUALISM TEAM" 
                            data-lang-en="MULTILINGUALISM TEAM LIBRARY" 
                            data-lang-fr="BIBLIOTHÈQUE DE L’ÉQUIPE MULTILINGUISME">
                            BIBLIOTECA MULTILINGUALISM TEAM
                        </h1>
                        <div class="divider"></div>
                        <h2 class="subtitle" data-lang-es="¡Bienvenido a tu espacio de conocimiento!" data-lang-en="Welcome to your space of knowledge!" data-lang-fr="Bienvenue dans votre espace de connaissance !">
                            ¡Bienvenido a tu espacio de conocimiento!
                        </h2>
                            <div class="info-cards">
                                <div class="info-card" onclick="window.location.href='https://biblioteca.sena.edu.co/'">
                                    <div class="icon-circle">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <h3 data-lang-es="Explora" data-lang-en="Explore" data-lang-fr="Explore">
                                        Explora
                                    </h3>
                                    <div class="description">
                                        <p data-lang-es="Descubre variedad de recursos educativos." data-lang-en="Discover a variety of educational resources." data-lang-fr="Découvrez une variété de ressources éducatives.">
                                            Descubre variedad de recursos educativos.
                                        </p>
                                    </div>
                                </div>

                                <div class="info-card" onclick="window.location.href='https://biblioteca.sena.edu.co/'">
                                    <div class="icon-circle">
                                        <i class="fas fa-book-reader"></i>
                                    </div>
                                    <h3 data-lang-es="Aprende" data-lang-en="Learn" data-lang-fr="Apprends">
                                        Aprende
                                    </h3>
                                    <div class="description">
                                        <p data-lang-es="Amplía tus horizontes con nuevos conocimientos." data-lang-en="Expand your horizons with new knowledge." data-lang-fr="Élargis tes horizons avec de nouvelles connaissances.">
                                            Amplía tus horizontes con nuevos conocimientos.
                                        </p>
                                    </div>
                                </div>

                                <div class="info-card" onclick="window.location.href='https://biblioteca.sena.edu.co/'">
                                    <div class="icon-circle">
                                        <i class="fas fa-hands-helping"></i>
                                    </div>
                                    <h3 data-lang-es="Contribuye" data-lang-en="Contribute" data-lang-fr="Contribue">
                                        Contribuye
                                    </h3>
                                    <div class="description">
                                        <p data-lang-es="Comparte tu sabiduría con la comunidad." data-lang-en="Share your wisdom with the community." data-lang-fr="Partage ta sagesse avec la communauté.">
                                            Comparte tu sabiduría con la comunidad.
                                        </p>
                                    </div>
                                </div>


                            </div>
                            <p class="info-text" data-lang-es="Este sistema te permite publicar libros y contenidos que enriquecen nuestra comunidad educativa." data-lang-en="This system allows you to publish books and content that enrich our educational community." data-lang-fr="Ce système vous permet de publier des livres et du contenu qui enrichissent notre communauté éducative.">
                                Este sistema te permite publicar libros y contenidos que enriquecen nuestra comunidad educativa.
                            </p>
                            <p class="highlight-text" data-lang-es="¡Tu participación es fundamental para construir un entorno de aprendizaje colaborativo y accesible para todos!" data-lang-en="Your participation is crucial to building a collaborative and accessible learning environment for everyone!" data-lang-fr="Votre participation est essentielle pour créer un environnement d'apprentissage collaboratif et accessible à tous !">
                                ¡Tu participación es fundamental para construir un entorno de aprendizaje colaborativo y accesible para todos!
                            </p>
                        </div>


                        <!-- Menú de navegación -->
                        <div class="navbar">
                            <div class="navbar-item has-dropdown is-hoverable">
                                <a class="navbar-link" href="#" data-lang-es="Secciones Biblioteca" data-lang-en="Library Sections" data-lang-fr="Sections de la Bibliothèque">
                                    Secciones Biblioteca <i class="fas fa-calendar-day"></i>
                                </a>
                                <div class="navbar-dropdown">
                                    <?php
                                    if ($_SESSION['id_rol'] == '3') {
                                        echo '<a href="category_new.php" class="navbar-dropdown-item" data-lang-es="Ingresar Nueva Sesión" data-lang-en="Enter New Session" data-lang-fr="Entrer Nouvelle Session">Ingresar Nueva Sesión</a>';
                                    }
                                    ?>
                                    <a href="category_list.php" class="navbar-dropdown-item" data-lang-es="Lista de Sesiones" data-lang-en="Session List" data-lang-fr="Liste des Sessions">
                                        Lista de Sesiones
                                    </a>
                                </div>
                            </div>

                            <div class="navbar-item has-dropdown is-hoverable">
                                <a class="navbar-link" href="#" data-lang-es="Ingresar a los Libros" data-lang-en="Enter the Books" data-lang-fr="Entrer les Livres">
                                    Ingresar a los Libros <i class="fas fa-book"></i>
                                </a>
                                <div class="navbar-dropdown">
                                    <?php
                                    if (-$_SESSION['id_rol'] != '3') {
                                        echo '<a href="product_new.php" class="navbar-dropdown-item" data-lang-es="Nuevo Libro" data-lang-en="New Book" data-lang-fr="Nouveau Livre">Nuevo Libro</a>';
                                    }
                                    ?>
                                    <a href="product_list.php" class="navbar-dropdown-item" data-lang-es="Lista de Libros" data-lang-en="Book List" data-lang-fr="Liste des Livres">
                                        Lista de Libros
                                    </a>
                                </div>
                            </div>
                        </div>


                        <!-- Imagen grande debajo del menú -->
                        <div class="large-image">
                            <img src="../../imagenes/bibli-home.png" alt="Imagen Grande">
                        </div>
                        <!-- Footer -->
                        <footer class="footer">
                            <div class="footer-content">
                                <div class="footer-left">
                                    <img src="../../imagenes/logo-home.png" alt="Logo">
                                </div>
                                <div class="footer-right">
                                <p data-lang-es="Servicio Nacional de Aprendizaje SENA - Dirección General"
                                    data-lang-en="National Learning Service SENA - General Directorate"
                                    data-lang-fr="Service National d'Apprentissage SENA - Direction Générale">
                                        Servicio Nacional de Aprendizaje SENA - Dirección General
                                    </p>
                                    <p data-lang-es="Calle 57 No. 8 - 69 Bogotá D.C. (Cundinamarca), Colombia"
                                    data-lang-en="57th Street No. 8 - 69 Bogotá D.C. (Cundinamarca), Colombia"
                                    data-lang-fr="Rue 57 No. 8 - 69 Bogotá D.C. (Cundinamarca), Colombie">
                                        Calle 57 No. 8 - 69 Bogotá D.C. (Cundinamarca), Colombia
                                    </p>
                                    <p data-lang-es="Atención presencial: lunes a viernes 8:00 a.m. a 5:30 p.m. - Resto del país sedes y horarios"
                                    data-lang-en="In-person service: Monday to Friday 8:00 a.m. to 5:30 p.m. - Rest of the country branches and schedules"
                                    data-lang-fr="Service en personne : du lundi au vendredi de 8h00 à 17h30 - Reste du pays agences et horaires">
                                        Atención presencial: lunes a viernes 8:00 a.m. a 5:30 p.m. - Resto del país sedes y horarios
                                    </p>
                                    <p data-lang-es="Atención telefónica: lunes a viernes 7:00 a.m. a 7:00 p.m. - sábados 8:00 a.m. a 1:00 p.m"
                                    data-lang-en="Phone service: Monday to Friday 7:00 a.m. to 7:00 p.m. - Saturdays 8:00 a.m. to 1:00 p.m"
                                    data-lang-fr="Service téléphonique : du lundi au vendredi de 7h00 à 19h00 - Samedi de 8h00 à 13h00">
                                        Atención telefónica: lunes a viernes 7:00 a.m. a 7:00 p.m. - sábados 8:00 a.m. a 1:00 p.m
                                    </p>
                                    <p data-lang-es="Líneas gratuitas atención al ciudadano: Bogotá (57 1) 3430111 - Resto del país 018000 910270"
                                    data-lang-en="Toll-free citizen service lines: Bogotá (57 1) 3430111 - Rest of the country 018000 910270"
                                    data-lang-fr="Lignes gratuites pour service citoyen : Bogotá (57 1) 3430111 - Reste du pays 018000 910270">
                                        Líneas gratuitas atención al ciudadano: Bogotá (57 1) 3430111 - Resto del país 018000 910270
                                    </p>
                                    <p data-lang-es="Líneas gratuitas atención al empresario: Bogotá (57 1) 3430101 - Resto del país 018000 910682"
                                    data-lang-en="Toll-free business service lines: Bogotá (57 1) 3430101 - Rest of the country 018000 910682"
                                    data-lang-fr="Lignes gratuites pour service aux entreprises : Bogotá (57 1) 3430101 - Reste du pays 018000 910682">
                                        Líneas gratuitas atención al empresario: Bogotá (57 1) 3430101 - Resto del país 018000 910682
                                    </p>
                                    <p data-lang-es="Conmutador Nacional (57 1) 5461500 - Extensiones 12586 - 13021 - 12535 - 12542"
                                    data-lang-en="National Switchboard (57 1) 5461500 - Extensions 12586 - 13021 - 12535 - 12542"
                                    data-lang-fr="Standard National (57 1) 5461500 - Postes 12586 - 13021 - 12535 - 12542">
                                        Conmutador Nacional (57 1) 5461500 - Extensiones 12586 - 13021 - 12535 - 12542
                                    </p>
                                    <p data-lang-es="biblioteca.sena@misena.edu.co"
                                    data-lang-en="biblioteca.sena@misena.edu.co"
                                    data-lang-fr="biblioteca.sena@misena.edu.co">
                                        biblioteca.sena@misena.edu.co
                                    </p>

                                </div>
                            </div>
                        </footer>


</body>

</html>
<?php
    // Si no hay sesión activa, redirigir al usuario a la página de inicio de sesión
} else {
    header("Location: ../../index.php");
}
?>