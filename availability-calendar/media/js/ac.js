jQuery(document).ready(function () {
    jQuery('.ac-add').click(function(e) {
        e.preventDefault();
        
        var last_row = jQuery('#ac_availability_options_table_last_tr');
        
        AC_AUTOINCREMENT++;
        
        last_row.before('<tr> \
                    <td> \
                        <label><input type="checkbox" name="ac_availability_options[' + AC_AUTOINCREMENT + '][remove]" value="1"> \
                        Remove</label> \
                    </td> \
                    <td> \
                        <input type="text" name="ac_availability_options[' + AC_AUTOINCREMENT + '][name]" value=""> \
                    </td> \
                    <td> \
                        <input id="color_bg' + AC_AUTOINCREMENT + '" class="color" type="text" name="ac_availability_options[' + AC_AUTOINCREMENT + '][color_bg]" value=""> \
                    </td> \
                    <td> \
                        <input id="color_text' + AC_AUTOINCREMENT + '" class="color" type="text" name="ac_availability_options[' + AC_AUTOINCREMENT + '][color_text]" value=""> \
                    </td> \
                    <td> \
                        <input type="file" name="image_' + AC_AUTOINCREMENT + '"> \
                    </td> \
                </tr>');
        
        new jscolor.color(jQuery('#color_bg' + AC_AUTOINCREMENT).get(0), {});
        new jscolor.color(jQuery('#color_text' + AC_AUTOINCREMENT).get(0), {});
        
        jQuery('#ac_autoincrement').val(AC_AUTOINCREMENT);
    });
    

    jQuery('.ac-add-duration').click(function(e) {
        e.preventDefault();
        
        var last_row = jQuery('#ac_availability_options_table_last_tr');
        
        AC_AUTOINCREMENT++;
        
        last_row.before('<tr> \
                    <td> \
                        <label><input type="checkbox" name="ac_duration_options[' + AC_AUTOINCREMENT + '][remove]" value="1"> \
                        Remove</label> \
                    </td> \
                    <td> \
                        <input type="text" name="ac_duration_options[' + AC_AUTOINCREMENT + '][name]" value=""> \
                    </td> \
                    <td> \
                        <input type="checkbox" name="ac_duration_options[' + AC_AUTOINCREMENT + '][minimal_stay]" value="1"> \
                    </td> \
                </tr>');
        
        jQuery('#ac_autoincrement').val(AC_AUTOINCREMENT);
    });
    

    jQuery('.ac-add-seasons').click(function(e) {
        e.preventDefault();
        
        var last_row = jQuery('#ac_availability_options_table_last_tr');
        
        AC_AUTOINCREMENT++;
        
        last_row.before('<tr> \
                    <td> \
                        <label><input type="checkbox" name="ac_seasons_options[' + AC_AUTOINCREMENT + '][remove]" value="1"> \
                        Remove</label> \
                    </td> \
                    <td> \
                        <input type="text" name="ac_seasons_options[' + AC_AUTOINCREMENT + '][name]" value=""> \
                    </td> \
                </tr>');
        
        jQuery('#ac_autoincrement').val(AC_AUTOINCREMENT);
    });
    
    jQuery('.add-new-pt').click(function(e) {
        e.preventDefault();
        
        var last_row = jQuery('#ac_table_last_tr');
        
        AC_PRICE_TABLE_ROWS++;
        
        var durations = AC_PRICE_TABLE_DURATIONS;
        
        while(durations.match("%d")) {
            durations = durations.replace('%d', AC_PRICE_TABLE_ROWS);
        }
        
        last_row.before('<tr> \
            <td><input type="checkbox" name="price_table[' + AC_PRICE_TABLE_ROWS + '][delete]" value="1"> Yes</td> \
            <td>' + AC_PRICE_TABLE_SEASONS.replace('%d', AC_PRICE_TABLE_ROWS) + '</td> \
            <td><input type="text" name="price_table[' + AC_PRICE_TABLE_ROWS + '][start]"class="date-picker"></td> \
            <td><input type="text" name="price_table[' + AC_PRICE_TABLE_ROWS + '][end]"class="date-picker"></td> \
            ' + durations + ' \
            <td>' + AC_PRICE_TABLE_MINIMAL_STAYS.replace('%d', AC_PRICE_TABLE_ROWS) + '</td> \
            <td>' + AC_PRICE_TABLE_CURRENCIES.replace('%d', AC_PRICE_TABLE_ROWS) + '</td> \
            </tr>');
        
        jQuery('#ac_pricetable_rows').val(AC_PRICE_TABLE_ROWS);
    
        jQuery('.date-picker').datepicker({
            dateFormat : 'dd/mm/yy'
        });
    });
    
    jQuery('.calendar-next').click(function() {
        var pid = jQuery(this).data('id');
        jQuery('#calendars-' + pid).html('');
        
        //console.log(AC_MONTH, AC_YEAR);
        
        if (typeof(AC_MONTHS[pid]) == 'undefined') {
            AC_MONTHS[pid] = AC_MONTH + AC_VIEW;
            AC_YEARS[pid] = AC_YEAR;
        } else {
            AC_MONTHS[pid] += AC_VIEW;
        }
        
        if (AC_MONTHS[pid] > 12) {
            AC_MONTHS[pid] -= 12;
            AC_YEARS[pid]++;
        }
        //console.log(pid, AC_MONTH, AC_VIEW, AC_MONTHS[pid], AC_YEARS[pid]);
        //console.log(AC_MONTH, AC_YEAR);
        
        var year = AC_YEARS[pid];
        var month = AC_MONTHS[pid];
        
        for(var i = 0; i < AC_VIEW; i++) {
            ac_calendar('calendars-' + pid, year, month, AC_ADMIN);
            
            if (month < 12) {
                month++;
            } else {
                month -= 11;
                year++;
            }
        }
        
        ac_colorize();
    });
    
    jQuery('.calendar-prev').click(function() {
        var pid = jQuery(this).data('id');
        jQuery('#calendars-' + pid).html('');
        
        //console.log(AC_MONTH, AC_YEAR);
        
        if (typeof(AC_MONTHS[pid]) == 'undefined') {
            AC_MONTHS[pid] = AC_MONTH - AC_VIEW;
            AC_YEARS[pid] = AC_YEAR;
        } else {
            AC_MONTHS[pid] -= AC_VIEW;
        }
        
        if (AC_MONTHS[pid] < 1) {
            AC_MONTHS[pid] += 12;
            AC_YEARS[pid]--;
        }
        
        //console.log(AC_MONTH, AC_YEAR);
        
        var year = AC_YEARS[pid];
        var month = AC_MONTHS[pid];
        
        for(var i = 0; i < AC_VIEW; i++) {
            ac_calendar('calendars-' + pid, year, month, AC_ADMIN);
            
            if (month < 12) {
                month++;
            } else {
                month -= 11;
                year++;
            }
        }
        
        ac_prepare_data();
        ac_colorize();
    });
    
    jQuery('.date-picker').datepicker({
        dateFormat : 'dd/mm/yy'
    });
});

