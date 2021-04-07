<section id="container">
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1>Üdvözöljük a szállodánk honlapján!</h1>
                <p></p>
            </div>
            <div class="col-12">
                <h3>Foglalás előtti tudnivalók</h3>
                <p>Szállodánkban három fő étkezés van MEGADOTT időpontban:</p>
                <ul class="ml-5">
                    <li>reggeli 08:30</li>
                    <li>ebéd 13:00</li>
                    <li>vacsora 19:00</li>
                </ul>
                <p>Az időpontok nem változtathatóak, de a nap bármelyik szakában látogatható a büfé.</p>
            </div>
        </div>
        <form action="index.php?controller=reservationGuest&action=date" method="post" id="reservation">
            <div class="row">
                <div class="col-12">
                    <h2>Foglalás megkezdése</h2>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <label for="start">Bejelentkezés</label>
                            <input type="date" name="reservation[start_date]" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="end">Kijelentkezés</label>
                            <input type="date" name="reservation[end_date]" id="end_date" class="form-control">
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="adult">Felnőtt</label>
                                    <input type="number" name="reservation[adult]" id="adult" class="form-control" value="1" min="1">
                                </div>
                                <div class="col-lg-6">
                                    <label for="child">Gyermek</label>
                                    <input type="number" name="reservation[child]" id="child" class="form-control" value="0" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="">Foglalás</label>
                            <input type="submit" value="Tovább" class="btn">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>