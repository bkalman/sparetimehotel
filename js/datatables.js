
"use strict"

$(document).ready(function(){
    /*if (window.location.href == 'index.php?controller=hyperlink&action=employees&update=true') {
        console.log('Sikeres változtatás!');
    } else if (window.location.href == 'index.php?controller=hyperlink&action=employees&update=false') {
        console.log('Hibás jelszó!');
    }*/

    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('.modal-title').text("Munkavállaló Felvétel");
        $('#action').val("Felvétel");
        $('#operation').val("Felvétel");
        $('#user_form .form-group input, #user_form .form-group select').attr('class','form-control');

        $('#user_form label').removeClass('labelUp labelColor');
        $('#user_form label[for=job_id]').addClass('labelUp');

        $('.modal-body label[for=password]').text('Jelszó');
    });

    let dataTable = $('#user_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"index.php?controller=employees&action=fetch",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[2,3,4,5,6],
                "orderable":false,
            },
        ],

    });

    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();

        let emptyBoolean = true;

        let empty = ['#first_name','#last_name','#email','#phone_number','#job_id','#zip','#city','#street_address','#house_number'];

        if($('#operation').val() != 'Változtat' && $('#password').val() == '' && $('#password_repeat').val() == '')
            empty.push('#password_repeat', '#password');

        if($('#password').val() != $('#password_repeat').val()) { alert('Nem egyeznek a jelszavak!'); emptyBoolean = false; }


        if (emptyBoolean == true) {
            empty.forEach(e => {
                if ($(e).val() != '') {
                    $(e).removeClass('is-invalid');
                    $(e).addClass('is-valid');
                } else {
                    emptyBoolean = $('.modal-body label[for=password]').text() == 'Új jelszó' && $('.modal-body #password').val() == $('.modal-body #password_repeat').val() ? true : false;
                    $(e).addClass('is-invalid');
                    $(e).removeClass('is-valid');
                }
            });
        }

        if (emptyBoolean == true) {
            $.ajax({
                url:"index.php?controller=employees&action=insert",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false,
                success:function(data)
                {
                    alert(data);
                    $('#user_form')[0].reset();
                    $('#userModal').modal('hide');
                    dataTable.ajax.reload();
                }
            });
        }
    });

    $(document).on('click', '.update', function(){
        let employee_id = $(this).attr("id");
        $('#user_form .form-group input, #user_form .form-group select').attr('class','form-control');
        $('#user_form label').removeClass('labelColor');
        $('#user_form label').addClass('labelUp');

        $('.modal-body label[for=password]').text('Új jelszó');

        $.ajax({
            url:"index.php?controller=employees&action=update",
            method:"POST",
            data:{employee_id:employee_id},
            dataType:"json",
            success:function(data)
            {
                $('#userModal').modal('show');
                $('#first_name').val(data.first_name);
                $('#last_name').val(data.last_name);
                $('#email').val(data.email);
                $('#phone_number').val(data.phone_number);
                $('#job_id').val(data.job_id);
                $('#zip').val(data.zip);
                $('#city').val(data.city);
                $('#street_address').val(data.street_address);
                $('#house_number').val(data.house_number);
                $('#floor_door').val(data.floor_door);
                $('#password').val(data.password);
                $('.modal-title').text("Adatok szerkesztése");
                $('#employee_id').val(employee_id);
                $('#user_uploaded_image').html(data.user_image);
                $('#action').val("Változtat");
                $('#operation').val("Változtat");
            }
        })
    });

    $(document).on('click', '.delete', function(){
        var employee_id = $(this).attr("id");
        if(confirm("Biztosan törölni szeretné?"))
        {
            $.ajax({
                url:"index.php?controller=employees&action=delete",
                method:"POST",
                data:{employee_id:employee_id},
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

    let inputs = ['last_name', 'first_name', 'job_id', 'email', 'phone_number', 'zip', 'city', 'street_address', 'house_number', 'floor_door', 'password', 'password_repeat'];
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