var AC_MONTH_NAMES = {
    en : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    es : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    sv : ['Januari', 'Februari', 'Mars', 'April', 'Maj', 'Juni', 'Juli', 'Augusti', 'September', 'Oktober', 'November', 'December'],
    de : ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
    fr : ['Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre'],
    it : ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
    tr : ['Ocak', '?ubat', 'Mart', 'Nisan', 'May?s', 'Haziran', 'Temmuz', 'A?ustos', 'Eylül', 'Ekim', 'Kas?m', 'Aral?k'],
    pt : ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outobro', 'Novembro', 'Dezembro']
};

//var AC_DAY_NAMES = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
var AC_DAY_NAMES = {
    en : ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
    es : ['Lu', 'Ma', 'Mx', 'Ju', 'Vi', 'Sa', 'Do'],
    sv : ['MÃ¥', 'Ti', 'On', 'To', 'Fr', 'LÃ¶', 'SÃ¶'],
    de : ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'],
    fr : ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di'],
    it : ['Lu', 'Ma', 'Me', 'Gi', 'Ve', 'Sa', 'Do'],
    tr : ['Pa', 'Sa', 'Ça', 'Pe', 'Cu', 'Ca', 'Pa'],
    pt : ['Se', 'Te', 'Qu', 'Qu', 'Se', 'Sá', 'Do']
};

