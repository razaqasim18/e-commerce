@extends('layouts.admin')
@section('title')
    Admin || Dashboard
@endsection
@section('style')
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Banner Setting</h4>
                                {{-- <div class="card-header-action">
                                    <a href="{{ route('admin.category.list') }}" class="btn btn-primary">Add Category</a>
                                </div> --}}
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.setting.banner.save') }}">
                                    @csrf
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    <div class="form-row mb-3">
                                        <div class="form-group col-md-12">
                                            <label>Banner Image</label>
                                            <input id="file" name="file[]" type="file" class="form-control"
                                                accept="image/png, image/gif, image/jpeg, image/jpg" multiple>
                                        </div>
                                        @error('file')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if (!empty($banner))
                                        <div class="col-md-12">
                                            <div class="row gutters-sm">
                                                @foreach ($banner as $image)
                                                    <div class="col-md-3 col-sm-3 text-center">
                                                        <label class="imagecheck mb-4">
                                                            {{-- <input name="imagecheck" type="checkbox" value="1"
                                                            class="imagecheck-input" /> --}}
                                                            {{-- <span class="imagecheck-figure"> --}}
                                                            <img src="{{ $image->getFirstMediaUrl('images') }}"
                                                                alt="{{ $image->name }}" class="imagecheck-image d-flex"
                                                                width="100px"><br />
                                                            <button type="button" class="btn btn-danger" id="removeImage"
                                                                data-mediaid="{{ $image->id }}"
                                                                data-bannerid="{{ $image->id }}">
                                                                <i class="fas fa-trash"></i> Remove</button>
                                                            {{-- </span> --}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="card-footer text-right">
                                        <button class="btn btn-secondary" type="reset">Reset</button>
                                        <button class="btn btn-primary mr-1" type="submit">Submit</button>
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
    <script>
        $(document).ready(function() {
            $("body").on("click", "button#removeImage", function() {
                var mediaid = $(this).data("mediaid");
                var bannerid = $(this).data("bannerid");
                swal({
                        title: 'Are you sure?',
                        text: "Once deleted, you will not be able to recover",
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var token = $("meta[name='csrf-token']").attr("content");
                            var url = '{{ url('/admin/setting/banner/delete/media') }}' + '/' +
                                bannerid;
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                dataType: 'json',
                                data: {
                                    // "id": id,
                                    "_token": token,
                                },
                                beforeSend: function() {
                                    $(".loader").show();
                                },
                                complete: function() {
                                    $(".loader").hide();
                                },
                                success: function(response) {
                                    var typeOfResponse = response.type;
                                    var res = response.msg;
                                    if (typeOfResponse == 0) {
                                        swal('Error', res, 'error');
                                    } else if (typeOfResponse == 1) {
                                        swal({
                                                title: 'Success',
                                                text: res,
                                                icon: 'success',
                                                type: 'success',
                                                showCancelButton: false, // There won't be any cancel button
                                                showConfirmButton: true // There won't be any confirm button
                                            })
                                            .then((ok) => {
                                                if (ok) {
                                                    // $(this).parent().parent().remove();
                                                    location.reload();
                                                }
                                            });
                                    }
                                }
                            });
                        }
                    });
            });
        });
    </script>
@endsection
