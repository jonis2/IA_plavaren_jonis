<?php
include('data.php');
hlavicka('Kalendár');
?>
      <section>
        <div class="container">
          <div class="container">
            <div class="row">
              <div class="col-xs-12 col-md-12 text-center">
                <h3>Rozvrh</h3>
              </div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-md-3 col-xs-12">
                <div class="container">
                  <div id="datepicker"></div>
                </div>
              </div>
              <div class="col-md-7 col-xs-12">
                  <div id="calendar_info">
                    <div class="alert alert-info">
                      <strong>Zvoľte deň</strong>
                    </div>
                  </div>
              </div>
            </div> 
          </div>
        </div>
      </section>
    <script>
    function procesResult(res){
      $("#calendar_info").empty();
      if (res == "ce"){
          $("#calendar_info").append(
          '<div class="alert alert-danger text-center"> <strong>Pri spojení s databázou nastala chyba!</strong></div>'
          );
          return;
      }
      if (res == "er"){
          $("#calendar_info").append(
          '<div class="alert alert-warning text-center"> <strong>V zovelný deň nie je plaváren otvorená!</strong></div>'
          );
          return;
      }
      data = res.split("&");
      message = '<div class="alert alert-success text-center"> <strong>Otvorené : ';
      message += data[1].substr(0, 5);
      message += ' - ';
      message += data[2].substr(0, 5);
      message +=  '</strong></div>';
      $("#calendar_info").append(message);
      $("#calendar_info").append(
      '<table id="time" class="table"><thead><tr><th class="lead text-center">Čas</th><th class="lead text-center">Voľné dráhy</th><th class="lead text-center">Obsadené dráhy</th></tr></thead></table>'
      );
      start = parseInt(data[1].substr(0, 2));
      close = parseInt(data[2].substr(0, 2));
      tbody = '<tbody>'; 
      for ( i = start;i < close;i++){
        tbody += '<tr class="success">';
        tbody += '<td class="lead text-center">';
        tbody += i.toString() + ":" +data[1].substr( 3, 2);
        tbody += '</td>'
        tbody += '<td class="lead text-center">';
        tbody += data[3];
        tbody += '</td>'
        tbody += '<td class="lead text-center">';
        tbody += data[4];
        tbody += '</td>';
        tbody += '</r>';
      }
      tbody += '</tbody>';
      $("#time").append(tbody);
    }
    function onDateSelect(value){     
      $.ajax({
        		  url: "get_date.php",
        			dataType: 'html',
        			data: "date="+value,
        		  success: function(arg){
                procesResult(arg);   
        		  },
        			error: function () { 
                alert('Ajax error on Date slecet from database'); 
              }
        	});    
    }
    $(function() {
      $( "#datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: 0,
        todayHighlight: true,
        onSelect: function(dateText, inst) { 
           onDateSelect(dateText);
        }
      });
    });
    </script>
<?php
pata();
?>                                                      