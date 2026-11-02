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

// CSS para ajustar el calendario
$this->registerCss("
    #calendar { max-width: 900px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; }
    .fc-event { cursor: pointer; }
");
?>

<div class="cita-entrega-agendar">
    <h2 class="mb-4"><?= Html::encode($this->title) ?></h2>

    <?php if ($citaExistente): ?>
        <!-- Mensaje si el alumno ya apartó su cita -->
        <div class="alert alert-success shadow-sm">
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
        <div class="alert alert-info">
            <strong>Instrucciones:</strong> Selecciona un día en color <span class="badge" style="background-color: #28a745;">Verde</span> para agendar tu cita. Los días en rojo indican que el cupo está lleno o no hay servicio.
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    <?php endif; ?>
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