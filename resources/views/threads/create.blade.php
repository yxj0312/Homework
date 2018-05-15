@extends('layouts.app')

@section('head')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">Create a New Thread</div>
                    <div class="card-body">
                      <form method="POST" action="{{ route('threads.store') }}">
                        {{ csrf_field() }}
                            <div class="form-group">
                                <label for="channel_id">Choose a Channel:</label>
                                <select name="channel_id" id="channel_id" class="form-control" required>
                                    <option value="" disabled selected="selected">Choose one...</option>
                                    @foreach ($channels as $channel)
                                        <option value="{{ $channel->id}}" {{ old('channel_id')== $channel->id ? 'selected' : ''}}>{{ $channel->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="title" required>
                            </div>
                            <div class="form-group">
                                <label for="body">Body:</label>
                                {{-- <textarea name="body" id="body" class="form-control" rows="8" required>{{ old('body') }}</textarea> --}}
                                <wysiwyg name="body"></wysiwyg>
                            </div>
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="6LdDilYUAAAAANR-TRNZtgRTRZdo35hA0NcMZI0e"></div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Publish</button>
                            </div>
                            @if (count($errors))    
                                <ul class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
