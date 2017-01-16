$(".tip").tooltip({
    show: {
        effect: "fade",
        delay: 500
    },
    position: {
        my: "center bottom-20", at: "center top",
        using: function (position, feedback) {
            $(this).css(position);
            $("<div>")
                    .addClass("arrow")
                    .addClass(feedback.vertical)
                    .addClass(feedback.horizontal)
                    .appendTo(this);
        }
    }
});


$(function () {
    $('#newExpense').dialog({
        width: 550,
        height: 180,
        resizable: false,
        closeOnEscape: false,
        modal: true,
        draggable: false,
        autoOpen: false,
        position: {my: 'center', at: 'center', of: $("#maincontent")},
        show: {
            effect: "fade",
            duration: 1000
        },
        hide: {
            effect: "explode",
            duration: 1000
        },
        open: function () {
            $('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<img src="./images/deleteButton.png" width=22 height=22 class="button noselect">');
            $("#operationsTable").empty();
            addExpensesTableRow(false);
            $('.ui-widget-overlay').hide().fadeIn(1000);
            $('.ui-widget-header img').bind('click.close', function () {
                $('.ui-widget-overlay').fadeOut(function () {
                    $('.ui-widget-header img').unbind('click.close');
                    $('.ui-widget-header img').trigger('click');
                });
                return false;
            });
        }
    });
})


$(function () {
    $('#modifyExpense').dialog({
        width: 550,
        height: 180,
        resizable: false,
        closeOnEscape: true,
        modal: true,
        draggable: true,
        autoOpen: false,
        position: {my: 'center', at: 'center', of: $("#maincontent")},
        show: {
            effect: "fade",
            duration: 1000
        },
        hide: {
            effect: "explode",
            duration: 1000
        },
        open: function () {
            $('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<img src="./images/deleteButton.png" width=22 height=22 class="button noselect">');
            $("#operationsTable").empty();
            addExpensesTableRow(false);
            $('.ui-widget-overlay').hide().fadeIn(1000);
            $('.ui-widget-header img').bind('click.close', function () {
                $('.ui-widget-overlay').fadeOut(function () {
                    $('.ui-widget-header img').unbind('click.close');
                    $('.ui-widget-header img').trigger('click');
                });
                return false;
            });
        }
    });
})


$(function () {
    $('#deleteExpense').dialog({
        width: 320,
        height: 145,
        resizable: false,
        closeOnEscape: true,
        modal: true,
        draggable: true,
        autoOpen: false,
        position: {my: 'center', at: 'center', of: $("#maincontent")},
        show: {
            effect: "highlight",
            duration: 1000
        },
        hide: {
            effect: "explode",
            duration: 1000
        },
        open: function () {
            $('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<img src="./images/deleteButton.png" width=22 height=22 class="button noselect">');
            $('.ui-widget-overlay').hide().fadeIn(1000);
            $('.ui-widget-header img').bind('click.close', function () {
                $('.ui-widget-overlay').fadeOut(function () {
                    $('.ui-widget-header img').unbind('click.close');
                    $('.ui-widget-header img').trigger('click');
                });
                return false;
            });
        }
    });
})



$(function () {
    $('#chartContainer').highcharts({
        credits: {
            enabled: false
        },
        chart: {
            type: 'column',
            renderTo: 'container',
            height: '300',
            marginTop: 30,
            options3d: {
                enabled: true,
                alpha: 10,
                beta: 25,
                depth: 70
            }
        },
        title: {
            text: 'Expences Graph from 01.01.2016 to 30.10.2016',
            style: {
                color: '#000000',
                fontWeight: 'bold',
                fontSize: '13px'
            }
        },
        plotOptions: {
            column: {
                colorByPoint: true,
                depth: 25
            }
        },
        xAxis: {
            type: "category"
        },
        yAxis: {
            title: {
                text: null
            }
        },
        tooltip: {
            formatter: function () {
                var dataSum = 0;
                for (var i = 0; i < $('#chartContainer').highcharts().series[0].data.length; i++) {
                    dataSum+=$('#chartContainer').highcharts().series[0].data[i].y;
                }
                var pcnt = (this.y / dataSum) * 100;
                return this.key+": <b>$"+(this.y).toFixed(2)+"</b> ("+pcnt.toFixed(1)+"%)";
            }
        },
        series: [{
                showInLegend: false,
                data: []
            }]
    });
});



$(function () {
    $('#transfer').dialog({
        width: 355,
        height: 240,
        resizable: false,
        closeOnEscape: true,
        modal: true,
        draggable: true,
        autoOpen: false,
        position: {my: 'center', at: 'center', of: $("#maincontent")},
        show: {
            effect: "fade",
            duration: 1000
        },
        hide: {
            effect: "explode",
            duration: 1000
        },
        open: function () {
            $('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<img src="./images/deleteButton.png" width=22 height=22 class="button noselect">');
            $('.ui-widget-overlay').hide().fadeIn(1000);
            $('.ui-widget-header img').bind('click.close', function () {
                $('.ui-widget-overlay').fadeOut(function () {
                    $('.ui-widget-header img').unbind('click.close');
                    $('.ui-widget-header img').trigger('click');
                });
                return false;
            });
        }
    });
});
