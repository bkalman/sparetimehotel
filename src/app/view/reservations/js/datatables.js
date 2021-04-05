$(document).ready(function(){
    $(document).on('click','#add_button',function(){
        $('#reservations_form')[0].reset();
        $('.modal-title').text("Foglalás hozzáadás");
        $('#action').val("Hozzáad");
        $('#operation').val("Hozzáad");

        $('#reservations_form .form-group > input + label').removeClass('labelUp labelColor');

        $('#guest_id').parent().attr('class','form-group col-md-6');
        $('#newUserButton').html('<input type="button" class="btn btn-primary w-100" id="newUser" value="Új vendég">');

        ajaxRequestGuests();
    });


    let dataTable = $('#reservations_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"index.php?controller=roomBooking&action=fetch",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[2,3,4],
                "orderable":false,
            },
        ],

    });

    $(document).on('submit', '#reservations_form', function(event){
        event.preventDefault();

        let emptyBoolean = true;

        let empty = ['#guest_id','#adult','#child','#room_id','#start_date','#end_date'];

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
                url:"index.php?controller=roomBooking&action=insert",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                success:function(data)
                {

                    $('#reservations_form')[0].reset();
                    $('#reservationsModal').modal('hide');
                    dataTable.ajax.reload();
                }
            });
        }
    });

    $(document).on('click', '.update', function(){
        $('#reservations_form .form-group input, #reservations_form .form-group select').attr('class','form-control');
        $('#newUser').attr('class','btn btn-primary w-100');
        $('#reservations_form .form-group > label').removeClass('labelColor');
        $('#reservations_form .form-group > label').attr('class','labelUp');


        $('#guest_id').parent().attr('class','form-group col-12');
        $('#newUserButton').html('');

        $('.modal-title').text('Foglalás szerkesztése');

        ajaxRequestGuests();

        $.ajax({
            url:"index.php?controller=roomBooking&action=fetchSingle",
            method:"POST",
            data:{
                room_booking_id:$(this).attr('id'),
            },
            dataType:"json",
            success:function(data)
            {
                $('#reservationsModal').modal('show');

                $('#guest_id').val(data.guest_id);
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

    $(document).on('click', '.check', function(){
        let room_booking_id = $(this).attr("id");
        $.ajax({
            url:"index.php?controller=roomBooking&action=check",
            method:"POST",
            data:{room_booking_id:room_booking_id},
            success:function(data)
            {

                dataTable.ajax.reload();
            }
        });
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



    $(document).on('click', '#newUser', function(){
        $('#reservationsModal').modal('hide');
        $('#guestModal').modal('show');
        $('last_name').val();
        $('first_name').val();
        $('email').val();
        $('phone_number').val();
        $('#operationUser').val("Regisztrál");
        $('.modal-title').text("Vendég regisztrálása");
    });



    $(document).on('submit', '#guest_form', function(event){
        event.preventDefault();

        let emptyBoolean = true;

        let empty = ['last_name','first_name','email','phone_number'];

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
                url:"index.php?controller=guests&action=insert",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                success:function(data)
                {
                    $('#guest_form')[0].reset();
                    $('#guestModal').modal('hide');
                    $('#reservationsModal').modal('show');
                    $('.modal-title').text("Foglalás hozzáadás");
                    dataTable.ajax.reload();

                    ajaxRequestGuests();
                }
            });
        }
    });

    ['last_name','first_name','email','phone_number'].forEach(e => {
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

function ajaxRequestGuests() {
    $('#guest_id').html('');
    $.ajax({
        url:"index.php?controller=guests&action=findAll",
        type:"POST",
        dataType:"json",
        success:function(data)
        {
            $('#guest_id').html('<option default></option>');
            data.forEach(e => {
                $('#guest_id').append(`<option value="${e.guest_id}">${e.last_name} ${e.first_name}</option>`);
            });
        }
    });
}