let now = new Date();
let day = ("0" + now.getDate()).slice(-2);
let day1 = ("0" + (now.getDate()+1)).slice(-2);
let month = ("0" + (now.getMonth() + 1)).slice(-2);
let today = now.getFullYear()+"-"+(month)+"-"+(day);
let tomorrow = now.getFullYear()+"-"+(month)+"-"+(day1);
document.querySelector('#startDate').value = today;
document.querySelector('#endDate').value = tomorrow;

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