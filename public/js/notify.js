var Notify = function () {
    var self = this;
    var title,
        message,
        status,
        form,
        timeout;

    this.setTitle = function(title) {
        self.title = "<b>"+title+"</b> <br>";
        return this;
    };

    this.setMessage = function(message) {
        self.message = message;
        return this;
    };

    this.setStatus = function(status) {
        self.status = status;
        return this;
    };

    this.setForm = function(form) {
        self.form = form;
        return this;
    };

    this.show = function () {
        $('#ajax-notify').removeClass('notify-success').removeClass('notify-danger');
        $('#ajax-notify').addClass('notify-' + self.status);
        $('#ajax-notify .inner').html(self.message);
        $.fancybox.open({
            src  : '#ajax-notify',
            type : 'inline'
        });
        clearTimeout(self.timeout);
        self.timeout = setTimeout(function() {
            $.fancybox.close({
                src  : '#ajax-notify'
            });
        }, 7000);
    };
}
var Notify = new Notify();