<style TYPE="text/css"> 

.strong {
    font-weight: bold;
} 

</style>

<div class="main-content" >
<div id="breadcrumbs" class="breadcrumbs">
 <script type="text/javascript">
  try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
 </script>
 <ul class="breadcrumb">
  <li>
   <i class="icon-home home-icon"></i>
   <a href="#">Home</a>
   <span class="divider">
    <i class="icon-angle-right arrow-icon"></i>
   </span>
  </li>
  <li class="active"><?php echo lang('reports');?></li>
 </ul>
</div>

<div class="page-header position-relative">
                <h1>
                    <?php echo lang("curriculum");?>
                    <small>
                        <i class="icon-double-angle-right"></i>
                        Unitats Formatives
                    </small>
                </h1>
</div><!-- /.page-header -->

<div style='height:10px;'></div>
	<div style="margin:10px;">
   		



      <script>

      function isPositiveInteger(n) {
		return 0 === n % (!isNaN(parseFloat(n)) && 0 <= ~~n);
	  }

      $(function(){


			  $.editable.addInputType('datepicker', {
				    element: function(settings, original) {
				        var input = $('<input/>');
				        $(this).append(input);
				        return (input);
				    },
				    plugin: function(settings, original) {
				        settings.onblur = 'ignore';
				        $(this).find('input').datepicker({
				            'autoclose': true,
				            format: 'dd-mm-yyyy',
				            weekStart: 1,
      						todayBtn: true,
      						language: "ca",
      						daysOfWeekDisabled: "0,6",
      					    todayHighlight: true
				        });
				    }
				});

			  	$('.editable_num').editable(
			  		function(value, settings) {

			  			if (!isPositiveInteger(value)) {
			  				alert ("El valor que heu proporcionat no és un enter positiu!");
			  				return "";
			  			}
			  			//Debug:

						/*console.debug(this);
     					console.debug(value);
     					console.debug(settings);
     					*/

     					new_total_hours = value;
     					study_submodule_id = this.getAttribute("studysubmoduleid");

     					//console.debug("new_total_hours: " . new_total_hours);
     					//console.debug("study_submodule_id: " . study_submodule_id);

     					$.ajax({
				            url:'<?php echo base_url("index.php/managment/change_study_submodule_total_hours");?>',
				            type: 'post',
				            data: {
				                new_total_hours : new_total_hours,
				                study_submodule_id : study_submodule_id,
				            },
				            datatype: 'json',
				            statusCode: {
				                  404: function() {
				                    $.gritter.add({
				                      title: 'Error connectant amb el servidor!',
				                      text: 'No s\'ha pogut contactar amb el servidor. Error 404 not found. URL: index.php/enrollment/change_study_submodule_total_hours' ,
				                      class_name: 'gritter-error'
				                    });
				                    skip_forward_step = true;
				                  },
				                  500: function() {
				                    $("#response").html('A server-side error has occurred.');
				                    $.gritter.add({
				                      title: 'Error connectant amb el servidor!',
				                      text: 'No s\'ha pogut contactar amb el servidor. Error 500 Internal Server error. URL: index.php/enrollment/change_study_submodule_total_hours' ,
				                      class_name: 'gritter-error'
				                    });
				                    skip_forward_step = true;
				                  }
				                },
				                error: function() {
				                  $.gritter.add({
				                      title: 'Error!',
				                      text: 'Ha succeït un error!' ,
				                      class_name: 'gritter-error'
				                    });
				                },
				          }).done(function(data){
				          		var all_data = $.parseJSON(data);

							    //console.debug (all_data);

							    result = all_data.result;
							    result_message = all_data.message;

							    if (result) {
							      console.debug(result_message);
							    } else {
							      $.gritter.add({
							        title: 'Error guardant la incidència a la base de dades!',
							        text: 'No s\'ha pogut guardar la incidència. Missatge d\'error:  ' + result_message ,
							        class_name: 'gritter-error'
							      });
							    }
				             
				          });


						$("#" + this.id).addClass("strong");
				        return value;
				    });

				$('.editable_initialDate').editable(
				    function(value, settings) {
				        //Debug:

						/*console.debug(this);
     					console.debug(value);
     					console.debug(settings);
     					*/

						var selected_academic_period_initial_date = "<?php echo $selected_academic_period_initial_date;?>";
						var selected_academic_period_initial_date_array = selected_academic_period_initial_date.split("-"); 
						var _selected_academic_period_initial_date_object= new Date(selected_academic_period_initial_date_array[0], parseInt(selected_academic_period_initial_date_array[1],10)-1, parseInt(selected_academic_period_initial_date_array[2],10), 0, 0, 0, 0); 
						var european_format_selected_academic_period_initial_date = selected_academic_period_initial_date_array[2] +  "-" + selected_academic_period_initial_date_array[1] + "-" + selected_academic_period_initial_date_array[0];

						var selected_academic_period_final_date = "<?php echo $selected_academic_period_final_date;?>";
						var selected_academic_period_final_date_array = selected_academic_period_final_date.split("-"); 
						var _selected_academic_period_final_date_object= new Date(selected_academic_period_final_date_array[0], parseInt(selected_academic_period_final_date_array[1],10)-1, parseInt(selected_academic_period_final_date_array[2],10), 0, 0, 0, 0); 
						var european_format_selected_academic_period_final_date = selected_academic_period_final_date_array[2] +  "-" + selected_academic_period_final_date_array[1] + "-" + selected_academic_period_final_date_array[0];

						new_initialDate = value;
     					var new_initialDate_array = new_initialDate.split("-"); 
     						/* DEBUG:
							console.debug("day: " + new_initialDate_array[0]);
							console.debug("month: " + new_initialDate_array[1]);
							console.debug("Year: "  + new_initialDate_array[2]);
							*/
						var _new_initialDate_object= new Date(new_initialDate_array[2], parseInt(new_initialDate_array[1],10)-1, parseInt(new_initialDate_array[0],10), 0, 0, 0, 0); 	

						/*
						console.debug("_selected_academic_period_initial_date_object: " + _selected_academic_period_initial_date_object);
						console.debug("_selected_academic_period_final_date_object: " + _selected_academic_period_final_date_object);
						console.debug("_new_initialDate_object: " + _new_initialDate_object);
						*/


						if ( ( _new_initialDate_object < _selected_academic_period_initial_date_object ) ) {
							alert("La data inicial proposada no és dins del rang de dates vàlides (" + european_format_selected_academic_period_initial_date + " <-> " + european_format_selected_academic_period_final_date + ") del període acadèmic seleccionat!");
				            return "";
						}

						if ( ( _new_initialDate_object > _selected_academic_period_final_date_object ) ) {
							alert("La data inicial proposada no és dins del rang de dates vàlides (" + european_format_selected_academic_period_initial_date + " <-> " + european_format_selected_academic_period_final_date + ") del període acadèmic seleccionat!");
				            return "";
						}

     					study_submodule_id = this.getAttribute("studysubmoduleid");

     					var finalDateValue = $('#final_date_' + study_submodule_id).text().trim();

     					//console.debug("finalDateValue: " + finalDateValue);
     					if (finalDateValue != "") {
     						//console.debug("finalDateValue: " + finalDateValue);
     						//Compare dates
							var new_finalDate_array = finalDateValue.split("-"); 
	     					var _new_finalDate_object = new Date(new_finalDate_array[2], parseInt(new_finalDate_array[1],10)-1, parseInt(new_finalDate_array[0],10), 0, 0, 0, 0); 

     						//console.debug("_new_initialDate_object:" + _new_initialDate_object.toDateString());
     						//console.debug("_new_finalDate_object:" + _new_finalDate_object.toDateString());
							if (_new_initialDate_object > _new_finalDate_object) {
				            	alert("La data inicial és posterior a la data final! No podeu fer aquesta modificació");
				            	return "";
				            }	
     					}

     					//console.debug("new_initialDate: " + new_initialDate);
     					//console.debug("study_submodule_id: " . study_submodule_id);

     					$.ajax({
				            url:'<?php echo base_url("index.php/managment/change_study_submodule_initial_date");?>',
				            type: 'post',
				            data: {
				                new_initialDate : new_initialDate,
				                study_submodule_id : study_submodule_id,
				            },
				            datatype: 'json',
				            statusCode: {
				                  404: function() {
				                    $.gritter.add({
				                      title: 'Error connectant amb el servidor!',
				                      text: 'No s\'ha pogut contactar amb el servidor. Error 404 not found. URL: index.php/enrollment/change_study_submodule_initial_date' ,
				                      class_name: 'gritter-error'
				                    });
				                    skip_forward_step = true;
				                  },
				                  500: function() {
				                    $("#response").html('A server-side error has occurred.');
				                    $.gritter.add({
				                      title: 'Error connectant amb el servidor!',
				                      text: 'No s\'ha pogut contactar amb el servidor. Error 500 Internal Server error. URL: index.php/enrollment/change_study_submodule_initial_date' ,
				                      class_name: 'gritter-error'
				                    });
				                    skip_forward_step = true;
				                  }
				                },
				                error: function() {
				                  $.gritter.add({
				                      title: 'Error!',
				                      text: 'Ha succeït un error!' ,
				                      class_name: 'gritter-error'
				                    });
				                },
				          }).done(function(data){
				          		var all_data = $.parseJSON(data);

							    //console.debug (all_data);

							    result = all_data.result;
							    result_message = all_data.message;

							    if (result) {
							      console.debug(result_message);
							    } else {
							      $.gritter.add({
							        title: 'Error guardant la incidència a la base de dades!',
							        text: 'No s\'ha pogut guardar la incidència. Missatge d\'error:  ' + result_message ,
							        class_name: 'gritter-error'
							      });
							    }
				             
				          });

				        $("#" + this.id).addClass("strong");  
				        return value;
				    }, {
				        type: 'datepicker',
				        indicator: 'Guardant...',
				        tooltip: 'Feu click per editar...',
				        placeholder: '<span class="muted">click per editar...</span>',
				        cancel: '<button class="btn btn-mini btn-forced-margin" type="cancel" >Cancel</button>',
				        submit: '<button class="btn btn-mini btn-primary btn-forced-margin" type="submit" >Save</button>',
				        style: 'display: inline;',
				        width: 'none',
				    }
				);

				

				$('.editable_finalDate').editable(
				    function(value, settings) {
				        //Debug:

						/*console.debug(this);
     					console.debug(value);
     					console.debug(settings);
     					*/

						var selected_academic_period_initial_date = "<?php echo $selected_academic_period_initial_date;?>";
						var selected_academic_period_initial_date_array = selected_academic_period_initial_date.split("-"); 
						var _selected_academic_period_initial_date_object= new Date(selected_academic_period_initial_date_array[0], parseInt(selected_academic_period_initial_date_array[1],10)-1, parseInt(selected_academic_period_initial_date_array[2],10), 0, 0, 0, 0); 
						var european_format_selected_academic_period_initial_date = selected_academic_period_initial_date_array[2] +  "-" + selected_academic_period_initial_date_array[1] + "-" + selected_academic_period_initial_date_array[0];

						var selected_academic_period_final_date = "<?php echo $selected_academic_period_final_date;?>";
						var selected_academic_period_final_date_array = selected_academic_period_final_date.split("-"); 
						var _selected_academic_period_final_date_object= new Date(selected_academic_period_final_date_array[0], parseInt(selected_academic_period_final_date_array[1],10)-1, parseInt(selected_academic_period_final_date_array[2],10), 0, 0, 0, 0); 
						var european_format_selected_academic_period_final_date = selected_academic_period_final_date_array[2] +  "-" + selected_academic_period_final_date_array[1] + "-" + selected_academic_period_final_date_array[0];


     					new_finalDate = value;
     					var new_finalDate_array = new_finalDate.split("-"); 							
							/* DEBUG:
							console.debug("day: " + new_finalDate_array[0]);
							console.debug("month: " + new_finalDate_array[1]);
							console.debug("Year: "  + new_finalDate_array[2]);
							*/
						var _new_finalDate_object= new Date(new_finalDate_array[2], parseInt(new_finalDate_array[1],10)-1, parseInt(new_finalDate_array[0],10), 0, 0, 0, 0);
							
     					study_submodule_id = this.getAttribute("studysubmoduleid");

     					//console.debug("new_finalDate: " . new_finalDate);
     					//console.debug("study_submodule_id: " . study_submodule_id);

     					var initialDateValue = $('#initial_date_' + study_submodule_id).text().trim();

     					if ( ( _new_finalDate_object < _selected_academic_period_initial_date_object ) ) {
							alert("La data inicial proposada no és dins del rang de dates vàlides (" + european_format_selected_academic_period_initial_date + " <-> " + european_format_selected_academic_period_final_date + ") del període acadèmic seleccionat!");
				            return "";
						}

						if ( ( _new_finalDate_object > _selected_academic_period_final_date_object ) ) {
							alert("La data inicial proposada no és dins del rang de dates vàlides (" + european_format_selected_academic_period_initial_date + " <-> " + european_format_selected_academic_period_final_date + ") del període acadèmic seleccionat!");
				            return "";
						}

     					//console.debug("initialDateValue: " + initialDateValue);
     					if (initialDateValue != "") {
     						//console.debug("initialDateValue: " + initialDateValue);
     						//Compare dates
							var new_initialDate_array = initialDateValue.split("-"); 
	     					var _new_initialDate_object = new Date(new_initialDate_array[2], parseInt(new_initialDate_array[1],10)-1, parseInt(new_initialDate_array[0],10), 0, 0, 0, 0); 

     						//console.debug("_new_initialDate_object:" + _new_initialDate_object.toDateString());
     						//console.debug("_new_finalDate_object:" + _new_finalDate_object.toDateString());
							if (_new_finalDate_object < _new_initialDate_object) {
				            	alert("La data final és anterior a la data inicial! No podeu fer aquesta modificació");
				            	return "";
				            }	
     					}

     					$.ajax({
				            url:'<?php echo base_url("index.php/managment/change_study_submodule_final_date");?>',
				            type: 'post',
				            data: {
				                new_finalDate : new_finalDate,
				                study_submodule_id : study_submodule_id,
				            },
				            datatype: 'json',
				            statusCode: {
				                  404: function() {
				                    $.gritter.add({
				                      title: 'Error connectant amb el servidor!',
				                      text: 'No s\'ha pogut contactar amb el servidor. Error 404 not found. URL: index.php/enrollment/change_study_submodule_final_date' ,
				                      class_name: 'gritter-error'
				                    });
				                    skip_forward_step = true;
				                  },
				                  500: function() {
				                    $("#response").html('A server-side error has occurred.');
				                    $.gritter.add({
				                      title: 'Error connectant amb el servidor!',
				                      text: 'No s\'ha pogut contactar amb el servidor. Error 500 Internal Server error. URL: index.php/enrollment/change_study_submodule_final_date' ,
				                      class_name: 'gritter-error'
				                    });
				                    skip_forward_step = true;
				                  }
				                },
				                error: function() {
				                  $.gritter.add({
				                      title: 'Error!',
				                      text: 'Ha succeït un error!' ,
				                      class_name: 'gritter-error'
				                    });
				                },
				          }).done(function(data){
				          		var all_data = $.parseJSON(data);

							    //console.debug (all_data);

							    result = all_data.result;
							    result_message = all_data.message;

							    if (result) {
							      console.debug(result_message);
							    } else {
							      $.gritter.add({
							        title: 'Error guardant la incidència a la base de dades!',
							        text: 'No s\'ha pogut guardar la incidència. Missatge d\'error:  ' + result_message ,
							        class_name: 'gritter-error'
							      });
							    }
				             
				          });

				        $("#" + this.id).addClass("strong");  
				        return value;
				    }, {
				        type: 'datepicker',
				        indicator: 'Guardant...',
				        tooltip: 'Feu click per editar...',
				        placeholder: '<span class="muted">Feu click per editar...</span>',
				        cancel: '<button class="btn btn-mini btn-forced-margin" type="cancel" >Cancel</button>',
				        submit: '<button class="btn btn-mini btn-primary btn-forced-margin" type="submit" >Save</button>',
				        style: 'display: inline;',
				        width: 'none',
				    }
				);
              

              //Jquery select plugin: http://ivaynberg.github.io/select2/
              $("#select_study_submodules_academic_period_filter").select2();

              $('#select_study_submodules_academic_period_filter').on("change", function(e) {  
                  var selectedValue = $("#select_study_submodules_academic_period_filter").select2("val");
                  var pathArray = window.location.pathname.split( '/' );
                  var secondLevelLocation = pathArray[1];
                  var baseURL = window.location.protocol + "//" + window.location.host + "/" + secondLevelLocation + "/index.php/managment/study_submodules_dates";
                  //alert(baseURL + "/" + selectedValue);
                  window.location.href = baseURL + "/" + selectedValue;

              });


              $("#teacher").select2({width: 'resolve', placeholder: "Seleccioneu un professor", allowClear: true }); 

			  $('#teacher').on("change", function(e) { 
			    teacher_code = $("#teacher").select2("val");
			    console.debug("teacher_code:" + teacher_code);

			    //TODO
			    academic_period_id= 5;

			    if (teacher_code == "") {
			    	teacher_code="void";
			    }

			    var pathArray = window.location.pathname.split( '/' );
			    var secondLevelLocation = pathArray[1];
			    var baseURL = window.location.protocol + "//" + window.location.host + "/" + secondLevelLocation + "/index.php/managment/study_submodules_dates";
			    //alert(baseURL + "/" + teacher_code);
			    window.location.href = baseURL + "/" + academic_period_id + "/" + teacher_code;

			  });

              var all_study_submodules_table = $('#all_study_submodules').DataTable( {
                      "columnDefs": [
                                      { "type": "html", "targets": 3 }
                                    ],
                      "aLengthMenu": [[10, 25, 50,100,200,-1], [10, 25, 50,100,200, "<?php echo lang('All');?>"]],                      
                              "oTableTools": {
                      "sSwfPath": "<?php echo base_url('assets/grocery_crud/themes/datatables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf');?>",
                              "aButtons": [
                                      {
                                              "sExtends": "copy",
                                              "sButtonText": "<?php echo lang("Copy");?>"
                                      },
                                      {
                                              "sExtends": "csv",
                                              "sButtonText": "CSV"
                                      },
                                      {
                                              "sExtends": "xls",
                                              "sButtonText": "XLS"
                                      },
                                      {
                                              "sExtends": "pdf",
                                              "sPdfOrientation": "landscape",
                                              "sPdfMessage": "<?php echo lang("all_study_submodules");?>",
                                              "sTitle": "TODO",
                                              "sButtonText": "PDF"
                                      },
                                      {
                                              "sExtends": "print",
                                              "sButtonText": "<?php echo lang("Print");?>"
                                      },
                              ]

              },
              "iDisplayLength": 100,
                "oLanguage": {
                        "sProcessing":   "Processant...",
                        "sLengthMenu":   "Mostra _MENU_ registres",
                        "sZeroRecords":  "No s'han trobat registres.",
                        "sInfo":         "Mostrant de _START_ a _END_ de _TOTAL_ registres",
                        "sInfoEmpty":    "Mostrant de 0 a 0 de 0 registres",
                        "sInfoFiltered": "(filtrat de _MAX_ total registres)",
                        "sInfoPostFix":  "",
                        "sSearch":       "Filtrar:",
                        "sUrl":          "",
                        "oPaginate": {
                                "sFirst":    "Primer",
                                "sPrevious": "Anterior",
                                "sNext":     "Següent", 
                                "sLast":     "Últim"    
                        }
            }
             
        }); 

});
</script>

