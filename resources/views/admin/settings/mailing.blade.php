@extends('layout-backend.master')

@section('content')
    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Mailing Settings</h5>
            </div>
            <div class="card-body">
                @include('layout-backend.notify')

                <form action="/admin/settings/mailing" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="mailingServer" class="d-block">Mailing Server</label>
                        <select required name="mailingServer" class="form-control" id="mailing_server">
                            <option
                                {{$settings[\App\Models\Settings::MAILING_SERVER] === 'smtp' ? 'selected':''}} value="smtp">
                                SMTP
                            </option>
                            <option
                                {{$settings[\App\Models\Settings::MAILING_SERVER] === 'mailgun' ? 'selected':''}} value="mailgun">
                                Mailgun
                            </option>
                        </select>
                    </div>

                    <div id="mailgunDiv" class="form-group">
                        <label for="mailgunSecret" class="d-block">Mailgun Secret</label>
                        <input type="text" name="mailgunSecret" id="mailgunSecret"
                               value="{{old('mailgunSecret', $settings[\App\Models\Settings::MAILGUN_SECRET])}}"
                               class="form-control"
                               placeholder="Enter mailgun secret">
                    </div>

                    <div class="form-group">
                        <label for="mailingDomain" class="d-block">Domain</label>
                        <input type="text" name="mailingDomain" id="mailingDomain"
                               value="{{old('mailingDomain', $settings[\App\Models\Settings::MAILING_DOMAIN])}}"
                               class="form-control"
                               placeholder="Enter domain">
                    </div>

                    <div id="smtpDiv" class="form-row">
                        <div class="form-group col-md-6">
                            <label for="mailingDomain" class="d-block">SMTP Username</label>
                            <input type="text" name="smtpUsername" id="smtpUsername"
                                   value="{{old('smtpUsername', $settings[\App\Models\Settings::MAILING_SMTP_USERNAME])}}"
                                   class="form-control"
                                   placeholder="Enter smtp username">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="smtpPassword">SMTP Password</label>
                            <input type="password" class="form-control"
                                   value="{{old('smtpPassword', $settings[\App\Models\Settings::MAILING_SMTP_PASSWORD])}}"
                                   id="smtpPassword" name="smtpPassword" placeholder="Enter smtp password">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="mailingFromName" class="d-block">From Name</label>
                            <input type="text" name="mailingFromName" id="mailingFromName"
                                   value="{{old('mailingFromName', $settings[\App\Models\Settings::MAILING_FROM_NAME])}}"
                                   class="form-control"
                                   placeholder="Enter from name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="mailingFromEmail" class="d-block">From Email</label>
                            <input type="text" name="mailingFromEmail" id="mailingFromEmail"
                                   value="{{old('mailingFromEmail', $settings[\App\Models\Settings::MAILING_FROM_EMAIL])}}"
                                   class="form-control"
                                   placeholder="Enter from email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mailingReplyTo" class="d-block">From Email</label>
                        <input type="text" name="mailingReplyTo" id="mailingReplyTo"
                               value="{{old('mailingReplyTo', $settings[\App\Models\Settings::MAILING_REPLY_TO_EMAIL])}}"
                               class="form-control"
                               placeholder="Enter reply to email">
                    </div>

                    <div class="row justify-content-center">
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">

        checkMailingServer();

        $('#mailing_server').on('change', () => {
            checkMailingServer();
        });

        function checkMailingServer() {
            let val = $('#mailing_server').find('option:selected').val();
            console.log(val);

            if (val === 'mailgun') {
                $('#mailgunDiv').show();
                $('#smtpDiv').hide();
            } else {
                $('#mailgunDiv').hide();
                $('#smtpDiv').show();
            }
        }
    </script>
@stop
