@extends('layouts.auth')
@section('title')
    RequestRG-code
@endsection
@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-10 offset-sm-1 col-md-10 offset-md-1 col-lg-10 offset-lg-1 col-xl-10 offset-xl-1">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>RequestRG-code</h4>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('request.epin.save') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="form-group col-6">
                                        <label for="email">Email</label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="phone">Phone</label>
                                        <input id="phone" type="number"
                                            class="form-control @error('phone') is-invalid @enderror" name="phone"
                                            value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="bank_id">Bank</label>
                                        <select class="form-control" id="bank_id" name="bank_id">
                                            <option value="">Select</option>
                                            @foreach ($bank as $row)
                                                <option value="{{ $row->id }}"
                                                    @if (old('bank_id') == $row->id) selected @endif>
                                                    {{ $row->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bank_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="transectionid">Transection ID</label>
                                        <input id="transectionid" type="text"
                                            class="form-control @error('transectionid') is-invalid @enderror"
                                            name="transectionid" value="{{ old('transectionid') }}" required>
                                        @error('transectionid')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="amount">Amount Deposit</label>
                                        <div class="input-group">
                                            <input type="number" min="0" step=".01"
                                                class="form-control @error('amount') is-invalid @enderror" name="amount"
                                                value="{!! SettingHelper::getSettingValueBySLug('epin_charges') !!}" required readonly>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    PKR
                                                </div>
                                            </div>
                                        </div>
                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="amount">Date</label>
                                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                                            name="date" value="{{ old('date') }}" required>
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="image">Image Proof</label>
                                        <input type="file" accept="image/png, image/gif, image/jpeg"
                                            class="form-control @error('image') is-invalid @enderror" name="image"
                                            required>
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
