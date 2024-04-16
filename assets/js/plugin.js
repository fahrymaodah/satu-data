plugin = {
    initDataTables: function() {
        $('#datatables').DataTable({
            "searching": false,
            "info": false,
            "dom": '<"top">t<"bottom"><"row"<"col-lg-6 col-md-6 col-sm-6 col-xs-6"l><"col-lg-6 col-md-6 col-sm-6 col-xs-6"p>>',
            "pagingType": "simple_numbers",
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua Data"]
            ],
            "order": [],
            "responsive": true,
            "language": {
                "lengthMenu": "Menampilkan _MENU_ Data",
                "paginate": {
                    "next": "Selanjuntnya",
                    "previous": "Sebelumnya"
                }
            }
        });
        var table = $('#datatables').DataTable();

        $('#datatablesmaterial').DataTable({
            "pagingType": "simple_numbers",
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "order": [],
            "responsive": true,
            "language": {
                "lengthMenu": "Menampilkan _MENU_ Data",
                "search": "_INPUT_",
                "searchPlaceholder": "Cari Data",
                "info": "Menampilkan <b> _START_ - _END_ </b> dari <b> _TOTAL_ </b> data",
                "paginate": {
                    "next": "<i class='fas fa-chevron-right'></i>",
                    "previous": "<i class='fas fa-chevron-left'></i>"
                },
                "aria": {
                    "next": "Selanjuntnya",
                    "previous": "Sebelumnya"
                }
            }
        });
        var table = $('#datatablesmaterial').DataTable();

        $('.datatablesmaterial').DataTable({
            "pagingType": "simple_numbers",
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "order": [],
            "responsive": true,
            "language": {
                "lengthMenu": "Menampilkan _MENU_ Data",
                "search": "_INPUT_",
                "searchPlaceholder": "Cari Data",
                "info": "Menampilkan <b> _START_ - _END_ </b> dari <b> _TOTAL_ </b> data",
                "paginate": {
                    "next": "Selanjuntnya",
                    "previous": "Sebelumnya"
                }
            }
        });
        var table = $('.datatablesmaterial').DataTable();

        $('.datatablesstruktur').DataTable({
            "pagingType": "simple_numbers",
            "lengthMenu": [
                [5, 10, -1],
                [5, 10, "All"]
            ],
            responsive: true,
            language: {
                lengthMenu: "Menampilkan _MENU_ Data",
                search: "_INPUT_",
                searchPlaceholder: "Cari Data",
                info: "Menampilkan <b> _START_ - _END_ </b> dari <b> _TOTAL_ </b> data",
                paginate: {
                    next: "Selanjuntnya",
                    previous: "Sebelumnya"
                }
            }
        });
        var table = $('.datatablesstruktur').DataTable();
    },
}