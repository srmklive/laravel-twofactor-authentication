@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/authy-form-helpers/2.3/flags.authy.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/authy-form-helpers/2.3/form.authy.css" />
@endsection

@section('content')
<div class="col-sm-4 col-sm-offset-4">
    <form method="POST">
        {{csrf_field()}}
        <h3>Enable Two-Factor Authentication</h3>
        <div class="row">
            <div class="col-xs-3">Country:</div>
            <div class="col-xs-9">
                <select id="authy-countries" name="country-code"></select>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-3">Cellphone:</div>
            <div class="col-xs-9">
                <input id="authy-cellphone" type="text" value="" name="authy-cellphone" />
            </div>
        </div>
        <div class="row">
            <div class="col-xs-9 col-xs-offset-3">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="send_sms" />
                        Send two-factor token via SMS
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-9 col-xs-offset-3">
                <button type="submit">Submit</button>
            </div>
        </div>        
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/authy-form-helpers/2.3/form.authy.js"></script>
@endsection
