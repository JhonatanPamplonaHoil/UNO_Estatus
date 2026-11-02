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
    #calendar { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .fc-event { cursor: pointer; }
");
?>
<div class="disponibilidad-cita-index">

    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>
    
    <div class="alert alert-info shadow-sm">
        <i class="fas fa-info-circle"></i> <strong>Instrucciones:</strong> Haz clic sobre un día vacío para crear una cita, de igual forma puedes hacer clic sobre una cita existente para editarlo o eliminarlo.
    </div>

    <!-- Contenedor del Calendario -->
    <div id="calendar"></div>

</div>

<!-- Modal para CREAR Disponibilidad -->
<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalCrearLabel">Habilitar Nuevo Día</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="c_fecha">
        <p class="fs-5"><strong>Fecha seleccionada:</strong> <span id="c_fecha_text" class="text-primary"></span></p>
        
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar ventana</button>
        <button type="button" class="btn btn-success" id="btnGuardarCita">Guardar Cita</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para EDITAR / CANCELAR Disponibilidad -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalEditarLabel">Detalles de Disponibilidad</h5>
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
        <button type="button" class="btn btn-danger" id="btnCancelarCita"><i class="fas fa-trash"></i> Cancelar Cita</button>
        <div>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="btnActualizarCita">Actualizar</button>
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
