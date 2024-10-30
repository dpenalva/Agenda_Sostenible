class Calendar {
    constructor() {
        this.date = new Date();
        this.currentMonth = this.date.getMonth();
        this.currentYear = this.date.getFullYear();
        this.monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        this.init();
    }

    init() {
        this.renderCalendar();
        this.addNavigationListeners();
    }

    renderCalendar() {
        const calendar = document.getElementById('calendar');
        const firstDay = new Date(this.currentYear, this.currentMonth, 1);
        const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
        const startingDay = firstDay.getDay();
        const totalDays = lastDay.getDate();

        let calendarHTML = `
            <div class="calendar-header d-flex justify-content-between align-items-center mb-4">
                <button class="btn-calendar" id="prevMonth">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="calendar-title">
                    <span class="month">${this.monthNames[this.currentMonth]}</span>
                    <span class="year">${this.currentYear}</span>
                </div>
                <button class="btn-calendar" id="nextMonth">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="calendar-body">
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

        // Días vacíos antes del primer día del mes
        for (let i = 0; i < startingDay; i++) {
            calendarHTML += '<div class="calendar-day empty"></div>';
        }

        // Días del mes
        for (let day = 1; day <= totalDays; day++) {
            const currentDate = new Date();
            const isToday = day === currentDate.getDate() && 
                          this.currentMonth === currentDate.getMonth() && 
                          this.currentYear === currentDate.getFullYear();
            
            calendarHTML += `
                <div class="calendar-day${isToday ? ' today' : ''}">
                    <span>${day}</span>
                </div>
            `;
        }

        calendarHTML += '</div></div>';
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
}

// Inicializar el calendario cuando se carga el documento
document.addEventListener('DOMContentLoaded', () => {
    new Calendar();
});
