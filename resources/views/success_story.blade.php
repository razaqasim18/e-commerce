@extends('layouts.eshop')
@section('style')
@endsection

@section('content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs SSImage d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-center align-items-center">
                    <a href="#">Leaders Stories</a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Start Success Stories -->
    <section id="success-stories" class="sectionOne">
        <div class="container">
            <div class="row rshadow">
                <div class="col-md-4 employes">
                    <img src="{{ asset('eshop/images/emp1.jpg ') }}" alt="" />
                </div>
                <div class="col-md-8 empDetails">
                    <h4>John Doe</h4>
                    <h5>Ceo</h5>
                    <p>
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                        Necessitatibus dicta numquam, laborum eaque vel totam illum nisi
                        architecto blanditiis sint earum ad debitis omnis delectus quisquam
                        error iure veniam ipsum? icta numquam, laborum eaque vel totam illum
                        nisi architecto blanditiis sint earum ad debitis omnis delectus
                        quisquam error iure veniam ipsum?
                    </p>
                </div>
            </div>
            <div class="row rshadow">
                <div class="col-md-4 employes">
                    <img src="{{ asset('eshop/images/emp2.jpg') }}" alt="" />
                </div>
                <div class="col-md-8 empDetails">
                    <h4>Chris Floyd</h4>
                    <h5>Director</h5>
                    <p>
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                        Necessitatibus dicta numquam, laborum eaque vel totam illum nisi
                        architecto blanditiis sint earum ad debitis omnis delectus quisquam
                        error iure veniam ipsum? icta numquam, laborum eaque vel totam illum
                        nisi architecto blanditiis sint earum ad debitis omnis delectus
                        quisquam error iure veniam ipsum?
                    </p>
                </div>
            </div>
            <div class="row rshadow">
                <div class="col-md-4 employes">
                    <img src="{{ asset('eshop/images/emp3.jpg') }}" alt="" />
                </div>
                <div class="col-md-8 empDetails">
                    <h4>Joe Rogan</h4>
                    <h5>Markeeting Expert</h5>
                    <p>
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                        Necessitatibus dicta numquam, laborum eaque vel totam illum nisi
                        architecto blanditiis sint earum ad debitis omnis delectus quisquam
                        error iure veniam ipsum? icta numquam, laborum eaque vel totam illum
                        nisi architecto blanditiis sint earum ad debitis omnis delectus
                        quisquam error iure veniam ipsum?
                    </p>
                </div>
            </div>
            <div class="row rshadow">
                <div class="col-md-4 employes">
                    <img src="{{ asset('eshop/images/emp4.jpg') }}" alt="" />
                </div>
                <div class="col-md-8 empDetails">
                    <h4>David White</h4>
                    <h5>HR Manager</h5>
                    <p>
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                        Necessitatibus dicta numquam, laborum eaque vel totam illum nisi
                        architecto blanditiis sint earum ad debitis omnis delectus quisquam
                        error iure veniam ipsum? icta numquam, laborum eaque vel totam illum
                        nisi architecto blanditiis sint earum ad debitis omnis delectus
                        quisquam error iure veniam ipsum?
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Sucess Stories -->
@endsection

@section('script')
@endsection
