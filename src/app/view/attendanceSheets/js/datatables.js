$(document).ready(function(){
    $(document).on('click','#add_month',function(){
        $.ajax({
            url:"index.php?controller=attendanceSheets&action=insertMonth",
            type:"POST",
            dataType:"json",
            success:function(data)
            {

            }
        });
    });


    let dataTable = $('#attendace_sheets_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"index.php?controller=attendanceSheets&action=fetch",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[2,3,4,5,7],
                "orderable":false,
            },
        ],

    });

    $(document).on('change', 'td > .status', function(){
        if (confirm('Biztosan megszeretné változtatni a állapotot?')) {
            $.ajax({
                url: "index.php?controller=attendanceSheets&action=updateStatus",
                method: "POST",
                data: {
                    status: $(this).val(),
                    employee_id: $(this).attr('data-employee_id'),
                    date: $(this).attr('data-date'),
                },
                dataType: "json",
                success: function (data) {
                    $(this).val(data);
                }
            })
        }
    });

    $(document).on('click', '.update', function(){
        $('#attendace_sheetsModal').modal('show');
        $('#attendace_sheetsModal input').removeClass('is-valid is-invalid');
        $.ajax({
            url:"index.php?controller=attendanceSheets&action=fetchSingle",
            method:'POST',
            data:{
                employee_id:$(this).attr('data-employee_id'),
                date:$(this).attr('data-date'),
            },
            dataType:"json",
            success:function(data)
            {
                $('#attendace_sheets_form')[0].reset();
                $('#attendace_sheetsModal').modal('hide');

                $('#name').val(data.name);
                $('#date:first-of-type').val(data.date);
                $('#date:nth-of-type(2)').val(data.date);
                $('#start_time').val(data.start_time);
                $('#end_time').val(data.end_time);
                $('#working_hours').val(data.working_hours);
                $('#break').val(data.break);
                $('#employee_id').val(data.employee_id);

                if (data.status == 'aláírt') {
                    $('#attendace_sheetsModal #status > option:nth-of-type(2)').attr('selected',true);
                } else if(data.status == 'szabadságon') {
                    $('#attendace_sheetsModal #status > option:nth-of-type(3)').attr('selected',true);
                } else $('#attendace_sheetsModal #status > option:first-child').attr('selected',true);

                dataTable.ajax.reload();
            }
        });
    });

    $(document).on('submit', '#attendace_sheets_form', function(event){
        event.preventDefault();

        let emptyBoolean = true;

        let empty = ['#name','#date','#start_time','#end_time','#working_hours','#break'];

        empty.forEach(e => {
            if ($(e).val() != '') {
                $(e).removeClass('is-invalid');
                $(e).addClass('is-valid');
            } else {
                emptyBoolean = false;
                $(e).addClass('is-invalid');
                $(e).removeClass('is-valid');
            }
        });


        if (emptyBoolean == true) {
            $.ajax({
                url:"index.php?controller=attendanceSheets&action=update",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                success:function(data)
                {
                    if (data == false) {
                        alert('Töltsön ki minden adatot!');
                    } else {
                        $('#attendace_sheets_form')[0].reset();
                        $('#attendace_sheetsModal').modal('hide');
                        dataTable.ajax.reload();
                    }
                }
            });
        }
    });

    ['guest_id','adult','child','room_id','start_date','end_date'].forEach(e => {
        let input = 'input#'+e;
        let label = 'label[for='+e+']';

        $(document).on('focus',input,function (){
            if($(input).val() == '') $(label).addClass('labelUp labelColor');
        });
        $(document).on('blur',input,function (){
            if($(input).val() == '') {
                $(label).removeClass('labelUp labelColor');
            } else {
                $(label).removeClass('labelColor');
                $(label).addClass('labelUp');
            }
        });
    });
});