/**
 * Linkan.ID — Compact Modern Date Range Picker Engine (Platform Admin)
 * Clean calendar popup with interactive date range selection & robust click-locking.
 */

(function () {
    "use strict";

    const MONTH_NAMES = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    const WEEKDAYS = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];

    function formatDate(date) {
        if (!date) return "";
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, "0");
        const d = String(date.getDate()).padStart(2, "0");
        return `${y}-${m}-${d}`;
    }

    function parseDate(str) {
        if (!str) return null;
        const parts = str.split("-");
        if (parts.length !== 3) return null;
        const y = parseInt(parts[0], 10);
        const m = parseInt(parts[1], 10) - 1;
        const d = parseInt(parts[2], 10);
        const dt = new Date(y, m, d, 0, 0, 0, 0);
        return isNaN(dt.getTime()) ? null : dt;
    }

    function formatDisplayDate(date) {
        if (!date) return "";
        const d = String(date.getDate()).padStart(2, "0");
        const m = MONTH_NAMES[date.getMonth()].substr(0, 3);
        const y = date.getFullYear();
        return `${d} ${m} ${y}`;
    }

    function isSameDay(d1, d2) {
        if (!d1 || !d2) return false;
        return (
            d1.getFullYear() === d2.getFullYear() &&
            d1.getMonth() === d2.getMonth() &&
            d1.getDate() === d2.getDate()
        );
    }

    function normalizeTime(d) {
        if (!d) return null;
        const dt = new Date(d);
        dt.setHours(0, 0, 0, 0);
        return dt;
    }

    class LinkanDateRangePicker {
        constructor(container, options = {}) {
            this.container = container;
            this.options = Object.assign(
                {
                    startName: container.dataset.startName || "start_date",
                    endName: container.dataset.endName || "end_date",
                    placeholder: container.dataset.placeholder || "Pilih Tanggal",
                    noSubmit: container.dataset.noSubmit === "true",
                    singleMode: container.dataset.singleMode === "true",
                    onSelect: null,
                },
                options
            );

            // Read initial dates
            const existingStartInput = this.container.querySelector(`input[name="${this.options.startName}"]`) ||
                                       this.container.querySelector('input[type="date"]');
            const existingEndInput = this.container.querySelector(`input[name="${this.options.endName}"]`);

            const initialStartStr = this.container.dataset.startValue || (existingStartInput ? existingStartInput.value : "");
            const initialEndStr = this.container.dataset.endValue || (existingEndInput ? existingEndInput.value : "");

            this.startDate = parseDate(initialStartStr);
            this.endDate = parseDate(initialEndStr);

            if (this.startDate && !this.endDate) {
                this.endDate = new Date(this.startDate);
            }

            // Temporary selection state when modal is open
            this.tempStartDate = this.startDate ? new Date(this.startDate) : null;
            this.tempEndDate = this.endDate ? new Date(this.endDate) : null;

            // View state
            const refDate = this.startDate || new Date();
            this.currentYear = refDate.getFullYear();
            this.currentMonth = refDate.getMonth();

            // Picking state (null = locked/idle, Date = picked start waiting for end)
            this.pickingStart = null;
            this.hoverDate = null;
            this.isOpen = false;

            this.initElements();
            this.initEvents();
            this.updateDisplay();
        }

        initElements() {
            this.container.classList.add("date-range-box");

            // Clear old plain date inputs if any
            const oldDateInput = this.container.querySelector('input[type="date"]');
            let startVal = this.startDate ? formatDate(this.startDate) : "";
            let endVal = this.endDate ? formatDate(this.endDate) : "";

            if (oldDateInput) {
                if (!startVal && oldDateInput.value) {
                    this.startDate = parseDate(oldDateInput.value);
                    this.endDate = new Date(this.startDate);
                    startVal = formatDate(this.startDate);
                    endVal = startVal;
                    this.tempStartDate = new Date(this.startDate);
                    this.tempEndDate = new Date(this.endDate);
                }
                oldDateInput.remove();
            }

            // Ensure Icon exists
            let icon = this.container.querySelector(".date-picker-icon, .date-range-icon, .fa-calendar-alt");
            if (!icon) {
                icon = document.createElement("i");
                icon.className = "fas fa-calendar-alt date-picker-icon";
                this.container.prepend(icon);
            }

            // Display text element
            let display = this.container.querySelector(".date-range-display, .date-picker-display");
            if (!display) {
                display = document.createElement("span");
                display.className = "date-range-display";
                this.container.appendChild(display);
            }
            this.displayEl = display;

            // Clear button
            let clearBtn = this.container.querySelector(".date-range-clear-btn");
            if (!clearBtn) {
                clearBtn = document.createElement("button");
                clearBtn.type = "button";
                clearBtn.className = "date-range-clear-btn";
                clearBtn.title = "Reset Tanggal";
                clearBtn.innerHTML = '<i class="fas fa-times"></i>';
                this.container.appendChild(clearBtn);
            }
            this.clearBtn = clearBtn;

            // Hidden Inputs
            let startInput = this.container.querySelector(`input[name="${this.options.startName}"]`);
            if (!startInput) {
                startInput = document.createElement("input");
                startInput.type = "hidden";
                startInput.name = this.options.startName;
                startInput.className = "date-range-hidden-input date-start-hidden";
                this.container.appendChild(startInput);
            }
            startInput.value = startVal;
            this.startInput = startInput;

            let endInput = this.container.querySelector(`input[name="${this.options.endName}"]`);
            if (!endInput) {
                endInput = document.createElement("input");
                endInput.type = "hidden";
                endInput.name = this.options.endName;
                endInput.className = "date-range-hidden-input date-end-hidden";
                this.container.appendChild(endInput);
            }
            endInput.value = endVal;
            this.endInput = endInput;

            // Create Floating Dropdown
            this.dropdown = document.createElement("div");
            this.dropdown.className = "linkan-datepicker-dropdown";
            document.body.appendChild(this.dropdown);
        }

        initEvents() {
            // Toggle dropdown
            this.container.addEventListener("click", (e) => {
                if (e.target.closest(".date-range-clear-btn")) return;
                this.toggle();
            });

            // Clear button
            this.clearBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                this.clear(true); // submit on clear
            });

            // Close on click outside
            document.addEventListener("mousedown", (e) => {
                if (!this.isOpen) return;
                if (!this.container.contains(e.target) && !this.dropdown.contains(e.target)) {
                    this.close();
                }
            });

            // Close on ESC
            document.addEventListener("keydown", (e) => {
                if (e.key === "Escape" && this.isOpen) {
                    this.close();
                }
            });

            // Reposition on resize/scroll
            window.addEventListener("scroll", () => {
                if (this.isOpen) this.updatePosition();
            }, true);

            window.addEventListener("resize", () => {
                if (this.isOpen) this.updatePosition();
            });
        }

        updatePosition() {
            if (!this.isOpen) return;
            const rect = this.container.getBoundingClientRect();
            const dropdownWidth = 270;
            const dropdownHeight = 310;

            let top = rect.bottom + 6;
            let left = rect.left;

            // Viewport boundary checks
            if (left + dropdownWidth > window.innerWidth - 12) {
                left = window.innerWidth - dropdownWidth - 12;
            }
            if (left < 12) left = 12;

            if (top + dropdownHeight > window.innerHeight - 12 && rect.top > dropdownHeight + 12) {
                top = rect.top - dropdownHeight - 6;
            }

            this.dropdown.style.top = `${top}px`;
            this.dropdown.style.left = `${left}px`;
        }

        open() {
            if (this.isOpen) return;

            // Close other open pickers
            document.querySelectorAll(".linkan-datepicker-dropdown.is-open").forEach((el) => {
                el.classList.remove("is-open");
            });

            this.isOpen = true;
            this.container.classList.add("is-active");
            this.tempStartDate = this.startDate ? new Date(this.startDate) : null;
            this.tempEndDate = this.endDate ? new Date(this.endDate) : null;
            this.pickingStart = null; // reset picking state
            this.hoverDate = null;

            const refDate = this.startDate || new Date();
            this.currentYear = refDate.getFullYear();
            this.currentMonth = refDate.getMonth();

            this.renderCalendar();
            this.updatePosition();
            this.dropdown.classList.add("is-open");
        }

        close() {
            if (!this.isOpen) return;
            this.isOpen = false;
            this.container.classList.remove("is-active");
            this.dropdown.classList.remove("is-open");
            this.pickingStart = null;
            this.hoverDate = null;
        }

        toggle() {
            if (this.isOpen) this.close();
            else this.open();
        }

        apply() {
            // If user clicked start date only and pressed OK, make end date same as start date
            if (this.pickingStart && !this.tempEndDate) {
                this.tempStartDate = new Date(this.pickingStart);
                this.tempEndDate = new Date(this.pickingStart);
            }

            if (this.tempStartDate && !this.tempEndDate) {
                this.tempEndDate = new Date(this.tempStartDate);
            }

            if (this.tempStartDate && this.tempEndDate && this.tempEndDate < this.tempStartDate) {
                const tmp = this.tempStartDate;
                this.tempStartDate = this.tempEndDate;
                this.tempEndDate = tmp;
            }

            this.startDate = this.tempStartDate ? new Date(this.tempStartDate) : null;
            this.endDate = this.tempEndDate ? new Date(this.tempEndDate) : null;
            this.pickingStart = null;
            this.hoverDate = null;

            this.startInput.value = this.startDate ? formatDate(this.startDate) : "";
            this.endInput.value = this.endDate ? formatDate(this.endDate) : "";

            this.updateDisplay();
            this.triggerChange(true);
            this.close();
        }

        clear(shouldSubmit = false) {
            this.startDate = null;
            this.endDate = null;
            this.tempStartDate = null;
            this.tempEndDate = null;
            this.pickingStart = null;
            this.hoverDate = null;
            this.startInput.value = "";
            this.endInput.value = "";
            this.updateDisplay();
            this.triggerChange(shouldSubmit);
            if (this.isOpen) {
                this.renderCalendar();
            }
        }

        updateDisplay() {
            if (this.startDate && this.endDate) {
                if (isSameDay(this.startDate, this.endDate)) {
                    this.displayEl.textContent = formatDisplayDate(this.startDate);
                } else {
                    this.displayEl.textContent = `${formatDisplayDate(this.startDate)} – ${formatDisplayDate(this.endDate)}`;
                }
                this.displayEl.classList.remove("is-placeholder");
                this.clearBtn.style.display = "inline-flex";
            } else if (this.startDate) {
                this.displayEl.textContent = formatDisplayDate(this.startDate);
                this.displayEl.classList.remove("is-placeholder");
                this.clearBtn.style.display = "inline-flex";
            } else {
                this.displayEl.textContent = this.options.placeholder;
                this.displayEl.classList.add("is-placeholder");
                this.clearBtn.style.display = "none";
            }
        }

        triggerChange(submitIfForm = false) {
            const event = new CustomEvent("dateRangeChange", {
                bubbles: true,
                detail: {
                    startDate: this.startDate ? formatDate(this.startDate) : "",
                    endDate: this.endDate ? formatDate(this.endDate) : "",
                    startObj: this.startDate,
                    endObj: this.endDate,
                },
            });
            this.container.dispatchEvent(event);

            this.startInput.dispatchEvent(new Event("change", { bubbles: true }));
            this.endInput.dispatchEvent(new Event("change", { bubbles: true }));

            if (typeof this.options.onSelect === "function") {
                this.options.onSelect(this.startDate, this.endDate);
            }

            // Auto submit enclosing form if present and not disabled
            if (submitIfForm && !this.options.noSubmit) {
                const form = this.container.closest("form");
                if (form) {
                    if (typeof form.requestSubmit === "function") {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                }
            }
        }

        prevMonth() {
            this.currentMonth--;
            if (this.currentMonth < 0) {
                this.currentMonth = 11;
                this.currentYear--;
            }
            this.renderCalendar();
        }

        nextMonth() {
            this.currentMonth++;
            if (this.currentMonth > 11) {
                this.currentMonth = 0;
                this.currentYear++;
            }
            this.renderCalendar();
        }

        handleDayClick(date) {
            const clicked = normalizeTime(date);

            if (this.options.singleMode) {
                this.tempStartDate = new Date(clicked);
                this.tempEndDate = new Date(clicked);
                this.pickingStart = null;
                this.hoverDate = null;
                this.updateRangeHighlight(this.tempStartDate, this.tempEndDate);
                return;
            }

            if (!this.pickingStart) {
                // FIRST CLICK: Pick Start Date
                this.pickingStart = new Date(clicked);
                this.tempStartDate = new Date(clicked);
                this.tempEndDate = null;
                this.hoverDate = new Date(clicked);
                this.updateRangeHighlight(this.tempStartDate, this.tempStartDate);
            } else {
                // SECOND CLICK: Lock End Date!
                let d1 = new Date(this.pickingStart);
                let d2 = new Date(clicked);

                if (d2.getTime() < d1.getTime()) {
                    const tmp = d1;
                    d1 = d2;
                    d2 = tmp;
                }

                this.tempStartDate = d1;
                this.tempEndDate = d2;
                this.pickingStart = null; // LOCK: No longer picking!
                this.hoverDate = null;
                this.updateRangeHighlight(this.tempStartDate, this.tempEndDate);
            }
        }

        handleDayHover(date) {
            // ONLY update preview when pickingStart is active (after click 1, before click 2)
            if (!this.pickingStart) return;

            const hovered = normalizeTime(date);
            this.hoverDate = hovered;

            const p = this.pickingStart;
            const h = hovered;
            let s = p <= h ? p : h;
            let e = p <= h ? h : p;

            this.updateRangeHighlight(s, e);
        }

        updateRangeHighlight(activeStart, activeEnd) {
            const startTime = activeStart ? normalizeTime(activeStart).getTime() : null;
            const endTime = activeEnd ? normalizeTime(activeEnd).getTime() : null;
            const hasRange = startTime !== null && endTime !== null && startTime !== endTime;

            const cells = this.dropdown.querySelectorAll(".linkan-dp-day-cell");
            cells.forEach((cell) => {
                const timeStr = cell.dataset.time;
                if (!timeStr) return;
                const t = parseInt(timeStr, 10);

                const isStart = startTime !== null && t === startTime;
                const isEnd = endTime !== null && t === endTime;
                const isSingle = isStart && isEnd;
                const inRange = hasRange && t > startTime && t < endTime;

                // Reset classes
                cell.classList.remove(
                    "is-range-start",
                    "is-range-end",
                    "has-range-right",
                    "has-range-left",
                    "is-in-range",
                    "is-single-selected"
                );

                // Manage range-bg element
                let rangeBg = cell.querySelector(".linkan-dp-range-bg");

                if (isSingle) {
                    cell.classList.add("is-single-selected");
                    if (rangeBg) rangeBg.remove();
                } else if (isStart) {
                    cell.classList.add("is-range-start");
                    if (hasRange) {
                        cell.classList.add("has-range-right");
                        if (!rangeBg) {
                            rangeBg = document.createElement("div");
                            rangeBg.className = "linkan-dp-range-bg";
                            cell.prepend(rangeBg);
                        }
                    } else if (rangeBg) {
                        rangeBg.remove();
                    }
                } else if (isEnd) {
                    cell.classList.add("is-range-end");
                    if (hasRange) {
                        cell.classList.add("has-range-left");
                        if (!rangeBg) {
                            rangeBg = document.createElement("div");
                            rangeBg.className = "linkan-dp-range-bg";
                            cell.prepend(rangeBg);
                        }
                    } else if (rangeBg) {
                        rangeBg.remove();
                    }
                } else if (inRange) {
                    cell.classList.add("is-in-range");
                    if (!rangeBg) {
                        rangeBg = document.createElement("div");
                        rangeBg.className = "linkan-dp-range-bg";
                        cell.prepend(rangeBg);
                    }
                } else {
                    if (rangeBg) rangeBg.remove();
                }
            });
        }

        selectShortcut(type) {
            const today = normalizeTime(new Date());

            if (type === "today") {
                this.tempStartDate = new Date(today);
                this.tempEndDate = new Date(today);
            } else if (type === "7days") {
                this.tempStartDate = new Date(today);
                this.tempStartDate.setDate(today.getDate() - 6);
                this.tempEndDate = new Date(today);
            } else if (type === "thisMonth") {
                this.tempStartDate = new Date(today.getFullYear(), today.getMonth(), 1);
                this.tempEndDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            }

            this.pickingStart = null; // Lock shortcut selection
            this.hoverDate = null;
            this.currentYear = (this.tempStartDate || today).getFullYear();
            this.currentMonth = (this.tempStartDate || today).getMonth();
            this.renderCalendar();
        }

        renderCalendar() {
            const y = this.currentYear;
            const m = this.currentMonth;

            let activeStart = normalizeTime(this.tempStartDate);
            let activeEnd = normalizeTime(this.tempEndDate);

            if (this.pickingStart && this.hoverDate) {
                const p = normalizeTime(this.pickingStart);
                const h = normalizeTime(this.hoverDate);
                activeStart = p <= h ? p : h;
                activeEnd = p <= h ? h : p;
            } else if (this.pickingStart) {
                activeStart = normalizeTime(this.pickingStart);
                activeEnd = normalizeTime(this.pickingStart);
            }

            const today = normalizeTime(new Date());

            // Month calculation
            const firstDayOfMonth = new Date(y, m, 1);
            const startDayIndex = firstDayOfMonth.getDay(); // 0: Sunday, 6: Saturday
            const lastDateOfMonth = new Date(y, m + 1, 0).getDate();
            const prevMonthLastDate = new Date(y, m, 0).getDate();

            // Header
            let html = `
                <div class="linkan-dp-header">
                    <button type="button" class="linkan-dp-nav-btn btn-prev" aria-label="Bulan Sebelumnya">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="linkan-dp-title">${MONTH_NAMES[m]} ${y}</div>
                    <button type="button" class="linkan-dp-nav-btn btn-next" aria-label="Bulan Selanjutnya">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="linkan-dp-weekdays">
                    ${WEEKDAYS.map((w) => `<div class="linkan-dp-weekday">${w}</div>`).join("")}
                </div>
                <div class="linkan-dp-days">
            `;

            // Build list of all 35 or 42 grid cells
            const cells = [];

            // 1. Previous month trailing days
            for (let i = startDayIndex - 1; i >= 0; i--) {
                const dayNum = prevMonthLastDate - i;
                const d = new Date(y, m - 1, dayNum, 0, 0, 0, 0);
                cells.push({ date: d, isOutside: true });
            }

            // 2. Current month days
            for (let i = 1; i <= lastDateOfMonth; i++) {
                const d = new Date(y, m, i, 0, 0, 0, 0);
                cells.push({ date: d, isOutside: false });
            }

            // 3. Next month leading days (to fill 35 or 42 cells)
            const remaining = (7 - (cells.length % 7)) % 7;
            const totalCells = cells.length + remaining < 35 ? 35 : cells.length + remaining;
            const nextDaysCount = totalCells - cells.length;

            for (let i = 1; i <= nextDaysCount; i++) {
                const d = new Date(y, m + 1, i, 0, 0, 0, 0);
                cells.push({ date: d, isOutside: true });
            }

            const startTime = activeStart ? activeStart.getTime() : null;
            const endTime = activeEnd ? activeEnd.getTime() : null;

            // Render day cells
            cells.forEach((cell, idx) => {
                const d = cell.date;
                const t = d.getTime();
                const isSun = idx % 7 === 0;
                const isSat = idx % 7 === 6;

                const isToday = isSameDay(d, today);
                const isStart = startTime !== null && t === startTime;
                const isEnd = endTime !== null && t === endTime;
                const isSingle = isStart && isEnd;
                const hasRange = startTime !== null && endTime !== null && startTime !== endTime;
                const inRange = hasRange && t > startTime && t < endTime;

                const classes = ["linkan-dp-day-cell"];
                if (cell.isOutside) classes.push("is-outside");
                if (isToday) classes.push("is-today");
                if (isSun) classes.push("is-row-start");
                if (isSat) classes.push("is-row-end");

                if (isSingle) {
                    classes.push("is-single-selected");
                } else {
                    if (isStart) {
                        classes.push("is-range-start");
                        if (hasRange) classes.push("has-range-right");
                    }
                    if (isEnd) {
                        classes.push("is-range-end");
                        if (hasRange) classes.push("has-range-left");
                    }
                    if (inRange) {
                        classes.push("is-in-range");
                    }
                }

                const dateStr = formatDate(d);

                // Range background indicator
                let rangeBgHtml = "";
                if (inRange || (isStart && hasRange) || (isEnd && hasRange)) {
                    rangeBgHtml = `<div class="linkan-dp-range-bg"></div>`;
                }

                html += `
                    <div class="${classes.join(" ")}" data-date="${dateStr}" data-time="${t}">
                        ${rangeBgHtml}
                        <span class="linkan-dp-day-number">${d.getDate()}</span>
                    </div>
                `;
            });

            html += `</div>`;

            // Footer with Shortcuts & Action Buttons (OK & Batal)
            html += `
                <div class="linkan-dp-footer">
                    <div class="linkan-dp-shortcuts-row">
                        <button type="button" class="linkan-dp-shortcut-btn" data-shortcut="today">Hari Ini</button>
                        <button type="button" class="linkan-dp-shortcut-btn" data-shortcut="7days">7 Hari</button>
                        <button type="button" class="linkan-dp-shortcut-btn" data-shortcut="thisMonth">Bulan Ini</button>
                        <button type="button" class="linkan-dp-shortcut-btn" data-shortcut="reset">Reset</button>
                    </div>
                    <div class="linkan-dp-actions-row">
                        <button type="button" class="linkan-dp-action-btn linkan-dp-cancel-btn btn-dp-cancel">Batal</button>
                        <button type="button" class="linkan-dp-action-btn linkan-dp-ok-btn btn-dp-ok">OK</button>
                    </div>
                </div>
            `;

            this.dropdown.innerHTML = html;

            // Attach calendar event listeners
            this.dropdown.querySelector(".btn-prev").addEventListener("click", (e) => {
                e.stopPropagation();
                this.prevMonth();
            });

            this.dropdown.querySelector(".btn-next").addEventListener("click", (e) => {
                e.stopPropagation();
                this.nextMonth();
            });

            this.dropdown.querySelectorAll(".linkan-dp-day-cell").forEach((el) => {
                el.addEventListener("click", (e) => {
                    e.stopPropagation();
                    const d = parseDate(el.dataset.date);
                    if (d) this.handleDayClick(d);
                });

                el.addEventListener("mouseenter", () => {
                    const d = parseDate(el.dataset.date);
                    if (d) this.handleDayHover(d);
                });
            });

            this.dropdown.querySelectorAll(".linkan-dp-shortcut-btn").forEach((btn) => {
                btn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    const shortcut = btn.dataset.shortcut;
                    if (shortcut === "reset") {
                        this.clear(false);
                    } else {
                        this.selectShortcut(shortcut);
                    }
                });
            });

            // Action Buttons
            this.dropdown.querySelector(".btn-dp-cancel").addEventListener("click", (e) => {
                e.stopPropagation();
                this.close();
            });

            this.dropdown.querySelector(".btn-dp-ok").addEventListener("click", (e) => {
                e.stopPropagation();
                this.apply();
            });
        }
    }

    // Global Auto-Initialization
    function initAllPlatformDatePickers() {
        const containers = document.querySelectorAll(
            ".date-picker-box:not([data-dp-initialized]), .date-range-box:not([data-dp-initialized]), .date-range-container:not([data-dp-initialized])"
        );

        containers.forEach((container) => {
            container.dataset.dpInitialized = "true";
            new LinkanDateRangePicker(container);
        });
    }

    // Expose class globally
    window.LinkanDateRangePicker = LinkanDateRangePicker;
    window.initAllPlatformDatePickers = initAllPlatformDatePickers;

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initAllPlatformDatePickers);
    } else {
        initAllPlatformDatePickers();
    }
})();
