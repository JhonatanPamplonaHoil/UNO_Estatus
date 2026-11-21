<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNO Estatus - Sistema de Titulación</title>
    <!-- Bootstrap 5 y FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f4f6f9 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .portal-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            border: none;
            border-radius: 15px;
        }
        .portal-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12) !important;
        }
        .icon-box {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.2rem;
        }
    </style>
</head>
<body>

    <main class="container my-auto py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-dark">UNO Estatus</h1>
            <p class="lead text-muted">Sistema de Seguimiento de Titulación y Entrega de Títulos</p>
            <p class="text-secondary">Selecciona tu tipo de perfil para ingresar al sistema:</p>
        </div>

        <div class="row justify-content-center g-4">
            <!-- Apartado: Estudiante (Frontend) -->
            <div class="col-md-5 col-lg-4">
                <div class="card portal-card shadow-sm h-100 text-center p-4">
                    <div class="card-body d-flex flex-column">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3 class="card-title fw-bold mb-3">Estudiante</h3>
                        <p class="card-text text-muted mb-4">
                            Consulta el avance de tu trámite de titulación, revisa tu historial y agenda tu cita de entrega.
                        </p>
                        <a href="frontend/web/index.php" class="btn btn-primary btn-lg mt-auto w-100 rounded-pill">
                            Ingresar como Estudiante <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Apartado: Docente / Administrativo (Backend) -->
            <div class="col-md-5 col-lg-4">
                <div class="card portal-card shadow-sm h-100 text-center p-4">
                    <div class="card-body d-flex flex-column">
                        <div class="icon-box bg-dark bg-opacity-10 text-dark">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="card-title fw-bold mb-3">Docente</h3>
                        <p class="card-text text-muted mb-4">
                            Aquí solo ingresan personal autorizado.
                        </p>
                        <a href="backend/web/index.php" class="btn btn-dark btn-lg mt-auto w-100 rounded-pill">
                            Ingresar <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center py-3 text-muted small">
        &copy; <?= date('Y') ?> Universidad de Oriente (UNO) - Todos los derechos reservados.
    </footer>

</body>
</html>