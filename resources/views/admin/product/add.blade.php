@extends('layouts.admin')
@section('title')
    Admin || Dashboard
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('bundles/dropzonejs/dropzone.css') }}">
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Add Product</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.product.list') }}" class="btn btn-primary">
                                        Product List</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" id="form" method="POST"
                                    enctype="multipart/form-data" action="{{ route('admin.product.insert') }}">
                                    @csrf
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="brand_id">Brand</label>
                                            <span class="text-danger">*</span>
                                            <select class="form-control" name="brand_id" id="brand_id">
                                                <option value="">Select</option>
                                                @foreach ($brand as $row)
                                                    <option value="{{ $row->id }}"
                                                        @if (old('brand_id') == $row->id) selected @endif>
                                                        {{ $row->brand }}</option>
                                                @endforeach
                                            </select>
                                            @error('brand_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="category_id">Category</label>
                                            <span class="text-danger">*</span>
                                            <select class="form-control" name="category_id" id="category_id">
                                                <option value="">Select</option>
                                                @foreach ($category as $row)
                                                    <option value="{{ $row->id }}"
                                                        @if (old('category_id') == $row->id) selected @endif>
                                                        {{ $row->category }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="product">Product</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="product" id="product"
                                                value="{{ old('product') }}" required>
                                            @error('product')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="points">Points</label>
                                            <span class="text-danger">*</span>
                                            <input type="number" class="form-control" name="points" id="points"
                                                value="{{ old('points') }}" required>
                                            @error('points')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="price">Price</label>
                                            <span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control" name="price"
                                                    id="price" step=".01" value="{{ old('price') }}"
                                                    required="">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        PKR
                                                    </div>
                                                </div>
                                            </div>
                                            @error('price')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="purchase_price">Purchase Price</label>
                                            <span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="purchase_price" id="purchase_price" step=".01"
                                                    value="{{ old('purchase_price') }}" required="">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        PKR
                                                    </div>
                                                </div>
                                            </div>
                                            @error('purchase_price')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="stock">Stock</label>
                                            <span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control" name="stock"
                                                    id="stock" value="{{ old('stock') }}" required="">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        Unit
                                                    </div>
                                                </div>
                                            </div>
                                            @error('stock')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <div class="form-group mt-2 mb-0">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" type="checkbox"
                                                                id="is_stock" name="is_stock" value="1"
                                                                {{ old('is_stock') == '1' ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="is_stock">
                                                                In Stock
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="discount">Discount</label>
                                            <span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="discount" id="discount" value="{{ old('discount') }}"
                                                    required="">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        %
                                                    </div>
                                                </div>
                                            </div>
                                            @error('discount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <div class="form-group mt-2 mb-0">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" type="checkbox"
                                                                id="is_discount" name="is_discount" value="1"
                                                                {{ old('is_discount') == '1' ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="is_discount">
                                                                In Discount
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="weight">Weight</label>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control" name="weight"
                                                    id="weight" value="{{ old('weight') }}" step="0.1">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        Kg
                                                    </div>
                                                </div>
                                            </div>
                                            @error('weight')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="description">Description</label>
                                            <input type="text" class="form-control" name="description"
                                                id="description" value="{{ old('description') }}" required="">
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row mb-3">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Product Feature Image</label>
                                                <input name="image" type="file" class="form-control"
                                                    accept="image/png, image/gif, image/jpeg, image/jpg" />
                                            </div>
                                            @error('image')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Product Image</label>
                                                <input id="file" name="file[]" type="file" class="form-control"
                                                    accept="image/png, image/gif, image/jpeg, image/jpg" multiple>
                                            </div>
                                            @error('file')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- working --}}
                                        {{-- <div class="col-md-12">
                                            <label>Images</label>
                                            <div class="dropzone" id="mydropzone">
                                                <div class="fallback">
                                                    <input name="file" type="file" multiple />
                                                </div>
                                            </div>
                                        </div>
                                          --}}
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label>Active</label>
                                            <div class="form-row mt-2">
                                                <div class="col-md-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox"
                                                            id="is_active" name="is_active" value="1"
                                                            {{ old('is_active') == '1' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_active">
                                                            Is Active
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Is Other</label>
                                            <div class="form-row mt-2">
                                                <div class="col-md-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox"
                                                            id="is_other" name="is_other" value="1"
                                                            {{ old('is_other') == '1' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_other">
                                                            Is Other
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Is Feature</label>
                                            <div class="form-row mt-2">
                                                <div class="col-md-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox"
                                                            id="is_feature" name="is_feature" value="1"
                                                            {{ old('is_feature') == '1' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_feature">
                                                            Is Feature
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer text-right">
                                        <button class="btn btn-secondary" type="reset">Reset</button>
                                        <button class="btn btn-primary mr-1" id="dropzoneSubmit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ asset('bundles/dropzonejs/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/page/multiple-upload.js') }}"></script>
    <script>
        // const input = document.querySelector('#file');
        // // Listen for files selection
        // input.addEventListener('change', (e) => {
        //     // Retrieve all files
        //     const files = input.files;
        //     // Check files count
        //     if (files.length > 3) {
        //         swal('Error', 'Only 3 files are allowed to upload.', 'error');
        //         $("#file").val(null);
        //         return false;
        //     }
        // });
        // Initialize Dropzone instance with custom options
        // if (window.Dropzone) {
        //     Dropzone.autoDiscover = false;
        // }
        // Disable auto-discover of dropzone elements

        // var myDropzone = new Dropzone("#mydropzone", {
        //     url: "{{ route('admin.product.insert') }}",
        //     paramName: "file",
        //     maxFilesize: 2, // Max file size in MB
        //     maxFiles: 10, // Max number of files
        //     parallelUploads: 1, // Number of files to upload at once
        //     acceptedFiles: ".png, .jpg, .jpeg, .gif", // Allowed file types
        //     addRemoveLinks: true,
        //     uploadMultiple: true,
        //     autoProcessQueue: false,
        // });

        // Add event listener for sending event to include additional form data
        // myDropzone.on("sending", function(file, xhr, formData) {
        //     formData.append("_token", "{{ csrf_token() }}");
        //     formData.append("brand_id", $("#brand_id").val());
        //     formData.append("category_id", $("#category_id").val());
        //     formData.append("product", $("#product").val());
        //     formData.append("points", $("#points").val());
        //     formData.append("price", $("#price").val());
        //     formData.append("purchase_price", $("#purchase_price").val());
        //     formData.append("is_active", $("#is_active").val());
        //     formData.append("is_other", $("#is_other").val());
        //     formData.append("is_stock", $("#product").val());
        //     formData.append("is_feature", $("#is_feature").val());
        //     formData.append("description", $("#description").val());
        // });
        // $('#imgsubbutt').click(function() {
        //     myDropzone.processQueue();
        // });

        // Dropzone.options.myDropzone = {
        //     // The setting up of the dropzone
        //     init: function() {
        //         var myDropzone = this;

        //         // First change the button to actually tell Dropzone to process the queue.
        //         $("#dropzoneSubmit").on("click", function(e) {
        //             // Make sure that the form isn't actually being sent.
        //             e.preventDefault();
        //             e.stopPropagation();

        //             console.log(myDropzone.files);
        //             if (myDropzone.files != "") {
        //                 console.log(myDropzone.processQueue());
        //                 myDropzone.processQueue();
        //             } else {
        //                 // $("#form").submit();
        //             }

        //         });

        //         // on add file
        //         this.on("addedfile", function(file) {
        //             // console.log(file);
        //         });
        //         // on error
        //         this.on("error", function(file, response) {
        //             // console.log(response);
        //         });
        //         // on start
        //         this.on("sendingmultiple", function(file) {
        //             // console.log(file);
        //         });
        //         // on success
        //         this.on("successmultiple", function(file) {
        //             // submit form
        //             console.log("successmult");
        //             // $("#form").submit();
        //         });
        //     }
        // };
    </script>
@endsection