<div class="container">

<table class="table table-striped table-bordered table-hover table-condensed" id="all_study_submodules_filter">
  <thead style="background-color: #d9edf7;">
    <tr>
      <td colspan="6" style="text-align: center;"> <h4>Filtres
        </h4></td>
    </tr>
    <tr> 
       <td><?php echo lang('study_submodules_academic_period')?>: </td>
       <td>
          <select id="select_study_submodules_academic_period_filter">
          <?php foreach ($academic_periods as $academic_period_key => $academic_period_value) : ?>

            <?php if ( $selected_academic_period_id) : ?>
              <?php if ( $academic_period_key == $selected_academic_period_id) : ?>
                <option selected="selected" value="<?php echo $academic_period_key ;?>"><?php echo $academic_period_value->shortname ;?></option>
              <?php else: ?>
                  <option value="<?php echo $academic_period_key ;?>"><?php echo $academic_period_value->shortname ;?></option>
              <?php endif; ?>
            <?php else: ?>   
                <?php if ( $academic_period_value->current == 1) : ?>
                  <option selected="selected" value="<?php echo $academic_period_key ;?>"><?php echo $academic_period_value->shortname ;?></option>
                <?php else: ?>
                  <option value="<?php echo $academic_period_key ;?>"><?php echo $academic_period_value->shortname ;?></option>
                <?php endif; ?> 
            <?php endif; ?> 


          <?php endforeach; ?>
          </select>
       </td>
       <td><?php echo lang('lesson_study_code')?>:</td>
       <td>
        <select id="select_all_study_submodules_study_code_filter"><option value=""></option></select>
      </td>
       <td><?php echo lang('lesson_course_code')?>:</td>
       <td>
        <select id="select_all_study_submodules_course_code_filter">
          <option value=""></option>
        </select>
      </td>
    </tr>

    <tr> 
       <td><?php echo lang('lesson_classroom_group')?>:</td>
       <td>
        <select id="select_all_study_submodules_classroomgroup_filter">
          <option value=""></option>
        </select>
       </td>       
      <td><?php echo lang('lesson_study_module')?>:</td>
      <td>
        <select id="select_all_study_submodules_study_module_filter"><option value=""></option></select>
      </td>
       <td><?php echo lang('lesson_teacher')?>:</td>
       <td>
       	<!-- id="select_all_study_submodules_teacher_filter" -->
         <select id="teacher" style="width: 400px">
  		   <option></option>
		   <?php foreach( (array) $teachers as $teacher_id => $teacher_name): ?>
				   <?php if( $teacher_id == $default_teacher): ?>
		            <option value="<?php echo $teacher_id; ?>" selected="selected"><?php echo $teacher_name; ?></option>
		           <?php else: ?> 
		            <option value="<?php echo $teacher_id; ?>" ><?php echo $teacher_name; ?></option>
		           <?php endif; ?> 
		   <?php endforeach; ?>	
		  </select> 
      </td>
    </tr>
  </thead>  
