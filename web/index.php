<?php
include('data.php');
hlavicka('Kalendár');
?>
      <section>
        <div class="row">
          <div class="col-sm-12 text-center">
            <h3>Rozvrh</h3>
          </div>
        </div>
          <div class="row">
            <div class="col-md-4 col-xs-12">
              <div class="container">
                <div id="datepicker"></div>
              </div>
            </div>
            <div class="col-md-8 col-xs-12">
                <div id="calendar_info">
                  <div class="alert alert-info">
                    <strong>Info!</strong> Indicates a neutral informative change or action.
                  </div>
                </div>
            </div>
          </div>
      </section>
    <script>
    function onDateSelect(value){     
      $.ajax({
        		  url: "get_date.php",
        			dataType: 'html',
        			data: "date="+value,
        		  success: function(arg){
                    
                   $("#calendar_info").empty()
                   $("#calendar_info").append("<p>"+arg+"</p>")
        		  },
        			error: function () { 
                alert('Ajax error'); 
              }
        	});    
    }
    $(function() {
      $( "#datepicker" ).datepicker({
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