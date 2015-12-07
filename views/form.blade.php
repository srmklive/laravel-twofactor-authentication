@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/authy-forms.css/2.2/flags.authy.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/authy-forms.css/2.2/form.authy.css" />
@endsection

<div class="login-form">
    <form method="POST">
        {{csrf_field()}}
        <h3>Enable Two-Factor Authentication</h3>
        Country:
        <select id="authy-countries" name="country-code"></select>
        <br/>
        Cellphone: <input id="authy-cellphone" type="text" value="" name="authy-cellphone" />
        <button type="submit">Submit</button>
    </form>
</div>

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/authy-forms.js/2.2/form.authy.js"></script>
@endsection
