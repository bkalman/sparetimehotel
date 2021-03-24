<?php include('src/app/view/template/startTemplate.php'); ?>
<?php include('src/app/view/template/navbar.php'); ?>

<?= /** @var index $content */ $content; ?>

<footer>
    <p>A honlapot Kálmán Bence készítette</p>
</footer>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>

<script src="js/jquery.datatables.min.js"></script>
<script src="js/reservation.js"></script>
<!--<script src="js/datatables.js"></script>-->
<script>

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
                url:"index.php?controller=restaurant&action=fetch",
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
                    url:"index.php?controller=restaurant&action=orderInsert",
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
                url:"index.php?controller=restaurant&action=fetchSingle",
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
                    url:"index.php?controller=restaurant&action=delete",
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

</script>
</body>
</html>