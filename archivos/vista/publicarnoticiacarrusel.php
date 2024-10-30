<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
            aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
            aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
            aria-label="Slide 3"></button>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define the URLs for each image
        const urls = {
            img1: 'https://zajuna.sena.edu.co',
            img2: 'https://zajuna.sena.edu.co',
            img3: 'https://zajuna.sena.edu.co'
        };

        // Attach click event listeners to the images
        document.getElementById('img1').addEventListener('click', function() {
            window.location.href = urls.img1;
        });
        document.getElementById('img2').addEventListener('click', function() {
            window.location.href = urls.img2;
        });
        document.getElementById('img3').addEventListener('click', function() {
            window.location.href = urls.img3;
        });
    });
</script>
    <style>
    

    .carousel-control-prev,
    .carousel-control-next {
        width: auto;
        /* Ajusta el ancho para que solo los íconos sean clicables */
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5);
        /* Fondo semitransparente para mejor visibilidad */
        border-radius: 50%;
        /* Forma circular */
        padding: 10px;
        /* Espacio alrededor del ícono */
    }
    </style>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img id="img1" src="../../imagenes/img/banner/banner12-banner.webp" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
            <img id="img2" src="../../imagenes/img/banner/banner11-banner.webp" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
            <img id="img3" src="../../imagenes/img/banner/BANNER-INGLES-BANNER.webp" class="d-block w-100" alt="...">
        </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
        data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previo</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
        data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>
