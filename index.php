<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNO ESTATUS - Sistema de Titulación</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='16' fill='%238B1538'/%3E%3Ctext x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' fill='white' font-family='Arial, sans-serif' font-weight='bold' font-size='24'%3EUNO%3C/text%3E%3C/svg%3E">
    <!-- Bootstrap 5, FontAwesome y Tipografías Modernas -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --blanco-puro: #FFFFFF;
            --blanco-perla: #FAF7F8;
            --vino-principal: #8B1538;
            --vino-brillante: #B01C47;
            --vino-oscuro: #590B22;
            --vino-profundo: #2B0611;
            --texto-oscuro: #1F0A10;
            --texto-suave: #5C474E;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--blanco-perla);
            color: var(--texto-oscuro);
            /* Fondo blanco luminoso con cuadrícula animada y franja superior vino */
            background-image:
                linear-gradient(90deg, var(--vino-oscuro) 0%, var(--vino-principal) 35%, var(--vino-brillante) 50%, var(--vino-principal) 65%, var(--vino-oscuro) 100%),
                radial-gradient(circle at 50% 42%, rgba(255, 255, 255, 0.92) 0%, rgba(250, 247, 248, 0.4) 70%),
                linear-gradient(rgba(139, 21, 56, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(139, 21, 56, 0.05) 1px, transparent 1px);
            background-size:
                100% 5px,
                100% 100%,
                48px 48px,
                48px 48px;
            background-position:
                top center,
                center,
                0 0,
                0 0;
            background-repeat:
                no-repeat,
                no-repeat,
                repeat,
                repeat;
            animation: gridMove 22s linear infinite;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        /* Ondas de luz rojo vino flotantes */
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(95px);
            z-index: -2;
            pointer-events: none;
            animation: floatGlow 11s ease-in-out infinite alternate;
        }

        body::before {
            width: 430px;
            height: 430px;
            background: rgba(176, 28, 71, 0.16);
            top: -100px;
            left: -80px;
        }

        body::after {
            width: 460px;
            height: 460px;
            background: rgba(139, 21, 56, 0.15);
            bottom: -110px;
            right: -80px;
            animation-delay: -5.5s;
        }

        /* Lienzo de partículas animadas */
        #uno-bg-canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        /* Título principal UNO ESTATUS en mayúsculas con brillo rojo vino */
        main .text-center h1 {
            font-family: 'Outfit', sans-serif;
            font-weight: 900 !important;
            text-transform: uppercase;
            font-size: clamp(2.5rem, 5.5vw, 3.75rem);
            letter-spacing: 0.05em;
            background: linear-gradient(135deg, var(--vino-oscuro) 0%, var(--vino-principal) 50%, var(--vino-brillante) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent !important;
            filter: drop-shadow(0 4px 16px rgba(176, 28, 71, 0.24));
            position: relative;
            display: inline-block;
            padding-bottom: 0.65rem;
            margin-bottom: 1rem;
            animation: fadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        /* Barra láser luminosa rojo vino bajo el título */
        main .text-center h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 115px;
            height: 4px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--vino-oscuro), var(--vino-brillante), var(--vino-oscuro));
            background-size: 200% auto;
            box-shadow: 0 0 14px rgba(176, 28, 71, 0.55);
            animation: laserExpand 0.85s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both, shimmerBar 3s linear infinite;
        }

        main .text-center .lead {
            color: var(--texto-oscuro) !important;
            font-weight: 600;
            font-size: 1.16rem;
            animation: fadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.12s both;
        }

        main .text-center .text-secondary {
            color: var(--texto-suave) !important;
            font-size: 0.96rem;
            animation: fadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.22s both;
        }

        /* Entrada escalonada de tarjetas */
        .row > div:nth-child(1) .portal-card {
            animation: fadeUp 0.75s cubic-bezier(0.16, 1, 0.3, 1) 0.28s both;
        }

        .row > div:nth-child(2) .portal-card {
            animation: fadeUp 0.75s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both;
        }

        /* Tarjetas blancas tipo cristal con acentos rojo vino */
        .portal-card {
            background: rgba(255, 255, 255, 0.88) !important;
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(139, 21, 56, 0.18) !important;
            border-radius: 26px;
            position: relative;
            overflow: hidden;
            box-shadow:
                0 16px 38px -10px rgba(89, 11, 34, 0.12),
                0 4px 14px -2px rgba(89, 11, 34, 0.06) !important;
            transition:
                transform 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                border-color 0.35s ease;
        }

        /* Línea superior luminosa al pasar el cursor */
        .portal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            opacity: 0.45;
            transform: scaleX(0.5);
            background: linear-gradient(90deg, var(--vino-oscuro), var(--vino-brillante), var(--vino-oscuro));
            box-shadow: 0 0 12px rgba(176, 28, 71, 0.5);
            transition: opacity 0.35s ease, transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .portal-card:hover {
            transform: translateY(-10px);
            border-color: rgba(176, 28, 71, 0.5) !important;
            box-shadow:
                0 24px 48px -12px rgba(139, 21, 56, 0.24),
                0 0 24px rgba(176, 28, 71, 0.16) !important;
        }

        .portal-card:hover::before {
            opacity: 1;
            transform: scaleX(1);
        }

        /* Iconos con resplandor rojo vino */
        .icon-box {
            width: 88px;
            height: 88px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 22px;
            font-size: 2.25rem;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
            animation: floatIcon 3.8s ease-in-out infinite;
        }

        .row > div:nth-child(1) .icon-box {
            background: linear-gradient(135deg, rgba(176, 28, 71, 0.13) 0%, rgba(139, 21, 56, 0.05) 100%) !important;
            color: var(--vino-principal) !important;
            border: 1px solid rgba(176, 28, 71, 0.26);
            box-shadow: 0 8px 20px rgba(139, 21, 56, 0.12);
        }

        .row > div:nth-child(2) .icon-box {
            background: linear-gradient(135deg, rgba(43, 6, 17, 0.11) 0%, rgba(89, 11, 34, 0.05) 100%) !important;
            color: var(--vino-oscuro) !important;
            border: 1px solid rgba(89, 11, 34, 0.26);
            box-shadow: 0 8px 20px rgba(43, 6, 17, 0.12);
            animation-delay: 1.9s;
        }

        .portal-card:hover .icon-box {
            transform: scale(1.1) rotate(-4deg);
            box-shadow: 0 12px 26px rgba(176, 28, 71, 0.25);
        }

        .card-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800 !important;
            font-size: 1.6rem;
            color: var(--texto-oscuro) !important;
        }

        .card-text {
            color: var(--texto-suave) !important;
            line-height: 1.6;
            font-size: 0.96rem;
        }

        /* Botones rojo vino con barrido de brillo */
        .portal-card .btn-lg {
            font-size: 1rem;
            font-weight: 700;
            padding: 0.88rem 1.5rem;
            position: relative;
            overflow: hidden;
            border: none;
            transition: transform 0.25s ease, box-shadow 0.3s ease, filter 0.3s ease;
        }

        .portal-card .btn-lg::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.35), transparent);
            transition: left 0.55s ease;
        }

        .portal-card .btn-lg:hover::after {
            left: 145%;
        }

        .portal-card .btn-primary {
            background: linear-gradient(135deg, var(--vino-brillante) 0%, var(--vino-principal) 100%) !important;
            color: #FFFFFF !important;
            box-shadow: 0 8px 22px -4px rgba(176, 28, 71, 0.5);
        }

        .portal-card .btn-primary:hover {
            transform: translateY(-2px);
            filter: brightness(1.07);
            box-shadow: 0 12px 28px -4px rgba(176, 28, 71, 0.72);
        }

        .portal-card .btn-dark {
            background: linear-gradient(135deg, var(--vino-oscuro) 0%, var(--vino-profundo) 100%) !important;
            color: #FFFFFF !important;
            box-shadow: 0 8px 22px -4px rgba(43, 6, 17, 0.45);
        }

        .portal-card .btn-dark:hover {
            transform: translateY(-2px);
            filter: brightness(1.12);
            box-shadow: 0 12px 28px -4px rgba(89, 11, 34, 0.65);
        }

        .portal-card .btn-lg:active {
            transform: scale(0.98);
        }

        .portal-card .btn-lg i {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .portal-card .btn-lg:hover i {
            transform: translateX(6px);
        }

        /* Pie de página blanco translúcido */
        footer {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid rgba(139, 21, 56, 0.12);
            color: var(--texto-suave) !important;
            animation: fadeIn 1s ease 0.5s both;
        }

        @keyframes gridMove {
            0% { background-position: top center, center, 0 0, 0 0; }
            100% { background-position: top center, center, 48px 48px, 48px 48px; }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes laserExpand {
            from { width: 0; opacity: 0; }
            to { width: 115px; opacity: 1; }
        }
        @keyframes shimmerBar {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }
        @keyframes floatGlow {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(45px, 35px) scale(1.14); }
        }
        @keyframes floatIcon {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>

    <main class="container my-auto py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-dark">UNO ESTATUS</h1>
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

    <script>
        (function () {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            const canvas = document.createElement('canvas');
            canvas.id = 'uno-bg-canvas';
            document.body.prepend(canvas);

            const ctx = canvas.getContext('2d');
            let width, height, particles;
            const mouse = { x: null, y: null, radius: 140 };

            window.addEventListener('mousemove', (e) => {
                mouse.x = e.clientX;
                mouse.y = e.clientY;
            });

            window.addEventListener('mouseout', () => {
                mouse.x = null;
                mouse.y = null;
            });

            function resize() {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
                initParticles();
            }

            function initParticles() {
                const count = Math.min(Math.floor((width * height) / 18000), 65);
                particles = [];
                for (let i = 0; i < count; i++) {
                    particles.push({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        vx: (Math.random() - 0.5) * 0.65,
                        vy: (Math.random() - 0.5) * 0.65,
                        r: Math.random() * 2.4 + 1.4,
                        alpha: Math.random() * 0.35 + 0.2
                    });
                }
            }

            function animate() {
                ctx.clearRect(0, 0, width, height);

                for (let i = 0; i < particles.length; i++) {
                    const p = particles[i];
                    p.x += p.vx;
                    p.y += p.vy;

                    if (p.x < 0 || p.x > width) p.vx *= -1;
                    if (p.y < 0 || p.y > height) p.vy *= -1;

                    // Interacción suave con el cursor
                    if (mouse.x !== null && mouse.y !== null) {
                        const dx = mouse.x - p.x;
                        const dy = mouse.y - p.y;
                        const dist = Math.hypot(dx, dy);
                        if (dist < mouse.radius && dist > 0) {
                            p.x -= (dx / dist) * 0.8;
                            p.y -= (dy / dist) * 0.8;
                        }
                    }

                    // Punto rojo vino luminoso
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(176, 28, 71, ${p.alpha})`;
                    ctx.fill();

                    // Líneas de conexión entre nodos cercanos
                    for (let j = i + 1; j < particles.length; j++) {
                        const p2 = particles[j];
                        const dist = Math.hypot(p.x - p2.x, p.y - p2.y);
                        if (dist < 135) {
                            const lineAlpha = (1 - dist / 135) * 0.22;
                            ctx.beginPath();
                            ctx.moveTo(p.x, p.y);
                            ctx.lineTo(p2.x, p2.y);
                            ctx.strokeStyle = `rgba(139, 21, 56, ${lineAlpha})`;
                            ctx.lineWidth = 1;
                            ctx.stroke();
                        }
                    }
                }

                requestAnimationFrame(animate);
            }

            window.addEventListener('resize', resize);
            resize();
            animate();
        })();
    </script>

</body>
</html>