Calendar.setup = function(g) {
    function f(h, i) {
        if (typeof g[h] == "undefined") {
            g[h] = i
        }
    }
    f("inputField", null);
    f("displayArea", null);
    f("button", null);
    f("eventName", "click");
    f("ifFormat", "%Y/%m/%d");
    f("daFormat", "%Y/%m/%d");
    f("singleClick", true);
    f("disableFunc", null);
    f("dateStatusFunc", g.disableFunc);
    f("dateTooltipFunc", null);
    f("dateText", null);
    f("firstDay", null);
    f("align", "Br");
    f("range", [1900, 2999]);
    f("weekNumbers", true);
    f("flat", null);
    f("flatCallback", null);
    f("onSelect", null);
    f("onClose", null);
    f("onUpdate", null);
    f("date", null);
    f("showsTime", false);
    f("timeFormat", "24");
    f("electric", true);
    f("step", 2);
    f("position", null);
    f("cache", false);
    f("showOthers", false);
    f("multiple", null);


    var c = ["inputField", "displayArea", "button"];
    for (var b in c) {
        if (typeof g[c[b]] == "string") {
            g[c[b]] = document.getElementById(g[c[b]])
        }
    }
    if (!(g.flat || g.multiple || g.inputField || g.displayArea || g.button)) {
        alert("Calendar.setup:\n  Nothing to setup (no fields found).  Please check your code");
        return false
    }

    function a(i) {
        var h = i.params;
        var j = (i.dateClicked || h.electric);
        if (j && h.inputField) {
            h.inputField.value = i.date.print(h.ifFormat);
            if (typeof h.inputField.onchange == "function") {
                h.inputField.onchange()
            }
        }
        if (j && h.displayArea) {
            h.displayArea.innerHTML = i.date.print(h.daFormat)
        }
        if (j && typeof h.onUpdate == "function") {
            h.onUpdate(i)
        }
        if (j && h.flat) {
            if (typeof h.flatCallback == "function") {
                h.flatCallback(i)
            }
        }
        if (j && h.singleClick && i.dateClicked) {
            i.callCloseHandler()
        }
    }
    if (g.flat != null) {
        if (typeof g.flat == "string") {
            g.flat = document.getElementById(g.flat)
        }
        if (!g.flat) {
            alert("Calendar.setup:\n  Flat specified but can't find parent.");
            return false
        }
        var e = new Calendar(g.firstDay, g.date, g.onSelect || a);
        e.setDateToolTipHandler(g.dateTooltipFunc);
        e.showsOtherMonths = g.showOthers;
        e.showsTime = g.showsTime;
        e.time24 = (g.timeFormat == "24");
        e.params = g;
        e.weekNumbers = g.weekNumbers;
        e.setRange(g.range[0], g.range[1]);
        e.setDateStatusHandler(g.dateStatusFunc);
        e.getDateText = g.dateText;
        if (g.ifFormat) {
            e.setDateFormat(g.ifFormat)
        }
        if (g.inputField && typeof g.inputField.value == "string") {
            e.parseDate(g.inputField.value)
        }
        e.create(g.flat);
        e.show();
        return false
    }
    var d = g.button || g.displayArea || g.inputField;
    d["on" + g.eventName] = function() {
        var h = g.inputField || g.displayArea;
        var k = g.inputField ? g.ifFormat : g.daFormat;
        var o = false;
        var m = window.calendar;
        if (h) {
            g.date = Date.parseDate(h.value || h.innerHTML, k)
        }
        if (!(m && g.cache)) {
            window.calendar = m = new Calendar(g.firstDay, g.date, g.onSelect || a, g.onClose || function(i) {
                i.hide()
            });
            m.setDateToolTipHandler(g.dateTooltipFunc);
            m.showsTime = g.showsTime;
            m.time24 = (g.timeFormat == "24");
            m.weekNumbers = g.weekNumbers;
            o = true
        } else {
            if (g.date) {
                m.setDate(g.date)
            }
            m.hide()
        }
        if (g.multiple) {
            m.multiple = {};
            for (var j = g.multiple.length; --j >= 0;) {
                var n = g.multiple[j];
                var l = n.print("%Y%m%d");
                m.multiple[l] = n
            }
        }
        m.showsOtherMonths = g.showOthers;
        m.yearStep = g.step;
        m.setRange(g.range[0], g.range[1]);
        m.params = g;
        m.setDateStatusHandler(g.dateStatusFunc);
        m.getDateText = g.dateText;
        m.setDateFormat(k);
        if (o) {
            m.create()
        }
        m.refresh();
        if (!g.position) {
            m.showAtElement(g.button || g.displayArea || g.inputField, g.align)
        } else {
            m.showAt(g.position[0], g.position[1])
        }
        return false
    };
    return e
};
