

function sleepWorshipTeamLineup(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

let tableTeamWorshipLineup;


function loadTableListWorshipTeamLineup() {
    let container = document.getElementById('div-table-list-worship-team-lineup');
    container.innerHTML = '<table id="tableTeamWorshipLineup" class="table table-striped-columns table-hover table-sm"><thead class="table-secondary"><tr><th style="width: 200px">Data</th><th style="width: 300px">Dia Da Semana</th><th style="width: 450px">Quem</th><th style="width: 400px">Ações</th></tr></thead><tbody id="tBodyWorshipTeamLineup"></tbody></table>';

    $('#div-table-list-worship-team-lineup').css("display", "none");
    $('#div-worship-team-lineup-loading').css("display", "block");

    $.ajax({
        url: `scheduler-worship-teams-lineup/items-teams-lineup`,
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
            initializeTableWorshipTeamLineup();

            $('#div-worship-team-lineup-loading').css("display", "none");
            $('#div-table-list-worship-team-lineup').css("display", "block");
        },
        error: function(xhr, status, error) {
            console.error('Erro na requisição:', error);
        }
    });
}


function initializeTableWorshipTeamLineup() {
    table = $('#tableTeamWorshipLineup').DataTable({
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

async function preLoadWorshipTeamLineup() {
    $('#div-worship-team-lineup-loading').css("display", "block");
    $('#div-table-list-worship-team-lineup').css("display", "none");

    await sleepWorshipTeamLineup(3000); // Aguardar 3 segundos
    loadTableListWorshipTeamLineup();

    $("#div-worship-team-lineup-loading").css("display", "none");
    $("#div-table-list-worship-team-lineup").css("display", "block");
}

preLoadWorshipTeamLineup().then(r => {});

const btnNext = document.querySelector('.fc-next-button');
btnNext.addEventListener('click', preLoadWorshipTeamLineup);

const btnPrevious = document.querySelector('.fc-prev-button');
btnPrevious.addEventListener('click', preLoadWorshipTeamLineup);

const btnToday = document.querySelector('.fc-today-button');
btnToday.addEventListener('click', preLoadWorshipTeamLineup);

// Função para adicionar evento de clique às linhas
function addClickWorshipTeamLineupToRows() {
    $('#tableTeamWorshipLineup tbody').on('click', 'tr', function() {
        let dataId = $(this).data('id'); // Obtém o valor do atributo data-id da linha clicada

        // Remove a classe de seleção de todas as linhas
        $('#tableTeamWorshipLineup tbody tr').removeClass('selecionado');

        // Adiciona a classe de seleção à linha clicada
        $(this).addClass('selecionado');

        // Abre o modal e carrega os dados
        modalViewWorshipTeamLineupIsOpen(dataId);
    });
}
$('#modalViewWorshipTeamLineup').on('shown.bs.modal', function () {
    // Inicializa o Select2 dentro do modal
    $(this).find('.select2').select2({
        theme: 'bootstrap4'
    });
});

function modalViewWorshipTeamLineupIsOpen(id) {
    $.ajax({
        url: `worship/${id}/search`,
        type: 'GET',
        dataType: 'json',
        data: {},
        success: function(data) {
            if (data.entries.length > 0) {
                let worshipTeam = data.entries[0].group_complete_names;
                let worshipMusics = data.entries[0].worship_music;
                let singers = data.entries[0].group_singer_names;
                let singerMusics = data.entries[0].singer_music;
                let schedulerDate = data.entries[0].scheduler_date;

                // Aguarda o modal estar completamente aberto antes de preencher os campos
                $('#modalViewWorshipTeamLineup').on('shown.bs.modal', function () {
                    let modal = $(this);

                    // Preenche o campo de data
                    modal.find('#schedulerDate').val(schedulerDate);

                    // Preenche o campo #worshipMusics (textarea)
                    modal.find('#worshipMusics').val(worshipMusics);

                    // Preenche o campo #singerMusics (textarea)
                    modal.find('#singerMusics').val(singerMusics);

                    // Preenche o campo #worshipTeam (select)
                    let worshipTeamArray = worshipTeam.split(',').map(item => item.trim());
                    selectOptionsByText(modal.find('#worshipTeam'), worshipTeamArray);

                    // Preenche o campo #singers (select)
                    let singersArray = singers.split(',').map(item => item.trim());
                    selectOptionsByText(modal.find('#singers'), singersArray);
                });

                // Abre o modal
                $('#modalViewWorshipTeamLineup').modal('show');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro na requisição:', error);
        }
    });
}

/**
 * Função para marcar como "selected" as opções de um select que correspondem aos textos fornecidos.
 * @param {jQuery} selectElement - O elemento select (jQuery).
 * @param {string[]} texts - Array de textos a serem comparados.
 */
function selectOptionsByText(selectElement, texts) {
    selectElement.find('option').each(function() {
        let optionText = $(this).text().trim();
        if (texts.includes(optionText)) {
            $(this).prop('selected', true);
        } else {
            $(this).prop('selected', false);
        }
    });

    // Atualiza o Select2 (se estiver sendo usado)
    if (selectElement.hasClass('select2')) {
        selectElement.trigger('change');
    }
}