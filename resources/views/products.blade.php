<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>
        <!-- Styles / Scripts -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">Products</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 card">

                    <div class="card-header">
                        <h4>Add new Product</h4>
                    </div>
                    <div class="card-body">
                        <form id="add_form" method="POST" >
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="product_name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" required>

                                </div>
                                <div class="col-md-3">
                                    <label for="quantity" class="form-label">Quantity in stock</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="product_price" class="form-label">Product Price</label>
                                    <input type="number" class="form-control" id="product_price" name="product_price" step=".01" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="submit" class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary form-control">Submit</button>
                                </div>
                                <div class="alert alert-success mt-3" id="success_msg" style="display: none">
                                    <strong>Success!</strong> Product added successfully.
                                </div>
                                <div class="alert alert-danger mt-3" id="error_msg" style="display: none">
                                    <strong>Success!</strong> <span id="msg"></span>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12 card">
                        <div class="card-header">
                            <h4>Product Lists</h4>
                        </div>
                        <table class="table table-striped table-hover">
                           <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Product Name</th>
                                    <th>Quantity in stock</th>
                                    <th>Price per item</th>
                                    <th>Datetime submitted</th>
                                    <th>Total value number</th>
                                    <th>Action</th>
                                </tr>
                           </thead>
                            <tbody id="table_data">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

{{--        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">--}}
{{--            <div class="modal-dialog">--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="modal-header">--}}
{{--                        <h5 class="modal-title" id="editModalLabel">Edit Product</h5>--}}
{{--                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        <form id="editProductForm" method="POST">--}}
{{--                            @csrf--}}
{{--                            <input type="hidden" id="product_id" name="product_id">--}}
{{--                            <div class="mb-3">--}}
{{--                                <label for="edit_product_name" class="form-label">Product Name</label>--}}
{{--                                <input type="text" class="form-control" id="edit_product_name" name="product_name">--}}
{{--                            </div>--}}
{{--                            <div class="mb-3">--}}
{{--                                <label for="edit_quantity" class="form-label">Quantity</label>--}}
{{--                                <input type="number" class="form-control" id="edit_quantity" name="quantity">--}}
{{--                            </div>--}}
{{--                            <div class="mb-3">--}}
{{--                                <label for="edit_product_price" class="form-label">Product Price</label>--}}
{{--                                <input type="number" class="form-control" id="edit_product_price" name="product_price" step="0.01">--}}
{{--                            </div>--}}
{{--                            <button type="submit" class="btn btn-primary">Save changes</button>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}



    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

        <script>
            $(document).ready(function(){
                fetchProducts();
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#add_form').submit(function(e){
                e.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url:"{{ route('add-product') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response){
                        if(response.status){
                            $('#success_msg').show();
                            $('#error_msg').hide();
                            fetchProducts();
                            $('#add_form').trigger('reset');

                        }
                    },
                    error: function(response){
                        $('#error_msg').show();
                        $('#success_msg').hide();
                        $('#msg').text(response.responseJSON.message);
                    }
                });
            });

            function fetchProducts() {
                    $.ajax({
                        url: "{{ route('fetch-products') }}",
                        type: 'GET',
                        success: function (response) {
                            $table_data = '';
                            $.each(response.products, function (key, value) {
                                $table_data += '<tr>';
                                $table_data += '<td>' + (key + 1) + '</td>';
                                $table_data += '<td>' + value.product_name + '</td>';
                                $table_data += '<td>' + value.quantity_in_stock + '</td>';
                                $table_data += '<td>' + value.price_per_item + '</td>';
                                $table_data += '<td>' + value.created_at + '</td>';
                                $table_data += '<td>' + (value.total_value_number) + '</td>';
                                $table_data += '<td><button class="btn btn-primary" onclick="editProduct(' + value.id + ')">Edit</button></td>';
                                $table_data += '</tr>';
                            });

                            $('#table_data').html($table_data);
                        },
                        error: function (response) {
                            $table_data = '<tr><td colspan="7" class="text-center">No data found</td></tr>';

                            $('#table_data').html($table_data);
                        }
                    });
                }
        </script>
    </body>
</html>
