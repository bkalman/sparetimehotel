<?php //include('src/app/view/template/startTemplate.php'); ?>
<?php //include('src/app/view/template/navbar.php'); ?>
<section id="container">
    <div class="container my-5">
        <form action="" id="reservation">
            <div class="row">
                <div class="col-12">
                    <h2>Foglalás megkezdése</h2>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <label for="start">Bejelentkezés</label>
                            <input type="date" name="startDate" id="startDate" class="form-control">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="end">Kijelentkezés</label>
                            <input type="date" name="endDate" id="endDate" class="form-control">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="guestNumber">Vendég</label>
                            <input type="text" name="guestNumber" id="guestNumber" class="form-control" onkeydown="return false">
                            <div id="guests">
                                <table>
                                    <tr>
                                        <td>
                                            Felnőtt
                                        </td>
                                        <td>
                                            <input type="number" name="adultNumber" id="adultNumber" class="form-control" value="1" min="1" onkeydown="return false">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><hr></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Gyerek(4 éven aluli)
                                        </td>
                                        <td>
                                            <input type="number" name="childNumber" id="childNumber" class="form-control" value="0" min="0" onkeydown="return false">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="submit" value="Foglalás" class="btn">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form action="" id="choice">
            <div class="row">
                <div class="col-12">
                    <h2>Szoba választás</h2>
                </div>
                <div class="offset-md-2 col-md-4">
                    <table>
                        <tr>
                            <td>
                                <input type="number" name="bedNumber" id="bedNumber" value="1" min="1" onkeydown="return false">
                            </td>
                            <td>
                                <label for="bedNumber">ágy</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="jacuzziCheck" id="jacuzziCheck">
                            </td>
                            <td>
                                <label for="jacuzziCheck">jakuzzi</label>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <table>
                        <tr>
                            <td>
                                <input type="number" name="bedNumber" id="bedNumber" value="1" min="1" onkeydown="return false">
                            </td>
                            <td>
                                <label for="bedNumber">parkoló hely</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="buffetBreakfast" id="buffetBreakfast">
                            </td>
                            <td>
                                <label for="buffetBreakfast">svédasztalos reggeli</label>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    #
                </div>
            </div>
        </form>
    </div>
</section>
<?php //include('src/app/view/template/endTemplate.php'); ?>