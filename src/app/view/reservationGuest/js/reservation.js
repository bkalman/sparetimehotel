"use strict"

$(document).ready(function (){
    $(document).on('submit', 'form#done_form', function(event){
        event.preventDefault();

        let emptyBoolean = true;

        ['#last_name','#first_name','#email','#phone_number'].forEach(e => {
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
                url:"index.php?controller=reservationGuest&action=done",
                method:'POST',
                data:new FormData(this),
                contentType:false,
                processData:false
            });
            alert('Sikeres szobafoglalás!');
            window.location.replace('index.php');
        } else alert('Töltsön ki mindegyik mezőt!');
    });


});