<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="build/js/app.js"></script>
    <link rel="shortcut icon" href="<?= asset('images/madena.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>SICOMAR</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark  bg-dark">
        
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/sicomar/">
                <img src="<?= asset('./images/madena.png') ?>" width="35px'" alt="cit" >
                SICOMAR
            </a>
            <div class="collapse navbar-collapse" id="navbarToggler">
                
                <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin: 0;">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/sicomar/"><i class="bi bi-house-fill me-2"></i>Inicio</a>
                    </li>
  
                    <div class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-shield-shaded me-2"></i>Operaciones
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-dark nav-index " id="dropwdownRevision">
                        <!-- <h6 class="dropdown-header">Información</h6> -->
                        <li><a class="dropdown-item nav-link text-white " href="#"><i class="ms-lg-0 ms-2 bi bi-file-earmark-text me-2"></i>Hoja de zarpe</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"><i class="ms-lg-0 ms-2 bi bi-card-list me-2"></i>Ver zarpes</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="./reporte"> <i class="ms-lg-0 ms-2 bi bi-activity me-2"></i>Reporte de patrulla</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"><i class="ms-lg-0 ms-2 bi bi-person-check me-2"></i>Validar reporte</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"><i class="ms-lg-0 ms-2 bi bi-ui-checks me-2"></i>Validar operación</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"><i class="ms-lg-0 ms-2 bi bi-globe-americas me-2"></i>Internacionales</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"><i class="ms-lg-0 ms-2 bi bi-globe-americas me-2"></i>Validar internacional</a></li>  
                    </ul>
                </div> 
                <div class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i>Reportes
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-dark nav-index " id="dropwdownRevision">
                        <!-- <h6 class="dropdown-header">Información</h6> -->
                        <li><a class="dropdown-item nav-link text-white " href="#"><i class="ms-lg-0 ms-2 bi bi-file-earmark-person me-2"></i>Perfil operativo</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"> <i class="ms-lg-0 ms-2 bi bi-graph-up-arrow me-2"></i>Estadísticas</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"> <i class="ms-lg-0 ms-2 bi bi-file-earmark-post me-2"></i>Perfil de Unidad</a></li>
                    
                    
                
                    
                    </ul>
                </div> 
                <div class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-gear-wide-connected me-2"></i>Administración
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-dark nav-index " id="dropwdownRevision">
                        <!-- <h6 class="dropdown-header">Información</h6> -->
                        <li><a class="dropdown-item nav-link text-white " href="#"><i class="ms-lg-0 ms-2 bi bi-boxes me-2"></i>Insumos</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"> <i class="ms-lg-0 ms-2 bi bi-diagram-2 me-2"></i>Operaciones</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"> <i class="ms-lg-0 ms-2 bi bi-broadcast me-2"></i>Medios</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"> <i class="ms-lg-0 ms-2 bi bi-bullseye me-2"></i>Receptores</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"> <i class="ms-lg-0 ms-2 bi bi-node-plus me-2"></i>Tipos de Embarcaciones</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"> <i class="ms-lg-0 ms-2 bi bi-diagram-3 me-2"></i>Embarcaciones</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"> <i class="ms-lg-0 ms-2 bi bi-gear me-2"></i>Motores</a></li>
                        <li><a class="dropdown-item nav-link text-white " href="#"> <i class="ms-lg-0 ms-2 bi bi-gear me-2"></i>Unidades de Medida</a></li>
                      
                      
                   
                       
                    </ul>
                </div> 
                </ul> 
                <div class="col-lg-1 d-grid mb-lg-0 mb-2">
                    <!-- Ruta relativa desde el archivo donde se incluye menu.php -->
                    <a href="/menu/" class="btn btn-danger"><i class="bi bi-arrow-bar-left"></i>MENÚ</a>
                </div>

            
            </div>
        </div>
        
    </nav>
    <div class="progress fixed-bottom" style="height: 6px;">
        <div class="progress-bar progress-bar-animated bg-danger" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="container-fluid pt-5 mb-4" style="min-height: 85vh">
        
        <?php echo $contenido; ?>
    </div>
    <div class="container-fluid " >
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p style="font-size:xx-small; font-weight: bold;">
                        Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
                </p>
            </div>
        </div>
    </div>
</body>
</html>