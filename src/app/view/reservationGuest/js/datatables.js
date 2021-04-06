function ordersOut() {
    $('#reservation_orders > table > tbody').html('');
    fetch('src/app/view/reservationGuest/orders.php')
        .then(response => response.json())
        .then(d => {

            for (let key in d) {
                $('#reservation_orders > table > tbody').append(`
                            <tr>
                                <td>${d[key]['date']}</td>
                                <td>${d[key]['breakfast'] == null ? '' : d[key]['breakfast']}</td>
                                <td>${d[key]['lunch'] == null ? '' : d[key]['lunch']}</td>
                                <td>${d[key]['dinner'] == null ? '' : d[key]['dinner']}</td>
                                <td>${d[key]['price'] == null ? '' : d[key]['price']}</td>
                                <td><button type="button" name="delete" id="${key}" class="btn btn-danger btn-xs delete"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg></button></td>
                            </tr>
                        `);
            }
        });
}

$(document).ready(function(){
    ordersOut();

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
            url:"index.php?controller=reservationGuest&action=fetch",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[1,3],
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
                url:"index.php?controller=reservationGuest&action=insert",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                success:function()
                {
                    ordersOut();
                }
            });
        }
    });

    $(document).on('click', '.delete', function(){
        let order_id = $(this).attr("id");
        if(confirm("Biztosan törölni szeretné?"))
        {
            $.ajax({
                url:"index.php?controller=reservationGuest&action=delete",
                method:"POST",
                data:{order_id:order_id},
                success:function ()
                {
                    ordersOut();
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