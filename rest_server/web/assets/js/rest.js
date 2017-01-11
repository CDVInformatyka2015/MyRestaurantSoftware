var MRS = new function () {

    this.mainUrl = "";
    this.apiUrl = "";
    this.invoiceUrl = "";

    this.init = function (url) {
        if (!window.jQuery) {
            console.error("jQuery is not loaded!");
            return false;
        }
        this.mainUrl = url;
        this.apiUrl = url + "api";
        this.invoiceUrl = this.mainUrl + "invoice/";
    };

    this.homepage = function () {
        $.ajax({
            url: this.apiUrl + "/invoices",
            success: function (result) {
                for (i = 0; i < result.length; i++) {
                    MRS.generateHomepage("newInvoiceList",result)
                }
            }
        });
    };

    this.generateHomepage = function (id, data) {
        $("#" + id).append(
            $('<li>').attr('class', 'list-group-item').append(
                $('<a>').attr('href', this.invoiceUrl + data[i].id).append(
                    "Zamówienie nr: " + data[i].id
                )
            )
        )
    }
};
