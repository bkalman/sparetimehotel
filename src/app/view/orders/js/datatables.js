$(document).ready(function(){
    $('#add_button').click(function(){
        $('#order_form')[0].reset();
        $('.modal-title').text("Rendelés Felvétel");
        $('#action').val("Felvétel");
        $('#operation').val("Felvétel");
    });

    let dataTable = $('#order_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"index.php?controller=orders&action=fetch",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[2,3,4,5,6],
                "orderable":false,
            },
        ],

    });

    $(document).on('submit', '#order_form', function(event){
        event.preventDefault();

        let emptyBoolean = true;

        let empty = ['#name'];

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
                url:"index.php?controller=orders&action=orderInsert",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                success:function(data)
                {
                    alert(data);
                    $('#order_form')[0].reset();
                    $('#orderModal').modal('hide');
                    dataTable.ajax.reload();
                }
            });
        }
    });

    $(document).on('click', '.update', function(){
        $('#order_form .form-group input, #order_form .form-group select').attr('class','form-control');
        $('#order_form label').removeClass('labelColor');

        $.ajax({
            url:"index.php?controller=orders&action=fetchSingle",
            method:"POST",
            data:{
                order_id:$(this).attr('id'),
            },
            dataType:"json",
            success:function(data)
            {
                $('#orderModal').modal('show');

                $('#order_id').val(data.order_id);
                $('#name').val(data.guest_id);
                $('#guest_id').val(data.guest_id);
                $('#date').val(data.date);
                $('#breakfast').val(data.breakfast);
                $('#lunch').val(data.lunch);
                $('#dinner').val(data.dinner);

                $('#action').val("Változtat");
                $('#operation').val("Változtat");
            }
        })
    });

    $(document).on('click', '.delete', function(){
        let guest_id = $(this).attr("id");
        if(confirm("Biztosan törölni szeretné?"))
        {
            $.ajax({
                url:"index.php?controller=orders&action=delete",
                method:"POST",
                data:{guest_id:guest_id},
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
});