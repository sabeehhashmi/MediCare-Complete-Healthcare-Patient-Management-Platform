@extends('admin.template.layout')

@section('content')
    <div class="card mb-5">
        <div class="card-body">
            
    
         
                   
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label><strong>Full Name</strong></label>
                                <p>{{$entry->name}}</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                        <div class="form-group">
                                <label><strong>Email</strong></label>
                                <p>{{$entry->email}}</p>
                            </div>
                        </div>
                         

                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label><strong>Subject</strong></label>
                                <p>{{$entry->subject}}</p>
                            </div>
                        </div>

                        <div class="col-md-3 mb-2">
                        <div class="form-group">
                                <label><strong>Submitted At</strong></label>
                                <p>{{web_date_in_timezone($entry->created_at,'d-M-Y h:i A')}}</p>
                            </div>
                        </div>
                         @if($entry->file)
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label><strong>File</strong></label>
                               
                            <div class="pb-3 pt-3">
                               <a target="_blank" href="{{ $entry->file }}">File</a>
                           
                            </div>
                            
                            </div>
                        </div>
                        @endif
                        <div class="col-8">
                        <div class="form-group">
                                <label><strong>Message</strong></label>
                                <p>{{$entry->message}}</p>
                            </div>
                        </div>

                        
                    </div>

                   
                
                    
                    

            <div class="col-xs-12 col-sm-6">
                <form method="POST" action="{{ route('admin.contact-us-entries.updateStatus', $entry->id) }}">
    @csrf

    <div class="row mt-4">
        <div class="col-md-4">
            <label><strong>Status</strong></label>
            <select name="status" class="form-control" required>
                <option value="open" {{ $entry->status == 'open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ $entry->status == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <div class="col-md-2 mt-4">
            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to change status?')">
                Update
            </button>
        </div>
    </div>
</form>
            </div>
        </div>
    </div>
@stop


