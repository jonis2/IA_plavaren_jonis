<?php
include('data.php');
hlavicka('Správa skpuín');
?>
    <section>
      <div class="container">
      <?php
        if (isset($_SESSION['login']) && !$_SESSION['admin'] && $_SESSION['login']){
            group_res();
            get_groups( $_SESSION['id']);
            if (isset($_GET['del'])) {
              delte_group();
              get_groups( $_SESSION['id']);
            }
            add_group();
            get_groups( $_SESSION['id']);
            if (isset($_GET['id']))
            {
              group_managment();
            }
            else {
      ?>
      <h2>Vaše skupiny</h2>
      <div class="table-responsive">
      <table class="table">
       <thead>
        <tr>
          <th>Názov skupiny</th>
          <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
          if (isset($_SESSION['groups']) && $_SESSION['groups'] != null){
            for ($i = 0;$i <   sizeof($_SESSION['groups']);$i ++){
              echo "<tr>";
              echo  '<td><a href="gropu_admin.php?id='.$i.'">'.$_SESSION['groups'][$i]['name'].'</a></td>';
              echo '<td><a href="gropu_admin.php?del='.$_SESSION['groups'][$i]['id'].'"><span class="glyphicon glyphicon-remove"></span></a></td>';
              echo "</tr>";
            }
          }    
        ?>
        </tbody>
      </table>
      </div>
      <?php
          form_add_group();
          }
        } else {
          echo '<p class="lead">Prístup omietnutý</p>';
        }
      ?>
      </div>
    </section>
<?php
pata();
?>                                                      