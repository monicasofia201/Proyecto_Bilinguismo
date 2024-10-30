<div class="navbar">

    <ul class="navbar-nav">

        <li>
            <a id="showRevista" type="button" class="nav-link nav-item-hover">
                <i class="fas fa-graduation-cap nav-link"></i>
                <span class="nav-item panel_script" data-lang-es="Revista" data-lang-en="Magazine"
                    data-lang-fr="Magazine">
                    Revista

                </span>
            </a>
        </li>

        <?php
                                    if ($_SESSION['id_rol'] != 1 && $_SESSION['id_rol'] != 5 && $_SESSION['id_rol'] != 4) {
                                        echo '
                                            <li>   
                                                <a type="button" data-bs-toggle="modal" data-bs-target="#noticiaModal" class="nav-link nav-item-hover">
                                                    <i class="fas fa-plus nav-link "> </i>
                                                        <span class="nav-item panel_script" data-lang-es="Crear" data-lang-en="Create" data-lang-fr="Créer"> 
                                                            Crear  
                                                            
                                                        </span>
                                                   
                                                    
                                                </a>
                                            </li>  
                                          
                                            <li>
                                                <a type="button" class="nav-link nav-item-hover">
                                                    <i class="fas fa-thin fa-folder-open nav-link"></i> 
                                                        <span id="MisSoliActivate" data-bs-toggle="modal" data-bs-target="#MisSoli" class="nav-item panel_script" data-lang-es="Mis Publicaciones" data-lang-en="My Publications" data-lang-fr="Mes Publications">
                                                            Mis Publicaciones
                                                        </span>
                                                       
                                                    
                                                </a>
                                               
                                            </li> 
                                    ';
                                    }
                                ?>

        <div class="modal fade" id="MisSoli" tabindex="-1" aria-labelledby="MisSoliLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="">Mis Publicaciones</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div id="MisSoliForm"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="close-button" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </ul>
</div>
<style>
.navbar-nav {
    margin-top: 1.5rem;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
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
    /* Asegura que los enlaces estén en línea */
}

.navbar-dark .navbar-nav .nav-link:hover {
    background-color: #007bff;
}

.navbar-dark .navbar-nav li {
    display: inline-block;
    /* Asegura que los elementos de la lista estén en línea */
}


/* hover iconos */
.nav-item-hover:hover {
    transform: scale(1.1);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}



.fixed-top-right {
    position: absolute;
    top: 10px;
    /* Ajusta este valor según necesites */
    right: 10px;
    /* Ajusta este valor según necesites */
    z-index: 1000;
    /* Asegura que esté por encima de otros elementos */
    padding: 5px 10px;
    /* Espaciado interno */
}

.fixed-top-right .btn i {
    margin-right: 5px;
    /* Espacio entre el icono y el texto */
}


.grid-container {
    display: grid;

    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(4, 2fr);
    gap: 3px;
    justify-items: center;
    /* Centrar elementos horizontalmente */
    align-items: center;
    /* Centrar elementos verticalmente */
    border-radius: 15px;
}
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