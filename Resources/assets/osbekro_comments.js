

var OsbekroCommentBox = function (config) {
    this.commentBox = config.element;
    this.url = config.element.getAttribute('data-url');
}


OsbekroCommentBox.prototype.load = function (page, limit) {
    function reloadElement (elements) {
        console.log(this.responseText);
    };

    var request = new XMLHttpRequest();
    request.onload = reqListener;
    request.open("get", this.url, true);
    request.send();
};

OsbekroCommentBox.prototype.append = function (comment) {
};



document.addEventListener("DOMContentLoaded", function (event) {
    var forms = document.getElementsByClassName('osbekro-comments-post-form'),
        i, l;
    for (i = 0, l = forms.length; i < l; i += 1 ) {
        console.log(forms[i]);
    }
});