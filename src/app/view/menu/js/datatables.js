$(document).ready(function(){
    $('#add_button').click(function(){
        $('#menu_form')[0].reset();
        $('.modal-title').text("Étel hozzáadás");
        $('#action').val("Hozzáad");
        $('#operation').val("Hozzáad");

        $('#menu_form .form-group > input + label').removeClass('labelUp labelColor');
    });

    let dataTable = $('#menu_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"index.php?controller=menu&action=fetch",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[1,4,5],
                "orderable":false,
            },
        ],

    });

    $(document).on('submit', '#menu_form', function(event){
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
                url:"index.php?controller=menu&action=menuInsert",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                success:function(data)
                {
                    $('#menu_form')[0].reset();
                    $('#menuModal').modal('hide');
                    dataTable.ajax.reload();
                }
            });
        }
    });

    $(document).on('click', '.update', function(){
        $('#menu_form .form-group input, #menu_form .form-group select').attr('class','form-control');
        $('#menu_form .form-group > label').removeClass('labelColor');
        $('#menu_form .form-group > label').attr('class','labelUp');

        $.ajax({
            url:"index.php?controller=menu&action=fetchSingle",
            method:"POST",
            data:{
                menu_id:$(this).attr('id'),
            },
            dataType:"json",
            success:function(data)
            {
                $('#menuModal').modal('show');

                $('#menu_id').val(data.menu_id);
                $('#name').val(data.name);

                $('.form-check-input').prop('checked', false);
                data.allergens.forEach(e => {
                    let checkbox = '#allergen'+e;
                    $(checkbox).prop('checked', true);
                });

                $('#price').val(data.price);
                $('#current').prop('checked', data.current == 1 ? true : false);
                $('#recommendation').val(data.recommendation);

                $('#action').val("Változtat");
                $('#operation').val("Változtat");
            }
        })
    });

    $(document).on('click', '.delete', function(){
        if(confirm("Biztosan törölni szeretné?"))
        {
            $.ajax({
                url:"index.php?controller=menu&action=delete",
                method:"POST",
                data:{menu_id:$(this).attr("id")},
                success:function(data)
                {
                    if (data.includes('false-in-menu')) {
                        alert('Ez az adat nem törölhető!');
                    } else dataTable.ajax.reload();
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