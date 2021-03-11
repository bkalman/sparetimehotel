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
<script src="js/conf.js"></script>
<script>

    $(document).on('change','select#guestName',function () {
        window.location.assign('index.php?controller=function&action=attendanceSheet&id='+document.querySelector('#guestName').value);
        /*var xhttp;
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.querySelector("body").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "index.php?controller=function&action=attendanceSheet&id="+str, true);
        xhttp.send();*/
    });

    /*function sort(s) {
        $ .ajax ({
            url: 'index.php?controller=function&action=attendanceSheet',
            type: 'post',
            data: { "sort": s},
            success: function(response) { console.log(response); }
        });
    }

    $(document).ready(function () {
       $(document).on('click','#column-sort',function () {
          let columnName = $(this).attr("id");
          let sort = $(this).attr("sort");
          let arrow = '';
          if(sort == 'desc') {
              arrow = `<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-sort-alpha-down" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10.082 5.629L9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371h-1.781zm1.57-.785L11 2.687h-.047l-.652 2.157h1.351z"/>
                            <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645V14zM4.5 2.5a.5.5 0 0 0-1 0v9.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L4.5 12.293V2.5z"/>
                       </svg>`;
          }else{
              arrow = `<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-sort-alpha-up" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10.082 5.629L9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371h-1.781zm1.57-.785L11 2.687h-.047l-.652 2.157h1.351z"/>
                            <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645V14zm-8.46-.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707V13.5z"/>
                       </svg>`;
          }
          $.ajax({
             url:"sort.php",
             method:"post",
             data:{columnName:columnName, sort:sort},
              success:function (data) {
                 $('.table').html(data);
                 $('#'+columnName+'').append(arrow);
              }
          });
       });
    });*/

    let date = new Date();
    let year = date.getFullYear();
    month = date.getMonth()+1;

    while(year >= 2015) {
        document.querySelector("select#year").innerHTML += `<option value="` + year + `">` + year + `.</option>`;
        year--;
    }

        while (month >= 1) {
            document.querySelector("select#month").innerHTML += `<option value="` + month + `">` + month + `.</option>`;
            month--;
        }

    $(document).on('change','select#year',function () {
        document.querySelector("select#month").innerHTML="";
        month = (document.querySelector("select#year").value < date.getFullYear()) ? 12 : date.getMonth()+1;

        while (month >= 1) {
            document.querySelector("select#month").innerHTML += `<option value="` + month + `">` + month + `.</option>`;
            month--;
        }
    });

    $(document).on('change','select#month',function () {
        window.location.assign('index.php?controller=function&action=attendanceSheet&id='+document.querySelector('#guestName').value+'&date='+document.querySelector('#year').value+document.querySelector('#month').value);
    });
</script>
</body>
</html>