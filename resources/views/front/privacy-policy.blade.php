@extends('front.template.layout')

@section('title', 'Privacy Policy')

@section('content')
   <div class="package-grid-page pt-100 mb-50">
        <div class="container">
            <div class="row pt-5">
                <div class="col-12 text-center">
                    <h1>Privacy Policy</h1>
                </div>
            </div>

            <div class="row py-5">
                <div class="col-12">
                    {!! $policy->desc_en ?? '' !!}
                </div>
            </div>

        </div>
    </div>
@endsection
