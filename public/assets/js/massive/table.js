(async () => {
    DevExpress.localization.locale('es');
    $("#dataGrid").dxDataGrid({
        dataSource: [],
        onToolbarPreparing: (e) => {
            const { items } = e.toolbarOptions;
            items.unshift({
                widget: 'dxButton',
                location: 'after',
                options: {
                    icon: 'refresh',
                    hint: 'REFRESCAR TABLA',
                    onClick: () => $('#dataGrid').dxDataGrid('instance').refresh()
                }
            });
            items.unshift({
                widget: 'dxButton',
                location: 'after',
                options: {
                    icon: 'plus',
                    hint: 'NUEVO REGISTRO',
                    onClick: () => $('#modal-companies').modal('show')
                }
            });
        },
        remoteOperations: true,
        columnResizingMode: "widget",
        columnAutoWidth: true,
        showBorders: true,
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
                    saveAs(new Blob([buffer], { type: 'application/octet-stream' }), 'companies.xlsx');
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
        // columns: [
        //     {
        //         dataField: 'id',
        //         caption: 'ID',
        //         dataType: 'number'
        //     },
        //     {
        //         dataField: 'business',
        //         caption: 'Empresa',
        //         dataType: 'string'
        //     },
        //     {
        //         dataField: '_person',
        //         caption: 'Persona',
        //         dataType: 'number'
        //     },
        //     {
        //         dataField: 'verified',
        //         caption: 'Verificado',
        //         dataType: 'boolean'
        //     },
        //     {
        //         dataField: 'status',
        //         caption: 'Estado',
        //         dataType: 'boolean'
        //     }
        // ]
    });
})();