var AC_DATA = {};

function ac_calendar_february(year) {
    return ( (year % 100 != 0) && (year % 4 == 0) || (year % 400 == 0) ) ? 29 : 28;
}

function ac_day_amounts(year, month) {
    var day_amounts = [31, ac_calendar_february(year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    return day_amounts[month];
}

function ac_day_names() {
    var il = AC_DAY_NAMES.length;
    var s = '<tr>';
    
    for(var i = 0; i < il; i++) {
        s += '<th>' + AC_DAY_NAMES[i] + '</th>';
    }
    
    s += '</tr>';
    
    return s;
}

function ac_head(year, month) {
    return '<tr><th colspan="7">' + AC_MONTH_NAMES[AC_LANG][month] + ' ' + year + '</th></tr>';
}

function ac_calendar(id, year, month, admin) { //console.log('cal');
    var pid = jQuery('#' + id).data('id'); //console.log('pid', pid);
    month -= 1; // starting since 0
    
    var date = new Date(year, month, 1);
    var day = date.getDay();
    var day_amount = ac_day_amounts(year, month);
    
    if (day == 0) { // make Sunday last day
        day = 6;
    } else {
        day -= 1;
    }
    
    var days = ac_day_names();
    
    for(var i = day; i > 0; i--) {
        var _date = new Date(date.getTime() - (3600 * 24 * 1000 * i));
        days += '<td class="ac-day ac-inactive">' + _date.getDate() + '</td>';
    }
    
    if (admin) {
        admin = ' onclick="ac_click(this, ' + pid + ');"';
    } else {
        admin = '';
    }
    
    if (typeof(AC_PRICE_TABLE) != 'undefined' && typeof(AC_DURATIONS) != 'undefined') {
        var price_table = AC_PRICE_TABLE;
    } else {
        var price_table = new Array();
    }
    
    for(var i = 1; i <= day_amount; i++) {
        if (day > 6) {
            day = 0;
            days += '</tr><tr>';
        }
        
        var ts = new Date(year, month, i).getTime();
        
        if (price_table.length > 0) {
            var tooltip = '';
            
            jQuery.each(price_table, function(i, e) {
                if (e.start <= ts && e.end >= ts) {
                    //console.log('cur', e.currency, 'dur', e.duration, 'ac dur', AC_DURATIONS);
                    jQuery.each(e.duration, function(ii, ee) {
                        if (tooltip.length > 0) {
                            tooltip += ', ';
                        }
                        
                        tooltip += AC_DURATIONS[ii].name + ': ' + e.currency + ee;
                        //console.log('ii', ii, ee);
                    });
                }
            });
            
            //console.log(tooltip);
        }
        
        days += '<td class="ac-day" data-date="' + year + '-' + month + '-' + i + '" data-availability="' + AC_DEFAULT_AVAILABILITY + '"' + admin + ' title="' + AC_TITLES[AC_DEFAULT_AVAILABILITY] + '" data-tooltip="' + tooltip + '" onmouseover="showMyTitle(title);">' + i + '</td>';
        
        day++;
    }
    
    if (day != 0) {
        var last_day = i;
        
        for(var i = 1; i <= (7 - day); i++) {
            days += '<td class="ac-day ac-inactive">' + i + '</td>';
        }
    }
    
    var calendar = '<div class="ac-table-wrapper">' + ac_multi(year, month, admin) + '<table id="ac-calendar-' + year + '-' + month + '" class="ac-calendar"><thead>' + ac_head(year, month) + '</thead><tbody>' + days + '</tbody></table></div>';
    
    jQuery('#' + id).append(calendar);
}

function ac_click(el, pid) {
    var jt = jQuery(el);
    var ca = jt.data('availability');

    for(var i in AC_AVAILABILITY_OPTIONS) {
        if (AC_AVAILABILITY_OPTIONS[i] == ca) {
            break;
        }
    }

    i++;

    if (i == AC_AVAILABILITY_OPTIONS.length) {
        i = 0;
    }

    var a = AC_AVAILABILITY_OPTIONS[i];

    //console.log(jt.data('date'), AC_AVAILABILITY_OPTIONS, ca, a, 'ac-day ' + 'ac-availability-' + a);

    jt.data('availability', a);
    jt.attr('class', 'ac-day ac-availability-' + a);
    
    AC_DATA[pid][jt.data('date')] = a;
    
    ac_prepare_data(pid);
}

function ac_prepare_data(pid) {
    var json = JSON.stringify(AC_DATA[pid]);
    jQuery('.ac_data').val(json);
	
    //console.log('prepare', AC_DATA, JSON.stringify(AC_DATA));
}

function ac_init_colors() { //console.log('init');
    jQuery('.ac_data').each(function(i, e) {
        var $e = jQuery(e);
        var pid = $e.data('id');
        
        var json = $e.val();
        var data = JSON.parse('[' + json + ']');

        if (data[0] !== undefined) {
            data = data[0];

            if (data[0] !== undefined) {
                data = data[0];
            }
        } else {
            data = {};
        }
        AC_DATA[pid] = data;
    });
}

function ac_colorize() { //console.log('color');
    for(var pid in AC_DATA) {
        for(var i in AC_DATA[pid]) {
            var td = jQuery('#calendars-' + pid + ' .ac-day[data-date="' + i + '"]');

            td.data('availability', AC_DATA[pid][i]);
            td.attr('class', 'ac-day ac-availability-' + AC_DATA[pid][i]);
			
            td.attr('title', AC_TITLES[AC_DATA[pid][i]]);
        }
    }
    
    jQuery('#calendars .ac-day').each(function(i, e) {
        var td = jQuery(e);
        
        if (td.data('tooltip')) {
            td.attr('title', td.attr('title') + ': ' + td.data('tooltip'));
        }
    });
}

function ac_is_number(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    
    return true;
}

function ac_multi(year, month, admin) {
    if (!admin) {
        return '';
    }
    
    return '<a class="ac-change-status" href="#" title="" onclick="return ac_multi_process(' + year + ',' + month + ')">Change Status</a>';
}

function ac_multi_process(year, month) {
    var days = jQuery('#ac-calendar-' + year + '-' + month + ' .ac-day');
    days.click();
    
    return false;
}

function showMyTitle(title){
	jQuery('.ac-day').hover(function(){
	jQuery("p.tooltip").remove();
        jQuery(this).data('tipText', title).removeAttr('title');
        jQuery('<p class="tooltip"></p>')
        .text(title)
        .appendTo('body')
        .fadeIn('slow');
	}, function() {
        // Hover out code
        jQuery(this).attr('title', jQuery(this).data('tipText'));
        jQuery('.tooltip').remove();
	}).mousemove(function(e) {
        var mousex = e.pageX + 20; //Get X coordinates
        var mousey = e.pageY + 10; //Get Y coordinates
        jQuery('.tooltip')
        .css({ top: mousey, left: mousex })
	});
}

var AC_MONTHS = new Array();
var AC_YEARS = new Array();