</table> 

<table class="table table-striped table-bordered table-hover table-condensed" id="all_study_submodules">
 <thead style="background-color: #d9edf7;">
  <tr>
    <td colspan="11" style="text-align: center;"> <h4>
      <a href="<?php echo base_url('/index.php/managment/study_submodules_dates') ;?>">
        <?php echo $study_submodules_table_title?>
      </a>
      </h4></td>
  </tr>
  <tr>
     <th>&nbsp;</th>
     <th><?php echo lang('study_submodules_id')?></th>
     <th><?php echo lang('study_submodules_shortname')?></th>
     <th><?php echo lang('study_submodules_name')?></th>
     <th><?php echo lang('study_submodules_module')?></th>
     <th><?php echo lang('study_submodules_course')?></th>
     <th><?php echo lang('study_submodules_study')?></th>
     <th><?php echo lang('study_submodules_order')?></th>     
     <th><?php echo lang('study_submodules_initialDate')?></th>
     <th><?php echo lang('study_submodules_endDate')?></th>
     <th><?php echo lang('study_submodules_academic_periods_totalHours')?></th>
  </tr>
 </thead>
 <tbody> 

  <!-- Iteration that shows study_submodules-->

  <?php if (is_array($all_study_submodules) ) : ?>
  <?php foreach ($all_study_submodules as $study_submodule_key => $study_submodule) : ?>
   <tr align="center" class="{cycle values='tr0,tr1'}">   
     <td><label><input class="ace" type="checkbox" name="form-field-checkbox" id="<?php echo $study_submodule->id;?>"><span class="lbl">&nbsp;</span></label></td>
     <td>
      <a href="<?php echo base_url('/index.php/curriculum/study_submodules/read/' . $study_submodule->id ) ;?>">
          <?php echo $study_submodule->id;?>
      </a> 
      (<a href="<?php echo base_url('/index.php/curriculum/study_submodules/edit/' . $study_submodule->id ) ;?>">
          edit
      </a>)
     </td>

     <td>
        <?php echo $study_submodule->shortname;?>
     </td>

     <td>
        <?php echo $study_submodule->name;?>
     </td>


     <td>
      <a href="<?php echo base_url('/index.php/curriculum/study_module/read/' . $study_submodule->module_id ) ;?>">
          <?php echo $study_submodule->module_shortname . ". " . $study_submodule->module_name ;?>
      </a> 
      (<a href="<?php echo base_url('/index.php/curriculum/study_module/edit/' . $study_submodule->module_id ) ;?>">
          <?php echo $study_submodule->module_id;?>
      </a> )
     </td>

     <td>
      <a href="<?php echo base_url('/index.php/curriculum/course/read/' . $study_submodule->course_id ) ;?>">
          <?php echo $study_submodule->course_shortname . ". " . $study_submodule->course_name ;?>
      </a> 
      (<a href="<?php echo base_url('/index.php/curriculum/course/edit/' . $study_submodule->course_id ) ;?>">
          <?php echo $study_submodule->course_id;?>
      </a> )
     </td>

     <td>
      <a href="<?php echo base_url('/index.php/curriculum/studies/read/' . $study_submodule->study_id ) ;?>">
          <?php echo $study_submodule->study_shortname . ". " . $study_submodule->study_name . ". " . $study_submodule->study_law_shortname . " " . $study_submodule->study_law_name ;?>
      </a> 
      (<a href="<?php echo base_url('/index.php/curriculum/studies/edit/' . $study_submodule->study_id ) ;?>">
          <?php echo $study_submodule->study_id;?>
      </a> )
     </td>

	 <td>
        <?php echo $study_submodule->study_submodules_order;?>
     </td> 

     <td>
        <span id="initial_date_<?php echo $study_submodule->id;?>" studysubmoduleid="<?php echo $study_submodule->id;?>"class="editable_initialDate" title="Feu click per editar la data">
        	<?php 
        	if ($study_submodule->study_submodule_initialDate == "0000-00-00") {
        		echo "";
        	} else {
        		echo date('d-m-Y', strtotime($study_submodule->study_submodule_initialDate));
        	}
        	?>        	
        </span>
        	<?php 
        	if ($study_submodule->study_submodule_initialDate == "0000-00-00") {
        		echo '<br/><i class="icon-warning-sign red bigger-130" title="Feu click per editar la data!"></i>';
        	} else {
        		echo '<br/><i class="icon-warning-sign red bigger-130" title="Feu click per editar la data!"></i><span title="Feu click per editar la data"> Editable</span>';
        	}
        	?>      
     </td>

     <td>
     	<span id="final_date_<?php echo $study_submodule->id;?>" studysubmoduleid="<?php echo $study_submodule->id;?>"class="editable_finalDate" title="Feu click per editar la data">
        	<?php 
        	if ($study_submodule->study_submodule_endDate == "0000-00-00") {
        		echo "";
        	} else {
        		echo date('d-m-Y', strtotime($study_submodule->study_submodule_endDate));
        	}
        	?>        	
        </span>
        	<?php 
        	if ($study_submodule->study_submodule_endDate == "0000-00-00") {
        		echo '<br/><i class="icon-warning-sign red bigger-130" title="Feu click per editar la data!"></i>';
        	} else {
        		echo '<br/><i class="icon-warning-sign red bigger-130" title="Feu click per editar la data!"></i><span title="Feu click per editar la data"> Editable</span>';
        	}
        	?>
     </td>

     <td>
        <span id="total_hours_<?php echo $study_submodule->id;?>" studysubmoduleid="<?php echo $study_submodule->id;?>" class="editable_num" title="Feu click per editar la data"><?php echo $study_submodule->study_submodules_totalHours;?></span>
        <br/><i class="icon-warning-sign red bigger-130" title="Feu click per editar la data!"></i><span title="Feu click per editar la data">Editable</span>
     </td>


   </tr>
  <?php endforeach; ?>
  <?php endif; ?>
 </tbody>
</table> 

</div>

<div class="space-30"></div>

	</div>	
</div>