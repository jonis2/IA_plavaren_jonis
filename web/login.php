<?php
include('data.php');
hlavicka('Registrácia');
?>
    <section>
      <div class="container">
      <?php
        if (isset($_SESSION['register']) && !$_SESSION['register']){ 
          echo '<p class="lead"> Registrácia neúspešná skúste znovu</p>';
        }
        if (isset($_SESSION['register']) &&   $_SESSION['register']){
          echo '<p class="lead"> Registrácia úspešná môžete sa prihlásiť</p>';
          unset($_SESSION['register']);
        } else {
      ?>
        <div class="row">
              <div class="col-md-12 col-xs-12 text-center">
                <h2>Zadajte svoje nové údaje</h2>
              </div>
        </div>
        <form class="form-horizontal" role="form">
          <div class="form-group">
            <label class="control-label col-md-2 col-xs-12" for="name">Prihlasovacie meno:</label>
            <div class="col-md-10 col-xs-12">
              <input type="input" class="form-control" name="name" id="name" placeholder="Zadajete prihlasovacie meno">
            </div>
            
            <label class="control-label col-md-2 col-xs-12" for="email">Email:</label>
            <div class="col-md-10 col-xs-12">
              <input type="email" class="form-control" name="email" id="email" placeholder="Zadajte email">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2 col-xs-12" for="pwd">Heslo:</label>
            <div class="col-md-10 col-xs-12"> 
              <input type="password" class="form-control" name="pwd" id="pwd" placeholder="Zadajte heslo">
            </div>
            
            <label class="control-label col-md-2 col-xs-12" for="rpwd">Heslo - Znovu:</label>
            <div class="col-md-10 col-xs-12"> 
              <input type="password" class="form-control" name="rpwd" id="rpwd" placeholder="Zopakujte heslo">
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-md-offset-6 col-md-6 col-xs-offset-6 col-xs-6">
              <button type="submit" name="register" class="btn btn-default">Zaregistrovať</button>
            </div>
          </div>
        </form>
        <?php
        }
        ?>
      </div>
    </section>
<?php
pata();
?>                                                      