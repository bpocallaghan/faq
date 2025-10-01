@extends('titan::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span><i class="fa fa-check"></i></span>
                        <span>FAQ Updated Successfully</span>
                    </h3>
                </div>

                <div class="box-body">
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle"></i>
                        The FAQ has been updated successfully!
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Updated FAQ Details:</h4>
                            
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5><strong>Question:</strong></h5>
                                </div>
                                <div class="panel-body">
                                    <p>{{ $item->question }}</p>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5><strong>Answer:</strong></h5>
                                </div>
                                <div class="panel-body">
                                    {!! $item->answer !!}
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5><strong>Category:</strong></h5>
                                </div>
                                <div class="panel-body">
                                    <p>{{ $item->category->name ?? 'No category' }}</p>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5><strong>Last Updated:</strong></h5>
                                </div>
                                <div class="panel-body">
                                    <p>{{ $item->updated_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group">
                                <a href="{{ $selectedNavigation->url }}" class="btn btn-primary">
                                    <i class="fa fa-list"></i> Back to FAQ List
                                </a>
                                <a href="{{ $selectedNavigation->url . '/' . $item->id . '/edit' }}" class="btn btn-warning">
                                    <i class="fa fa-edit"></i> Edit Again
                                </a>
                                <a href="{{ $selectedNavigation->url . '/' . $item->id }}" class="btn btn-info">
                                    <i class="fa fa-eye"></i> View FAQ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
