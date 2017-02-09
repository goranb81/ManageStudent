    /*
    * This is javascript file for /users/exams page
    * relate to file Resources/vies/users/exams.html.twig
    */
         
        var id = 0;
        var table = null;
        var table1 = null;

        $(document).ready(function () {
            table = $("#datatables").DataTable();
            table1 = $("#datatables1").DataTable();
            all_results();

        });

        function all_results() {
            //clear modal table
            $("#tableModal.in").on("show.bs.modal", function () {
                table1.clear();
            });

            $(document).on("click", "#exam-link", function () {
                //ajax call to return all student's passed exams
                $.ajax({
                    url: $(this).data('url'),
                    type: 'post',
                    dataType: 'json',
                    data: {id: $(this).attr("data-id")}
                }).done(function (response) {


                    $('#tableModal').modal('show');
                    fill_table(response);

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert('Error : ' + errorThrown);
                });
            });

        }

        function fill_table(json_content) {
            content = json_content;

            var item = null;
            for (i = 0; i < content.length; i++) {
                item = content[i];
                add_row(item);
            }

        }

        function add_row(item) {
            var t_row = create_row(item);
            table1.row.add($(t_row)).draw();
        }

        //return HTML for <tr>
        function create_row(item) {
            // item.datepass has value in this format 2011-10-10T00:00:00+02:00
            // we split that value into two parts 2011-10-10 and 00:00:00+02:00
            // use only first part
            var dateParts = item.datepass.split('T');

            var row = '<tr>';
            row += '<td class="name-field">' + item.studentName + '</td>';
            row += '<td class="date-field">' + dateParts[0] + '</td>';
            row += '<td class="date-field">' + item.mark + '</td>';
            row += '<tr>';
            return row;
        }

        


