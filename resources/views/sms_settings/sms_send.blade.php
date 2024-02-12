@extends('layouts.app')
@section('title', __('SMS'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>SMS_Settings</h1>
</section>

<!-- Main content -->
<section class="content">
	@if ($message = Session::get('success'))
              <div class="alert alert-success alert-block">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <strong>{{ $message }}</strong>
              </div>
    @endif
    
	{!! Form::open(['method' => 'post','url' => action([\App\Http\Controllers\SMSController::class, 'postSendSMS']),'files' => true])!!}
	<div class="box box-solid">
		<div class="box-body">
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							{!! Form::label('Sender Number'.':') !!}
						{!! Form::text('to',null,['class' => 'form-control']); !!}
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							{!! Form::label('Message Body'. ':') !!}
								{!! Form::textarea('msg',null,['class' => 'form-control', 'rows' => 3]); !!}
						</div>
					</div>
				</div>
		</div>
	</div> <!--box end-->
	<div class="col-sm-6 text-center">
		<button type="submit" class="btn btn-primary btn-big">Submit</button>
	</div>
{!! Form::close() !!}
</section>
@endsection
