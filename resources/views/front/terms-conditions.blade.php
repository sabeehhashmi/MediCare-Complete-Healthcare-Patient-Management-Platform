@extends('front.template.layout')

@section('title', 'Terms & Conditions')

@section('content')
   <div class="package-grid-page pt-100 mb-50">
        <div class="container">
            <div class="row pt-5">
                <div class="col-12 text-center">
                    <h1>Terms & Conditions</h1>
                </div>
            </div>

            <div class="row py-5">
                <div class="col-12">
                    {!! $terms->desc_en ?? '' !!}
                </div>
            </div>

        </div>
    </div>
@endsection
