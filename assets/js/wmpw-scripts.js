var $doc = jQuery(document);

$doc.ready(function ($) {
    $doc.on('submit', '.custom-form-js', function (e) {
        e.preventDefault();
        var $form = jQuery(this);
        var $filePreview = $form.find('.file-preview');
        var this_form = $form.attr('id');
        var test = true,
            thsInputs = $form.find('input, textarea'),
            $select = $form.find('select[required]');
        $select.each(function () {
            var $ths = jQuery(this);
            var $label = $ths.closest('.form-label');
            var val = $ths.val();
            if (Array.isArray(val) && val.length === 0) {
                test = false;
                $label.addClass('error');
            } else {
                $label.removeClass('error');
                if (val === null || val === undefined) {
                    test = false;
                    $label.addClass('error');
                }
            }
        });
        thsInputs.each(function () {
            var thsInput = jQuery(this),
                $label = thsInput.closest('.form-label'),
                thsInputType = thsInput.attr('type'),
                thsInputVal = thsInput.val().trim(),
                inputReg = new RegExp(thsInput.data('reg')),
                inputTest = inputReg.test(thsInputVal);
            if (thsInput.attr('required')) {
                if (thsInputVal.length <= 0) {
                    test = false;
                    thsInput.addClass('error');
                    $label.addClass('error');
                    thsInput.focus();
                } else {
                    thsInput.removeClass('error');
                    $label.removeClass('error');
                    if (thsInput.data('reg')) {
                        if (inputTest === false) {
                            test = false;
                            thsInput.addClass('error');
                            $label.addClass('error');
                            thsInput.focus();
                        } else {
                            thsInput.removeClass('error');
                            $label.removeClass('error');
                        }
                    }
                }
            }
        });
        if (test) {
            var thisForm = document.getElementById(this_form);
            var formData = new FormData(thisForm);
            var data = {
                type: $form.attr('method'),
                url: admin_ajax,
                processData: false,
                contentType: false,
                data: formData,
            };
            if ($form.hasClass('create-product')) {
                $form.trigger('reset');
                $filePreview.html($filePreview.attr('data-text'));
                $doc.find('#editor .ql-editor').html('');
            }
            sendRequest(data);

        }
    });
    quillInit();
});

document.addEventListener("DOMContentLoaded", function () {
    var fileInputs = document.querySelectorAll('.upload-files');
    fileInputs.forEach(input => {
        var label = input.closest('.form-label');
        var previewContainer = label.querySelector('.file-preview');
        input.addEventListener('change', function () {
            var files = input.files;
            previewContainer.innerHTML = '';
            if (files.length === 0) {
                previewContainer.textContent = previewContainer.getAttribute('data-text');
                return;
            }
            if (files.length > 10) {
                alert('Max 10 files');
                previewContainer.textContent = previewContainer.getAttribute('data-text');
                return;
            }
            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) return;
                var reader = new FileReader();
                reader.onload = function (event) {
                    var img = document.createElement('img');
                    img.src = event.target.result;
                    img.alt = file.name;
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });

    });
    document.querySelectorAll('input[type="tel"], .number-input').forEach(function (input) {
        input.addEventListener('input', function (e) {
            let validChars = /[0-9\s\-()+]/;
            let inputValue = e.target.value;
            let filteredInput = inputValue.split('').filter(char => validChars.test(char)).join('');
            if (inputValue !== filteredInput) {
                e.target.value = filteredInput;
            }
        });
    });
});

function quillInit() {
    var $editor = $doc.find('#editor');
    if ($editor.length === 0) return;
    var quill = new Quill('#editor', {
        theme: 'snow'
    });
    quill.on('text-change', (delta, oldDelta, source) => {
        let val = quill.getSemanticHTML();
        $editor.closest('form').find('.value-field').val(val);
    });
}

function sendRequest(data) {
    jQuery.ajax(data).done(function (r) {
        if (r) {
            if (isJsonString(r)) {
                var res = JSON.parse(r);
                if (res.msg !== '' && res.msg !== undefined) {
                    showMassage(res.msg);
                }
            } else {
                showMassage(r);
            }
        }
    });
}

function showMassage(message) {
    var $msg = $doc.find('.msg');
    $msg.html(message);
    $msg.slideDown();
    jQuery('html, body').animate({
        scrollTop: $msg.offset().top
    })
    setTimeout(function () {
        $msg.slideUp();
    }, 3000);
}

function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}