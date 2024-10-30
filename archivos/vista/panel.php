<style>
.navbar-nav {
    margin-top: 1.5rem;
    display: flex;
    flex-direction: row;
    padding-left: 0;
    margin-bottom: 0;
    list-style: none;
}

.navbar-dark .navbar-nav {
    display: flex;
}

.navbar-dark .navbar-nav .nav-link {
    transition: all 0.3s ease;
    padding: 18px 15px;
    text-decoration: none;
    display: inline-block;
}

.navbar-dark .navbar-nav .nav-link:hover {
    background-color: #007bff;
}

.navbar-dark .navbar-nav li {
    display: inline-block;
}

.nav-item-hover:hover {
    transform: scale(1.1);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.fixed-top-right {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1000;
    padding: 5px 10px;
}

.fixed-top-right .btn i {
    margin-right: 5px;
}

.grid-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(4, 2fr);
    gap: 3px;
    justify-items: center;
    align-items: center;
    border-radius: 15px;
}

/* Media query para pantallas pequeñas */
@media (max-width: 768px) {
    .navbar-nav {
        flex-direction: column;
        align-items: flex-start;
    }

    .navbar-dark .navbar-nav .nav-link {
        padding: 10px 15px;
    }

    .grid-container {
        grid-template-columns: 1fr;
        grid-template-rows: auto;
    }
    
    .camilo, .camilo2 {
        left: 0;
        position: relative;
        
    }
}

</style>
<ul class="navbar-nav">
    <?php
        if ($_SESSION['id_rol'] == 3) {
            echo '
            <style>
                .camilo{
                top:1.5rem;
                left:5rem;
                position: relative;
                    text-align: center;
                }
                .camilo2{
                top:3rem;
                left:6rem;
                position: relative;
                    text-align: center;
                }
            </style>
                <li>
                    <a href="usuarios.php" class="nav-link nav-item-hover">
                        <i class="fas fa-graduation-cap camilo2"></i>
                        <span class="camilo">
                            Administrar Usuarios
                            
                        </span>
                    </a>
                </li>
                <li>
                    <a href="solicitud.php" class="nav-link nav-item-hover">
                        <i class="fas fa-check-double nav-link"></i>
                        <span class="">
                            Administrar Solicitudes
                            
                        </span>
                    </a>
                </li>
                <li>
                    <a href="ofertas.php" class="nav-link nav-item-hover ">
                        <i class="fas fa-graduation-cap nav-link"></i>
                        <span class="">
                            Ofertas
                        </span>
                    </a>
                </li>
                <li>
                    <a href="GprogramaFormacion.php" class="nav-link nav-item-hover">
                        <i class="fas fa-graduation-cap nav-link"></i>
                        <span class="">
                            Programacion
                            
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
                    <a href="solicitud.php" class="nav-link nav-item-hover">
                        <i class="fas fa-check-double nav-link "></i>
                        <span class="">Solicitudes
                            
                        </span>
                    </a>
                </li>
                <li>
                    <a href="asignaciones.php" class="nav-link nav-item-hover">
                        <i class="fas fa-check-double fa-fw fa-lg nav-link"></i>
                        <span class="">Asignaciones
                        
                        </span>
                    </a>
                </li>
                <li>
                    <a href="GprogramaFormacion.php" class="nav-link nav-item-hover">
                        <i class="fas fa-graduation-cap fa-fw fa-lg nav-link" ></i>
                        <span class="">Programa Formación
                            
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
                    <a href="solicitud.php" class="nav-link nav-item-hover">
                        <i class="fas fa-check-double fa-fw fa-lg nav-lin"></i>
                        <span class="">Solicitudes
                            
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
                    <a href="solicitud.php" class="nav-link nav-item-hover">
                        <i class="fas fa-check-double fa-fw fa-lg nav-link"></i>
                        <span class="">Solicitudes
                            
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
                    <a href="solicitud.php" class="nav-link nav-item-hover">
                        <i class="fas fa-check-double fa-fw fa-lg nav-link"></i>
                        <span class="">Solicitudes
                            
                        </span>
                    </a>
                </li>
            ';
        }
    ?>
</ul>