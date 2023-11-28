var dataGrid;

(async () => {
    DevExpress.localization.locale('es');

    const [resEstados, resUsuarios] = await Promise.all([
        Fetch('./api/estados'),
        Fetch('./api/usuarios')
    ])

    const { result: resultEstados } = resEstados
    const estados = resultEstados.data ?? []

    const { result: resultUsuarios } = resUsuarios
    const usuarios = resultUsuarios.data ?? []

    loadEstados(estados)

    dataGrid = $("#dataGrid").dxDataGrid({
        dataSource: {
            load: async (params) => {
                const { result } = await Fetch('./api/tickets/paginado', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ ...params, mine: true })
                })
                return result
            },
        },
        onToolbarPreparing: (e) => {
            const { items } = e.toolbarOptions;
            items.unshift({
                widget: 'dxButton',
                location: 'after',
                options: {
                    icon: 'refresh',
                    hint: 'REFRESCAR TABLA',
                    onClick: () => dataGrid.refresh()
                }
            });
        },
        remoteOperations: true,
        columnResizingMode: "widget",
        columnAutoWidth: true,
        showBorders: true,
        scrollbars: 'auto',
        filterPanel: { visible: true },
        searchPanel: { visible: true },
        headerFilter: { visible: true },
        height: 'calc(100vh - 185px)',
        export: {
            enabled: true
        },
        onExporting: function (e) {
            var workbook = new ExcelJS.Workbook();
            var worksheet = workbook.addWorksheet('Main sheet');
            DevExpress.excelExporter.exportDataGrid({
                worksheet: worksheet,
                component: e.component,
                customizeCell: function (options) {
                    // options.excelCell.font = { name: 'Arial', size: 12 };
                    options.excelCell.alignment = { horizontal: 'left' };
                }
            }).then(function () {
                workbook.xlsx.writeBuffer().then(function (buffer) {
                    saveAs(new Blob([buffer], { type: 'application/octet-stream' }), `categorias.${SERVICE}.xlsx`);
                });
            });
        },
        filterRow: {
            visible: true,
            applyFilter: "auto"
        },
        filterBuilderPopup: {
            visible: false,
            position: {
                of: window, at: 'top', my: 'top', offset: { y: 10 },
            },
        },
        paging: {
            pageSize: 10,
        },
        pager: {
            visible: true,
            allowedPageSizes: [5, 10, 25, 50, 100],
            showPageSizeSelector: true,
            showInfo: true,
            showNavigationButtons: true,
        },
        allowFiltering: true,
        scrolling: {
            mode: 'standard',
            useNative: true,
            preloadEnabled: true,
            rowRenderingMode: 'standard'
        },
        columnChooser: {
            enabled: true,
            mode: 'select'
        },
        columns: [
            {
                dataField: 'id',
                caption: 'ID',
                dataType: 'number'
            },
            {
                dataField: 'tipo.categoria.categoria',
                caption: 'Categoria',
                dataType: 'string'
            },
            {
                dataField: 'tipo.tipo',
                caption: 'Tipo',
                dataType: 'string'
            },
            {
                dataField: 'asunto',
                caption: 'Asunto',
                dataType: 'string'
            },
            {
                dataField: 'estado.estado',
                caption: 'Estado',
                dataType: 'string',
                cellTemplate: (container, { data }) => {
                    container.html(data?.estado?.estado || '<i class="text-muted">- Sin estado -</i>')
                }
            },
            {
                dataField: 'informador.importancia',
                caption: 'Importancia',
                dataType: 'number'
            },
            {
                dataField: 'informador.nombres',
                caption: 'Informador',
                dataType: 'string',
                cellTemplate: (container, { data }) => {
                    container.text(data.informador.nombres)
                    container.attr('title', `${data.informador.nombres} ${data.informador.apellidos}`)
                    tippy(container.get(0), { arrow: true })
                }
            },
            {
                dataField: 'responsable.nombres',
                caption: 'Responsable',
                dataType: 'string',
                cellTemplate: (container, { data }) => {
                    container.html(data.responsable.nombres ?? '<i class="text-muted">- Sin responsable -</i>')
                    container.attr('title', `${data.responsable.nombres ?? ''} ${data.responsable.apellidos ?? ''}`.trim())
                    tippy(container.get(0), { arrow: true })
                }
            },
            {
                dataField: 'fecha_creacion',
                caption: 'Fecha creacion',
                dataType: 'string'
            },
            {
                caption: 'Acciones',
                cellTemplate: (container, { data }) => {
                    container.attr('style', 'display: flex; gap: 4px; overflow: unset')

                    const btnDetalles = $('<button>').addClass('btn btn-xs btn-soft-primary')
                    btnDetalles.append('<i class="fa fa-list"></i>')
                    btnDetalles.attr('title', 'Ver detalles')
                    btnDetalles.on('click', () => onButtonDetailClicked(data))
                    tippy(btnDetalles.get(0), { arrow: true })
                    container.append(btnDetalles)
                },
                allowFiltering: false,
                allowExporting: false
            }
        ]
    }).dxDataGrid('instance');
})();