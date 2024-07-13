@extends('layouts.master')


@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <style>
        .table_style {
            table-layout: fixed;
            overflow-wrap: break-word;
            width: 100% !important
        }
    </style>
@endsection

@section('content')
    <div class="box box-success">

        <div class="box-header">
            <h3 class="box-title">List of cards</h3>

            <a onclick="addForm()" class="btn btn-success pull-right" style="margin-top: -8px;"><i class="fa fa-plus"></i> Add cards</a>
        </div>


        <!-- /.box-header -->
        <div class="box-body">
            <table id="cards-table" class="table_style table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th style="width: 3%;">ID</th>
                    <th style="width: 12%;">Category</th>
                    <th style="width: 9%;">Customer</th>
                    <th style="width: 11%;">Number</th>
                    <th style="width: 32%;">Working Days</th>
                    <th style="width: 8%;">Bus Lines</th>
                    <th style="width: 8%;">Usage Hour</th>
                    <th style="width: 13%;">Actions</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    @include('cards.form_days')
    @include('cards.form')

@endsection

@section('bot')

    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>

    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    {{--<script>--}}
    {{--$(function () {--}}
    {{--$('#items-table').DataTable()--}}
    {{--$('#example2').DataTable({--}}
    {{--'paging'      : true,--}}
    {{--'lengthChange': false,--}}
    {{--'searching'   : false,--}}
    {{--'ordering'    : true,--}}
    {{--'info'        : true,--}}
    {{--'autoWidth'   : false--}}
    {{--})--}}
    {{--})--}}
    {{--</script>--}}

    <script type="text/javascript">

            $('.date').datepicker({
                multidate: true,
                format: 'dd/mm/yyyy'
            });

            $(function () {
                $('select').each(function () {
                    $(this).select2({
                    theme: 'bootstrap4',
                    width: 'style',
                    placeholder: $(this).attr('placeholder'),
                    allowClear: Boolean($(this).data('allow-clear')),
                    });
                });
            });





        var table = $('#cards-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.cards') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'category_name', name: 'category_name'},
                {data: 'customer_name', name: 'customer_name'},
                {data: 'number', name: 'number'},
                {data: 'working_days', name: 'working_days'},
                {data: 'bus_lines', name: 'bus_lines'},
                {data: 'usage_hours', name: 'usage_hours'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Add cards');
            var url = "{{ route('getworkingdays') }}";
            console.log('called')
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    if (data.success) {
                        console.log('Received working days:', data.working_days);
                        $('#working_days').val(data.working_days);

                        let dateArray = data.working_days.split(',').map(function(dateStr) {
                            let parts = dateStr.split('/');
                            return new Date(parts[2], parts[1] - 1, parts[0]); 
                        });
                        console.log('dateArray', dateArray);
                        $('#working_days').datepicker('setDates', dateArray);
                    }
                },
                error: function(data) {
                }
            });

        }
        
        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $.ajax({
                url: "{{ url('cards') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log('data', data)
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Edit cards');

                    $('#id').val(data.id);
                    $('#number').val(data.number);
                    // $('#working_days').val(data.working_days);


                    let dateArray = data.working_days.split(',').map(function(dateStr) {
                        let parts = dateStr.split('/');
                        let result = new Date(parts[2], parts[1] - 1, parts[0]); 
                        // console.log('result', result)
                        return result;
                    });
                    $('#working_days').datepicker('setDates', dateArray);
                    $('#bus_lines').val(data.bus_lines);
                    var selectedOptions = data.usage_hours.split(',');
                    $('#multiselect').val(selectedOptions).trigger('change');
                    $('#category_id').val(data.category_id).trigger('change');
                    $('#customer_id').val(data.customer_id).trigger('change');
                },
                error : function() {
                    alert("Nothing Data");
                }
            });
        }

        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                $.ajax({
                    url : "{{ url('cards') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        })
                    },
                    error : function () {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            });
        }
        $(function() {
            function saveWorkingDays() {
                var url = "{{ route('saveworkingdays') }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: new FormData($("#defaultdays-form form")[0]),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data.success) {
                            
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            });
                        }
                    },
                    error: function(data) {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        });
                    }
                });
            }
            $('#defaultdays-form form').validator().on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    saveWorkingDays();
                    return false;
                }
            });
        });
        
        function getWorkingDays(){
            var url = "{{ route('getworkingdays') }}";
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    if (data.success) {
                        $('#working_day').val(data.working_days);

                        let dateArray = data.working_days.split(',').map(function(dateStr) {
                            let parts = dateStr.split('/');
                            return new Date(parts[2], parts[1] - 1, parts[0]); 
                        });
                        $('#working_day').datepicker('setDates', dateArray);
                    }
                },
                error: function(data) {
                }
            });
        }
        getWorkingDays();

        $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    console.log('dfdf')
                    if (save_method == 'add') url = "{{ url('cards') }}";
                    else url = "{{ url('cards') . '/' }}" + id;

                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });
        });
    </script>

@endsection
