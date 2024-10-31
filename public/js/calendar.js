class Calendar {
    constructor() {
        this.date = new Date();
        this.currentMonth = this.date.getMonth();
        this.currentYear = this.date.getFullYear();
        this.monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                          "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        this.render();
        this.addEventListeners();
    }

    render() {
        const calendar = document.getElementById('calendar');
        if (!calendar) return;

        const savedEvents = JSON.parse(localStorage.getItem('calendarEvents') || '[]');
        
        let html = `
            <div class="calendar-header">
                <button class="btn btn-sm text-white" id="prevMonth">&lt;</button>
                <h5 class="mb-0">${this.monthNames[this.currentMonth]} ${this.currentYear}</h5>
                <button class="btn btn-sm text-white" id="nextMonth">&gt;</button>
            </div>
            <table class="calendar-table">
                <thead>
                    <tr>
                        <th>Lu</th><th>Ma</th><th>Mi</th><th>Ju</th><th>Vi</th><th>Sá</th><th>Do</th>
                    </tr>
                </thead>
                <tbody>
        `;

        let firstDay = new Date(this.currentYear, this.currentMonth, 1);
        let startingDay = firstDay.getDay() || 7; // Ajustar para que la semana empiece en lunes
        startingDay = startingDay === 1 ? 7 : startingDay - 1;

        let monthLength = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        let day = 1;
        
        for (let i = 0; i < 6; i++) {
            html += '<tr>';
            for (let j = 1; j <= 7; j++) {
                if (i === 0 && j < startingDay || day > monthLength) {
                    html += '<td></td>';
                } else {
                    const currentDate = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const dateEvents = savedEvents.filter(event => event.date === currentDate);
                    
                    html += `
                        <td class="${dateEvents.length > 0 ? 'has-event' : ''}" data-date="${currentDate}">
                            <span class="day-number">${day}</span>
                            <div class="event-indicators">
                                ${dateEvents.map(() => '<div class="event-dot"></div>').join('')}
                            </div>
                            ${dateEvents.length > 0 ? `
                                <div class="event-tooltip">
                                    <div class="tooltip-header">Eventos (${dateEvents.length})</div>
                                    ${dateEvents.map(event => `
                                        <div class="tooltip-event">
                                            <span class="event-time">${event.time}</span>
                                            <span class="event-title">${event.title}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            ` : ''}
                        </td>
                    `;
                    day++;
                }
            }
            html += '</tr>';
            if (day > monthLength) break;
        }

        html += '</tbody></table>';
        calendar.innerHTML = html;
    }

    addEventListeners() {
        document.getElementById('prevMonth')?.addEventListener('click', () => {
            this.currentMonth--;
            if (this.currentMonth < 0) {
                this.currentMonth = 11;
                this.currentYear--;
            }
            this.render();
        });

        document.getElementById('nextMonth')?.addEventListener('click', () => {
            this.currentMonth++;
            if (this.currentMonth > 11) {
                this.currentMonth = 0;
                this.currentYear++;
            }
            this.render();
        });

        // Añadir listeners para los botones de eliminar eventos
        document.querySelectorAll('.remove-event-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                const eventData = {
                    title: button.dataset.title,
                    date: button.dataset.date,
                    time: button.dataset.time
                };
                this.removeEvent(eventData);
            });
        });
    }

    removeEvent(eventData) {
        let savedEvents = JSON.parse(localStorage.getItem('calendarEvents') || '[]');
        savedEvents = savedEvents.filter(event => 
            !(event.title === eventData.title && 
              event.date === eventData.date && 
              event.time === eventData.time)
        );
        localStorage.setItem('calendarEvents', JSON.stringify(savedEvents));
        
        // Actualizar el estado del botón "Me interesa" en el feed
        const interestBtn = document.querySelector(`.interest-btn[data-event-title="${eventData.title}"][data-event-date="${eventData.date}"]`);
        if (interestBtn) {
            interestBtn.classList.remove('btn-primary');
            interestBtn.classList.add('btn-outline-primary');
        }
        
        this.render();
    }

    createEventTooltip(events) {
        if (events.length === 0) return '';
        
        return `
            <div class="event-tooltip">
                <div class="tooltip-header">Eventos del día</div>
                ${events.map(event => `
                    <div class="tooltip-event">
                        <div class="event-time">${event.time}</div>
                        <div class="event-title">${event.title}</div>
                        <button class="remove-event-btn" 
                                data-title="${event.title}"
                                data-date="${event.date}"
                                data-time="${event.time}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `).join('')}
            </div>
        `;
    }
}

// Función para actualizar el calendario
function updateCalendar() {
    const calendarInstance = new Calendar();
    calendarInstance.render();
}

// Inicializar el calendario
document.addEventListener('DOMContentLoaded', () => {
    new Calendar();
});
