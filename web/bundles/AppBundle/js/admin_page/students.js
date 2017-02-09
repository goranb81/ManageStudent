   /*
    * This is javascript file for /admin/students page
    * relate to file Resources/vies/admin/students.html.twig
    */
   
   var id = 0;
        var table = null;
        
        // apply DataTable to table
        // attach selectors to HTML
        $(document).ready(function () {
            table = $("#datatables").DataTable();
            element_on();
        });
        
         // detach selectors from HTML
        //this function belongs earlier version. 
        //this isn't using
        function element_off() {
            $(document).off('click', '#delete');
            $(document).off("click", "#edit");
            $(document).off("click", "#savebtn");
            $(document).off("click", "#addsavebtn");
            $(document).off('click', '#add');
        }

        // attach selectors to HTML 
        function element_on() {
            del_on();
            edit_on();
            add_on();
        }

        function del_on() {
            $('#datatables tbody').on("click", "#delete", function () {
                confirmAction("Are you sure to delete student?", $(this), null, null);
            });
        }

        function edit_on() {
            //temp var which contain modal input fields
            var vname = null;
            var vdate = null;
            var id = null;

            //jQuery object which contain row element(tr)
            var $tr_edit = null;

            //temp var which contain edit row index 
            var rowindex = null;

            //get row's index and data
            $("#datatables tbody").on("click", "tr", function (event) {
                rowindex = table.row(this).index();
            });

            //open editModal form
            $(document).on("click", "#edit", function (event) {           
                //get reference to the jQuery object which contain row element(tr)
                $tr_edit = $(this).parent().parent();
                //get name and birthdate from table row
                vname = $tr_edit.children(".name-field").html();
                vdateofbirth = $tr_edit.children(".date-field").html();
                id = $(this).attr("data-id");

                //fill modal inputs               
                $("#editModal #name-text-input").val(vname);
                $("#editModal #birth-date-input").val(vdateofbirth);

                $('#editModal').modal('show');
            });

            //save student edit modifications  
            $(document).on("click", "#savebtn", function (event) {
                //confirm dialog box
                confirmAction("Are you sure to edit student?", $(this), id, rowindex);
            });
        }

        function add_on() {

            //clear modal input fields
            $("#addModal").on("hide.bs.modal", function () {
                $(this).find("input").val('').end();
            });

            // add button trigger modal showing
            $(document).on('click', '#add', function (event) {
                //document.getElementById("add-text-input").value = 'Enter students name';
                $('#addModal').modal('show');

            });

            // save button
            $(document).on("click", "#addsavebtn", function (event) {
                //confirmation dialog box
                confirmAction("Are you sure to add student?", $(this), null, null);
            });

        }

       

        //return HTML for <tr>
        function create_row(name, date, id) {
            var row = '<tr>';
            row += '<td class="name-field">' + name + '</td>';
            row += '<td class="date-field">' + date + '</td>';
            row += '<td><button id="edit" class="btn edit" data-toggle="modal" data-id="' + id + '" >Edit</button></td>';
            row += '<td> <button id="delete" class="btn " data-id="' + id + '">Delete</button></td>';
            row += '<tr>';
            return row;
        }

        //check string to contain only characters
        function allLetter(inputtxt)
        {
            var letters = /^[A-Za-z]+$/;
            if (inputtxt.match(letters))
            {
                //alert('Your name have accepted : you can try another');
                return true;
            } else
            {
                //alert('Please input alphabet characters only');
                return false;
            }
        }

        // validate addModal and editModal data
        function validate(name, date, type) {

            var lower_limit_date = new Date('1950-01-01');
            var upper_limit_date = new Date('1998-01-01');

            //check if date string is correct
            if (isNaN(Date.parse(date))) {
                bootbox.alert("Enter valid date!");
                return false;
            }
            var tdate = new Date(date);

            if (name == '') {
                bootbox.alert("Enter name!");
                return false;
            } else if (name.length < 3) {
                bootbox.alert("Name must have more than 2 characters!");
                return false;
            } else if (!allLetter(name)) {
                bootbox.alert("Name must contains only characters!");
                return false;
            } else if (tdate.getTime() < lower_limit_date.getTime()) {
                bootbox.alert("Student is too older for university! Student must born after 1950");
                return false;
            } else if (tdate.getTime() > upper_limit_date.getTime()) {
                bootbox.alert("Student is too young for university! Student must born before 1998");
                return false;
            } else {
                switch (type) {
                    case 'edit':
                        bootbox.alert("You successfully edit student!");
                        return true;
                        break;
                    case 'add':
                        bootbox.alert("You successfully enter student!");
                        return true;
                        break;
                }
            }

        }

        // comfirmation dialog box
        function confirmAction(message, selected_button, id, rowindex) {
            bootbox.confirm({
                message: message,
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    switch (message) {
                        case "Are you sure to add student?":
                            applyToTableAndAjaxAdd(result,selected_button);
                            break;
                        case "Are you sure to edit student?":
                            applyToTableAndAjaxEdit(result, id, rowindex, selected_button);
                            break;
                        case "Are you sure to delete student?":
                            applyToTableAndAjaxDelete(result, selected_button);
                            break;
                    }
                }
            });
        }

        function applyToTableAndAjaxDelete(r, selected_button) {
            if (r === true) {
                //delete row
                table.row(selected_button.parents('tr')).remove().draw();
                
                // delete from DB throw Ajax
                $.ajax({
                    url: selected_button.data('url'),
                    type: 'post',
                    dataType: 'json',
                    data: {id: selected_button.attr("data-id")}
                }).done(function (response) {

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert('Error : ' + errorThrown);
                });
            }
        }

        function applyToTableAndAjaxEdit(r, id, rowindex, selected_button) {
            if (r === true) {
                // get data from modal's inputs
                vname = $("#editModal #name-text-input").val();
                vdate = $("#editModal #birth-date-input").val();

                //validate editMOdal form
                if (validate(vname, vdate, 'edit')) {
                    //hide edit modal
                    $('#editModal.in').modal('hide');

                    //edit table row
                    var name = table.cell(rowindex, 0);
                    name.data(vname).draw();
                    var date = table.cell(rowindex, 1);
                    date.data(vdate).draw();

                    // edit in DB throw Ajax
                    $.ajax({
                        url: selected_button.data('url'),
                        type: 'post',
                        dataType: 'json',
                        data: {id: id, name: vname, date: vdate}

                    }).done(function (response) {

                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        alert('Error : ' + errorThrown);
                    });

                }

            }
        }

        function applyToTableAndAjaxAdd(r, selected_button) {
            if (r === true) {
                // get data from modal's inputs
                vname = $("#addModal #add-text-input").val();
                vdate = $("#addModal #add-date-input").val();

                // validate data
                if (validate(vname, vdate, 'add')) {
                    //hide modal
                    $("#addModal.in").modal('hide');

                    $.ajax({
                        url: selected_button.data('url'),
                        type: 'post',
                        dataType: 'json',
                        data: {name: vname, date: vdate}

                    }).done(function (response) {
                        //add new row into Students table
                        var t_row = create_row(vname, vdate, response.id);
                        table.row.add($(t_row)).draw();
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        alert('Error : ' + errorThrown);
                    });
                }
            }
        }