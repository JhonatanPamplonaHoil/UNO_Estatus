<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'Disponibilidad Citas';
$this->params['breadcrumbs'][] = $this->title;

// Librerías de FullCalendar y SweetAlert2
$this->registerJsFile('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['position' => \yii\web\View::POS_HEAD]);

$this->registerCss("
    :root {
        --uno-guinda: #6B1D3A;
        --uno-guinda-oscuro: #4E1329;
        --uno-guinda-suave: #FDF6F8;
        --uno-dorado: #C59B27;
        --uno-dorado-claro: #DFB94C;
        --uno-dorado-suave: #FBF8EE;
        --uno-borde: #E6DCE0;
    }

    .disponibilidad-cita-index {
        padding-bottom: 2rem;
    }

    /* Encabezado institucional */
    .uno-page-header {
        background: linear-gradient(135deg, var(--uno-guinda) 0%, var(--uno-guinda-oscuro) 100%);
        color: #ffffff;
        padding: 1.5rem 1.75rem;
        border-radius: 14px;
        border-bottom: 4px solid var(--uno-dorado);
        box-shadow: 0 6px 18px rgba(107, 29, 58, 0.16);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .uno-page-header h1 {
        font-size: 1.65rem;
        font-weight: 700;
        margin: 0;
        color: #ffffff;
        letter-spacing: 0.3px;
    }

    .uno-page-subtitle {
        margin: 0.25rem 0 0 0;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.85);
    }

    .uno-header-badge {
        background: rgba(197, 155, 39, 0.18);
        border: 1px solid var(--uno-dorado);
        color: #FCE9B2;
        padding: 0.45rem 0.95rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Alerta de instrucciones */
    .uno-alert-instrucciones {
        background-color: var(--uno-dorado-suave);
        border: 1px solid rgba(197, 155, 39, 0.35);
        border-left: 5px solid var(--uno-dorado);
        color: #4A3B10;
        border-radius: 10px;
        padding: 0.95rem 1.2rem;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    .uno-alert-instrucciones i {
        color: var(--uno-guinda);
        margin-right: 6px;
        font-size: 1.05rem;
    }

    /* Contenedor del Calendario */
    #calendar {
        background: #ffffff;
        padding: 24px;
        border-radius: 14px;
        border: 1px solid var(--uno-borde);
        border-top: 4px solid var(--uno-guinda);
        box-shadow: 0 8px 24px rgba(44, 36, 39, 0.07);
    }

    /* Barra superior de FullCalendar */
    #calendar .fc-toolbar-title {
        color: var(--uno-guinda);
        font-weight: 700;
        font-size: 1.4rem;
        text-transform: capitalize;
    }

    #calendar .fc-button-primary {
        background-color: var(--uno-guinda) !important;
        border-color: var(--uno-guinda) !important;
        color: #ffffff !important;
        font-weight: 600;
        border-radius: 8px !important;
        padding: 0.42rem 0.9rem;
        transition: all 0.2s ease;
        box-shadow: none !important;
    }

    #calendar .fc-button-primary:hover {
        background-color: var(--uno-guinda-oscuro) !important;
        border-color: var(--uno-dorado) !important;
    }

    #calendar .fc-button-primary:not(:disabled).fc-button-active,
    #calendar .fc-button-primary:not(:disabled):active {
        background-color: var(--uno-dorado) !important;
        border-color: var(--uno-dorado) !important;
        color: #2C2427 !important;
    }

    /* Encabezados de días de la semana */
    #calendar .fc-col-header-cell {
        background-color: #F9F2F5;
        padding: 10px 0;
        border-color: var(--uno-borde);
    }

    #calendar .fc-col-header-cell-cushion {
        color: var(--uno-guinda);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.82rem;
        text-decoration: none !important;
        letter-spacing: 0.5px;
    }

    /* Celdas de días */
    #calendar .fc-daygrid-day {
        border-color: var(--uno-borde);
        transition: background-color 0.15s ease;
    }

    #calendar .fc-daygrid-day:hover {
        background-color: var(--uno-guinda-suave);
        cursor: pointer;
    }

    #calendar .fc-daygrid-day-number {
        color: #3D2E34;
        font-weight: 600;
        text-decoration: none !important;
        padding: 6px 10px;
    }

    #calendar .fc-day-today {
        background-color: var(--uno-dorado-suave) !important;
    }

    #calendar .fc-day-today .fc-daygrid-day-number {
        background-color: var(--uno-dorado);
        color: #2C2427;
        border-radius: 6px;
        margin: 4px;
        padding: 3px 8px;
    }

    /* Eventos / Cupos en el calendario */
    .fc-event {
        cursor: pointer;
        border-radius: 6px !important;
        padding: 3px 7px !important;
        font-weight: 600;
        font-size: 0.85rem;
        border: none !important;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.12);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .fc-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(107, 29, 58, 0.22);
    }

    /* Estilo institucional para los Modales */
    .uno-modal .modal-content {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 12px 32px rgba(44, 36, 39, 0.2);
    }

    .uno-modal .modal-header {
        background: linear-gradient(135deg, var(--uno-guinda) 0%, var(--uno-guinda-oscuro) 100%);
        color: #ffffff;
        border-bottom: 3px solid var(--uno-dorado);
        padding: 1.1rem 1.4rem;
    }

    .uno-modal .modal-title {
        font-weight: 700;
        letter-spacing: 0.2px;
    }

    .uno-modal .modal-body {
        padding: 1.5rem;
        background-color: #ffffff;
    }

    .uno-modal .form-label {
        font-weight: 600;
        color: var(--uno-guinda);
        font-size: 0.9rem;
    }

    .uno-modal .form-control {
        border-radius: 8px;
        border: 1px solid #D5C8CD;
        padding: 0.55rem 0.85rem;
    }

    .uno-modal .form-control:focus {
        border-color: var(--uno-dorado);
        box-shadow: 0 0 0 0.2rem rgba(197, 155, 39, 0.25);
    }

    .uno-modal .modal-footer {
        background-color: #FAF7F8;
        border-top: 1px solid var(--uno-borde);
        padding: 0.95rem 1.4rem;
    }

    .uno-fecha-badge {
        display: inline-block;
        background-color: var(--uno-dorado-suave);
        border: 1px solid var(--uno-dorado);
        color: var(--uno-guinda) !important;
        font-weight: 700;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 1rem;
    }

    .btn-uno-guinda {
        background-color: var(--uno-guinda);
        border-color: var(--uno-guinda);
        color: #ffffff;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.48rem 1.15rem;
    }

    .btn-uno-guinda:hover {
        background-color: var(--uno-guinda-oscuro);
        border-color: var(--uno-dorado);
        color: #ffffff;
    }

    .btn-uno-dorado {
        background-color: var(--uno-dorado);
        border-color: var(--uno-dorado);
        color: #2C2427;
        font-weight: 700;
        border-radius: 8px;
        padding: 0.48rem 1.15rem;
    }

    .btn-uno-dorado:hover {
        background-color: var(--uno-dorado-claro);
        border-color: var(--uno-dorado-claro);
        color: #2C2427;
    }
