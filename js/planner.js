document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('student-planner');
    let calendar = new FullCalendar.Calendar(calendarEl, {
        aspectRatio: 2,
        height: 500,

        windowResize: function (view) {
        },
        plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        views: {
            dayGridMonth: {buttonText: "Month"},
            timeGridWeek: {buttonText: "Week"},
            timeGridDay: {buttonText: "Day"},
            listMonth: {buttonText: "List"}
        },
        displayEventTime: false,
        defaultView: 'dayGridMonth',
        defaultDate: '2020-01-15',
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventLimit: true,
        selectable: true,

        events: {
            url: '/mysql/planner/get-events.php',
            failure: function () {
                alert("Check JSON file!");
            }
        },



    });


    calendar.render();
})
;







