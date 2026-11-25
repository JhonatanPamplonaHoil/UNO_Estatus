<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\TramiteTitulacion $tramite */
/** @var common\models\CitaEntrega|null $citaExistente */

$this->title = 'Agendar cita de entrega de título';

// Registrar librerías (FullCalendar y SweetAlert2) para la interfaz
$this->registerJsFile('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['position' => \yii\web\View::POS_HEAD]);

// CSS para ajustar el calendario y la interfaz sin deformar al achicar la ventana
$this->registerCss("
    .cita-entrega-agendar .uno-cita-shell {
        background-color: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(158, 42, 75, 0.18);
        border-radius: 14px;
        box-shadow: 0 10px 26px rgba(68, 12, 28, 0.1);
        overflow: hidden;
    }

    .cita-entrega-agendar .uno-cita-banner {
        background-color: #5c0f24;
        padding: 1.35rem 1.75rem;
        border-bottom: 2px solid rgba(233, 190, 88, 0.55);
    }

    .cita-entrega-agendar .uno-cita-title {
        margin: 0;
        font-size: clamp(1.35rem, 2.3vw, 1.9rem);
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.01em;
        line-height: 1.25;
        word-break: break-word;
    }

    .cita-entrega-agendar .uno-cita-subtitle {
        margin: 0.3rem 0 0;
        font-size: 0.92rem;
        color: rgba(255, 255, 255, 0.78);
        line-height: 1.35;
    }

    .cita-entrega-agendar .uno-cita-body {
        padding: 1.35rem 1.5rem 1.5rem;
    }

    .cita-entrega-agendar .uno-cita-programada {
        background-color: #ffffff;
        border: 1px solid rgba(25, 135, 84, 0.28);
        border-left: 5px solid #198754;
        border-radius: 12px;
        color: #1f2d26;
        padding: 1.25rem 1.4rem;
    }

    .cita-entrega-agendar .uno-cita-programada h4 {
        color: #146c43;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .cita-entrega-agendar .uno-cita-info {
        background-color: rgba(158, 42, 75, 0.05);
        border: 1px solid rgba(158, 42, 75, 0.16);
        border-left: 4px solid #e9be58;
        border-radius: 10px;
        color: #2d141c;
        padding: 0.95rem 1.15rem;
        margin-bottom: 1.25rem;
    }

    .cita-entrega-agendar .uno-calendar-card {
        background-color: #ffffff;
        border: 1px solid rgba(158, 42, 75, 0.14);
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(158, 42, 75, 0.06);
    }

    #calendar {
        max-width: 900px;
        width: 100%;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
    }

    .fc-event {
        cursor: pointer;
    }

    .fc .fc-toolbar {
        flex-wrap: wrap;
        gap: 0.6rem;
    }

    .fc .fc-toolbar-title {
        font-size: clamp(1.05rem, 2vw, 1.45rem);
        font-weight: 700;
        color: #2d141c;
    }

    @media (max-width: 767.98px) {
        .cita-entrega-agendar .uno-cita-banner {
            padding: 1.1rem 1.15rem;
        }

        .cita-entrega-agendar .uno-cita-body {
            padding: 1rem;
        }

        #calendar {
            padding: 10px;
        }

        .fc .fc-toolbar {
            justify-content: center;
        }
    }
");
?>

<div class="cita-entrega-agendar">
    <div class="uno-cita-shell">
        <div class="uno-cita-banner">
            <h2 class="uno-cita-title"><?= Html::encode($this->title) ?></h2>
            <p class="uno-cita-subtitle">Selecciona una fecha disponible o revisa el detalle de tu cita asignada</p>
        </div>

        <div class="uno-cita-body">
            <?php if ($citaExistente): ?>
                <!-- Mensaje si el alumno ya apartó su cita -->
                <div class="alert alert-success shadow-sm uno-cita-programada">
                    <h4><i class="fas fa-check-circle"></i> ¡Ya tienes una cita programada!</h4>
                    <hr>
                    <p style="font-size: 16px;">
                        Tu cita para recoger el título está programada para el día 
                        <strong><?= Yii::$app->formatter->asDate($citaExistente->fecha, 'php:d/m/Y') ?></strong> 
                        a las <strong><?= date('H:i', strtotime($citaExistente->hora)) ?> hrs</strong>.
                    </p>
                    <p class="mb-0">Por favor, preséntate en la ventanilla de Servicios Escolares con tu identificación oficial.</p>
                </div>
            <?php else: ?>
                <!-- Instrucciones y Calendario -->
                <div class="alert alert-info uno-cita-info">
                    <strong>Instrucciones:</strong> Selecciona un día en color <span class="badge" style="background-color: #28a745;">Verde</span> para agendar tu cita. Los días en rojo indican que el cupo está lleno o no hay servicio.
                </div>

                <div class="card shadow-sm uno-calendar-card">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!$citaExistente): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es', // Idioma español
        eventDisplay: 'block',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes'
        },
        // Aquí conectamos con la función JSON del controlador
        events: '<?= Url::to(['cita-entrega/eventos']) ?>',
        
        // Acción al hacer clic en un evento (día)
        eventClick: function(info) {
            info.jsEvent.preventDefault();

            let esDisponible = info.event.extendedProps.disponible;
            let fechaFmt = info.event.extendedProps.fecha_formateada;
            let horario = info.event.extendedProps.horario;
            let disponibilidadId = info.event.id;

            if (!esDisponible) {
                Swal.fire('Cupo Lleno', 'Lo sentimos, este día ya no tiene lugares disponibles. Por favor, elige otro día en color verde.', 'error');
                return;
            }

            // Confirmación antes de agendar
            Swal.fire({
                title: 'Confirmar Cita',
                text: `¿Deseas agendar tu entrega para el día ${fechaFmt} en horario de ${horario}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Petición AJAX POST
                    fetch('<?= Url::to(['cita-entrega/guardar-cita']) ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-Token': yii.getCsrfToken() // Protección nativa de Yii2
                        },
                        body: new URLSearchParams({
                            'disponibilidad_id': disponibilidadId,
                            'tramite_id': <?= $tramite->id ?>
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Agendada!', data.message, 'success').then(() => {
                                location.reload(); // Recarga para mostrar el cuadro verde de éxito
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                            calendar.refetchEvents(); // Refresca los cupos por si alguien más la ganó
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Hubo un error de red al procesar la cita.', 'error');
                    });
                }
            });
        }
    });
    
    calendar.render();
});
</script>
<?php endif; ?>