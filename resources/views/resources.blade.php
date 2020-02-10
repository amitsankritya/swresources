@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text text-success mb-3">Saved Resources</div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header sw-card-header"><strong>Films</strong></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Title</th>
                                        <th>Director</th>
                                        <th>Producer</th>
                                        <th>Release Date</th>
                                        <th>Episode Id</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-4" id="resources">
                    <div class="card-header sw-card-header"><strong>Peoples</strong></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Birth Year</th>
                                    <th>Height</th>
                                    <th>Mass</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/additional-methods.min.js"></script>
    <script>
        $.validator.addMethod("resource_id_value", function(value) {
            return $("input[name='resource_type']:checked").val() == "film" && value <= 7 || $("input[name='resource_type']:checked").val() == "people" && value <= 87;
        }, $.validator.format("Please enter between 0 and 7 for film and between 0 and 87 for people"));

        $("#sw-resource-form").validate({
            rules: {
                resource: {
                    required: true,
                },
                resource_id: {
                    required: true,
                    digits: true,
                    resource_id_value: true,
                    min: 0,
                    max: 87,
                }
            },
            messages : {
                resource: {
                    required: "Please choose any resource you want to fetch",
                },
                resource_id: {
                    required: "Please enter selected resource id",
                    digits: "Please enter digits only",
                    resource_id_value: "Please enter up to 7 for film and up to 87 for people",
                    min: "Please enter a value greater than or equal to 0",
                    max: "Please enter a value lower than or equal to 87"
                }
            },
            submitHandler: function() {
                $("#save").attr("disabled", true);
                $("#fetch").attr("disabled", true);
                $("#fetch").html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Loading...');
                getResources();
            }

        });

        function getResources() {
            let url = "https:/swapi.co/api/";
            let resource = $("input[name='resource_type']:checked").val();
            let resource_id = $("#resource_id").val();

            if (resource == "film") {
                url = url + "films/";
            } else {
                url += "people/";
            }

            if (resource_id > 0) {
                url = url + resource_id;
            }

            $.ajax({
                method: "GET",
                url: url,
                success: function (response) {

                    console.log(response);
                    $("#resource-table > thead > tr").remove();
                    $("#resource-table thead").append(getTableHeader(resource));

                    let row = "";
                    if (resource_id == 0 && !$.isEmptyObject(response.results)) {
                        $.each(response.results, function (index, value) {
                            if (resource == "film") {
                                row += '<tr>';
                                row += '<td>' + value.title + '</td>';
                                row += '<td>' + value.director + '</td>';
                                row += '<td>' + value.producer + '</td>';
                                row += '<td>' + value.release_date + '</td>';
                                row += '<td>' + value.episode_id + '</td></tr>';
                            } else {
                                row += '<tr>';
                                row += '<td>' + value.name + '</td>';
                                row += '<td>' + value.birth_year + '</td>';
                                row += '<td>' + value.height + '</td>';
                                row += '<td>' + value.gender + '</td>';
                                row += '<td>' + value.hair_color + '</td></tr>';
                            }
                        });
                    } else if (!$.isEmptyObject(response)) {
                        if (resource == "film") {
                            row = '<tr>';
                            row += '<td>' + response.title + '</td>';
                            row += '<td>' + response.director + '</td>';
                            row += '<td>' + response.producer + '</td>';
                            row += '<td>' + response.release_date + '</td>';
                            row += '<td>' + response.episode_id + '</td></tr>';
                        } else {
                            row += '<tr>';
                            row += '<td>' + response.name + '</td>';
                            row += '<td>' + response.birth_year + '</td>';
                            row += '<td>' + response.height + '</td>';
                            row += '<td>' + response.gender + '</td>';
                            row += '<td>' + response.hair_color + '</td></tr>';
                        }
                    }

                    if (row.length > 0) {
                        console.log(row);
                        $("#resource-table > tbody > tr").remove();
                        $("#resource-table tbody").append(row);
                    }

                    $("#fetch").attr("disabled", false);
                    $("#fetch").text("Get");
                    $("#save").attr("disabled", false);
                    $("#resources").show();
                },

                error: function (xhr) {
                    $("#fetch").attr("disabled", false);
                    $("#fetch").text("Get");
                    console.log("error");
                    console.log(xhr);
                }
            });
        }

        function getTableHeader(resource_type) {
            let thead = "";
            if (resource_type == "film") {
                thead += '<tr>';
                thead += '<th>Title</th>';
                thead += '<th>Director</th>';
                thead += '<th>Producer</th>';
                thead += '<th>Release Date</th>';
                thead += '<th>Episode Id</th>';
                thead += '</tr>';
            } else {
                thead += '<tr>';
                thead += '<th>Name</th>';
                thead += '<th>Birth Year</th>';
                thead += '<th>Height</th>';
                thead += '<th>Gender</th>';
                thead += '<th>Hair Color</th>';
                thead += '</tr>';
            }

            return thead;
        }

        function saveResource() {

        }
    </script>
@endsection