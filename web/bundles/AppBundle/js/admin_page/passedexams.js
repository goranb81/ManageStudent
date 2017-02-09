           /*
    * This is javascript file for /admin/passedexams page
    * relate to file Resources/vies/admin/passedexams.html.twig
    */
        
        
        var table = null;

        $(document).ready(function () {
            table = $('#datatables').DataTable();
            element_on();
        });
        
        function element_on() {
            del_on();
            edit_on();
            add_on();
        }

        //this function belongs earlier version. 
        //this isn't using
        function element_off() {
            $(document).off("click", "#delete");
            $(document).off("click", "#edit");
            $(document).off("click", "#savebtn");
            $(document).off("click", "#addsavebtn");
            $(document).off('click', '#add');
        }
        
        function del_on() {
            $("#datatables tbody").on("click", "#delete", function () {
                // confirmation dialog box
                confirmAction("Are you sure to delete passed exam?", null, null, $(this));
            });
        }

        function edit_on() {

            // temp vars for table row data
            var student_name = null;
            var exam_name = null;
            var pass_date = null;
            var mark = null;
            var id = null;

            // jQuery object which contain row element(tr)
            var $tr_edit = null;

            //var which contain row index of edit row
            var rowindex = null;

            //get row's index and data
            $("#datatables tbody").on("click", "tr", function (event) {
                rowindex = table.row(this).index();
            });

            // click on edit button            
            $('#datatables tbody').on("click", "#edit", function (event) {

                //get data from table row 
                $tr_edit = $(this).parent().parent();

                student_name = $tr_edit.children('.name-field-stname').html();
                exam_name = $tr_edit.children('.name-field-exname').html();
                pass_date = $tr_edit.children('.date-field').html();
                mark = $tr_edit.children('.mark-field').html();
                id = $(this).attr("data-id");

                //fill modal input's value
                $('#editModal #student-label').html(student_name);
                $('#editModal #exam-label').html(exam_name);
                $('#editModal #birth-date-input').val(pass_date);
                $('#editModal #mark-number-input').val(mark);

                $('#editModal').modal('show');
            });

            // save edit data 
            $(document).on("click", "#savebtn", function (event) {
                //event.stopPropagation();
                // confirmation dialog box
                confirmAction("Are you sure to edit passed exam?", rowindex, id, $(this));
            });

        }

        function add_on() {

            $(document).on('click', '#add', function (event) {

                // get all student and exams info
                $.ajax({
                    url: $(this).data('url'),
                    type: 'post',
                    dataType: 'json',
                    data: {}

                }).done(function (response) {
                    //fill modal with students and exams info
                    //add students
                    var studentList = document.getElementById('student_id');
                    var option = null;
                    for (i = 0; i < response.students.length; i++) {
                        option = document.createElement('option');
                        option.textContent = response.students[i].name;
                        option.setAttribute('value', response.students[i].id);
                        studentList.appendChild(option);
                    }

                    //add exams
                    var examsList = document.getElementById('exam_id');
                    for (i = 0; i < response.exams.length; i++) {
                        option = document.createElement('option');
                        option.textContent = response.exams[i].name;
                        option.setAttribute('value', response.exams[i].id);
                        examsList.appendChild(option);
                    }

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert('Error : ' + errorThrown);
                });

                // show modal
                $('#addModal').modal('show');
            });


            $('#addModal').on("click", "#addsavebtn", function (event) {
                // confirmation dialog box
                confirmAction("Are you sure to add passed exam?", null, null, $(this));
            });

        }

        // validate addModal and editModal data
        function validate(passdate, type) {

            //check if date string is correct
            if (isNaN(Date.parse(passdate))) {
                bootbox.alert("Enter valid date!");
                return false;
            } else {
                switch (type) {
                    case 'edit':
                        bootbox.alert("You successfully edit passedexam!");
                        return true;
                        break;
                    case 'add':
                        bootbox.alert("You successfully add passedexam!");
                        return true;
                        break;
                }
            }


        }

        //return HTML for <tr>
        function create_row(student_name, exam_name, date, mark, exam_id) {
            var row = '<tr>';
            row += '<td class="name-field-stname">' + student_name + '</td>';
            row += '<td class="name-field-exname">' + exam_name + '</td>';
            row += '<td class="date-field">' + date + '</td>';
            row += '<td class="mark-field">' + mark + '</td>';
            row += '<td><button id="edit" class="btn edit" data-toggle="modal" data-id="' + exam_id + '" >Edit</button></td>';
            row += '<td> <button id="delete" class="btn " data-id="' + exam_id + '">Delete</button></td>';
            row += '<tr>';
            return row;
        }
       
        // comfirmation dialog box
        function confirmAction(message, rowindex, id, selected_button) {
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
                        case "Are you sure to add passed exam?":
                            applyToTableAndAjaxAdd(result,selected_button);
                            break;
                        case "Are you sure to edit passed exam?":
                            applyToTableAndAjaxEdit(result, rowindex, id, selected_button);
                            break;
                        case "Are you sure to delete passed exam?":
                            applyToTableAndAjaxDelete(result, selected_button);
                            break;
                    }
                }
            });
        }

        // this function will call when edit confirmation has value YES
        // add data to table and call ajax
        function applyToTableAndAjaxEdit(r, rowindex, id, selected_button) {
            //if we confirm edit(r = true) call ajax and save changes into DB
            if (r == true) {

                //get data from modal's inputs
                //student_name = $('#editModal #student-label').html();
                //exam_name = $('#editModal #exam-label').html();
                pass_date = $('#editModal #birth-date-input').val();
                mark = $('#mark-number-input').val();

                if (validate(pass_date, 'edit')) {
                    //hide modal
                    $('#editModal').modal('hide');

                    //edit table row
                    var tdate = table.cell(rowindex, 2);
                    tdate.data(pass_date).draw();
                    var tmark = table.cell(rowindex, 3);
                    tmark.data(mark).draw();

                    $.ajax({
                        url: selected_button.data('url'),
                        type: 'post',
                        dataType: 'json',
                        data: {id: id, date: pass_date, mark: mark}

                    }).done(function (response) {

                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        alert('Error : ' + errorThrown);
                    });

                }

            }
        }

        function applyToTableAndAjaxAdd(r,selected_button) {
            //if we confirm add(r = true) call ajax and save data into DB
            if (r == true) {
                //get data from modal input fields
                var vstudentid = $('#addModal #student_id').val();
                var student_name = $('#student_id option:selected').text();
                var vexamid = $('#addModal #exam_id').val();
                var exam_name = $('#exam_id option:selected').text();

                var vdate = $('#addModal #add-exam-date').val();
                var vmark = $('#addModal #mark-input').val();

                //validate addModal form data
                if (validate(vdate, 'add')) {
                    $("#addModal").modal('hide');
                    $.ajax({
                        url: selected_button.data('url'),
                        type: 'post',
                        dataType: 'json',
                        //data: {studentid: vstudentid, examid: vexamid, date: vdate, mark:vmark, studentname: vstudentname, examname: vexamname}
                        data: {studentid: vstudentid, examid: vexamid, date: vdate, mark: vmark}

                    }).done(function (response) {
                        //add row into Passedexams table
                        var t_row = create_row(student_name, exam_name, vdate, vmark, response.id);
                        table.row.add($(t_row)).draw();
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        alert('Error : ' + errorThrown);
                    });

                }
            }
        }

        function applyToTableAndAjaxDelete(r, selected_del_button) {
            if (r === true) {
                //delete row 
                table.row(selected_del_button.parents('tr')).remove().draw();

                $.ajax({
                    url: selected_del_button.data('url'),
                    type: 'post',
                    dataType: 'json',
                    data: {id: selected_del_button.attr("data-id")}
                }).done(function (response) {

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert('Error : ' + errorThrown);
                });
            }
        }


