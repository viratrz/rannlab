define(["jquery"], function(jQuery) {
    /*! flatpickr v3.0.6, @license MIT */
    function FlatpickrInstance(e, t) {
        function n(e) {
            return e.bind(De)
        }

        function a(e) {
            De.config.noCalendar && !De.selectedDates.length && (De.selectedDates = [De.now]), he(e), De.selectedDates.length && (!De.minDateHasTime || "input" !== e.type || e.target.value.length >= 2 ? (i(), le()) : setTimeout(function() {
                i(), le()
            }, 1e3))
        }

        function i() {
            if (De.config.enableTime) {
                var e = (parseInt(De.hourElement.value, 10) || 0) % (De.amPM ? 12 : 24),
                    t = (parseInt(De.minuteElement.value, 10) || 0) % 60,
                    n = De.config.enableSeconds ? (parseInt(De.secondElement.value, 10) || 0) % 60 : 0;
                void 0 !== De.amPM && (e = e % 12 + 12 * ("PM" === De.amPM.textContent)), De.minDateHasTime && 0 === pe(De.latestSelectedDateObj, De.config.minDate) && (e = Math.max(e, De.config.minDate.getHours())) === De.config.minDate.getHours() && (t = Math.max(t, De.config.minDate.getMinutes())), De.maxDateHasTime && 0 === pe(De.latestSelectedDateObj, De.config.maxDate) && (e = Math.min(e, De.config.maxDate.getHours())) === De.config.maxDate.getHours() && (t = Math.min(t, De.config.maxDate.getMinutes())), o(e, t, n)
            }
        }

        function r(e) {
            var t = e || De.latestSelectedDateObj;
            t && o(t.getHours(), t.getMinutes(), t.getSeconds())
        }

        function o(e, t, n) {
            De.selectedDates.length && De.latestSelectedDateObj.setHours(e % 24, t, n || 0, 0), De.config.enableTime && !De.isMobile && (De.hourElement.value = De.pad(De.config.time_24hr ? e : (12 + e) % 12 + 12 * (e % 12 == 0)), De.minuteElement.value = De.pad(t), De.config.time_24hr || (De.amPM.textContent = e >= 12 ? "PM" : "AM"), !0 === De.config.enableSeconds && (De.secondElement.value = De.pad(n)))
        }

        function l(e) {
            var t = e.target.value;
            e.delta && (t = (parseInt(t) + e.delta).toString()), 4 !== t.length && "Enter" !== e.key || (De.currentYearElement.blur(), /[^\d]/.test(t) || O(t))
        }

        function c(e, t, n) {
            return t instanceof Array ? t.forEach(function(t) {
                return c(e, t, n)
            }) : e instanceof Array ? e.forEach(function(e) {
                return c(e, t, n)
            }) : (e.addEventListener(t, n), void De._handlers.push({
                element: e,
                event: t,
                handler: n
            }))
        }

        function s(e) {
            return function(t) {
                return 1 === t.which && e(t)
            }
        }

        function d() {
            if (De._handlers = [], De._animationLoop = [], De.config.wrap && ["open", "close", "toggle", "clear"].forEach(function(e) {
                Array.prototype.forEach.call(De.element.querySelectorAll("[data-" + e + "]"), function(t) {
                    return c(t, "mousedown", s(De[e]))
                })
            }), De.isMobile) return ee();
            if (De.debouncedResize = ge(j, 50), De.triggerChange = function() {
                ne("Change")
            }, De.debouncedChange = ge(De.triggerChange, 300), "range" === De.config.mode && De.daysContainer && c(De.daysContainer, "mouseover", function(e) {
                return P(e.target)
            }), c(window.document.body, "keydown", L), De.config.static || c(De._input, "keydown", L), De.config.inline || De.config.static || c(window, "resize", De.debouncedResize), void 0 !== window.ontouchstart && c(window.document, "touchstart", Y), c(window.document, "mousedown", s(Y)), c(De._input, "blur", Y), !0 === De.config.clickOpens && (c(De._input, "focus", De.open), c(De._input, "mousedown", s(De.open))), De.config.noCalendar || (De.monthNav.addEventListener("wheel", function(e) {
                return e.preventDefault()
            }), c(De.monthNav, "wheel", ge(se, 10)), c(De.monthNav, "mousedown", s(de)), c(De.monthNav, ["keyup", "increment"], l), c(De.daysContainer, "mousedown", s(U)), De.config.animate && (c(De.daysContainer, ["webkitAnimationEnd", "animationend"], f), c(De.monthNav, ["webkitAnimationEnd", "animationend"], m))), De.config.enableTime) {
                var e = function(e) {
                    return e.target.select()
                };
                c(De.timeContainer, ["wheel", "input", "increment"], a), c(De.timeContainer, "mousedown", s(p)), c(De.timeContainer, ["wheel", "increment"], De.debouncedChange), c(De.timeContainer, "input", De.triggerChange), c([De.hourElement, De.minuteElement], "focus", e), void 0 !== De.secondElement && c(De.secondElement, "focus", function() {
                    return De.secondElement.select()
                }), void 0 !== De.amPM && c(De.amPM, "mousedown", s(function(e) {
                    a(e), De.triggerChange(e)
                }))
            }
        }

        function u() {
            for (var e = De._animationLoop.length; e--;) De._animationLoop[e](), De._animationLoop.splice(e, 1)
        }

        function f(e) {
            if (De.daysContainer.childNodes.length > 1) switch (e.animationName) {
                case "fpSlideLeft":
                    De.daysContainer.lastChild.classList.remove("slideLeftNew"), De.daysContainer.removeChild(De.daysContainer.firstChild), De.days = De.daysContainer.firstChild, u();
                    break;
                case "fpSlideRight":
                    De.daysContainer.firstChild.classList.remove("slideRightNew"), De.daysContainer.removeChild(De.daysContainer.lastChild), De.days = De.daysContainer.firstChild, u()
            }
        }

        function m(e) {
            switch (e.animationName) {
                case "fpSlideLeftNew":
                case "fpSlideRightNew":
                    De.navigationCurrentMonth.classList.remove("slideLeftNew"), De.navigationCurrentMonth.classList.remove("slideRightNew");
                    for (var t = De.navigationCurrentMonth; t.nextSibling && /curr/.test(t.nextSibling.className);) De.monthNav.removeChild(t.nextSibling);
                    for (; t.previousSibling && /curr/.test(t.previousSibling.className);) De.monthNav.removeChild(t.previousSibling);
                    De.oldCurMonth = null
            }
        }

        function g(e) {
            e = e ? De.parseDate(e) : De.latestSelectedDateObj || (De.config.minDate > De.now ? De.config.minDate : De.config.maxDate && De.config.maxDate < De.now ? De.config.maxDate : De.now);
            try {
                De.currentYear = e.getFullYear(), De.currentMonth = e.getMonth()
            } catch (t) {
                console.error(t.stack), console.warn("Invalid date supplied: " + e)
            }
            De.redraw()
        }

        function p(e) {
            ~e.target.className.indexOf("arrow") && h(e, e.target.classList.contains("arrowUp") ? 1 : -1)
        }

        function h(e, t, n) {
            var a = n || e.target.parentNode.childNodes[0],
                i = ae("increment");
            i.delta = t, a.dispatchEvent(i)
        }

        function D(e) {
            var t = ue("div", "numInputWrapper"),
                n = ue("input", "numInput " + e),
                a = ue("span", "arrowUp"),
                i = ue("span", "arrowDown");
            return n.type = "text", n.pattern = "\\d*", t.appendChild(n), t.appendChild(a), t.appendChild(i), t
        }

        function v() {
            var e = window.document.createDocumentFragment();
            De.calendarContainer = ue("div", "flatpickr-calendar"), De.calendarContainer.tabIndex = -1, De.config.noCalendar || (e.appendChild(k()), De.innerContainer = ue("div", "flatpickr-innerContainer"), De.config.weekNumbers && De.innerContainer.appendChild(N()), De.rContainer = ue("div", "flatpickr-rContainer"), De.rContainer.appendChild(E()), De.daysContainer || (De.daysContainer = ue("div", "flatpickr-days"), De.daysContainer.tabIndex = -1), M(), De.rContainer.appendChild(De.daysContainer), De.innerContainer.appendChild(De.rContainer), e.appendChild(De.innerContainer)), De.config.enableTime && e.appendChild(x()), me(De.calendarContainer, "rangeMode", "range" === De.config.mode), me(De.calendarContainer, "animate", De.config.animate), De.calendarContainer.appendChild(e);
            var t = De.config.appendTo && De.config.appendTo.nodeType;
            if (De.config.inline || De.config.static) {
                if (De.calendarContainer.classList.add(De.config.inline ? "inline" : "static"), De.config.inline && !t) return De.element.parentNode.insertBefore(De.calendarContainer, De._input.nextSibling);
                if (De.config.static) {
                    var n = ue("div", "flatpickr-wrapper");
                    return De.element.parentNode.insertBefore(n, De.element), n.appendChild(De.element), De.altInput && n.appendChild(De.altInput), void n.appendChild(De.calendarContainer)
                }
            }(t ? De.config.appendTo : window.document.body).appendChild(De.calendarContainer)
        }

        function C(e, t, n, a) {
            var i = A(t, !0),
                r = ue("span", "flatpickr-day " + e, t.getDate());
            return r.dateObj = t, r.$i = a, r.setAttribute("aria-label", De.formatDate(t, De.config.ariaDateFormat)), 0 === pe(t, De.now) && (De.todayDateElem = r, r.classList.add("today")), i ? (r.tabIndex = -1, ie(t) && (r.classList.add("selected"), De.selectedDateElem = r, "range" === De.config.mode && (me(r, "startRange", 0 === pe(t, De.selectedDates[0])), me(r, "endRange", 0 === pe(t, De.selectedDates[1]))))) : (r.classList.add("disabled"), De.selectedDates[0] && t > De.minRangeDate && t < De.selectedDates[0] ? De.minRangeDate = t : De.selectedDates[0] && t < De.maxRangeDate && t > De.selectedDates[0] && (De.maxRangeDate = t)), "range" === De.config.mode && (re(t) && !ie(t) && r.classList.add("inRange"), 1 === De.selectedDates.length && (t < De.minRangeDate || t > De.maxRangeDate) && r.classList.add("notAllowed")), De.config.weekNumbers && "prevMonthDay" !== e && n % 7 == 1 && De.weekNumbers.insertAdjacentHTML("beforeend", "<span class='disabled flatpickr-day'>" + De.config.getWeek(t) + "</span>"), ne("DayCreate", r), r
        }

        function w(e, t) {
            var n = e + t || 0,
                a = void 0 !== e ? De.days.childNodes[n] : De.selectedDateElem || De.todayDateElem || De.days.childNodes[0],
                i = function() {
                    (a = a || De.days.childNodes[n]).focus(), "range" === De.config.mode && P(a)
                };
            if (void 0 === a && 0 !== t) return t > 0 ? (De.changeMonth(1), n %= 42) : t < 0 && (De.changeMonth(-1), n += 42), b(i);
            i()
        }

        function b(e) {
            if (!0 === De.config.animate) return De._animationLoop.push(e);
            e()
        }

        function M(e) {
            var t = (new Date(De.currentYear, De.currentMonth, 1).getDay() - De.l10n.firstDayOfWeek + 7) % 7,
                n = "range" === De.config.mode;
            De.prevMonthDays = De.utils.getDaysinMonth((De.currentMonth - 1 + 12) % 12), De.selectedDateElem = void 0, De.todayDateElem = void 0;
            var a = De.utils.getDaysinMonth(),
                i = window.document.createDocumentFragment(),
                r = De.prevMonthDays + 1 - t,
                o = 0;
            for (De.config.weekNumbers && De.weekNumbers.firstChild && (De.weekNumbers.textContent = ""), n && (De.minRangeDate = new Date(De.currentYear, De.currentMonth - 1, r), De.maxRangeDate = new Date(De.currentYear, De.currentMonth + 1, (42 - t) % a)); r <= De.prevMonthDays; r++, o++) i.appendChild(C("prevMonthDay", new Date(De.currentYear, De.currentMonth - 1, r), r, o));
            for (r = 1; r <= a; r++, o++) i.appendChild(C("", new Date(De.currentYear, De.currentMonth, r), r, o));
            for (var l = a + 1; l <= 42 - t; l++, o++) i.appendChild(C("nextMonthDay", new Date(De.currentYear, De.currentMonth + 1, l % a), l, o));
            n && 1 === De.selectedDates.length && i.childNodes[0] ? (De._hidePrevMonthArrow = De._hidePrevMonthArrow || De.minRangeDate > i.childNodes[0].dateObj, De._hideNextMonthArrow = De._hideNextMonthArrow || De.maxRangeDate < new Date(De.currentYear, De.currentMonth + 1, 1)) : oe();
            var c = ue("div", "dayContainer");
            if (c.appendChild(i), De.config.animate && void 0 !== e)
                for (; De.daysContainer.childNodes.length > 1;) De.daysContainer.removeChild(De.daysContainer.firstChild);
            else y(De.daysContainer);
            return e >= 0 ? De.daysContainer.appendChild(c) : De.daysContainer.insertBefore(c, De.daysContainer.firstChild), De.days = De.daysContainer.firstChild, De.daysContainer
        }

        function y(e) {
            for (; e.firstChild;) e.removeChild(e.firstChild)
        }

        function k() {
            var e = window.document.createDocumentFragment();
            De.monthNav = ue("div", "flatpickr-month"), De.prevMonthNav = ue("span", "flatpickr-prev-month"), De.prevMonthNav.innerHTML = De.config.prevArrow, De.currentMonthElement = ue("span", "cur-month"), De.currentMonthElement.title = De.l10n.scrollTitle;
            var t = D("cur-year");
            return De.currentYearElement = t.childNodes[0], De.currentYearElement.title = De.l10n.scrollTitle, De.config.minDate && (De.currentYearElement.min = De.config.minDate.getFullYear()), De.config.maxDate && (De.currentYearElement.max = De.config.maxDate.getFullYear(), De.currentYearElement.disabled = De.config.minDate && De.config.minDate.getFullYear() === De.config.maxDate.getFullYear()), De.nextMonthNav = ue("span", "flatpickr-next-month"), De.nextMonthNav.innerHTML = De.config.nextArrow, De.navigationCurrentMonth = ue("span", "flatpickr-current-month"), De.navigationCurrentMonth.appendChild(De.currentMonthElement), De.navigationCurrentMonth.appendChild(t), e.appendChild(De.prevMonthNav), e.appendChild(De.navigationCurrentMonth), e.appendChild(De.nextMonthNav), De.monthNav.appendChild(e), Object.defineProperty(De, "_hidePrevMonthArrow", {
                get: function() {
                    return this.__hidePrevMonthArrow
                },
                set: function(e) {
                    this.__hidePrevMonthArrow !== e && (De.prevMonthNav.style.display = e ? "none" : "block"), this.__hidePrevMonthArrow = e
                }
            }), Object.defineProperty(De, "_hideNextMonthArrow", {
                get: function() {
                    return this.__hideNextMonthArrow
                },
                set: function(e) {
                    this.__hideNextMonthArrow !== e && (De.nextMonthNav.style.display = e ? "none" : "block"), this.__hideNextMonthArrow = e
                }
            }), oe(), De.monthNav
        }

        function x() {
            De.calendarContainer.classList.add("hasTime"), De.config.noCalendar && De.calendarContainer.classList.add("noCalendar"), De.timeContainer = ue("div", "flatpickr-time"), De.timeContainer.tabIndex = -1;
            var e = ue("span", "flatpickr-time-separator", ":"),
                t = D("flatpickr-hour");
            De.hourElement = t.childNodes[0];
            var n = D("flatpickr-minute");
            if (De.minuteElement = n.childNodes[0], De.hourElement.tabIndex = De.minuteElement.tabIndex = -1, De.hourElement.value = De.pad(De.latestSelectedDateObj ? De.latestSelectedDateObj.getHours() : De.config.defaultHour), De.minuteElement.value = De.pad(De.latestSelectedDateObj ? De.latestSelectedDateObj.getMinutes() : De.config.defaultMinute), De.hourElement.step = De.config.hourIncrement, De.minuteElement.step = De.config.minuteIncrement, De.hourElement.min = De.config.time_24hr ? 0 : 1, De.hourElement.max = De.config.time_24hr ? 23 : 12, De.minuteElement.min = 0, De.minuteElement.max = 59, De.hourElement.title = De.minuteElement.title = De.l10n.scrollTitle, De.timeContainer.appendChild(t), De.timeContainer.appendChild(e), De.timeContainer.appendChild(n), De.config.time_24hr && De.timeContainer.classList.add("time24hr"), De.config.enableSeconds) {
                De.timeContainer.classList.add("hasSeconds");
                var a = D("flatpickr-second");
                De.secondElement = a.childNodes[0], De.secondElement.value = De.latestSelectedDateObj ? De.pad(De.latestSelectedDateObj.getSeconds()) : "00", De.secondElement.step = De.minuteElement.step, De.secondElement.min = De.minuteElement.min, De.secondElement.max = De.minuteElement.max, De.timeContainer.appendChild(ue("span", "flatpickr-time-separator", ":")), De.timeContainer.appendChild(a)
            }
            return De.config.time_24hr || (De.amPM = ue("span", "flatpickr-am-pm", ["AM", "PM"][De.hourElement.value > 11 | 0]), De.amPM.title = De.l10n.toggleTitle, De.amPM.tabIndex = -1, De.timeContainer.appendChild(De.amPM)), De.timeContainer
        }

        function E() {
            De.weekdayContainer || (De.weekdayContainer = ue("div", "flatpickr-weekdays"));
            var e = De.l10n.firstDayOfWeek,
                t = De.l10n.weekdays.shorthand.slice();
            return e > 0 && e < t.length && (t = [].concat(t.splice(e, t.length), t.splice(0, e))), De.weekdayContainer.innerHTML = "\n\t\t<span class=flatpickr-weekday>\n\t\t\t" + t.join("</span><span class=flatpickr-weekday>") + "\n\t\t</span>\n\t\t", De.weekdayContainer
        }

        function N() {
            return De.calendarContainer.classList.add("hasWeeks"), De.weekWrapper = ue("div", "flatpickr-weekwrapper"), De.weekWrapper.appendChild(ue("span", "flatpickr-weekday", De.l10n.weekAbbreviation)), De.weekNumbers = ue("div", "flatpickr-weeks"), De.weekWrapper.appendChild(De.weekNumbers), De.weekWrapper
        }

        function _(e, t, n) {
            var a = (t = void 0 === t || t) ? e : e - De.currentMonth,
                i = !De.config.animate || !1 === n;
            if (!(a < 0 && De._hidePrevMonthArrow || a > 0 && De._hideNextMonthArrow)) {
                if (De.currentMonth += a, (De.currentMonth < 0 || De.currentMonth > 11) && (De.currentYear += De.currentMonth > 11 ? 1 : -1, De.currentMonth = (De.currentMonth + 12) % 12, ne("YearChange")), M(i ? void 0 : a), i) return ne("MonthChange"), oe();
                var r = De.navigationCurrentMonth;
                if (a < 0)
                    for (; r.nextSibling && /curr/.test(r.nextSibling.className);) De.monthNav.removeChild(r.nextSibling);
                else if (a > 0)
                    for (; r.previousSibling && /curr/.test(r.previousSibling.className);) De.monthNav.removeChild(r.previousSibling);
                if (De.oldCurMonth = De.navigationCurrentMonth, De.navigationCurrentMonth = De.monthNav.insertBefore(De.oldCurMonth.cloneNode(!0), a > 0 ? De.oldCurMonth.nextSibling : De.oldCurMonth), a > 0 ? (De.daysContainer.firstChild.classList.add("slideLeft"), De.daysContainer.lastChild.classList.add("slideLeftNew"), De.oldCurMonth.classList.add("slideLeft"), De.navigationCurrentMonth.classList.add("slideLeftNew")) : a < 0 && (De.daysContainer.firstChild.classList.add("slideRightNew"), De.daysContainer.lastChild.classList.add("slideRight"), De.oldCurMonth.classList.add("slideRight"), De.navigationCurrentMonth.classList.add("slideRightNew")), De.currentMonthElement = De.navigationCurrentMonth.firstChild, De.currentYearElement = De.navigationCurrentMonth.lastChild.childNodes[0], oe(), De.oldCurMonth.firstChild.textContent = De.utils.monthToStr(De.currentMonth - a), ne("MonthChange"), document.activeElement && document.activeElement.$i) {
                    var o = document.activeElement.$i;
                    b(function() {
                        w(o, 0)
                    })
                }
            }
        }

        function T(e) {
            De.input.value = "", De.altInput && (De.altInput.value = ""), De.mobileInput && (De.mobileInput.value = ""), De.selectedDates = [], De.latestSelectedDateObj = void 0, De.showTimeInput = !1, De.redraw(), !1 !== e && ne("Change")
        }

        function I() {
            De.isOpen = !1, De.isMobile || (De.calendarContainer.classList.remove("open"), De._input.classList.remove("active")), ne("Close")
        }

        function S() {
            void 0 !== De.config && ne("Destroy");
            for (var e = De._handlers.length; e--;) {
                var t = De._handlers[e];
                t.element.removeEventListener(t.event, t.handler)
            }
            De._handlers = [], De.mobileInput ? (De.mobileInput.parentNode && De.mobileInput.parentNode.removeChild(De.mobileInput), De.mobileInput = null) : De.calendarContainer && De.calendarContainer.parentNode && De.calendarContainer.parentNode.removeChild(De.calendarContainer), De.altInput && (De.input.type = "text", De.altInput.parentNode && De.altInput.parentNode.removeChild(De.altInput), delete De.altInput), De.input && (De.input.type = De.input._type, De.input.classList.remove("flatpickr-input"), De.input.removeAttribute("readonly"), De.input.value = ""), ["_showTimeInput", "latestSelectedDateObj", "_hideNextMonthArrow", "_hidePrevMonthArrow", "__hideNextMonthArrow", "__hidePrevMonthArrow", "isMobile", "isOpen", "selectedDateElem", "minDateHasTime", "maxDateHasTime", "days", "daysContainer", "_input", "_positionElement", "innerContainer", "rContainer", "monthNav", "todayDateElem", "calendarContainer", "weekdayContainer", "prevMonthNav", "nextMonthNav", "currentMonthElement", "currentYearElement", "navigationCurrentMonth", "selectedDateElem", "config"].forEach(function(e) {
                return delete De[e]
            })
        }

        function F(e) {
            return !(!De.config.appendTo || !De.config.appendTo.contains(e)) || De.calendarContainer.contains(e)
        }

        function Y(e) {
            if (De.isOpen && !De.config.inline) {
                var t = F(e.target),
                    n = e.target === De.input || e.target === De.altInput || De.element.contains(e.target) || e.path && e.path.indexOf && (~e.path.indexOf(De.input) || ~e.path.indexOf(De.altInput));
                ("blur" === e.type ? n && e.relatedTarget && !F(e.relatedTarget) : !n && !t) && -1 === De.config.ignoredFocusElements.indexOf(e.target) && (De.close(), "range" === De.config.mode && 1 === De.selectedDates.length && (De.clear(!1), De.redraw()))
            }
        }

        function O(e) {
            if (!(!e || De.currentYearElement.min && e < De.currentYearElement.min || De.currentYearElement.max && e > De.currentYearElement.max)) {
                var t = parseInt(e, 10),
                    n = De.currentYear !== t;
                De.currentYear = t || De.currentYear, De.config.maxDate && De.currentYear === De.config.maxDate.getFullYear() ? De.currentMonth = Math.min(De.config.maxDate.getMonth(), De.currentMonth) : De.config.minDate && De.currentYear === De.config.minDate.getFullYear() && (De.currentMonth = Math.max(De.config.minDate.getMonth(), De.currentMonth)), n && (De.redraw(), ne("YearChange"))
            }
        }

        function A(e, t) {
            if (De.config.minDate && pe(e, De.config.minDate, void 0 !== t ? t : !De.minDateHasTime) < 0 || De.config.maxDate && pe(e, De.config.maxDate, void 0 !== t ? t : !De.maxDateHasTime) > 0) return !1;
            if (!De.config.enable.length && !De.config.disable.length) return !0;
            for (var n, a = De.parseDate(e, null, !0), i = De.config.enable.length > 0, r = i ? De.config.enable : De.config.disable, o = 0; o < r.length; o++) {
                if ((n = r[o]) instanceof Function && n(a)) return i;
                if (n instanceof Date && n.getTime() === a.getTime()) return i;
                if ("string" == typeof n && De.parseDate(n, null, !0).getTime() === a.getTime()) return i;
                if ("object" === (void 0 === n ? "undefined" : _typeof(n)) && n.from && n.to && a >= n.from && a <= n.to) return i
            }
            return !i
        }

        function L(e) {
            var t = e.target === De._input,
                n = F(e.target),
                r = De.config.allowInput,
                o = De.isOpen && (!r || !t),
                l = De.config.inline && t && !r;
            if ("Enter" === e.key && r && t) return De.setDate(De._input.value, !0, e.target === De.altInput ? De.config.altFormat : De.config.dateFormat), e.target.blur();
            if (n || o || l) {
                var c = De.timeContainer && De.timeContainer.contains(e.target);
                switch (e.key) {
                    case "Enter":
                        c ? le() : U(e);
                        break;
                    case "Escape":
                        e.preventDefault(), De.close();
                        break;
                    case "ArrowLeft":
                    case "ArrowRight":
                        if (!c)
                            if (e.preventDefault(), De.daysContainer) {
                                var s = "ArrowRight" === e.key ? 1 : -1;
                                e.ctrlKey ? _(s, !0) : w(e.target.$i, s)
                            } else De.config.enableTime && !c && De.hourElement.focus();
                        break;
                    case "ArrowUp":
                    case "ArrowDown":
                        e.preventDefault();
                        var d = "ArrowDown" === e.key ? 1 : -1;
                        De.daysContainer ? e.ctrlKey ? (O(De.currentYear - d), w(e.target.$i, 0)) : c || w(e.target.$i, 7 * d) : De.config.enableTime && (c || De.hourElement.focus(), a(e));
                        break;
                    case "Tab":
                        e.target === De.hourElement ? (e.preventDefault(), De.minuteElement.select()) : e.target === De.minuteElement && (De.secondElement || De.amPM) ? (e.preventDefault(), (De.secondElement || De.amPM).focus()) : e.target === De.secondElement && (e.preventDefault(), De.amPM.focus());
                        break;
                    case "a":
                        e.target === De.amPM && (De.amPM.textContent = "AM", i(), le());
                        break;
                    case "p":
                        e.target === De.amPM && (De.amPM.textContent = "PM", i(), le())
                }
                ne("KeyDown", e)
            }
        }

        function P(e) {
            if (1 === De.selectedDates.length && e.classList.contains("flatpickr-day")) {
                for (var t = e.dateObj, n = De.parseDate(De.selectedDates[0], null, !0), a = Math.min(t.getTime(), De.selectedDates[0].getTime()), i = Math.max(t.getTime(), De.selectedDates[0].getTime()), r = !1, o = a; o < i; o += De.utils.duration.DAY)
                    if (!A(new Date(o))) {
                        r = !0;
                        break
                    } for (var l = De.days.childNodes[0].dateObj.getTime(), c = 0; c < 42; c++, l += De.utils.duration.DAY) {
                    (function(o, l) {
                        var c = o < De.minRangeDate.getTime() || o > De.maxRangeDate.getTime(),
                            s = De.days.childNodes[l];
                        if (c) return De.days.childNodes[l].classList.add("notAllowed"), ["inRange", "startRange", "endRange"].forEach(function(e) {
                            s.classList.remove(e)
                        }), "continue";
                        if (r && !c) return "continue";
                        ["startRange", "inRange", "endRange", "notAllowed"].forEach(function(e) {
                            s.classList.remove(e)
                        });
                        var d = Math.max(De.minRangeDate.getTime(), a),
                            u = Math.min(De.maxRangeDate.getTime(), i);
                        e.classList.add(t < De.selectedDates[0] ? "startRange" : "endRange"), n < t && o === n.getTime() ? s.classList.add("startRange") : n > t && o === n.getTime() && s.classList.add("endRange"), o >= d && o <= u && s.classList.add("inRange")
                    })(l, c)
                }
            }
        }

        function j() {
            !De.isOpen || De.config.static || De.config.inline || J()
        }

        function H(e, t) {
            if (De.isMobile) return e && (e.preventDefault(), e.target.blur()), setTimeout(function() {
                De.mobileInput.click()
            }, 0), void ne("Open");
            De.isOpen || De._input.disabled || De.config.inline || (De.isOpen = !0, De.calendarContainer.classList.add("open"), J(t), De._input.classList.add("active"), ne("Open"))
        }

        function R(e) {
            return function(t) {
                var n = De.config["_" + e + "Date"] = De.parseDate(t),
                    a = De.config["_" + ("min" === e ? "max" : "min") + "Date"],
                    i = t && n instanceof Date;
                i && (De[e + "DateHasTime"] = n.getHours() || n.getMinutes() || n.getSeconds()), De.selectedDates && (De.selectedDates = De.selectedDates.filter(function(e) {
                    return A(e)
                }), De.selectedDates.length || "min" !== e || r(n), le()), De.daysContainer && (K(), i ? De.currentYearElement[e] = n.getFullYear() : De.currentYearElement.removeAttribute(e), De.currentYearElement.disabled = a && n && a.getFullYear() === n.getFullYear())
            }
        }

        function W() {
            var e = ["wrap", "weekNumbers", "allowInput", "clickOpens", "time_24hr", "enableTime", "noCalendar", "altInput", "shorthandCurrentMonth", "inline", "static", "enableSeconds", "disableMobile"],
                t = ["onChange", "onClose", "onDayCreate", "onDestroy", "onKeyDown", "onMonthChange", "onOpen", "onParseConfig", "onReady", "onValueUpdate", "onYearChange"];
            De.config = Object.create(flatpickr.defaultConfig);
            var a = _extends({}, De.instanceConfig, JSON.parse(JSON.stringify(De.element.dataset || {})));
            De.config.parseDate = a.parseDate, De.config.formatDate = a.formatDate, Object.defineProperty(De.config, "enable", {
                get: function() {
                    return De.config._enable || []
                },
                set: function(e) {
                    return De.config._enable = G(e)
                }
            }), Object.defineProperty(De.config, "disable", {
                get: function() {
                    return De.config._disable || []
                },
                set: function(e) {
                    return De.config._disable = G(e)
                }
            }), _extends(De.config, a), !a.dateFormat && a.enableTime && (De.config.dateFormat = De.config.noCalendar ? "H:i" + (De.config.enableSeconds ? ":S" : "") : flatpickr.defaultConfig.dateFormat + " H:i" + (De.config.enableSeconds ? ":S" : "")), a.altInput && a.enableTime && !a.altFormat && (De.config.altFormat = De.config.noCalendar ? "h:i" + (De.config.enableSeconds ? ":S K" : " K") : flatpickr.defaultConfig.altFormat + " h:i" + (De.config.enableSeconds ? ":S" : "") + " K"), Object.defineProperty(De.config, "minDate", {
                get: function() {
                    return this._minDate
                },
                set: R("min")
            }), Object.defineProperty(De.config, "maxDate", {
                get: function() {
                    return this._maxDate
                },
                set: R("max")
            }), De.config.minDate = a.minDate, De.config.maxDate = a.maxDate;
            for (var i = 0; i < e.length; i++) De.config[e[i]] = !0 === De.config[e[i]] || "true" === De.config[e[i]];
            for (var r = t.length; r--;) void 0 !== De.config[t[r]] && (De.config[t[r]] = fe(De.config[t[r]] || []).map(n));
            for (var o = 0; o < De.config.plugins.length; o++) {
                var l = De.config.plugins[o](De) || {};
                for (var c in l) De.config[c] instanceof Array || ~t.indexOf(c) ? De.config[c] = fe(l[c]).map(n).concat(De.config[c]) : void 0 === a[c] && (De.config[c] = l[c])
            }
            ne("ParseConfig")
        }

        function B() {
            "object" !== _typeof(De.config.locale) && void 0 === flatpickr.l10ns[De.config.locale] && console.warn("flatpickr: invalid locale " + De.config.locale), De.l10n = _extends(Object.create(flatpickr.l10ns.default), "object" === _typeof(De.config.locale) ? De.config.locale : "default" !== De.config.locale ? flatpickr.l10ns[De.config.locale] || {} : {})
        }

        function J() {
            var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : De._positionElement;
            if (void 0 !== De.calendarContainer) {
                var t = De.calendarContainer.offsetHeight,
                    n = De.calendarContainer.offsetWidth,
                    a = De.config.position,
                    i = e.getBoundingClientRect(),
                    r = window.innerHeight - i.bottom,
                    o = "above" === a || "below" !== a && r < t && i.top > t,
                    l = window.pageYOffset + i.top + (o ? -t - 2 : e.offsetHeight + 2);
                if (me(De.calendarContainer, "arrowTop", !o), me(De.calendarContainer, "arrowBottom", o), !De.config.inline) {
                    var c = window.pageXOffset + i.left,
                        s = window.document.body.offsetWidth - i.right,
                        d = c + n > window.document.body.offsetWidth;
                    me(De.calendarContainer, "rightMost", d), De.config.static || (De.calendarContainer.style.top = l + "px", d ? (De.calendarContainer.style.left = "auto", De.calendarContainer.style.right = s + "px") : (De.calendarContainer.style.left = c + "px", De.calendarContainer.style.right = "auto"))
                }
            }
        }

        function K() {
            De.config.noCalendar || De.isMobile || (E(), oe(), M())
        }

        function U(e) {
            if (e.preventDefault(), e.stopPropagation(), e.target.classList.contains("flatpickr-day") && !e.target.classList.contains("disabled") && !e.target.classList.contains("notAllowed")) {
                var t = De.latestSelectedDateObj = new Date(e.target.dateObj.getTime()),
                    n = t.getMonth() !== De.currentMonth && "range" !== De.config.mode;
                if (De.selectedDateElem = e.target, "single" === De.config.mode) De.selectedDates = [t];
                else if ("multiple" === De.config.mode) {
                    var a = ie(t);
                    a ? De.selectedDates.splice(a, 1) : De.selectedDates.push(t)
                } else "range" === De.config.mode && (2 === De.selectedDates.length && De.clear(), De.selectedDates.push(t), 0 !== pe(t, De.selectedDates[0], !0) && De.selectedDates.sort(function(e, t) {
                    return e.getTime() - t.getTime()
                }));
                if (i(), n) {
                    var o = De.currentYear !== t.getFullYear();
                    De.currentYear = t.getFullYear(), De.currentMonth = t.getMonth(), o && ne("YearChange"), ne("MonthChange")
                }
                if (M(), De.minDateHasTime && De.config.enableTime && 0 === pe(t, De.config.minDate) && r(De.config.minDate), le(), De.config.enableTime && setTimeout(function() {
                    return De.showTimeInput = !0
                }, 50), "range" === De.config.mode && (1 === De.selectedDates.length ? (P(e.target), De._hidePrevMonthArrow = De._hidePrevMonthArrow || De.minRangeDate > De.days.childNodes[0].dateObj, De._hideNextMonthArrow = De._hideNextMonthArrow || De.maxRangeDate < new Date(De.currentYear, De.currentMonth + 1, 1)) : oe()), ne("Change"), n ? b(function() {
                    return De.selectedDateElem.focus()
                }) : w(e.target.$i, 0), De.config.enableTime && setTimeout(function() {
                    return De.hourElement.select()
                }, 451), De.config.closeOnSelect) {
                    var l = "single" === De.config.mode && !De.config.enableTime,
                        c = "range" === De.config.mode && 2 === De.selectedDates.length && !De.config.enableTime;
                    (l || c) && De.close()
                }
            }
        }

        function $(e, t) {
            De.config[e] = t, De.redraw(), g()
        }

        function z(e, t) {
            if (e instanceof Array) De.selectedDates = e.map(function(e) {
                return De.parseDate(e, t)
            });
            else if (e instanceof Date || !isNaN(e)) De.selectedDates = [De.parseDate(e, t)];
            else if (e && e.substring) switch (De.config.mode) {
                case "single":
                    De.selectedDates = [De.parseDate(e, t)];
                    break;
                case "multiple":
                    De.selectedDates = e.split("; ").map(function(e) {
                        return De.parseDate(e, t)
                    });
                    break;
                case "range":
                    De.selectedDates = e.split(De.l10n.rangeSeparator).map(function(e) {
                        return De.parseDate(e, t)
                    })
            }
            De.selectedDates = De.selectedDates.filter(function(e) {
                return e instanceof Date && A(e, !1)
            }), De.selectedDates.sort(function(e, t) {
                return e.getTime() - t.getTime()
            })
        }

        function q(e, t, n) {
            if (0 !== e && !e) return De.clear(t);
            z(e, n), De.showTimeInput = De.selectedDates.length > 0, De.latestSelectedDateObj = De.selectedDates[0], De.redraw(), g(), r(), le(t), t && ne("Change")
        }

        function G(e) {
            for (var t = e.length; t--;) "string" == typeof e[t] || +e[t] ? e[t] = De.parseDate(e[t], null, !0) : e[t] && e[t].from && e[t].to && (e[t].from = De.parseDate(e[t].from), e[t].to = De.parseDate(e[t].to));
            return e.filter(function(e) {
                return e
            })
        }

        function V() {
            De.selectedDates = [], De.now = new Date;
            var e = De.config.defaultDate || De.input.value;
            e && z(e, De.config.dateFormat);
            var t = De.selectedDates.length ? De.selectedDates[0] : De.config.minDate && De.config.minDate.getTime() > De.now ? De.config.minDate : De.config.maxDate && De.config.maxDate.getTime() < De.now ? De.config.maxDate : De.now;
            De.currentYear = t.getFullYear(), De.currentMonth = t.getMonth(), De.selectedDates.length && (De.latestSelectedDateObj = De.selectedDates[0]), De.minDateHasTime = De.config.minDate && (De.config.minDate.getHours() || De.config.minDate.getMinutes() || De.config.minDate.getSeconds()), De.maxDateHasTime = De.config.maxDate && (De.config.maxDate.getHours() || De.config.maxDate.getMinutes() || De.config.maxDate.getSeconds()), Object.defineProperty(De, "latestSelectedDateObj", {
                get: function() {
                    return De._selectedDateObj || De.selectedDates[De.selectedDates.length - 1]
                },
                set: function(e) {
                    De._selectedDateObj = e
                }
            }), De.isMobile || Object.defineProperty(De, "showTimeInput", {
                get: function() {
                    return De._showTimeInput
                },
                set: function(e) {
                    De._showTimeInput = e, De.calendarContainer && me(De.calendarContainer, "showTimeInput", e), J()
                }
            })
        }

        function Z() {
            De.utils = {
                duration: {
                    DAY: 864e5
                },
                getDaysinMonth: function(e, t) {
                    return e = void 0 === e ? De.currentMonth : e, t = void 0 === t ? De.currentYear : t, 1 === e && (t % 4 == 0 && t % 100 != 0 || t % 400 == 0) ? 29 : De.l10n.daysInMonth[e]
                },
                monthToStr: function(e, t) {
                    return t = void 0 === t ? De.config.shorthandCurrentMonth : t, De.l10n.months[(t ? "short" : "long") + "hand"][e]
                }
            }
        }

        function Q() {
            De.formats = Object.create(FlatpickrInstance.prototype.formats), ["D", "F", "J", "M", "W", "l"].forEach(function(e) {
                De.formats[e] = FlatpickrInstance.prototype.formats[e].bind(De)
            }), De.revFormat.F = FlatpickrInstance.prototype.revFormat.F.bind(De), De.revFormat.M = FlatpickrInstance.prototype.revFormat.M.bind(De)
        }

        function X() {
            if (De.input = De.config.wrap ? De.element.querySelector("[data-input]") : De.element, !De.input) return console.warn("Error: invalid input element specified", De.input);
            De.input._type = De.input.type, De.input.type = "text", De.input.classList.add("flatpickr-input"), De._input = De.input, De.config.altInput && (De.altInput = ue(De.input.nodeName, De.input.className + " " + De.config.altInputClass), De._input = De.altInput, De.altInput.placeholder = De.input.placeholder, De.altInput.disabled = De.input.disabled, De.altInput.required = De.input.required, De.altInput.type = "text", De.input.type = "hidden", !De.config.static && De.input.parentNode && De.input.parentNode.insertBefore(De.altInput, De.input.nextSibling)), De.config.allowInput || De._input.setAttribute("readonly", "readonly"), De._positionElement = De.config.positionElement || De._input
        }

        function ee() {
            var e = De.config.enableTime ? De.config.noCalendar ? "time" : "datetime-local" : "date";
            De.mobileInput = ue("input", De.input.className + " flatpickr-mobile"), De.mobileInput.step = "any", De.mobileInput.tabIndex = 1, De.mobileInput.type = e, De.mobileInput.disabled = De.input.disabled, De.mobileInput.placeholder = De.input.placeholder, De.mobileFormatStr = "datetime-local" === e ? "Y-m-d\\TH:i:S" : "date" === e ? "Y-m-d" : "H:i:S", De.selectedDates.length && (De.mobileInput.defaultValue = De.mobileInput.value = De.formatDate(De.selectedDates[0], De.mobileFormatStr)), De.config.minDate && (De.mobileInput.min = De.formatDate(De.config.minDate, "Y-m-d")), De.config.maxDate && (De.mobileInput.max = De.formatDate(De.config.maxDate, "Y-m-d")), De.input.type = "hidden", De.config.altInput && (De.altInput.type = "hidden");
            try {
                De.input.parentNode.insertBefore(De.mobileInput, De.input.nextSibling)
            } catch (e) {}
            De.mobileInput.addEventListener("change", function(e) {
                De.setDate(e.target.value, !1, De.mobileFormatStr), ne("Change"), ne("Close")
            })
        }

        function te() {
            if (De.isOpen) return De.close();
            De.open()
        }

        function ne(e, t) {
            var n = De.config["on" + e];
            if (void 0 !== n && n.length > 0)
                for (var a = 0; n[a] && a < n.length; a++) n[a](De.selectedDates, De.input.value, De, t);
            "Change" === e && (De.input.dispatchEvent(ae("change")), De.input.dispatchEvent(ae("input")))
        }

        function ae(e) {
            return De._supportsEvents ? new Event(e, {
                bubbles: !0
            }) : (De._[e + "Event"] = document.createEvent("Event"), De._[e + "Event"].initEvent(e, !0, !0), De._[e + "Event"])
        }

        function ie(e) {
            for (var t = 0; t < De.selectedDates.length; t++)
                if (0 === pe(De.selectedDates[t], e)) return "" + t;
            return !1
        }

        function re(e) {
            return !("range" !== De.config.mode || De.selectedDates.length < 2) && (pe(e, De.selectedDates[0]) >= 0 && pe(e, De.selectedDates[1]) <= 0)
        }

        function oe() {
            De.config.noCalendar || De.isMobile || !De.monthNav || (De.currentMonthElement.textContent = De.utils.monthToStr(De.currentMonth) + " ", De.currentYearElement.value = De.currentYear, De._hidePrevMonthArrow = De.config.minDate && (De.currentYear === De.config.minDate.getFullYear() ? De.currentMonth <= De.config.minDate.getMonth() : De.currentYear < De.config.minDate.getFullYear()), De._hideNextMonthArrow = De.config.maxDate && (De.currentYear === De.config.maxDate.getFullYear() ? De.currentMonth + 1 > De.config.maxDate.getMonth() : De.currentYear > De.config.maxDate.getFullYear()))
        }

        function le(e) {
            if (!De.selectedDates.length) return De.clear(e);
            De.isMobile && (De.mobileInput.value = De.selectedDates.length ? De.formatDate(De.latestSelectedDateObj, De.mobileFormatStr) : "");
            var t = "range" !== De.config.mode ? "; " : De.l10n.rangeSeparator;
            De.input.value = De.selectedDates.map(function(e) {
                return De.formatDate(e, De.config.dateFormat)
            }).join(t), De.config.altInput && (De.altInput.value = De.selectedDates.map(function(e) {
                return De.formatDate(e, De.config.altFormat)
            }).join(t)), !1 !== e && ne("ValueUpdate")
        }

        function ce(e) {
            return Math.max(-1, Math.min(1, e.wheelDelta || -e.deltaY))
        }

        function se(e) {
            e.preventDefault();
            var t = De.currentYearElement.parentNode.contains(e.target);
            if (e.target === De.currentMonthElement || t) {
                var n = ce(e);
                t ? (O(De.currentYear + n), e.target.value = De.currentYear) : De.changeMonth(n, !0, !1)
            }
        }

        function de(e) {
            var t = De.prevMonthNav.contains(e.target),
                n = De.nextMonthNav.contains(e.target);
            t || n ? _(t ? -1 : 1) : e.target === De.currentYearElement ? (e.preventDefault(), De.currentYearElement.select()) : "arrowUp" === e.target.className ? De.changeYear(De.currentYear + 1) : "arrowDown" === e.target.className && De.changeYear(De.currentYear - 1)
        }

        function ue(e, t, n) {
            var a = window.document.createElement(e);
            return t = t || "", n = n || "", a.className = t, void 0 !== n && (a.textContent = n), a
        }

        function fe(e) {
            return e instanceof Array ? e : [e]
        }

        function me(e, t, n) {
            if (n) return e.classList.add(t);
            e.classList.remove(t)
        }

        function ge(e, t, n) {
            var a = void 0;
            return function() {
                var i = this,
                    r = arguments;
                clearTimeout(a), a = setTimeout(function() {
                    a = null, n || e.apply(i, r)
                }, t), n && !a && e.apply(i, r)
            }
        }

        function pe(e, t, n) {
            return e instanceof Date && t instanceof Date && (!1 !== n ? new Date(e.getTime()).setHours(0, 0, 0, 0) - new Date(t.getTime()).setHours(0, 0, 0, 0) : e.getTime() - t.getTime())
        }

        function he(e) {
            e.preventDefault();
            var t = "keydown" === e.type,
                n = (e.type, e.type, e.target);
            if (De.amPM && e.target === De.amPM) return e.target.textContent = ["AM", "PM"]["AM" === e.target.textContent | 0];
            var a = Number(n.min),
                i = Number(n.max),
                r = Number(n.step),
                o = parseInt(n.value, 10),
                l = o + r * (e.delta || (t ? 38 === e.which ? 1 : -1 : Math.max(-1, Math.min(1, e.wheelDelta || -e.deltaY)) || 0));
            if (void 0 !== n.value && 2 === n.value.length) {
                var c = n === De.hourElement,
                    s = n === De.minuteElement;
                l < a ? (l = i + l + !c + (c && !De.amPM), s && h(null, -1, De.hourElement)) : l > i && (l = n === De.hourElement ? l - i - !De.amPM : a, s && h(null, 1, De.hourElement)), De.amPM && c && (1 === r ? l + o === 23 : Math.abs(l - o) > r) && (De.amPM.textContent = "PM" === De.amPM.textContent ? "AM" : "PM"), n.value = De.pad(l)
            }
        }
        var De = this;
        return De._ = {}, De._.afterDayAnim = b, De._bind = c, De._compareDates = pe, De._setHoursFromDate = r, De.changeMonth = _, De.changeYear = O, De.clear = T, De.close = I, De._createElement = ue, De.destroy = S, De.isEnabled = A, De.jumpToDate = g, De.open = H, De.redraw = K, De.set = $, De.setDate = q, De.toggle = te,
            function() {
                De.element = De.input = e, De.instanceConfig = t || {}, De.parseDate = FlatpickrInstance.prototype.parseDate.bind(De), De.formatDate = FlatpickrInstance.prototype.formatDate.bind(De), Q(), W(), B(), X(), V(), Z(), De.isOpen = !1, De.isMobile = !De.config.disableMobile && !De.config.inline && "single" === De.config.mode && !De.config.disable.length && !De.config.enable.length && !De.config.weekNumbers && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent), De.isMobile || v(), d(), (De.selectedDates.length || De.config.noCalendar) && (De.config.enableTime && r(De.config.noCalendar ? De.latestSelectedDateObj || De.config.minDate : null), le()), De.showTimeInput = De.selectedDates.length > 0 || De.config.noCalendar, De.config.weekNumbers && (De.calendarContainer.style.width = De.daysContainer.offsetWidth + De.weekWrapper.offsetWidth + "px"), De.isMobile || J(), ne("Ready")
            }(), De
    }

    function _flatpickr(e, t) {
        for (var n = Array.prototype.slice.call(e), a = [], i = 0; i < n.length; i++) try {
            if (null !== n[i].getAttribute("data-fp-omit")) continue;
            n[i]._flatpickr && (n[i]._flatpickr.destroy(), n[i]._flatpickr = null), n[i]._flatpickr = new FlatpickrInstance(n[i], t || {}), a.push(n[i]._flatpickr)
        } catch (e) {
            console.warn(e, e.stack)
        }
        return 1 === a.length ? a[0] : a
    }

    function flatpickr(e, t) {
        return e instanceof NodeList ? _flatpickr(e, t) : e instanceof HTMLElement ? _flatpickr([e], t) : _flatpickr(window.document.querySelectorAll(e), t)
    }
    var _extends = Object.assign || function(e) {
            for (var t = 1; t < arguments.length; t++) {
                var n = arguments[t];
                for (var a in n) Object.prototype.hasOwnProperty.call(n, a) && (e[a] = n[a])
            }
            return e
        },
        _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
            return typeof e
        } : function(e) {
            return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
        };
    FlatpickrInstance.prototype = {
        formats: {
            Z: function(e) {
                return e.toISOString()
            },
            D: function(e) {
                return this.l10n.weekdays.shorthand[this.formats.w(e)]
            },
            F: function(e) {
                return this.utils.monthToStr(this.formats.n(e) - 1, !1)
            },
            G: function(e) {
                return FlatpickrInstance.prototype.pad(FlatpickrInstance.prototype.formats.h(e))
            },
            H: function(e) {
                return FlatpickrInstance.prototype.pad(e.getHours())
            },
            J: function(e) {
                return e.getDate() + this.l10n.ordinal(e.getDate())
            },
            K: function(e) {
                return e.getHours() > 11 ? "PM" : "AM"
            },
            M: function(e) {
                return this.utils.monthToStr(e.getMonth(), !0)
            },
            S: function(e) {
                return FlatpickrInstance.prototype.pad(e.getSeconds())
            },
            U: function(e) {
                return e.getTime() / 1e3
            },
            W: function(e) {
                return this.config.getWeek(e)
            },
            Y: function(e) {
                return e.getFullYear()
            },
            d: function(e) {
                return FlatpickrInstance.prototype.pad(e.getDate())
            },
            h: function(e) {
                return e.getHours() % 12 ? e.getHours() % 12 : 12
            },
            i: function(e) {
                return FlatpickrInstance.prototype.pad(e.getMinutes())
            },
            j: function(e) {
                return e.getDate()
            },
            l: function(e) {
                return this.l10n.weekdays.longhand[e.getDay()]
            },
            m: function(e) {
                return FlatpickrInstance.prototype.pad(e.getMonth() + 1)
            },
            n: function(e) {
                return e.getMonth() + 1
            },
            s: function(e) {
                return e.getSeconds()
            },
            w: function(e) {
                return e.getDay()
            },
            y: function(e) {
                return String(e.getFullYear()).substring(2)
            }
        },
        formatDate: function(e, t) {
            var n = this;
            return void 0 !== this.config && void 0 !== this.config.formatDate ? this.config.formatDate(e, t) : t.split("").map(function(t, a, i) {
                return n.formats[t] && "\\" !== i[a - 1] ? n.formats[t](e) : "\\" !== t ? t : ""
            }).join("")
        },
        revFormat: {
            D: function() {},
            F: function(e, t) {
                e.setMonth(this.l10n.months.longhand.indexOf(t))
            },
            G: function(e, t) {
                e.setHours(parseFloat(t))
            },
            H: function(e, t) {
                e.setHours(parseFloat(t))
            },
            J: function(e, t) {
                e.setDate(parseFloat(t))
            },
            K: function(e, t) {
                var n = e.getHours();
                12 !== n && e.setHours(n % 12 + 12 * /pm/i.test(t))
            },
            M: function(e, t) {
                e.setMonth(this.l10n.months.shorthand.indexOf(t))
            },
            S: function(e, t) {
                e.setSeconds(t)
            },
            U: function(e, t) {
                return new Date(1e3 * parseFloat(t))
            },
            W: function(e, t) {
                return t = parseInt(t), new Date(e.getFullYear(), 0, 2 + 7 * (t - 1), 0, 0, 0, 0, 0)
            },
            Y: function(e, t) {
                e.setFullYear(t)
            },
            Z: function(e, t) {
                return new Date(t)
            },
            d: function(e, t) {
                e.setDate(parseFloat(t))
            },
            h: function(e, t) {
                e.setHours(parseFloat(t))
            },
            i: function(e, t) {
                e.setMinutes(parseFloat(t))
            },
            j: function(e, t) {
                e.setDate(parseFloat(t))
            },
            l: function() {},
            m: function(e, t) {
                e.setMonth(parseFloat(t) - 1)
            },
            n: function(e, t) {
                e.setMonth(parseFloat(t) - 1)
            },
            s: function(e, t) {
                e.setSeconds(parseFloat(t))
            },
            w: function() {},
            y: function(e, t) {
                e.setFullYear(2e3 + parseFloat(t))
            }
        },
        tokenRegex: {
            D: "(\\w+)",
            F: "(\\w+)",
            G: "(\\d\\d|\\d)",
            H: "(\\d\\d|\\d)",
            J: "(\\d\\d|\\d)\\w+",
            K: "(am|AM|Am|aM|pm|PM|Pm|pM)",
            M: "(\\w+)",
            S: "(\\d\\d|\\d)",
            U: "(.+)",
            W: "(\\d\\d|\\d)",
            Y: "(\\d{4})",
            Z: "(.+)",
            d: "(\\d\\d|\\d)",
            h: "(\\d\\d|\\d)",
            i: "(\\d\\d|\\d)",
            j: "(\\d\\d|\\d)",
            l: "(\\w+)",
            m: "(\\d\\d|\\d)",
            n: "(\\d\\d|\\d)",
            s: "(\\d\\d|\\d)",
            w: "(\\d\\d|\\d)",
            y: "(\\d{2})"
        },
        pad: function(e) {
            return ("0" + e).slice(-2)
        },
        parseDate: function(e, t, n) {
            if (0 !== e && !e) return null;
            var a = e;
            if (e instanceof Date) e = new Date(e.getTime());
            else if (void 0 !== e.toFixed) e = new Date(e);
            else {
                var i = t || (this.config || flatpickr.defaultConfig).dateFormat;
                if ("today" === (e = String(e).trim())) e = new Date, n = !0;
                else if (/Z$/.test(e) || /GMT$/.test(e)) e = new Date(e);
                else if (this.config && this.config.parseDate) e = this.config.parseDate(e, i);
                else {
                    for (var r = this.config && this.config.noCalendar ? new Date((new Date).setHours(0, 0, 0, 0)) : new Date((new Date).getFullYear(), 0, 1, 0, 0, 0, 0), o = void 0, l = 0, c = 0, s = ""; l < i.length; l++) {
                        var d = i[l],
                            u = "\\" === d,
                            f = "\\" === i[l - 1] || u;
                        if (this.tokenRegex[d] && !f) {
                            s += this.tokenRegex[d];
                            var m = new RegExp(s).exec(e);
                            m && (o = !0) && (r = this.revFormat[d](r, m[++c]) || r)
                        } else u || (s += ".")
                    }
                    e = o ? r : null
                }
            }
            return e instanceof Date ? (!0 === n && e.setHours(0, 0, 0, 0), e) : (console.warn("flatpickr: invalid date " + a), console.info(this.element), null)
        }
    }, "undefined" != typeof HTMLElement && (HTMLCollection.prototype.flatpickr = NodeList.prototype.flatpickr = function(e) {
        return _flatpickr(this, e)
    }, HTMLElement.prototype.flatpickr = function(e) {
        return _flatpickr([this], e)
    }), flatpickr.defaultConfig = FlatpickrInstance.defaultConfig = {
        mode: "single",
        position: "auto",
        animate: -1 === window.navigator.userAgent.indexOf("MSIE"),
        wrap: !1,
        weekNumbers: !1,
        allowInput: !1,
        clickOpens: !0,
        closeOnSelect: !0,
        time_24hr: !1,
        enableTime: !1,
        noCalendar: !1,
        dateFormat: "Y-m-d",
        ariaDateFormat: "F j, Y",
        altInput: !1,
        altInputClass: "form-control input",
        altFormat: "F j, Y",
        defaultDate: null,
        minDate: null,
        maxDate: null,
        parseDate: null,
        formatDate: null,
        getWeek: function(e) {
            var t = new Date(e.getTime()),
                n = new Date(t.getFullYear(), 0, 1);
            return Math.ceil(((t - n) / 864e5 + n.getDay() + 1) / 7)
        },
        enable: [],
        disable: [],
        shorthandCurrentMonth: !1,
        inline: !1,
        static: !1,
        appendTo: null,
        prevArrow: "<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M5.207 8.471l7.146 7.147-0.707 0.707-7.853-7.854 7.854-7.853 0.707 0.707-7.147 7.146z' /></svg>",
        nextArrow: "<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M13.207 8.472l-7.854 7.854-0.707-0.707 7.146-7.146-7.146-7.148 0.707-0.707 7.854 7.854z' /></svg>",
        enableSeconds: !1,
        hourIncrement: 1,
        minuteIncrement: 5,
        defaultHour: 12,
        defaultMinute: 0,
        disableMobile: !1,
        locale: "default",
        plugins: [],
        ignoredFocusElements: [],
        onClose: void 0,
        onChange: void 0,
        onDayCreate: void 0,
        onMonthChange: void 0,
        onOpen: void 0,
        onParseConfig: void 0,
        onReady: void 0,
        onValueUpdate: void 0,
        onYearChange: void 0,
        onKeyDown: void 0,
        onDestroy: void 0
    }, flatpickr.l10ns = {
        en: {
            weekdays: {
                shorthand: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                longhand: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
            },
            months: {
                shorthand: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                longhand: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
            },
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
            firstDayOfWeek: 0,
            ordinal: function(e) {
                var t = e % 100;
                if (t > 3 && t < 21) return "th";
                switch (t % 10) {
                    case 1:
                        return "st";
                    case 2:
                        return "nd";
                    case 3:
                        return "rd";
                    default:
                        return "th"
                }
            },
            rangeSeparator: " to ",
            weekAbbreviation: "Wk",
            scrollTitle: "Scroll to increment",
            toggleTitle: "Click to toggle"
        },
        de: {
            weekdays: {
                shorthand: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
                longhand: ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"]
            },
            months: {
                shorthand: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"],
                longhand: ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"]
            },
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
            firstDayOfWeek: 0,
            weekAbbreviation: "KW",
            rangeSeparator: " bis ",
            scrollTitle: "Zum Ändern scrollen",
            toggleTitle: "Zum Umschalten klicken"
        },
        es: {
            weekdays: {
                shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                longhand: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"]
            },
            months: {
                shorthand: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                longhand: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]
            },
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
            firstDayOfWeek: 0,
            ordinal: function(e) {
                return "º";
            }
        },
        fr: {
            weekdays: {
                shorthand: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
                longhand: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"]
            },
            months: {
                shorthand: ["Janv", "Févr", "Mars", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"],
                longhand: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]
            },
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
            firstDayOfWeek: 0,
            ordinal: function(e) {
                if (e > 1) return "ème";

                return "er";
            },
            weekAbbreviation: "Sem",
            rangeSeparator: " au ",
            scrollTitle: "Défiler pour augmenter la valeur",
            toggleTitle: "Cliquer pour basculer"
        },
        it: {
            weekdays: {
                shorthand: ["Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab"],
                longhand: ["Domenica", "Lunedì", "Martedì", "Mercoledì", "Giovedì", "Venerdì", "Sabato"]
            },
            months: {
                shorthand: ["Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"],
                longhand: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"]
            },
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
            firstDayOfWeek: 0,
            ordinal: function(e) {
                return "°";
            },
            weekAbbreviation: "Se",
            scrollTitle: "Scrolla per aumentare",
            toggleTitle: "Clicca per cambiare"
        },
        zh_cn: {
            weekdays: {
                shorthand: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
                longhand: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"]
            },
            months: {
                shorthand: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                longhand: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"]
            },
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
            firstDayOfWeek: 0,
            rangeSeparator: " 至 "
        },
        zh_tw: {
            weekdays: {
                shorthand: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
                longhand: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"]
            },
            months: {
                shorthand: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                longhand: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"]
            },
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
            firstDayOfWeek: 0,
            rangeSeparator: " 至 "
        },
        ja: {
            weekdays: {
                shorthand: ["日", "月", "火", "水", "木", "金", "土"],
                longhand: ["日曜日", "月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日"]
            },
            months: {
                shorthand: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
                longhand: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"]
            },
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
            firstDayOfWeek: 0
        },
        pt: {
            weekdays: {
                shorthand: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                longhand: ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"]
            },
            months: {
                shorthand: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                longhand: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"]
            },
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
            firstDayOfWeek: 0,
            rangeSeparator: " até "
        },
        tr: {
            weekdays: {
                longhand: ['Pazar', 'Pazartesi','Salı','Çarşamba','Perşembe', 'Cuma','Cumartesi'],
                shorthand: ['Paz', 'Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt']
            },
            months: {
                longhand: ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos', 'Eylül','Ekim','Kasım','Aralık'],
                shorthand: ['Oca','Şub','Mar','Nis','May','Haz','Tem','Ağu','Eyl','Eki','Kas','Ara']
            },
            today: 'Bugün',
            clear: 'Temizle',
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
        },
        ro: {
            weekdays: {
                shorthand: ["Dum", "Lun", "Mar", "Mie", "Joi", "Vin", "Sâm"],
                longhand: [
                    "Duminică",
                    "Luni",
                    "Marți",
                    "Miercuri",
                    "Joi",
                    "Vineri",
                    "Sâmbătă",
                ],
            },
            months: {
                shorthand: [
                    "Ian",
                    "Feb",
                    "Mar",
                    "Apr",
                    "Mai",
                    "Iun",
                    "Iul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Noi",
                    "Dec",
                ],
                longhand: [
                    "Ianuarie",
                    "Februarie",
                    "Martie",
                    "Aprilie",
                    "Mai",
                    "Iunie",
                    "Iulie",
                    "August",
                    "Septembrie",
                    "Octombrie",
                    "Noiembrie",
                    "Decembrie",
                ],
            },
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
        },
        ar: {
            weekdays: {
                shorthand: ["أحد", "اثنين", "ثلاثاء", "أربعاء", "خميس", "جمعة", "سبت"],
                longhand: [
                    "الأحد",
                    "الاثنين",
                    "الثلاثاء",
                    "الأربعاء",
                    "الخميس",
                    "الجمعة",
                    "السبت",
                ],
            },
            months: {
                shorthand: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
                longhand: [
                    "يناير",
                    "فبراير",
                    "مارس",
                    "أبريل",
                    "مايو",
                    "يونيو",
                    "يوليو",
                    "أغسطس",
                    "سبتمبر",
                    "أكتوبر",
                    "نوفمبر",
                    "ديسمبر",
                ],
            },
            firstDayOfWeek: 6,
            rangeSeparator: " إلى ",
            weekAbbreviation: "Wk",
            scrollTitle: "قم بالتمرير للزيادة",
            toggleTitle: "اضغط للتبديل",
            amPM: ["ص", "م"],
            yearAriaLabel: "سنة",
            monthAriaLabel: "شهر",
            hourAriaLabel: "ساعة",
            minuteAriaLabel: "دقيقة",
            time_24hr: false,
            daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
        },
    }, flatpickr.l10ns.default = Object.create(flatpickr.l10ns.en), flatpickr.localize = function(e) {
        return _extends(flatpickr.l10ns.default, e || {})
    }, flatpickr.setDefaults = function(e) {
        return _extends(flatpickr.defaultConfig, e || {})
    }, "undefined" != typeof jQuery && (jQuery.fn.flatpickr = function(e) {
        return _flatpickr(this, e)
    }), Date.prototype.fp_incr = function(e) {
        return new Date(this.getFullYear(), this.getMonth(), this.getDate() + parseInt(e, 10))
    }, "undefined" != typeof module && (module.exports = flatpickr);
});