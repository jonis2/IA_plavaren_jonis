<?php
include('data.php');
hlavicka('Správa rozvrhu');
?>
    <section>
      <div class="container">
      <?php
        if (isset($_SESSION['login']) && $_SESSION['admin']){
          if (isset( $_GET['add_date'])){ 
            try_insert_date($_GET['date'],$_GET['open'],$_GET['close'],$_GET['count']);
          }
      ?>
            <div class="container">
              <div class="row">
                <div class="col-xs-12 col-md-12 text-center">
                  <h3>Pridať deň</h3>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3 col-xs-12">
                <div class="container">
                  <div id="datepicker"></div>
                </div>
              </div>
              <div class="col-md-7 col-xs-12">
                <form class="form-horizontal" role="form">
                  <div class="form-group">
                    <label class="control-label col-md-2 col-xs-12" for="date">Dátum:</label>
                    <div class="col-md-10 col-xs-12">
                      <input type="input" class="form-control" name="date" id="date" value="" placeholder="Zvoľte dátum v kalendáry">
                    </div>
                    <label class="control-label col-md-2 col-xs-12" for="open">Otvotenie:</label>
                    <div class="col-md-10 col-xs-12">
                      <input type="input" class="form-control" name="open" id="open" placeholder="Klik">
                    </div>
                    <label class="control-label col-md-2 col-xs-12" for="close">Zatvorenie:</label>
                    <div class="col-md-10 col-xs-12">
                      <input type="input" class="form-control" name="close" id="close" placeholder="Klik">
                    </div>
                    <label class="control-label col-md-2 col-xs-12" for="count">Počet dráh:</label>
                    <div class="col-md-10 col-xs-12">
                      <input type="input" class="form-control" name="count" id="count" placeholder="Zadajte počet">
                    </div>
                    
                  </div
                  <div class="form-group"> 
                    <div class="col-md-offset-6 col-md-6 col-xs-offset-6 col-xs-6">
                      <button type="submit" name="add_date" class="btn btn-default">Pridať</button>
                    </div>
                  </div>
                </form>  
              </div>
            </div>
            <script type="text/javascript" src="tp/jquery.timepicker.js"></script>
            <link rel="stylesheet" type="text/css" href="tp/jquery.timepicker.css">
            <script>
              $(document).ready(function() {
                $("#open").timepicker({
                 timeFormat: 'HH:mm:ss',
                  minTime: '00:00:00',
                  maxHour: 17,
                  maxMinutes: 50,
                  startTime: new Date(0,0,0,0,0,0),
                  interval: 10
                });
                $("#close").timepicker({
                 timeFormat: 'HH:mm:ss',
                  minTime: '00:00:00',
                  maxHour: 23,
                  maxMinutes: 50,
                  startTime: new Date(0,0,0,15,0,0),
                  interval: 10
                });
                $( "#datepicker" ).datepicker({
                  dateFormat: 'yy-mm-dd',
                  minDate: 0,
                  todayHighlight: true,
                  onSelect: function(dateText, inst) { 
                    $( "#date" ).val(dateText);
                  }
                });
            });
          </script> 
      <?php
        } else {
          echo '<p class="lead">Prístup omietnutý</p>';
        }
      ?>
      </div>
    </section>
<?php
pata();
?>                                                      