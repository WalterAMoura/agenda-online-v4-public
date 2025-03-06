(function (win, doc) {
    'use strict';

    //Exibir o calendário
    function getCalendarIndex(iPerfil, iDiv, filter) {
        let iCalendarEl = doc.querySelector(iDiv);
        let iCalendar = new FullCalendar.Calendar(iCalendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                start: 'prev,today',
                center: 'title',
                end: 'next'
            },
            dayMaxEventRows: false,
            views: {
                dayGridMonth: {
                    titleFormat: {
                        year: 'numeric',
                        month: 'long'
                    }
                }
            },
            showNonCurrentDates: false,
            themeSystem: 'bootstrap',
            nowIndicator: true,
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia'
            },
            timeZone: 'America/Sao_Paulo',
            locale: 'pt-BR',
            editable: true,
            events: 'calendar',
            navLinks: false,
            dateClick: function (info) {
                waitingFilterTable(info, 'dateClick').then(r => false);
            },
            eventClick: function (info) {
                waitingFilterTable(info, 'eventClick').then(r => false);
            }
        });
        iCalendar.render();
        //console.log(iCalendar.currentData);
    }

    function getCalendarEventsChurchIndex(iPerfil, iDiv, filter) {
        let iCalendarEl = doc.querySelector(iDiv);
        let iCalendar = new FullCalendar.Calendar(iCalendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                start: 'prev,today',
                center: 'title',
                end: 'next'
            },
            dayMaxEventRows: false,
            views: {
                dayGridMonth: {
                    titleFormat: {
                        year: 'numeric',
                        month: 'long'
                    }
                }
            },
            showNonCurrentDates: false,
            themeSystem: 'bootstrap',
            nowIndicator: true,
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia'
            },
            timeZone: 'America/Sao_Paulo',
            locale: 'pt-BR',
            editable: true,
            events: 'calendar-events-church',
            navLinks: false,
            dateClick: function (info) {
                waitingFilterTableEventsChurch(info, 'dateClick').then(r => false);
            },
            eventClick: function (info) {
                waitingFilterTableEventsChurch(info, 'eventClick').then(r => false);
            }
        });
        iCalendar.render();
        //console.log(iCalendar.currentData);
    }

    function getCalendarTeamLineup(iPerfil, iDiv) {
        let iCalendarEl = doc.querySelector(iDiv);
        let iCalendar = new FullCalendar.Calendar(iCalendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                start: 'prev,today',
                center: 'title',
                end: 'next'
            },
            dayMaxEventRows: false,
            views: {
                dayGridMonth: {
                    titleFormat: {
                        year: 'numeric',
                        month: 'long'
                    }
                }
            },
            showNonCurrentDates: false,
            themeSystem: 'bootstrap',
            nowIndicator: true,
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia'
            },
            timeZone: 'America/Sao_Paulo',
            locale: 'pt-BR',
            editable: true,
            events: 'scheduler-teams-lineup',
            navLinks: false,
            dateClick: function (info) {
                waitingFilterTableTeamLineup(info, 'dateClick').then(r => false);
            },
            eventClick: function (info) {
                waitingFilterTableTeamLineup(info, 'eventClick').then(r => false);
            }
        });
        iCalendar.render();
        //console.log(iCalendar.currentData);
    }

    // carregar calendário recepção
    function getCalendarReceptionTeamLineup(iPerfil, iDiv) {
        let iCalendarEl = doc.querySelector(iDiv);
        let iCalendar = new FullCalendar.Calendar(iCalendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                start: 'prev,today',
                center: 'title',
                end: 'next'
            },
            dayMaxEventRows: false,
            views: {
                dayGridMonth: {
                    titleFormat: {
                        year: 'numeric',
                        month: 'long'
                    }
                }
            },
            showNonCurrentDates: false,
            themeSystem: 'bootstrap',
            nowIndicator: true,
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia'
            },
            timeZone: 'America/Sao_Paulo',
            locale: 'pt-BR',
            editable: true,
            events: 'scheduler-reception-teams-lineup',
            navLinks: false,
            dateClick: function (info) {
                waitingFilterTableReceptionTeamLineup(info, 'dateClick').then(r => false);
            },
            eventClick: function (info) {
                waitingFilterTableReceptionTeamLineup(info, 'eventClick').then(r => false);
            }
        });
        iCalendar.render();
        //console.log(iCalendar.currentData);
    }

    // carregar calendário recepção
    function getCalendarWorshipTeamLineup(iPerfil, iDiv) {
        let iCalendarEl = doc.querySelector(iDiv);
        let iCalendar = new FullCalendar.Calendar(iCalendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                start: 'prev,today',
                center: 'title',
                end: 'next'
            },
            dayMaxEventRows: false,
            views: {
                dayGridMonth: {
                    titleFormat: {
                        year: 'numeric',
                        month: 'long'
                    }
                }
            },
            showNonCurrentDates: false,
            themeSystem: 'bootstrap',
            nowIndicator: true,
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia'
            },
            timeZone: 'America/Sao_Paulo',
            locale: 'pt-BR',
            editable: true,
            events: 'scheduler-worship-teams-lineup',
            navLinks: false,
            dateClick: function (info) {
                waitingFilterTableWorshipTeamLineup(info, 'dateClick').then(r => false);
            },
            eventClick: function (info) {
                waitingFilterTableWorshipTeamLineup(info, 'eventClick').then(r => false);
            }
        });
        iCalendar.render();
        //console.log(iCalendar.currentData);
    }

    function getCalendar(perfil, div) {
        let calendarEl = doc.querySelector(div);
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                start: 'prev,next,today',
                center: 'title',
                end: 'dayGridMonth, timeGridWeek, timeGridDay'
            },
            dayMaxEventRows: true,
            views: {
                dayGridMonth: {
                    titleFormat: {
                        year: 'numeric',
                        month: 'long'
                    }
                },
                timeGrid: {
                    dayMaxEventRows: 4
                }
            },
            showNonCurrentDates: false,
            themeSystem: 'bootstrap',
            nowIndicator: true,
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia'
            },
            timeZone: 'UTC',
            locale: 'pt-BR',
            editable: true,
            droppable: true,
            dateClick: function (info) {
                if (perfil === 'manager') {
                    calendar.changeView('timeGrid', info.dateStr);
                } else {
                    if (info.view.type === 'dayGridMonth') {
                        calendar.changeView('timeGrid', info.dateStr);
                    } else {
                        $('#modalEvent').modal('show');
                        $('[data-mask]').inputmask();
                        modalNewIsOpen(info);
                    }
                }
            },
            //eventContent: eventRender,
            events: 'calendar',
            navLinks: false,
            eventDrop: function (info) {
                resizeAndDrop(info).then(r => '');
            },
            eventResize: function (info) {
                resizeAndDrop(info).then(r => '');
            },
            eventClick: function (info) {
                if (perfil === 'manager' || perfil === 'mainCalendar') {
                    let id = info.event.id;
                    $('#modalEvent').modal('show');
                    $('[data-mask]').inputmask();
                    modalEditIsOpen(info);
                }
            }
        });
        calendar.render();
    }

    function getCalendarEventsChurch(perfil, div) {
        let calendarEl = doc.querySelector(div);
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                start: 'prev,next,today',
                center: 'title',
                end: 'dayGridMonth, timeGridWeek, timeGridDay'
            },
            dayMaxEventRows: true,
            views: {
                dayGridMonth: {
                    titleFormat: {
                        year: 'numeric',
                        month: 'long'
                    }
                },
                timeGrid: {
                    dayMaxEventRows: 4
                }
            },
            showNonCurrentDates: false,
            themeSystem: 'bootstrap',
            nowIndicator: true,
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia'
            },
            timeZone: 'UTC',
            locale: 'pt-BR',
            editable: true,
            droppable: true,
            dateClick: function (info) {
                if (perfil === 'manager') {
                    calendar.changeView('timeGrid', info.dateStr);
                } else {
                    if (info.view.type === 'dayGridMonth') {
                        calendar.changeView('timeGrid', info.dateStr);
                    } else {
                        $('#modalEventsChurch').modal('show');
                        $('[data-mask]').inputmask();
                        modalNewEventsChurchIsOpen(info);
                    }
                }
            },
            //eventContent: eventRender,
            events: 'calendar-events-church',
            navLinks: false,
            eventDrop: function (info) {
                resizeAndDropEventsChurch(info).then(r => '');
            },
            eventResize: function (info) {
                resizeAndDropEventsChurch(info).then(r => '');
            },
            eventClick: function (info) {
                if (perfil === 'manager' || perfil === 'mainCalendarEventsChurch') {
                    let id = info.event.id;
                    $('#modalEventsChurch').modal('show');
                    $('[data-mask]').inputmask();
                    modalEditEventsChurchIsOpen(info);
                }
            }
        });
        calendar.render();
    }

    // Função para estilizar eventos
    function eventRender(info) {
        let eventTitle = info.event.title;
        if (info.event.extendedProps.canceled) {
            eventTitle = '<span style="text-decoration: line-through;">' + eventTitle + '</span>';
        }
        return {
            html: eventTitle
        };
    }


    if (doc.querySelector('.calendarUser')) {
        getCalendar('user', '.calendarUser');
    } else if (doc.querySelector('.calendarManager')) {
        getCalendar('manager', '.calendarManager');
    } else if (doc.querySelector('.mainCalendar')) {
        getCalendar('mainCalendar', '.mainCalendar');
    } else if (doc.querySelector('.calendar')) {
        getCalendarIndex('calendar', '.calendar', null);
    }else if (doc.querySelector('.calendarTeamLineup')) {
        getCalendarTeamLineup('calendarTeamLineup', '.calendarTeamLineup', null);
    }else if (doc.querySelector('.calendarManagerEventsChurch')) {
        getCalendarEventsChurch('manager', '.calendarManagerEventsChurch');
    }else if (doc.querySelector('.mainCalendarEventsChurch')) {
        getCalendarEventsChurch('mainCalendarEventsChurch', '.mainCalendarEventsChurch');
    }else if (doc.querySelector('.mainCalendarEventsChurch')) {
        getCalendarEventsChurch('user', '.mainCalendarEventsChurch');
    } else if (doc.querySelector('.calendarEventsChurch')) {
        getCalendarEventsChurchIndex('calendarEventsChurch', '.calendarEventsChurch', null);
    }else if (doc.querySelector('.calendarReceptionTeamLineup')) {
        getCalendarReceptionTeamLineup('calendarReceptionTeamLineup', '.calendarReceptionTeamLineup', null);
    }else if (doc.querySelector('.calendarWorshipTeamLineup')) {
        getCalendarWorshipTeamLineup('calendarWorshipTeamLineup', '.calendarWorshipTeamLineup', null);
    }

    if (doc.querySelector('#btn-delete')) {
        let btn = doc.querySelector('#btn-delete');
        btn.addEventListener('click', (event) => {
            event.preventDefault();
            let form = doc.getElementById('formEvent');
            let originalAction = form.action;
            let id = doc.getElementById('id').value;
            originalAction = originalAction.split('event');
            originalAction = originalAction[0];
            win.location.href = originalAction+'event/'+id+'/delete';
        }, false);
    }

    if (doc.querySelector('#btn-delete-event-church')) {
        let btn = doc.querySelector('#btn-delete-event-church');
        btn.addEventListener('click', (event) => {
            event.preventDefault();
            let form = doc.getElementById('formEventsChurch');
            let originalAction = form.action;
            let id = doc.getElementById('id').value;
            originalAction = originalAction.split('event');
            originalAction = originalAction[0];
            win.location.href = originalAction+'events-church/'+id+'/delete';
        }, false);
    }

    if (doc.querySelector('.indexPage')) {
        let mHome = document.getElementById('idHome');
        mHome.classList.remove('active');

        let mCal = document.getElementById('idCalendario');
        mCal.classList.add('active');

    }

    // função de arraste e redimensionamento
    async function resizeAndDrop(info) {
        let startDate = new  Date(moment(new Date(info.event.start)).utcOffset('+00:00').format('lll'));
        let month = ((startDate.getMonth() + 1) <= 9) ? "0" + (startDate.getMonth() + 1) : (startDate.getMonth() + 1);
        let day = ((startDate.getDate()) <= 9) ? "0" + startDate.getDate() : startDate.getDate();
        let minutes = ((startDate.getMinutes()) <= 9) ? "0" + startDate.getMinutes() : startDate.getMinutes();
        startDate = `${startDate.getFullYear()}-${month}-${day} ${startDate.getHours()}:${minutes}:00`;

        let endDate = new  Date(moment(new Date(info.event.end)).utcOffset('+00:00').format('lll'));
        let eMonth = ((endDate.getMonth() + 1) <= 9) ? "0" + (endDate.getMonth() + 1) : (endDate.getMonth() + 1);
        let eDay = ((endDate.getDate()) <= 9) ? "0" + endDate.getDate() : endDate.getDate();
        let eMinutes = ((endDate.getMinutes()) <= 9) ? "0" + endDate.getMinutes() : endDate.getMinutes();
        endDate = `${endDate.getFullYear()}-${eMonth}-${eDay} ${endDate.getHours()}:${eMinutes}:00`;

        let reqs = await fetch('event-drop', {
            method: 'post', headers: {
                'Content-Type': 'application/json', 'Accept': 'application/json'
            }, body: JSON.stringify({
                id: (typeof info.event.extendedProps._id === "undefined") ? info.event.id : info.event.extendedProps._id.$oid,
                start: startDate,
                end: endDate
            })
        });
        let ress = await reqs.json();
    }

    async function resizeAndDropEventsChurch(info) {
        let startDate = new  Date(moment(new Date(info.event.start)).utcOffset('+00:00').format('lll'));
        let month = ((startDate.getMonth() + 1) <= 9) ? "0" + (startDate.getMonth() + 1) : (startDate.getMonth() + 1);
        let day = ((startDate.getDate()) <= 9) ? "0" + startDate.getDate() : startDate.getDate();
        let minutes = ((startDate.getMinutes()) <= 9) ? "0" + startDate.getMinutes() : startDate.getMinutes();
        startDate = `${startDate.getFullYear()}-${month}-${day} ${startDate.getHours()}:${minutes}:00`;

        let endDate = new  Date(moment(new Date(info.event.end)).utcOffset('+00:00').format('lll'));
        let eMonth = ((endDate.getMonth() + 1) <= 9) ? "0" + (endDate.getMonth() + 1) : (endDate.getMonth() + 1);
        let eDay = ((endDate.getDate()) <= 9) ? "0" + endDate.getDate() : endDate.getDate();
        let eMinutes = ((endDate.getMinutes()) <= 9) ? "0" + endDate.getMinutes() : endDate.getMinutes();
        endDate = `${endDate.getFullYear()}-${eMonth}-${eDay} ${endDate.getHours()}:${eMinutes}:00`;

        let reqs = await fetch('events-church-drop', {
            method: 'post', headers: {
                'Content-Type': 'application/json', 'Accept': 'application/json'
            }, body: JSON.stringify({
                id: (typeof info.event.extendedProps._id === "undefined") ? info.event.id : info.event.extendedProps._id.$oid,
                start: startDate,
                end: endDate
            })
        });
        let ress = await reqs.json();
    }

    function modalEditIsOpen(info) {



        let id = info.event.id;
        let startDate = new Date(moment(new Date(info.event.start)).utcOffset('+00:00').format('lll'));
        let month = ((startDate.getMonth() + 1) <= 9) ? "0" + (startDate.getMonth() + 1) : (startDate.getMonth() + 1);
        let day = ((startDate.getDate()) <= 9) ? "0" + startDate.getDate() : startDate.getDate();
        let minutes = ((startDate.getMinutes()) <= 9) ? "0" + startDate.getMinutes() : startDate.getMinutes();
        let start = `${startDate.getFullYear()}-${month}-${day}`;
        let hour = ((startDate.getHours()) <= 9) ? "0" + startDate.getHours() : startDate.getHours();
        let time = `${hour}:${minutes}:00`;
        let orador = info.event.extendedProps.orador;
        let telefone = info.event.extendedProps.contato;
        let primeiroHino = info.event.extendedProps.hino_inicial;
        let hinoFinal = info.event.extendedProps.hino_final;
        let tema = info.event.extendedProps.description;
        let status = info.event.extendedProps.status_id;
        let departamento = info.event.extendedProps.department_id;
        let program = info.event.extendedProps.program_id;
        let observacoes = info.event.extendedProps.observacoes;

        $('#modalEvent').on('shown.bs.modal', function () {

            let modal = $(this);
            modal.find('#id').val(id);
            modal.find('#date').val(start);
            modal.find('#time').val(time);
            modal.find('#orador').val(orador);
            modal.find('#telefone').val(telefone);
            modal.find('#primeiroHino').val(primeiroHino);
            modal.find('#hinoFinal').val(hinoFinal);
            modal.find('#tema').val(tema);
            modal.find('#statusEvent').val(status);
            modal.find('#departamentos').val(departamento);
            modal.find('#programs').val(program);
            modal.find('#observacoes').val(observacoes);

            let title = doc.getElementById('idTitleModal');
            title.innerHTML = 'Atualizar Evento';
            let btn = doc.getElementById('btn-salvar');
            btn.innerHTML = 'Atualizar Evento';
            btn.classList.remove('btn-success');
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-success');

            $('#btn-delete').css("display", "block");

        });

        let form = doc.getElementById('formEvent');
        let originalAction = form.action;
        originalAction = originalAction.split('event');
        originalAction = originalAction[0];
        form.setAttribute('action',originalAction+'event/edit');

    }

    function modalEditEventsChurchIsOpen(info) {


        let id = info.event.id;
        let startDate = new Date(moment(new Date(info.event.start)).utcOffset('+00:00').format('lll'));
        let month = ((startDate.getMonth() + 1) <= 9) ? "0" + (startDate.getMonth() + 1) : (startDate.getMonth() + 1);
        let day = ((startDate.getDate()) <= 9) ? "0" + startDate.getDate() : startDate.getDate();
        let minutes = ((startDate.getMinutes()) <= 9) ? "0" + startDate.getMinutes() : startDate.getMinutes();
        let start = `${startDate.getFullYear()}-${month}-${day}`;
        let hour = ((startDate.getHours()) <= 9) ? "0" + startDate.getHours() : startDate.getHours();
        let time = `${hour}:${minutes}:00`;
        let orador = info.event.extendedProps.orador;
        let telefone = info.event.extendedProps.contato;
        let primeiroHino = info.event.extendedProps.hino_inicial;
        let hinoFinal = info.event.extendedProps.hino_final;
        let tema = info.event.extendedProps.description;
        let status = info.event.extendedProps.status_id;
        let departamento = info.event.extendedProps.department_id;
        let program = info.event.extendedProps.program_id;
        let observacoes = info.event.extendedProps.observacoes;

        $('#modalEventsChurch').on('shown.bs.modal', function () {

            let modal = $(this);
            modal.find('#id').val(id);
            modal.find('#date').val(start);
            modal.find('#time').val(time);
            modal.find('#orador').val(orador);
            modal.find('#telefone').val(telefone);
            modal.find('#primeiroHino').val(primeiroHino);
            modal.find('#hinoFinal').val(hinoFinal);
            modal.find('#tema').val(tema);
            modal.find('#statusEvent').val(status);
            modal.find('#departamentos').val(departamento);
            modal.find('#programs').val(program);
            modal.find('#observacoes').val(observacoes);

            let title = doc.getElementById('idTitleModal');
            title.innerHTML = 'Atualizar Evento';
            let btn = doc.getElementById('btn-salvar-event-church');
            btn.innerHTML = 'Atualizar Evento';
            btn.classList.remove('btn-success');
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-success');

            $('#btn-delete').css("display", "block");

        });

        let form = doc.getElementById('formEventsChurch');
        let originalAction = form.action;
        originalAction = originalAction.split('events-church');
        originalAction = originalAction[0];
        form.setAttribute('action',originalAction+'events-church/edit');

    }

    function modalNewEventsChurchIsOpen(info) {
        $('#modalEventsChurch').on('shown.bs.modal', function () {
            let startDate = new Date(moment(new Date(info.dateStr)).utcOffset('+00:00').format('lll'));
            let month = ((startDate.getMonth() + 1) <= 9) ? "0" + (startDate.getMonth() + 1) : (startDate.getMonth() + 1);
            let day = ((startDate.getDate()) <= 9) ? "0" + startDate.getDate() : startDate.getDate();
            let minutes = ((startDate.getMinutes()) <= 9) ? "0" + startDate.getMinutes() : startDate.getMinutes();
            let start = `${startDate.getFullYear()}-${month}-${day}`;
            let hour = ((startDate.getHours()) <= 9) ? "0" + startDate.getHours() : startDate.getHours();
            let time = `${hour}:${minutes}:00`;

            let modal = $(this);
            modal.find('#date').val(start);
            modal.find('#time').val(time);
            modal.find('#id').val(null);
            modal.find('#orador').val(null);
            modal.find('#telefone').val(null);
            modal.find('#primeiroHino').val(null);
            modal.find('#hinoFinal').val(null);
            modal.find('#tema').val(null);
            modal.find('#statusEvent').val('1');
            modal.find('#departamentos').val('1');
            modal.find('#programs').val('0');
            modal.find('#observacoes').val(null);

            let title = doc.getElementById('idTitleModal');
            title.innerHTML = 'Incluir Evento';

            let btn = doc.getElementById('btn-salvar-event-church');
            btn.innerHTML = 'Incluir';
            btn.classList.remove('btn-success');
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-primary');

            $('#btn-delete').css("display", "none");

        });
        let form = doc.getElementById('formEventsChurch');
        let originalAction = form.action;
        originalAction = originalAction.split('events-church');
        originalAction = originalAction[0];
        form.setAttribute('action',originalAction+'events-church/new');
    }

    function modalNewIsOpen(info) {
        $('#modalEvent').on('shown.bs.modal', function () {
            let startDate = new Date(moment(new Date(info.dateStr)).utcOffset('+00:00').format('lll'));
            let month = ((startDate.getMonth() + 1) <= 9) ? "0" + (startDate.getMonth() + 1) : (startDate.getMonth() + 1);
            let day = ((startDate.getDate()) <= 9) ? "0" + startDate.getDate() : startDate.getDate();
            let minutes = ((startDate.getMinutes()) <= 9) ? "0" + startDate.getMinutes() : startDate.getMinutes();
            let start = `${startDate.getFullYear()}-${month}-${day}`;
            let hour = ((startDate.getHours()) <= 9) ? "0" + startDate.getHours() : startDate.getHours();
            let time = `${hour}:${minutes}:00`;

            let modal = $(this);
            modal.find('#date').val(start);
            modal.find('#time').val(time);
            modal.find('#id').val(null);
            modal.find('#orador').val(null);
            modal.find('#telefone').val(null);
            modal.find('#primeiroHino').val(null);
            modal.find('#hinoFinal').val(null);
            modal.find('#tema').val(null);
            modal.find('#statusEvent').val('1');
            modal.find('#departamentos').val('1');
            modal.find('#programs').val('0');
            modal.find('#observacoes').val(null);

            let title = doc.getElementById('idTitleModal');
            title.innerHTML = 'Incluir Evento';

            let btn = doc.getElementById('btn-salvar');
            btn.innerHTML = 'Incluir';
            btn.classList.remove('btn-success');
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-primary');

            $('#btn-delete').css("display", "none");

        });
        let form = doc.getElementById('formEvent');
        let originalAction = form.action;
        originalAction = originalAction.split('event');
        originalAction = originalAction[0];
        form.setAttribute('action',originalAction+'event/new');
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    let tableTeamLineup;
    async function waitingFilterTableTeamLineup(info, origem){

        $('#div-table-list-team-lineup').css("display", "none");
        $('#div-team-lineup-loading').css("display", "block");

        for (let i = 0; i < 3; i++) {
            //console.log(`Waiting ${i} seconds...`);
            await sleep(i * 1000);
        }
        //console.log("Done...");
        let startDate;

        if (origem === 'dateClick') {
            console.log('dateClick');
            startDate = (typeof info.dateStr === "undefined") ? info.event.extendedProps._id.start : info.dateStr;
        } else {
            let dtStart = new Date(info.event.start);
            dtStart.setDate(dtStart.getDate() + 1);
            let month = ((dtStart.getMonth() + 1) <= 9) ? "0" + (dtStart.getMonth() + 1) : (dtStart.getMonth() + 1);
            let day = ((dtStart.getDate()) <= 9) ? "0" + dtStart.getDate() : dtStart.getDate();
            dtStart = `${dtStart.getFullYear()}-${month}-${day}`;
            startDate = dtStart;
        }

        let start = `${startDate} 00:00:00`;
        let end = `${startDate} 23:59:59`;

        carregarTabelFilterTeamLineup(start, end);
    }


    function carregarTabelFilterTeamLineup(start, end) {
        let container = document.getElementById('div-table-list-team-lineup');
        container.innerHTML = '<table id="tableTeamLineup" class="table table-striped-columns table-hover table-sm"><thead class="table-secondary"><tr><th style="width: 200px">Data</th><th style="width: 200px">Horário Sugerido</th><th style="width: 300px">Dia Da Semana</th><th style="width: 450px">Quem</th><th style="width: 450px">Onde</th><th style="width: 400px">Ações</th></tr></thead><tbody id="tBodyTeamLineup"></tbody></table>';

        $('#div-table-list-team-lineup').css("display", "none");
        $('#div-team-lineup-loading').css("display", "block");

        $.ajax({
            url: `scheduler-teams-lineup/items-teams-lineup/filter?start=${start}&end=${end}`,
            type: 'GET',
            dataType: 'json',
            data: {},
            success: function(data) {
                if (data.eventos.length > 0) {
                    for (let i = 0; i < data.eventos.length; i++) {
                        $('#tBodyTeamLineup').append('<tr data-id="'+data.eventos[i].id+'"><td>' + data.eventos[i].schedulerDate + '</td><td>' + data.eventos[i].suggestedTime + '</td><td>' + data.eventos[i].dayOfWeek + '</td><td>' + data.eventos[i].who + '</td><td>' + data.eventos[i].where+'<td><a href="'+data.eventos[i].urlEdit+'" class="'+data.eventos[i].disabledEdit+'"><i class="fas fa-edit '+data.eventos[i].disabledEdit+'" style="font-size: 24px;"></i></a>  <a href="'+data.eventos[i].urlDelete+'" class="'+data.eventos[i].disabledRemove+'"><i class="fas fa-trash-alt '+data.eventos[i].disabledRemove+'" style="font-size: 24px;"></i></a></td'+'</tr>');
                    }
                }

                // Destruir a tabela existente
                if (typeof tableTeamLineup !== 'undefined' && $.fn.DataTable.isDataTable('#tableTeamLineup')) {
                    tableTeamLineup.destroy();
                }

                // Recriar a tabela com os novos dados
                initializeTableTeamLineup_();

                $('#div-team-lineup-loading').css("display", "none");
                $('#div-table-list-team-lineup').css("display", "block");
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });
    }

    // Função para inicializar a tabela
    function initializeTableTeamLineup_() {
        table = $('#tableTeamLineup').DataTable({
            "destroy": true,
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": true,
            "pageLength": 50,
            "language": {
                "emptyTable": "Sem escala cadastrada..."
            }
        });
    }

    let tableTeamReceptionLineup;
    async function waitingFilterTableReceptionTeamLineup(info, origem){

        $('#div-table-list-reception-team-lineup').css("display", "none");
        $('#div-reception-team-lineup-loading').css("display", "block");

        for (let i = 0; i < 3; i++) {
            //console.log(`Waiting ${i} seconds...`);
            await sleep(i * 1000);
        }
        //console.log("Done...");
        let startDate;

        if (origem === 'dateClick') {
            console.log('dateClick');
            startDate = (typeof info.dateStr === "undefined") ? info.event.extendedProps._id.start : info.dateStr;
        } else {
            let dtStart = new Date(info.event.start);
            dtStart.setDate(dtStart.getDate() + 1);
            let month = ((dtStart.getMonth() + 1) <= 9) ? "0" + (dtStart.getMonth() + 1) : (dtStart.getMonth() + 1);
            let day = ((dtStart.getDate()) <= 9) ? "0" + dtStart.getDate() : dtStart.getDate();
            dtStart = `${dtStart.getFullYear()}-${month}-${day}`;
            startDate = dtStart;
        }

        let start = `${startDate} 00:00:00`;
        let end = `${startDate} 23:59:59`;

        carregarTabelFilterReceptionTeamLineup(start, end);
    }


    function carregarTabelFilterReceptionTeamLineup(start, end) {
        let container = document.getElementById('div-table-list-reception-team-lineup');
        container.innerHTML = '<table id="tableTeamReceptionLineup" class="table table-striped-columns table-hover table-sm"><thead class="table-secondary"><tr><th style="width: 200px">Data</th><th style="width: 300px">Dia Da Semana</th><th style="width: 450px">Quem</th><th style="width: 400px">Ações</th></tr></thead><tbody id="tBodyReceptionTeamLineup"></tbody></table>';

        $('#div-table-list-reception-team-lineup').css("display", "none");
        $('#div-reception-team-lineup-loading').css("display", "block");


        $.ajax({
            url: `scheduler-reception-teams-lineup/items-teams-lineup/filter?start=${start}&end=${end}`,
            type: 'GET',
            dataType: 'json',
            data: {},
            success: function(data) {
                if (data.eventos.length > 0) {
                    for (let i = 0; i < data.eventos.length; i++) {
                        $('#tBodyReceptionTeamLineup').append('<tr data-id="'+data.eventos[i].id+'"><td>' + data.eventos[i].schedulerDate + '</td><td>' + data.eventos[i].dayOfWeek + '</td><td>' + data.eventos[i].who + '</td><td><a href="'+data.eventos[i].urlEdit+'" class="'+data.eventos[i].disabledEdit+'"><i class="fas fa-edit '+data.eventos[i].disabledEdit+'" style="font-size: 24px;"></i></a>  <a href="'+data.eventos[i].urlDelete+'" class="'+data.eventos[i].disabledRemove+'"><i class="fas fa-trash-alt '+data.eventos[i].disabledRemove+'" style="font-size: 24px;"></i></a></td'+'</tr>');
                    }
                }

                // Destruir a tabela existente
                if (typeof tableTeamReceptionLineup !== 'undefined' && $.fn.DataTable.isDataTable('#tableReceptionTeamLineup')) {
                    tableTeamReceptionLineup.destroy();
                }

                // Recriar a tabela com os novos dados
                initializeTableReceptionTeamLineup_();

                $('#div-reception-team-lineup-loading').css("display", "none");
                $('#div-table-list-reception-team-lineup').css("display", "block");
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });
    }

    // Função para inicializar a tabela
    function initializeTableReceptionTeamLineup_() {
        table = $('#tableReceptionTeamLineup').DataTable({
            "destroy": true,
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": true,
            "pageLength": 50,
            "language": {
                "emptyTable": "Sem escala cadastrada..."
            }
        });
    }

    let tableTeamWorshipLineup;
    async function waitingFilterTableWorshipTeamLineup(info, origem){

        $('#div-table-list-worship-team-lineup').css("display", "none");
        $('#div-worship-team-lineup-loading').css("display", "block");

        for (let i = 0; i < 3; i++) {
            //console.log(`Waiting ${i} seconds...`);
            await sleep(i * 1000);
        }
        //console.log("Done...");
        let startDate;

        if (origem === 'dateClick') {
            console.log('dateClick');
            startDate = (typeof info.dateStr === "undefined") ? info.event.extendedProps._id.start : info.dateStr;
        } else {
            let dtStart = new Date(info.event.start);
            dtStart.setDate(dtStart.getDate() + 1);
            let month = ((dtStart.getMonth() + 1) <= 9) ? "0" + (dtStart.getMonth() + 1) : (dtStart.getMonth() + 1);
            let day = ((dtStart.getDate()) <= 9) ? "0" + dtStart.getDate() : dtStart.getDate();
            dtStart = `${dtStart.getFullYear()}-${month}-${day}`;
            startDate = dtStart;
        }

        let start = `${startDate} 00:00:00`;
        let end = `${startDate} 23:59:59`;

        carregarTabelFilterWorshipTeamLineup(start, end);
    }

    function carregarTabelFilterWorshipTeamLineup(start, end) {
        let container = document.getElementById('div-table-list-worship-team-lineup');
        container.innerHTML = '<table id="tableTeamWorshipLineup" class="table table-striped-columns table-hover table-sm"><thead class="table-secondary"><tr><th style="width: 200px">Data</th><th style="width: 300px">Dia Da Semana</th><th style="width: 450px">Quem</th><th style="width: 400px">Ações</th></tr></thead><tbody id="tBodyWorshipTeamLineup"></tbody></table>';

        $('#div-table-list-worship-team-lineup').css("display", "none");
        $('#div-worship-team-lineup-loading').css("display", "block");


        $.ajax({
            url: `scheduler-worship-teams-lineup/items-teams-lineup/filter?start=${start}&end=${end}`,
            type: 'GET',
            dataType: 'json',
            data: {},
            success: function(data) {
                if (data.eventos.length > 0) {
                    for (let i = 0; i < data.eventos.length; i++) {
                        $('#tBodyWorshipTeamLineup').append('<tr data-id="'+data.eventos[i].id+'"><td>' + data.eventos[i].schedulerDate + '</td><td>' + data.eventos[i].dayOfWeek + '</td><td>' + data.eventos[i].who + '</td><td><a href="'+data.eventos[i].urlEdit+'" class="'+data.eventos[i].disabledEdit+'"><i class="fas fa-edit '+data.eventos[i].disabledEdit+'" style="font-size: 24px;"></i></a>  <a href="'+data.eventos[i].urlDelete+'" class="'+data.eventos[i].disabledRemove+'"><i class="fas fa-trash-alt '+data.eventos[i].disabledRemove+'" style="font-size: 24px;"></i></a></td'+'</tr>');
                    }
                }

                // Destruir a tabela existente
                if (typeof tableTeamWorshipLineup !== 'undefined' && $.fn.DataTable.isDataTable('#tableWorshipTeamLineup')) {
                    tableTeamWorshipLineup.destroy();
                }

                // Recriar a tabela com os novos dados
                initializeTableWorshipTeamLineup_();

                $('#div-worship-team-lineup-loading').css("display", "none");
                $('#div-table-list-worship-team-lineup').css("display", "block");
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });
    }

    // Função para inicializar a tabela
    function initializeTableWorshipTeamLineup_() {
        table = $('#tableWorshipTeamLineup').DataTable({
            "destroy": true,
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": true,
            "pageLength": 50,
            "language": {
                "emptyTable": "Sem escala cadastrada..."
            }
        });
        // Adicionar evento de clique às linhas
        addClickWorshipTeamLineupToRows();
    }

    // // Função para adicionar evento de clique às linhas
    // function addClickWorshipTeamLineupToRows() {
    //     $('#tableTeamWorshipLineup tbody').on('click', 'tr', function() {
    //         let rowData = table.row(this).data();
    //         let dataId = $(this).data('id'); // Obtém o valor do atributo data-id da linha clicada
    //         // console.log('Linha clicada:', rowData);
    //         // console.log('Valor do data-id:', dataId);
    //         $('#modalViewWorshipTeamLineup').modal('show');
    //         $('[data-mask]').inputmask();
    //         modalViewWorshipTeamLineupIsOpen(dataId);
    //     });
    // }
    //
    // function modalViewWorshipTeamLineupIsOpen(id) {
    //     $.ajax({
    //         url: `worship/${id}/search`,
    //         type: 'GET',
    //         dataType: 'json',
    //         data: {},
    //         success: function(data) {
    //             if (data.entries.length > 0) {
    //                 let worshipTeam = data.entries[0].group_complete_names;
    //                 let worshipMusics = data.entries[0].worship_music;
    //                 let singers =  data.entries[0].group_singer_names;
    //                 let singerMusics = data.entries[0].singer_music;
    //                 let schedulerDate = data.entries[0].schedulerDate;
    //                 // let horario = data.eventos[0].start;
    //                 // horario = horario.replace(" ", "T").replace("+03:00", "");
    //                 // let startDate = new  Date(moment(new Date(horario)).utcOffset('-06:00').format('lll'));
    //                 // let month = ((startDate.getMonth() + 1) <= 9) ? "0" + (startDate.getMonth() + 1) : (startDate.getMonth() + 1);
    //                 // let day = ((startDate.getDate()) <= 9) ? "0" + startDate.getDate() : startDate.getDate();
    //                 // let minutes = ((startDate.getMinutes()) <= 9) ? "0" + startDate.getMinutes() : startDate.getMinutes();
    //                 // let start = `${startDate.getFullYear()}-${month}-${day}`;
    //                 // let hour = ((startDate.getHours()) <= 9) ? "0" + startDate.getHours() : startDate.getHours();
    //                 // let time = `${hour}:${minutes}:00`;
    //
    //                 $('#tableTeamWorshipLineup').on('shown.bs.modal', function () {
    //
    //                     let modal = $(this);
    //                     modal.find('#id').val(id);
    //                     modal.find('#date').val(schedulerDate);
    //                     modal.find('#worshipTeam').val(worshipTeam);
    //                     modal.find('#worshipMusics').val(worshipMusics);
    //                     modal.find('#singers').val(singers);
    //                     modal.find('#singerMusics').val(singerMusics);
    //                 });
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Erro na requisição:', error);
    //         }
    //     });
    //
    // }

    async function waitingFilterTableEventsChurch(info, origem) {
        $('#div-table-list-events-church').css("display", "none");
        $('#div-loading-events-church').css("display", "block");

        for (let i = 0; i < 3; i++) {
            //console.log(`Waiting ${i} seconds...`);
            await sleep(i * 1000);
        }
        //console.log("Done...");
        let startDate;

        if (origem === 'dateClick') {
            console.log('dateClick');
            startDate = (typeof info.dateStr === "undefined") ? info.event.extendedProps._id.start : info.dateStr;
        } else {
            let dtStart = new Date(info.event.start);
            dtStart.setDate(dtStart.getDate() + 1);
            let month = ((dtStart.getMonth() + 1) <= 9) ? "0" + (dtStart.getMonth() + 1) : (dtStart.getMonth() + 1);
            let day = ((dtStart.getDate()) <= 9) ? "0" + dtStart.getDate() : dtStart.getDate();
            dtStart = `${dtStart.getFullYear()}-${month}-${day}`;
            startDate = dtStart;
        }

        let start = `${startDate} 00:00:00`;
        let end = `${startDate} 23:59:59`;

        carregarTableEventsChruchFilter(start, end);

    }

    async function waitingFilterTable(info, origem) {
        $('#div-table-list-events').css("display", "none");
        $('#div-loading').css("display", "block");

        for (let i = 0; i < 3; i++) {
            //console.log(`Waiting ${i} seconds...`);
            await sleep(i * 1000);
        }
        //console.log("Done...");
        let startDate;

        if (origem === 'dateClick') {
            console.log('dateClick');
            startDate = (typeof info.dateStr === "undefined") ? info.event.extendedProps._id.start : info.dateStr;
        } else {
            let dtStart = new Date(info.event.start);
            dtStart.setDate(dtStart.getDate() + 1);
            let month = ((dtStart.getMonth() + 1) <= 9) ? "0" + (dtStart.getMonth() + 1) : (dtStart.getMonth() + 1);
            let day = ((dtStart.getDate()) <= 9) ? "0" + dtStart.getDate() : dtStart.getDate();
            dtStart = `${dtStart.getFullYear()}-${month}-${day}`;
            startDate = dtStart;
        }

        let start = `${startDate} 00:00:00`;
        let end = `${startDate} 23:59:59`;

        carregarTabelFilter(start, end);

    }

    let table;

    // Função para adicionar evento de clique às linhas
    function addClickEventToRows() {
        $('#tableListEvents tbody').on('click', 'tr', function() {
            let rowData = table.row(this).data();
            let dataId = $(this).data('id'); // Obtém o valor do atributo data-id da linha clicada
            // console.log('Linha clicada:', rowData);
            // console.log('Valor do data-id:', dataId);
            $('#modalViewEvent').modal('show');
            $('[data-mask]').inputmask();
            modalViewIsOpen(dataId);
        });
    }

    // Função para inicializar a tabela
    function initializeTable() {
        table = $('#tableListEvents').DataTable({
            "destroy": true,
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": true,
            "pageLength": 25,
            "language": {
                "emptyTable": "Sem agenda..."
            }
        });

        // Adicionar evento de clique às linhas
        addClickEventToRows();
    }

    // Função para adicionar evento de clique às linhas
    function addClickEventsChurchToRows() {
        $('#tableListEventsChurch tbody').on('click', 'tr', function() {
            let rowData = table.row(this).data();
            let dataId = $(this).data('id'); // Obtém o valor do atributo data-id da linha clicada
            // console.log('Linha clicada:', rowData);
            // console.log('Valor do data-id:', dataId);
            $('#modalViewEventsChurch').modal('show');
            $('[data-mask]').inputmask();
            modalViewEventsChurchIsOpen(dataId);
        });
    }

    // Função para inicializar a tabela
    function initializeTableEventsChurch() {
        table = $('#tableListEventsChurch').DataTable({
            "destroy": true,
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": true,
            "pageLength": 25,
            "language": {
                "emptyTable": "Sem agenda..."
            }
        });

        // Adicionar evento de clique às linhas
        addClickEventsChurchToRows();
    }

    function modalViewIsOpen(id) {
        $.ajax({
            url: `event/${id}/search`,
            type: 'GET',
            dataType: 'json',
            data: {},
            success: function(data) {
                if (data.eventos.length > 0) {
                    let orador = data.eventos[0].orador;
                    let telefone = data.eventos[0].contato;
                    let primeiroHino = data.eventos[0].hino_inicial;
                    let hinoFinal =  data.eventos[0].hino_final;
                    let tema = data.eventos[0].description;
                    let status = data.eventos[0].status_id;
                    let departamento = data.eventos[0].department_id;
                    let program = data.eventos[0].program_id;
                    let observacoes = data.eventos[0].observacoes;
                    let horario = data.eventos[0].start;
                    horario = horario.replace(" ", "T").replace("+03:00", "");
                    let startDate = new  Date(moment(new Date(horario)).utcOffset('-06:00').format('lll'));
                    let month = ((startDate.getMonth() + 1) <= 9) ? "0" + (startDate.getMonth() + 1) : (startDate.getMonth() + 1);
                    let day = ((startDate.getDate()) <= 9) ? "0" + startDate.getDate() : startDate.getDate();
                    let minutes = ((startDate.getMinutes()) <= 9) ? "0" + startDate.getMinutes() : startDate.getMinutes();
                    let start = `${startDate.getFullYear()}-${month}-${day}`;
                    let hour = ((startDate.getHours()) <= 9) ? "0" + startDate.getHours() : startDate.getHours();
                    let time = `${hour}:${minutes}:00`;

                    $('#modalViewEvent').on('shown.bs.modal', function () {

                        let modal = $(this);
                        modal.find('#id').val(id);
                        modal.find('#date').val(start);
                        modal.find('#time').val(time);
                        modal.find('#orador').val(orador);
                        modal.find('#telefone').val(telefone);
                        modal.find('#primeiroHino').val(primeiroHino);
                        modal.find('#hinoFinal').val(hinoFinal);
                        modal.find('#tema').val(tema);
                        modal.find('#statusEvent').val(status);
                        modal.find('#departamentos').val(departamento);
                        modal.find('#programs').val(program);
                        modal.find('#observacoes').val(observacoes);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });

    }

    function modalViewEventsChurchIsOpen(id) {
        $.ajax({
            url: `events-church/${id}/search`,
            type: 'GET',
            dataType: 'json',
            data: {},
            success: function(data) {
                if (data.eventos.length > 0) {
                    let orador = data.eventos[0].orador;
                    let telefone = data.eventos[0].contato;
                    let primeiroHino = data.eventos[0].hino_inicial;
                    let hinoFinal =  data.eventos[0].hino_final;
                    let tema = data.eventos[0].description;
                    let status = data.eventos[0].status_id;
                    let departamento = data.eventos[0].department_id;
                    let program = data.eventos[0].program_id;
                    let observacoes = data.eventos[0].observacoes;
                    let horario = data.eventos[0].start;
                    horario = horario.replace(" ", "T").replace("+03:00", "");
                    let startDate = new  Date(moment(new Date(horario)).utcOffset('-06:00').format('lll'));
                    let month = ((startDate.getMonth() + 1) <= 9) ? "0" + (startDate.getMonth() + 1) : (startDate.getMonth() + 1);
                    let day = ((startDate.getDate()) <= 9) ? "0" + startDate.getDate() : startDate.getDate();
                    let minutes = ((startDate.getMinutes()) <= 9) ? "0" + startDate.getMinutes() : startDate.getMinutes();
                    let start = `${startDate.getFullYear()}-${month}-${day}`;
                    let hour = ((startDate.getHours()) <= 9) ? "0" + startDate.getHours() : startDate.getHours();
                    let time = `${hour}:${minutes}:00`;

                    $('#modalViewEventsChurch').on('shown.bs.modal', function () {

                        let modal = $(this);
                        modal.find('#id').val(id);
                        modal.find('#date').val(start);
                        modal.find('#time').val(time);
                        modal.find('#orador').val(orador);
                        modal.find('#telefone').val(telefone);
                        modal.find('#primeiroHino').val(primeiroHino);
                        modal.find('#hinoFinal').val(hinoFinal);
                        modal.find('#tema').val(tema);
                        modal.find('#statusEvent').val(status);
                        modal.find('#departamentos').val(departamento);
                        modal.find('#programs').val(program);
                        modal.find('#observacoes').val(observacoes);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });

    }

    function carregarTabelFilter(start, end) {
        let container = document.getElementById('div-table-list-events');
        container.innerHTML = '<table id="tableListEvents" class="table table-striped-columns table-hover table-sm" style="font-size:12px"><thead class="table-secondary"><tr><th style="width: 50px">Data</th><th>Orador</th><th>Contato</th><th>Tema</th><th>Hino Inicial</th><th>Hino Final</th><th>Programa</th><th>Departamento</th><th>Status</th></tr></thead><tbody id="tBodyListEvents"></tbody></table>';

        $('#div-table-list-events').css("display", "none");
        $('#div-loading').css("display", "block");

        $.ajax({
            url: `table-events?start=${start}&end=${end}`,
            type: 'GET',
            dataType: 'json',
            data: {},
            success: function(data) {
                if (data.eventos.length > 0) {
                    for (let i = 0; i < data.eventos.length; i++) {
                        $('#tBodyListEvents').append('<tr data-id="'+data.eventos[i].id+'"><td>' + data.eventos[i].start + '</td><td>' + data.eventos[i].orador + '</td><td>' + data.eventos[i].contato + '</td><td>' + data.eventos[i].tema + '</td><td>' + data.eventos[i].hinoInicial + '</td><td>' + data.eventos[i].hinoFinal + '</td><td>' + data.eventos[i].programa + '</td><td>' + data.eventos[i].departamento + '</td><td><img width="10px" height="10px" src="' + data.eventos[i].url + '" alt="' + data.eventos[i].statusEvento + '" /> ' + data.eventos[i].statusEvento + '</td></tr>');
                    }
                }

                // Destruir a tabela existente
                if (typeof table !== 'undefined' && $.fn.DataTable.isDataTable('#tableListEvents')) {
                    table.destroy();
                }

                // Recriar a tabela com os novos dados
                initializeTable();

                $('#div-loading').css("display", "none");
                $('#div-table-list-events').css("display", "block");
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });
    }

    function carregarTableEventsChruchFilter(start, end) {
        let container = document.getElementById('div-table-list-events-church');
        container.innerHTML = '<table id="tableListEventsChurch" class="table table-striped-columns table-hover table-sm" style="font-size:12px"><thead class="table-secondary"><tr><th style="width: 50px">Data</th><th>Ancião Conselheiro</th><th>Responsável</th><th>Contato</th><th>Tema</th><th>Programação</th><th>Departamento</th><th>Observações</th><th>Status</th></tr></thead><tbody id="tBodyListEventsChurch"></tbody></table>';

        $('#div-table-list-events-church').css("display", "none");
        $('#div-loading-events-church').css("display", "block");

        $.ajax({
            url: `table-events-church?start=${start}&end=${end}`,
            type: 'GET',
            dataType: 'json',
            data: {},
            success: function(data) {
                if (data.eventos.length > 0) {
                    for (let i = 0; i < data.eventos.length; i++) {
                        $('#tBodyListEventsChurch').append('<tr data-id="'+data.eventos[i].id+'"><td>' + data.eventos[i].start + '</td><td>'+ data.eventos[i].elder_complete_name + '</td><td>' + data.eventos[i].owner + '</td><td>' + data.eventos[i].contato + '</td><td>' + data.eventos[i].tema + '</td><td>'+ data.eventos[i].programa +'</td></td><td>' + data.eventos[i].departamento + '</td><td>' + data.eventos[i].observacoes + '</td><td><img width="10px" height="10px" src="' + data.eventos[i].url + '" alt="' + data.eventos[i].statusEvento + '" /> ' + data.eventos[i].statusEvento + '</td></tr>');
                    }
                }

                // Destruir a tabela existente
                if (typeof table !== 'undefined' && $.fn.DataTable.isDataTable('#tableListEventsChurch')) {
                    table.destroy();
                }

                // Recriar a tabela com os novos dados
                initializeTableEventsChurch();

                $('#div-loading-events-church').css("display", "none");
                $('#div-table-list-events-church').css("display", "block");
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });
    }

})(window, document);