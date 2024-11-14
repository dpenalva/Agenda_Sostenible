class Calendar {
    constructor() {
        this.date = new Date();
        this.currentMonth = this.date.getMonth();
        this.currentYear = this.date.getFullYear();
        this.monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        this.savedEvents = [];
        
        window.calendarInstance = this;
        
        this.init();
    }

    init() {
        this.loadSavedEvents();
        this.renderCalendar();
        this.addNavigationListeners();
    }

    loadSavedEvents() {
        const events = localStorage.getItem('interestedEvents');
        this.savedEvents = events ? JSON.parse(events) : [];
    }

    hasEventsOnDate(day) {
        const currentDate = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        return this.savedEvents.some(event => event.event_date === currentDate);
    }

    renderCalendar() {
        const monthDisplay = document.getElementById('monthDisplay');
        monthDisplay.innerHTML = `
            ${this.monthNames[this.currentMonth]}
            <span class="year">${this.currentYear}</span>
        `;

        const daysContainer = document.getElementById('daysContainer');
        if (!daysContainer) return;

        daysContainer.innerHTML = '';

        const firstDay = new Date(this.currentYear, this.currentMonth, 1);
        const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
        const totalDays = lastDay.getDate();
        const startingDay = firstDay.getDay();

        // Días vacíos del mes anterior
        for (let i = 0; i < startingDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'day empty';
            daysContainer.appendChild(emptyDay);
        }

        // Días del mes actual
        for (let day = 1; day <= totalDays; day++) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'day';
            dayDiv.textContent = day;

            // Verificar si es el día actual
            const today = new Date();
            if (day === today.getDate() && 
                this.currentMonth === today.getMonth() && 
                this.currentYear === today.getFullYear()) {
                dayDiv.classList.add('today');
            }

            if (this.hasEventsOnDate(day)) {
                dayDiv.classList.add('has-favorite-event');
            }

            daysContainer.appendChild(dayDiv);
        }
    }

    addNavigationListeners() {
        document.getElementById('prevMonth').addEventListener('click', () => {
            this.currentMonth--;
            if (this.currentMonth < 0) {
                this.currentMonth = 11;
                this.currentYear--;
            }
            this.renderCalendar();
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            this.currentMonth++;
            if (this.currentMonth > 11) {
                this.currentMonth = 0;
                this.currentYear++;
            }
            this.renderCalendar();
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new Calendar();
});
