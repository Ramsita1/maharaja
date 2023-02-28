<div class="page-body">
   <div class="row">
      <style type="text/css">
         #datepicker-container{
           text-align:center;
         }
         #datepicker-center{
           display:inline-block;
           margin:0 auto;
           width: 100%;
         }
         #storeHolidayDatePicker{
            width: 100%;
            display: inline-block;
         }
         #storeHolidayDatePicker .ui-datepicker-inline {
             width: 100%;
         }
         #storeHolidayDatePicker td a, #storeHolidayDatePicker td span, #storeHolidayDatePicker th a {
             padding: 20px;
             text-align: center;
         }
         #storeHolidayDatePicker td{
            border: none;
         }
         #storeHolidayDatePicker td a{
            border: none;
            color: #fff;
            background: green;           
         }
         #storeHolidayDatePicker th a{
            border: 1px solid;
         }
         #storeHolidayDatePicker td.ui-state-highlight a, #storeHolidayDatePicker td.selectedHighlight a, #storeHolidayDatePicker td.ui-state-disabled span{
            background: #ed1c24;
         }
         ul.datepickerColorSTatus {
             display: inline-block;
             width: 40%;
             margin: 0px auto;
         }
         ul.datepickerColorSTatus li {
             width: 33%;
             float: left;
             display: inline-block;
             padding: 5px;
             text-align: center;
         }
         ul.datepickerColorSTatus li span {
             width: 20px;
             height: 20px;
             display: inline-block;
             margin-top: 5px;
            border-radius: 50%;
         }
         ul.datepickerColorSTatus li span.green {
            background: green;
         }
         ul.datepickerColorSTatus li span.blue {
            background: #5f91fe;
         }
         ul.datepickerColorSTatus li span.red {
            background: #ed1c24;
         }
      </style>
      <div class="col-md-12 col-xl-12">
         <div class="card">
            <div class="card-block">
               <div class="card-block table-border-style">
                  <div class="row">
                     <div class="col-md-6">
                        <h5 class="m-b-10">Store Holidays</h5>
                     </div>
                     <div class="col-md-6">
                     </div>
                  </div>
                  <div id="datepicker-container">
                    <div id="datepicker-center">
                      <div id="storeHolidayDatePicker"></div>
                    </div>
                    <ul class="datepickerColorSTatus">
                       <li><span class="green"></span><br>Available</li>
                       <li><span class="blue"></span><br>Current Day</li>
                       <li><span class="red"></span><br>Holidays</li>
                    </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
   </div>
</div>
<div id="styleSelector">
</div>
<script type="text/javascript">
   jQuery(document).ready(function($) {
      var dats = $.parseJSON('<?php echo $holidays; ?>');
      function formatDate(date) {
          var d = new Date(date),
              month = '' + (d.getMonth() + 1),
              day = '' + d.getDate(),
              year = d.getFullYear();

          if (month.length < 2) 
              month = '0' + month;
          if (day.length < 2) 
              day = '0' + day;

          return [year, month, day].join('-');
      }
      function highlightDays(date) {
          for (var i = 0; i < dats.length; i++) {
              if (dats[i] == formatDate(date)) {
                  return [true, 'selectedHighlight'];
              }
          }
          return [true, ''];
      }
      function showDatePicker()
      {
        $('#storeHolidayDatePicker').multiDatesPicker({
           minDate: 1,
           dateFormat: "yy-mm-dd",
           onSelect: function(dateText, inst) {
              var date = formatDate();
              $.ajax({
                 url: '<?php echo route('storeHolidays.store') ?>',
                 type: 'POST',
                 data: {date: dateText},
              })
              .done(function(response) {
                 dats = $.parseJSON(response);
                 showDatePicker();
                 console.log(dats)
              });
           },
           beforeShowDay: highlightDays
        });
      }
      showDatePicker();
   });
</script>