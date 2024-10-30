<nav class="dropdownmenu">
    <i class="menu-toggle fas fa-bars"></i>
    <!-- <a href="actualizarusuariooriginal.php" class="logo">
        <img src="../../imagenes/logo.jpg" />
    </a> -->

    <?php
        if ($_SESSION['id_rol'] == 3) {
            echo '
                <li>
                    <a href="inicio.php">
                        <span class="nav-item">
                            <i class="fas fa-home "></i>
                            Inicio
                        </span>
                    </a>
                </li>
                <li>
                    <a href="panelAcciones.php">
                        <span class="nav-item">
                            <i class="fas fa-th"></i>
                            Acciones
                        </span>
                    </a>
                </li>
                <li>
                    <a href="instructor.php">
                        <span class="nav-item">
                            <i class="fas fa-users"></i>
                            M-Team
                        </span>
                    </a>
                </li>
                <li>
                    <a href="home.php">
                        <span class="nav-item">
                            <i class="fas fa-book"></i>
                            Biblíoteca
                        </span>
                    </a>
                </li>
                <li>
                    <a href="perfil.php">
                        <span class="nav-item">
                            <i class="fas fa-cog"></i>
                            Perfíl
                        </span>
                    </a>
                </li>
                <li>
                    <a id="btnCerrarSession">
                        <span class="nav-item">
                            <i class="fas fa-sign-out-alt "></i>
                            Salir
                        </span>
                    </a>
                </li>
            ';
        }
    ?>
    <?php
        if ($_SESSION['id_rol'] == 2) {
            echo '
                <li>
                    <a href="inicio.php">
                        <span class="nav-item">
                            <i class="fas fa-home "></i>
                            Inicio
                        </span>
                    </a>
                </li>
                <li>
                    <a href="panelAcciones.php">
                        <span class="nav-item">
                            <i class="fas fa-th"></i>
                            Acciones
                        </span>
                    </a>
                </li>
                <li>
                    <a href="instructor.php">
                        <span class="nav-item">
                            <i class="fas fa-users"></i>
                            M-Team
                        </span>
                    </a>
                </li>
                <li>
                    <a href="home.php">
                        <span class="nav-item">
                            <i class="fas fa-book"></i>
                            Biblíoteca
                        </span>
                    </a>
                </li>
                <li>
                    <a href="perfil.php">
                        <span class="nav-item">
                            <i class="fas fa-cog"></i>
                            Perfíl
                        </span>
                    </a>
                </li>
                <li>
                    <a id="btnCerrarSession">
                        <span class="nav-item">
                            <i class="fas fa-sign-out-alt "></i>
                            Salir
                        </span>
                    </a>
                </li>
            ';
        }
    ?>
    <?php
        if ($_SESSION['id_rol'] == 1) {
            echo '
                <li>
                    <a href="inicio.php">
                        <span class="nav-item">
                            <i class="fas fa-home "></i>
                            Inicio
                        </span>
                    </a>
                </li>
                <li>
                    <a href="panelAcciones.php">
                        <span class="nav-item">
                            <i class="fas fa-th"></i>
                            Acciones
                        </span>
                    </a>
                </li>
                <li>
                    <a href="instructor.php">
                        <span class="nav-item">
                            <i class="fas fa-users"></i>
                            M-Team
                        </span>
                    </a>
                </li>
                <li>
                    <a href="perfil.php">
                        <span class="nav-item">
                            <i class="fas fa-cog"></i>
                            Perfíl
                        </span>
                    </a>
                </li>
                <li>
                    <a id="btnCerrarSession">
                        <span class="nav-item">
                            <i class="fas fa-sign-out-alt "></i>
                            Salir
                        </span>
                    </a>
                </li>
            ';
        }
    ?>
    <?php
        if ($_SESSION['id_rol'] == 5) {
            echo '
                <li>
                    <a href="inicio.php">
                        <span class="nav-item">
                            <i class="fas fa-home "></i>
                            Inicio
                        </span>
                    </a>
                </li>
                <li>
                    <a href="panelAcciones.php">
                        <span class="nav-item">
                            <i class="fas fa-th"></i>
                            Acciones
                        </span>
                    </a>
                </li>
                <li>
                    <a href="instructor.php">
                        <span class="nav-item">
                            <i class="fas fa-users"></i>
                            B-Team
                        </span>
                    </a>
                </li>
                <li>
                    <a href="perfil.php">
                        <span class="nav-item">
                            <i class="fas fa-cog"></i>
                            Perfíl
                        </span>
                    </a>
                </li>
                <li>
                    <a id="btnCerrarSession">
                        <span class="nav-item">
                            <i class="fas fa-sign-out-alt "></i>
                            Salir
                        </span>
                    </a>
                </li>
            ';
        }
    ?>
    <?php
        if ($_SESSION['id_rol'] == 4) {
            echo '
                <li>
                    <a href="inicio.php">
                        <span class="nav-item">
                            <i class="fas fa-home "></i>
                            Inicio
                        </span>
                    </a>
                </li>
                <li>
                    <a href="panelAcciones.php">
                        <span class="nav-item">
                            <i class="fas fa-th"></i>
                            Acciones
                        </span>
                    </a>
                </li>
                <li>
                    <a href="instructor.php">
                        <span class="nav-item">
                            <i class="fas fa-users"></i>
                            M-Team
                        </span>
                    </a>
                </li>
                <li>
                    <a href="perfil.php">
                        <span class="nav-item">
                            <i class="fas fa-cog"></i>
                            Perfíl
                        </span>
                    </a>
                </li>
                <li>
                    <a id="btnCerrarSession">
                        <span class="nav-item">
                            <i class="fas fa-sign-out-alt "></i>
                            Salir
                        </span>
                    </a>
                </li>
            ';
        }
    ?>




    
    
    
</nav>
<script>
$(document).ready(function() {
    // Maneja el clic en el ícono del menú para abrir/cerrar el menú
    $(document).on("click", ".menu-toggle", function() {
        $("nav").toggleClass("active");
    });

    // Maneja el clic en el botón de cerrar sesión
    $(document).on("click", "#btnCerrarSession", function() {
        $.post("../../include/ctrlIndex2.php", {
            action: 'salir'
        }, function(data) {
            location.href = "../../index.php";
        }, 'json');
    });

    // Cierra el menú si se hace clic fuera de él
    $(document).on("click", function(event) {
        if (!$("nav").is(event.target) && $("nav").has(event.target).length === 0 && $("nav").hasClass(
                "active")) {
            $("nav").removeClass("active");
        }
    });
});
</script>