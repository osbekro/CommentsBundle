(function () {
    'use strict';

    var OsbekroCommentBox = function (element) {
        this.commentBox = null;
        this.element = element;
        this.url = element.getAttribute('data-source');
    };

    OsbekroCommentBox.prototype.load = function (page, limit) {
        var request = new XMLHttpRequest();
        request.onload = reloadElement;
        request.open("get", this.url + '?page=' + parseInt(page, 10) + '&limit=' + parseInt(limit, 10), true);
        request.send();
    };

    OsbekroCommentBox.prototype.reload = function () {
        var request = new XMLHttpRequest(),
            limit = 3,
            that = this;
        request.open("get", this.url + '?limit=' + parseInt(limit, 10) + '&nodiv=1', true);
        request.setRequestHeader('X-Requested-With','XMLHttpRequest');

        request.onload = function () {
            that.element.innerHTML = this.responseText;
        };
        request.send();
    }

    OsbekroCommentBox.prototype.attachPager = function() {
        //TBI
    };

    var OsbekroCommentForm = function (element) {
        var that = this;
        this.element = element;
        element.onsubmit = function() {
            var request = new XMLHttpRequest();
            request.onload = function () {
                if (that.commentBox instanceof OsbekroCommentBox) {
                    that.commentBox.reload();
                }
            };
            request.open(element.getAttribute('method'), element.getAttribute('action'));
            request.setRequestHeader('X-Requested-With','XMLHttpRequest');
            request.send(new FormData(element));
            return false;
        }
    }

    OsbekroCommentBox.prototype.appendHtml = function (commentHtml, removeLast) {
        var listElement = this.element.getElementsByClassName('osbekro-comment-list')[0],
            i;

        if (listElement !== undefined) {
            listElement.insertAdjacentHTML('afterbegin', commentHtml);
        }
        if (removeLast === true) {
            var last = listElement.children[listElement.children.length - 1];
            if (last.parentNode) {
                last.parentNode.removeChild(last);
            }
        }
    };

    document.addEventListener("DOMContentLoaded", function (event) {
        var elements = document.getElementsByClassName('osbekro-comments-box'),
            formElements = document.getElementsByClassName('osbekro-comments-post-form'),
            comments = {},
            forms = {},
            i, l, tid = null;

        for (i = 0, l = elements.length; i < l; i += 1) {
            tid = elements[i].getAttribute('data-thread-id');

            if (tid !== null) {
                comments[tid] = new OsbekroCommentBox(elements[i]);
            } else {
                comments['u' + tid] = new OsbekroCommentBox(elements[i]);
            }
        }

        for (i = 0, l = formElements.length; i < l; i += 1 ) {
            tid = formElements[i].getAttribute('data-thread-id');

            if (tid !== null) {
                forms[tid] = new OsbekroCommentForm(formElements[i]);
                if (comments[tid] instanceof OsbekroCommentBox) {
                    forms[tid].commentBox = comments[tid];
                }
            } else {
                forms['u' + i] = new OsbekroCommentForm(formElements[i]);
            }
        }

    });

}())
