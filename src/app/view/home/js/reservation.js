"use strict"

$(document).ready(function () {
    $(document).on('submit','form', function () {
        if ($('#start_date').val() < today) alert('Hibás kitöltés!');
        if ($('#end_date').val() < tomorrow) alert('Hibás kitöltés!');
        if ($('#adult').val() < 1) alert('Hibás kitöltés!');
        if ($('#child').val() < 0) alert('Hibás kitöltés!');
    });

    let now = new Date();
    let day = ("0" + now.getDate()).slice(-2);
    let day1 = ("0" + (now.getDate()+1)).slice(-2);
    let month = ("0" + (now.getMonth() + 1)).slice(-2);
    let today = now.getFullYear()+"-"+(month)+"-"+(day);
    let tomorrow = now.getFullYear()+"-"+(month)+"-"+(day1);

    $('#start_date').attr('min',today);
    $('#start_date').attr('value',today);
    $('#end_date').attr('min',tomorrow);
    $('#end_date').attr('value',tomorrow);

    let adult = 1;
    let child = 0;
    document.querySelector('#guestNumber').value = adult+" felnőtt, "+child+" gyerek";
    document.querySelector('#guestNumber').onclick = function() {
        if(document.querySelector('#guests').style.display == 'none') {
            document.querySelector('#guests').style.display = 'block';
        } else {
            document.querySelector('#guests').style.display = 'none';
        }
    };
    document.querySelector('#adultNumber').onclick = function() {
        adult = this.value;
        document.querySelector('#guestNumber').value = adult+" felnőtt, "+child+" gyerek";
    };
    document.querySelector('#childNumber').onclick = function() {
        child = this.value;
        document.querySelector('#guestNumber').value = adult+" felnőtt, "+child+" gyerek";
    };



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
});