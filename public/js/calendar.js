class Calendar {
    constructor() {
        this.date = new Date();
        this.currentMonth = this.date.getMonth();
        this.currentYear = this.date.getFullYear();
        this.monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        this.savedEvents = [];
        
        // Hacer la instancia disponible globalmente
        window.calendarInstance = this;
        
        this.init();
        console.log('Calendario inicializado');
    }

    init() {
        this.loadSavedEvents();
        this.renderCalendar();
        this.addNavigationListeners();
    }

    loadSavedEvents() {
        const events = localStorage.getItem('interestedEvents');
        console.log('Eventos raw del localStorage:', events);
        this.savedEvents = events ? JSON.parse(events) : [];
        console.log('Eventos parseados:', this.savedEvents);
    }

    hasEventsOnDate(day) {
        const currentDate = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        console.log('Verificando eventos para fecha:', currentDate);
        
        return this.savedEvents.some(event => {
            const eventDate = new Date(event.event_date);
            const eventDateString = eventDate.toISOString().split('T')[0];
            console.log('Comparando con evento fecha:', eventDateString);
            
            return eventDateString === currentDate;
        });
    }

    renderCalendar() {
        const calendar = document.getElementById('calendar');
        if (!calendar) return;

        let calendarHTML = `
            <div class="calendar-header">
                <button class="btn-calendar" id="prevMonth">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="calendar-title">
                    <div class="month">${this.monthNames[this.currentMonth]}</div>
                    <div class="year">${this.currentYear}</div>
                </div>
                <button class="btn-calendar" id="nextMonth">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="calendar-weekdays">
                <div>Do</div>
                <div>Lu</div>
                <div>Ma</div>
                <div>Mi</div>
                <div>Ju</div>
                <div>Vi</div>
                <div>Sa</div>
            </div>
            <div class="calendar-days">
        `;

        const firstDay = new Date(this.currentYear, this.currentMonth, 1);
        const startingDay = firstDay.getDay();
        const totalDays = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();

        // Días vacíos
        for (let i = 0; i < startingDay; i++) {
            calendarHTML += '<div class="calendar-day empty"></div>';
        }

        // Días del mes
        for (let day = 1; day <= totalDays; day++) {
            const hasEvents = this.hasEventsOnDate(day);
            const today = new Date();
            const isToday = day === today.getDate() && 
                           this.currentMonth === today.getMonth() && 
                           this.currentYear === today.getFullYear();

            // Obtener los eventos para este día
            const eventsForDay = this.getEventsForDate(day);
            const tooltipContent = eventsForDay.map(event => 
                `${event.title} - ${event.event_time}`
            ).join('\n');

            calendarHTML += `
                <div class="calendar-day${isToday ? ' today' : ''}${hasEvents ? ' has-events' : ''}"
                     data-date="${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}"
                     ${hasEvents ? `data-tooltip="${tooltipContent}"` : ''}>
                    <span>${day}</span>
                    ${hasEvents ? '<div class="event-indicator"></div>' : ''}
                </div>
            `;
        }

        calendarHTML += '</div>';
        calendar.innerHTML = calendarHTML;
    }

    addNavigationListeners() {
        document.getElementById('prevMonth').addEventListener('click', () => {
            this.currentMonth--;
            if (this.currentMonth < 0) {
                this.currentMonth = 11;
                this.currentYear--;
            }
            this.renderCalendar();
            this.addNavigationListeners();
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            this.currentMonth++;
            if (this.currentMonth > 11) {
                this.currentMonth = 0;
                this.currentYear++;
            }
            this.renderCalendar();
            this.addNavigationListeners();
        });
    }

    getEventsForDate(day) {
        const currentDate = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        return this.savedEvents.filter(event => {
            const eventDate = new Date(event.event_date);
            const eventDateString = eventDate.toISOString().split('T')[0];
            return eventDateString === currentDate;
        });
    }
}

// Asegurarse de que el calendario se inicializa
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM cargado, inicializando calendario');
    new Calendar();
});
