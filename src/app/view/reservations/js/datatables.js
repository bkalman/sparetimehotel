$(document).ready(function(){
    $('#add_button').click(function(){
        $('#reservations_form')[0].reset();
        $('.modal-title').text("Étel hozzáadás");
        $('#action').val("Hozzáad");
        $('#operation').val("Hozzáad");

        $('#reservations_form .form-group > input + label').removeClass('labelUp labelColor');
    });

    let dataTable = $('#reservations_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"index.php?controller=reservationss&action=fetch",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[2,3],
                "orderable":false,
            },
        ],

    });

    $(document).on('submit', '#reservations_form', function(event){
        event.preventDefault();

        let emptyBoolean = true;

        let empty = ['#name','#price'];

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
                url:"index.php?controller=reservations&action=reservationsInsert",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                success:function(data)
                {
                    alert(data);
                    $('#reservations_form')[0].reset();
                    $('#reservationsModal').modal('hide');
                    dataTable.ajax.reload();
                }
            });
        }
    });

    $(document).on('click', '.update', function(){
        $('#reservations_form .form-group input, #reservations_form .form-group select').attr('class','form-control');
        $('#reservations_form .form-group > label').removeClass('labelColor');
        $('#reservations_form .form-group > label').attr('class','labelUp');

        $.ajax({
            url:"index.php?controller=reservations&action=fetchSingle",
            method:"POST",
            data:{
                reservations_id:$(this).attr('id'),
            },
            dataType:"json",
            success:function(data)
            {
                $('#reservationsModal').modal('show');

                $('#last_name').val(data.last_name);
                $('#first_name').val(data.first_name);
                $('#email').val(data.email);
                $('#phone_number').val(data.phone_number);
                $('#adult').val(data.adult);
                $('#child').val(data.child);
                $('#room_id').val(data.room_id);
                $('#start_date').val(data.start_date);
                $('#end_date').val(data.end_date);
                $('#room_booking_id').val(data.room_booking_id);

                $('#action').val("Változtat");
                $('#operation').val("Változtat");
            }
        })
    });

    $(document).on('click', '.delete', function(){
        let reservations_id = $(this).attr("id");
        if(confirm("Biztosan törölni szeretné?"))
        {
            $.ajax({
                url:"index.php?controller=reservations&action=delete",
                method:"POST",
                data:{reservations_id:reservations_id},
                success:function(data)
                {
                    alert(data);
                    dataTable.ajax.reload();
                }
            });
        }
        else
        {
            return false;
        }
    });

    let inputs = ['name','price'];
    inputs.forEach(e => {
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