");
?>
<div class="disponibilidad-cita-index">

    <div class="uno-page-header">
        <div>
            <h1><i class="fas fa-calendar-check me-2" style="color: #DFB94C;"></i><?= Html::encode($this->title) ?></h1>
            <p class="uno-page-subtitle">Administración de fechas, horarios y cupos de atención para titulación</p>
        </div>
        <span class="uno-header-badge">
            <i class="fas fa-university me-1"></i> Gestión de Agenda UNO
        </span>
    </div>
    
    <div class="alert uno-alert-instrucciones shadow-sm">
        <i class="fas fa-info-circle"></i> <strong>Instrucciones:</strong> Haz clic sobre un día vacío para crear una cita, de igual forma puedes hacer clic sobre una cita existente para editarlo o eliminarlo.
    </div>

    <!-- Contenedor del Calendario -->
    <div id="calendar"></div>

</div>

<!-- Modal para CREAR Disponibilidad -->
<div class="modal fade uno-modal" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white">
        <h5 class="modal-title" id="modalCrearLabel"><i class="fas fa-calendar-plus me-2" style="color: #DFB94C;"></i>Habilitar Nuevo Día</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="c_fecha">
        <p class="fs-6 mb-3"><strong>Fecha seleccionada:</strong> <span id="c_fecha_text" class="uno-fecha-badge ms-1"></span></p>
        
        <div class="mb-3">
            <label class="form-label">Hora de Inicio (Ej. 09:00 AM)</label>
            <input type="time" id="c_hora_inicio" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Hora de Fin (Ej. 02:00 PM)</label>
            <input type="time" id="c_hora_fin" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Cantidad máxima de citas</label>
            <input type="number" id="c_cupo" class="form-control" min="1" placeholder="Ej. 10" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" style="border-radius: 8px;" data-bs-dismiss="modal">Cerrar ventana</button>
        <button type="button" class="btn btn-uno-guinda" id="btnGuardarCita"><i class="fas fa-save me-1"></i> Guardar Cita</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para EDITAR / CANCELAR Disponibilidad -->
