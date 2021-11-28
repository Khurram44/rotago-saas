{{ Form::model($user, array('route' => array('user.update', $user->id), 'method' => 'PUT')) }}
<div class="form-group">
    {{Form::label('name',__('Name'),array('class'=>'')) }}
    {{Form::text('company_name',null,array('class'=>'form-control','placeholder'=>__('Enter User Name'),'required'=>'required'))}}
</div>
<div class="form-group">
    {{Form::label('email',__('Email'))}}
    {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email'),'required'=>'required'))}}
</div>
<div class="modal-footer">
    {{Form::submit(__('Update'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
</div>
{{ Form::close() }}
