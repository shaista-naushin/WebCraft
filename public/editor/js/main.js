$(document).ready(function () {
    let forms = $('form').toArray();

    forms.forEach((form) => {
        let redirectType = $(form).attr('x-form-redirect-type');
        let redirectURL = $(form).attr('x-form-redirect-url');
        let redirectMessage = $(form).attr('x-form-redirect-message');
        let formType = $(form).attr('gs-type');
        let autoresponder = $(form).attr('autoresponder');
        let aweberAccount = $(form).attr('aweber-account');
        let getResponseCampaign = $(form).attr('get-response-campaign');

        if (formType && formType === 'lead') {
            $(form).attr("action", '/capture/lead');
        } else {
            $(form).attr("action", '/capture/default');
        }

        $(form).append("<input type='hidden' name='x-form-page-id' value='" + window.currentPage.id + "'/>");
        $(form).append("<input type='hidden' name='x-form-redirect-type' value='" + redirectType + "'/>");
        $(form).append("<input type='hidden' name='x-form-redirect-message' value='" + redirectMessage + "'/>");
        $(form).append("<input type='hidden' name='x-form-redirect-url' value='" + redirectURL + "'/>");
        $(form).append("<input type='hidden' name='x-form-type' value='" + formType + "'/>");
        $(form).append("<input type='hidden' name='x-form-autoresponder' value='" + autoresponder + "'/>");
        $(form).append("<input type='hidden' name='x-form-aweber-account' value='" + aweberAccount + "'/>");
        $(form).append("<input type='hidden' name='x-form-get-response-campaign' value='" + getResponseCampaign + "'/>");
    });
});
