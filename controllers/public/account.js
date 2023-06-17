/*
*   Controlador es de uso general en las páginas web del sitio público.
*   Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'business/public/estudiante_login.php';
// Constantes para establecer las etiquetas de encabezado y pie de la página web.
const HEADER = document.querySelector('header');
const FOOTER = document.querySelector('footer');

// Método manejador de eventos para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Petición para obtener en nombre del usuario que ha iniciado sesión.
    const JSON = await dataFetch(USER_API, 'getUser');
    // Se comprueba si el usuario está autenticado para establecer el encabezado respectivo.
    if (JSON.session) {
        HEADER.innerHTML = `
<div>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
<!-- Container wrapper -->
<div class="container">
    <!-- Navbar brand -->
    <a class="img_navbar">
        <img src="../../recursos/logo.png" style="width: 9rem;" alt="">
    </a>

    <!-- Toggle button -->
    <button class="navbar-toggler" type="button" data-mdb-toggle="collapse"
        data-mdb-target="#navbarButtonsExample" aria-controls="navbarButtonsExample" aria-expanded="false"
        aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarRightAlignExample">
        <!-- Left links -->
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.html">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="notas.html">Notas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="conducta.html">Conducta</a>
            </li>
        </ul>
        <!-- Left links -->
    </div>
    <div class="d-flex align-items-center">
        <button type="button" class="btn btn-link px-3 me-2">
            <img src="../../recursos/iconos/usuario.png" alt="">
            
            <p>
            <a  onclick="logOut()">Salir</a>
        </p>
        </button>
        
    </div>
    <a  href="#"><b>${JSON.username}</b></a>
</div>
<!-- Collapsible wrapper -->
</div>
<!-- Container wrapper -->
</nav>

</div>
        `;
    } else {
        HEADER.innerHTML = `
        <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <!-- Container wrapper -->
        <div class="container">
            <!-- Navbar brand -->
            <a class="img_navbar">
                <img src="../../recursos/logo.png" style="width: 9rem;" alt="">
            </a>

            <!-- Toggle button -->
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse"
                data-mdb-target="#navbarButtonsExample" aria-controls="navbarButtonsExample" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-link px-3 me-2">
                    <a href="login.html"><img src="../../recursos/iconos/usuario.png" alt=""></a>
                </button>

            </div>
        </div>
        <!-- Collapsible wrapper -->
        </div>
        <!-- Container wrapper -->
    </nav>
    </div>
    
        `;}
    // Se establece el pie del encabezado.
    FOOTER.innerHTML = `
    <footer class="bg-dark text-center text-white">
    <!-- Grid container -->
    <div class="container p-4 pb-0">
        <!-- Section: Social media -->
        <section class="mb-4">
            <img src="../../recursos/logo_abajo.png" style="width: 20rem;" alt="">
        </section>
        <div class="row">
            <div class="col">
                DIRECCIÓN: San Salvador,El Salvador
            </div>
            <div class="col">
                CONTACTO:+503 7787-8604
            </div>

        </div>

        <!-- Section: Social media -->
    </div>
    <!-- Grid container -->

    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        <!-- Facebook -->
        <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                class="fab fa-facebook-f"></i></a>
        <!-- Instagram -->
        <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                class="fab fa-instagram"></i></a>
    </div>

    <!-- Copyright -->
</footer>
    `;

});