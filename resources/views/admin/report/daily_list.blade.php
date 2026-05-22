@extends('admin.template.layout')

@section('content')

<div class="card mb-5">
  

  <div class="card-header">
  @if(get_user_permission('orders','c'))
    <!-- <a href="{{route('admin.create_product')}}" class="btn-custom btn mr-2 mt-2 mb-2"><i
        class="fa-solid fa-plus"></i> Create</a> -->
        @endif
        <div class="col-md-12">
          <form method="get" action="">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <input class="form-control" type="text" name="search_key" value="{{$_GET['search_key']??''}}" placeholder="Ticket Number">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <input class="form-control" type="text" name="drow_date" data-provide="datepicker" value="{{$_GET['drow_date']??''}}" placeholder="Drow Date">
                </div>
              </div>
              
              
              <div class="from-group">
                <button type="submit" class="btn btn-primary">FIlter</button>
                <a href="#" class="btn btn-success">Clear</a>
              </div>
            </div>
          </form>
        </div>
  </div>
  
  <div class="card-body">
    <div class="table-responsive">
      

      <div class="col-sm-12 col-md-12 pull-right">
        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
         
        </div>
      </div>

    </div>
  </div>
</div>
@stop
@section('script')
<script>
  jQuery(document).ready(function(){

      App.initTreeView();

  })
</script>
@stop