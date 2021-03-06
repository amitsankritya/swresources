@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text text-success mb-3">Fetch & Save</div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header sw-card-header"><strong>Fetch Star Wars Films and Peoples</strong></div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <form id="sw-resource-form" onsubmit="return false">
                                <div class="justify-content-center row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-check-label"><strong>Resource Type:</strong></label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" name="resource_type" type="radio" id="input-film" value="film">
                                                    <label class="form-check-label" for="input-film">Film</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" name="resource_type" type="radio" id="input-character" value="people">
                                                    <label class="form-check-label" for="input-character">People</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <input id="resource_id" type="text" class="form-control" name="resource_id" value="" placeholder="Resource Id (eg. 1)">
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" id="fetch" class="btn btn-outline-primary">Get</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4" id="resources" style="display: none">
                <div class="card-header sw-card-header">
                    <strong style="vertical-align: -moz-middle-with-baseline; vertical-align: middle">Available Resources</strong>
                    <button type="button" id="save" class="btn btn-outline-success float-right" data-resource="" data-resource_id="" onclick="saveResources(this)">Save Data</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div style="text-align: center; display: none" id="spinner">
                                <div class="spinner-border text-info" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="resource-table" class="table table-bordered table-hover text-center">
                                    <thead class="thead-dark">

                                    </thead>
                                    <tbody id="film-tbody">

                                    </tbody>
                                </table>
                            </div>
                        </div>
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
                    $("#save").data("resource", resource);
                    $("#save").data("resource_id", resource_id);
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

        function saveResources(obj) {
            let resource = $(obj).data("resource");
            let resource_id = $(obj).data("resource_id");
            console.log(resource_id);

            let url = '{{ url('/') }}';

            if(resource === 'film') {
                if (resource_id > 7) {
                    //TODO: show error message
                    return;
                }
                url += '/films';
            } else {
                if (resource_id > 87) {
                    //TODO: show error message
                    return;
                }

                url += '/characters';
            }

            $.ajax({
                method: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "resource_id": resource_id
                },
                success: function (response) {

                },

                error: function (xhr) {
                    $("#fetch").attr("disabled", false);
                    $("#fetch").text("Get");
                    console.log("error");
                    console.log(xhr);
                }
            });
        }
    </script>
@endsection