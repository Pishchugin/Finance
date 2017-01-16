// this function is for the file uploading status
function progressHandler(event) {
    $("#loadedTotal").html("Uploaded " + event.loaded + " bytes of " + event.total);
    var percent = (event.loaded / event.total) * 100;
    $("#progressBar").val(Math.round(percent));
    $("#statusUploading").html(Math.round(percent) + "% uploaded... please wait");
}


// this function is for the file uploading status
function completeHandler(event) {
    // $("#statusUploading").html(event.target.responseText);
    $("#statusUploading").html("The file has been uploaded!");
    userMaps = eval(event.target.responseText);
    displaySoilMapList();
}


// this function is for the file uploading status
function errorHandler(event) {
    $("#statusUploading").html("Upload Failed");
}


// this function is for the file uploading status
function abortHandler(event) {
    $("#statusUploading").html("Upload Aborted");
}


// EMULATE EXCEL SUMPRODUCT() FUNCTION
function sumproduct(array, column1, array2, column2, size) {
    // console.log("array = " + array, "column1 = " + column1, "array2 = " + array2, "column2 = " + column2, size);
    size = size || Math.min(array.length, array2.length);
    if (size > array.length || size > array2.length)
        size = Math.min(array.length, array2.length);
    var sumProduct = 0;

    for (var a = 0; a < size; a++) {
        var temp1 = array[a];
        var temp2 = array2[a];
        sumProduct += eval(temp1[column1]) * eval(temp2[column2]);
    }
    return sumProduct;
}


// EMULATE EXCEL SUM() FUNCTION
function sum(array, column, size) {
    size = size || array.length;
    var sum = 0;
    for (var a = 0; a < size; a++) {
        var temp = array[a];
        sum += eval(temp[column]);
    }
    return sum;
}


// SHOW THE ANIMATED LABEL WHILE R SCRIPT IS WORKING
function showAnimation(txt) {
    $('h1, h2', '#animation').html(txt);
    $('#animation').animate({'opacity': '0.1'}, 600).animate({'opacity': '1'}, 600, showAnimation);
}


// CAPITALISE THE FIRST LETTER OF EACH WORD IN A STRING
function ucwords(str) {
    return str.replace(/\w\S*/g, function (txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
}


// CHECK WHETHER ALL FIELDS ON THE FORM ARE FILLED IN
function validateForm(form) {
    var isValid = true;
    $(form).find('input:text').each(function () {
        if ($(this).val() === '')
            isValid = false;
    });
    return isValid;
}


// DISABLE BACK BUTTON IN THE BROWSER
function disableBack() {
    window.location.hash = "no-back-button";
    window.location.hash = "Again-No-back-button"; // for Chrome

    window.onhashchange = function () {
        window.location.hash = "no-back-button";
    };
}


// EMULATE PHP FUNCTION isset()
function isset(object) {
    return (typeof object !== 'undefined');
}


// REPLACE ALL INSTANCES OF A SUB-STRING IN THE STRING GIVEN
function replaceAll(str, find, replace) {
    return str.split(find).join(replace);
}


// PERFORM A JQUERY UI EFFECT ON THE ELEMENT GIVEN
function runEffect(element, effect, mode) {
    var options = {};
    if (effect === "scale") {
        options = {percent: 50};
    } else if (effect === "size") {
        options = {to: {width: 280, height: 185}};
    }
    if (mode == "show")
        $(element).show(effect, options, 500);
    else
        $(element).effect(effect, options, 500);
}


// RETURNS HOW MANY DAYS PASSED BETWEEN TWO DATES
function daysBetween(one, another) {
    return Math.ceil(Math.abs((+one) - (+another)) / 8.64e7);
}


// INITIALISE TOOLTIPS ON THE PAGE
function prepareTooltips() {
    $(".tip").tooltip({
        track: false,
        show: {
            effect: "fade",
            delay: 800
        },
        position: {
            my: "center bottom-10",
            at: "left+15 top",
            using: function (position, feedback) {
                $(this).css(position);
                $(this).css("z-index", "999999999");
                $("<div>")
                        .addClass("arrow")
                        .addClass(feedback.vertical)
                        .addClass(feedback.horizontal)
                        .appendTo(this);
            }
        }
    });
}