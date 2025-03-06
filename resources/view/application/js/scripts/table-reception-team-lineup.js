

function sleepReceptionTeamLineup(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

let tableTeamReceptionLineup;


function loadTableListReceptionTeamLineup() {
    let container = document.getElementById('div-table-list-reception-team-lineup');
    container.innerHTML = '<table id="tableTeamReceptionLineup" class="table table-striped-columns table-hover table-sm"><thead class="table-secondary"><tr><th style="width: 200px">Data</th><th style="width: 300px">Dia Da Semana</th><th style="width: 450px">Quem</th><th style="width: 400px">Ações</th></tr></thead><tbody id="tBodyReceptionTeamLineup"></tbody></table>';

    $('#div-table-list-reception-team-lineup').css("display", "none");
    $('#div-reception-team-lineup-loading').css("display", "block");

    $.ajax({
        url: `scheduler-reception-teams-lineup/items-teams-lineup`,
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
            initializeTableReceptionTeamLineup();

            $('#div-reception-team-lineup-loading').css("display", "none");
            $('#div-table-list-reception-team-lineup').css("display", "block");
        },
        error: function(xhr, status, error) {
            console.error('Erro na requisição:', error);
        }
    });
}


function initializeTableReceptionTeamLineup() {
    table = $('#tableTeamReceptionLineup').DataTable({
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

async function preLoadReceptionTeamLineup() {
    $('#div-reception-team-lineup-loading').css("display", "block");
    $('#div-table-list-reception-team-lineup').css("display", "none");

    await sleepReceptionTeamLineup(3000); // Aguardar 3 segundos
    loadTableListReceptionTeamLineup();

    $("#div-reception-team-lineup-loading").css("display", "none");
    $("#div-table-list-reception-team-lineup").css("display", "block");
}

preLoadReceptionTeamLineup().then(r => {});

const btnNext = document.querySelector('.fc-next-button');
btnNext.addEventListener('click', preLoadReceptionTeamLineup);

const btnPrevious = document.querySelector('.fc-prev-button');
btnPrevious.addEventListener('click', preLoadReceptionTeamLineup);

const btnToday = document.querySelector('.fc-today-button');
btnToday.addEventListener('click', preLoadReceptionTeamLineup);