<div class="modal fade uno-modal" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white">
        <h5 class="modal-title" id="modalEditarLabel"><i class="fas fa-edit me-2" style="color: #DFB94C;"></i>Detalles de Disponibilidad</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="e_id">
        
        <div class="mb-3">
            <label class="form-label">Hora de Inicio</label>
            <input type="time" id="e_hora_inicio" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Hora de Fin</label>
            <input type="time" id="e_hora_fin" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Cantidad máxima de citas</label>
            <input type="number" id="e_cupo" class="form-control" min="1" required>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-danger" style="border-radius: 8px;" id="btnCancelarCita"><i class="fas fa-trash me-1"></i> Cancelar Cita</button>
        <div>
            <button type="button" class="btn btn-outline-secondary me-1" style="border-radius: 8px;" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-uno-dorado" id="btnActualizarCita"><i class="fas fa-sync-alt me-1"></i> Actualizar</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    // Instancias de Modales de Bootstrap 5
    const modalCrear = new bootstrap.Modal(document.getElementById('modalCrear'));
    const modalEditar = new bootstrap.Modal(document.getElementById('modalEditar'));

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        eventDisplay: 'block',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        events: '<?= Url::to(['disponibilidad-cita/eventos']) ?>',
        
        // 1. AL HACER CLIC EN UN DÍA VACÍO (CREAR)
        dateClick: function(info) {
            document.getElementById('c_fecha').value = info.dateStr;
            document.getElementById('c_fecha_text').innerText = info.dateStr;
            // Limpiar campos previos
            document.getElementById('c_hora_inicio').value = '';
            document.getElementById('c_hora_fin').value = '';
            document.getElementById('c_cupo').value = '';
            
            modalCrear.show();
        },

        // 2. AL HACER CLIC EN UN EVENTO YA EXISTENTE (EDITAR/ELIMINAR)
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            let props = info.event.extendedProps;
            
            document.getElementById('e_id').value = info.event.id;
            document.getElementById('e_hora_inicio').value = props.hora_inicio;
            document.getElementById('e_hora_fin').value = props.hora_fin;
            document.getElementById('e_cupo').value = props.cupo_maximo;
            
            modalEditar.show();
        }
    });
    
    calendar.render();

    // ACCIÓN: BOTÓN GUARDAR (NUEVA CITA)
    document.getElementById('btnGuardarCita').addEventListener('click', function() {
        Swal.fire({
            title: '¿Está seguro que desea crear una nueva cita?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, crear',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= Url::to(['disponibilidad-cita/crear-ajax']) ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': yii.getCsrfToken()
                    },
                    body: new URLSearchParams({
                        'fecha': document.getElementById('c_fecha').value,
                        'hora_inicio': document.getElementById('c_hora_inicio').value,
                        'hora_fin': document.getElementById('c_hora_fin').value,
                        'cupo_maximo': document.getElementById('c_cupo').value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        modalCrear.hide();
                        calendar.refetchEvents();
                        Swal.fire('¡Creado!', 'La cita ha sido guardada.', 'success');
                    }
                });
            } else {
                modalCrear.hide();
            }
        });
    });

    // ACCIÓN: BOTÓN ACTUALIZAR (EDITAR CITA)
    document.getElementById('btnActualizarCita').addEventListener('click', function() {
        fetch('<?= Url::to(['disponibilidad-cita/actualizar-ajax']) ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-Token': yii.getCsrfToken()
            },
            body: new URLSearchParams({
                'id': document.getElementById('e_id').value,
                'hora_inicio': document.getElementById('e_hora_inicio').value,
                'hora_fin': document.getElementById('e_hora_fin').value,
                'cupo_maximo': document.getElementById('e_cupo').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                modalEditar.hide();
                calendar.refetchEvents();
                Swal.fire('¡Actualizado!', 'Los datos han sido modificados.', 'success');
            }
        });
    });

    // ACCIÓN: BOTÓN ELIMINAR (CANCELAR CITA)
    document.getElementById('btnCancelarCita').addEventListener('click', function() {
        Swal.fire({
            title: '¿Está seguro de que desea cancelar la cita?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= Url::to(['disponibilidad-cita/eliminar-ajax']) ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': yii.getCsrfToken()
                    },
                    body: new URLSearchParams({
                        'id': document.getElementById('e_id').value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        modalEditar.hide();
                        calendar.refetchEvents();
                        Swal.fire('¡Cancelada!', 'La cita ha sido eliminada.', 'success');
                    }
                });
            }
        });
    });
});
</script>