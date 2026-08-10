import Swiper from 'swiper/bundle'

function initBlock1() {
  const marquee = document.querySelector(".marquee");
  if (marquee) {
    const track = marquee.querySelector(".marquee__track");
    const original = track.innerHTML;
    const setWidth = track.scrollWidth;

    while (track.scrollWidth < marquee.offsetWidth + setWidth) {
      track.insertAdjacentHTML("beforeend", original);
    }

    let offset = 0;
    let last = null;
    let raf = null;

    function step(now) {
      if (last !== null) {
        offset += (60 * (now - last)) / 1000;
        if (offset >= setWidth) offset -= setWidth;
        track.style.transform = "translateX(" + -offset + "px)";
      }
      last = now;
      raf = requestAnimationFrame(step);
    }
    function start() {
      if (raf === null) {
        last = null;
        raf = requestAnimationFrame(step);
      }
    }
    function stop() {
      cancelAnimationFrame(raf);
      raf = null;
    }

    marquee.addEventListener("mouseenter", stop);
    marquee.addEventListener("mouseleave", start);
    start();
  }

  const tariffsSwiper = document.querySelector(".tariffs__swiper");
  if (tariffsSwiper && typeof Swiper !== "undefined") {
    const slider = new Swiper(tariffsSwiper, {
      slidesPerView: 2,
      spaceBetween: 24,
      watchOverflow: true,
      navigation: {
        prevEl: ".tariffs .slider-arrow_prev",
        nextEl: ".tariffs .slider-arrow_next",
      },
      breakpoints: {
        // the phone frame fits two compact cards side by side, not one
        0: { slidesPerView: 2, spaceBetween: 12 },
        768: { slidesPerView: 2, spaceBetween: 16 },
        1200: { slidesPerView: 3, spaceBetween: 20 },
        1400: { slidesPerView: 4, spaceBetween: 24 },
      },
    });

    const tabs = document.querySelectorAll(".tariffs__tab");
    const slides = tariffsSwiper.querySelectorAll(".swiper-slide");

    function showGroup(group) {
      slides.forEach(function (slide) {
        const visible = group === "all" || slide.dataset.group === group;
        slide.classList.toggle("is-hidden", !visible);
      });
      slider.update();
      slider.slideTo(0, 0);
    }

    tabs.forEach(function (tab) {
      tab.addEventListener("click", function () {
        tabs.forEach(function (item) {
          item.classList.remove("tariffs__tab_active");
        });
        tab.classList.add("tariffs__tab_active");
        showGroup(tab.dataset.tab);
      });
    });

    showGroup("home");
  }

  const tariffsListSlider = document.querySelector(".tariffs-list__slider");
  if (tariffsListSlider && typeof Swiper !== "undefined") {
    new Swiper(tariffsListSlider, {
      slidesPerView: "auto",
      centeredSlides: true,
      spaceBetween: 12,
      watchOverflow: true,
      breakpoints: {
        0: { slidesPerView: "auto", spaceBetween: 12, centeredSlides: true },
        576: { slidesPerView: 2, spaceBetween: 16, centeredSlides: false },
        992: { slidesPerView: 2, spaceBetween: 20, centeredSlides: false },
        1200: { slidesPerView: 3, spaceBetween: 24, centeredSlides: false },
        1700: { slidesPerView: 4, spaceBetween: 24, centeredSlides: false },
      },
    });
  }

  const lang = document.querySelector(".header__lang");
  if (lang) {
    const toggle = lang.querySelector(".header__lang-toggle");
    const current = lang.querySelector(".header__lang-current");
    const options = lang.querySelectorAll(".header__lang-option");

    toggle.addEventListener("click", function (event) {
      event.stopPropagation();
      lang.classList.toggle("header__lang_open");
    });

    options.forEach(function (option) {
      option.addEventListener("click", function () {
        options.forEach(function (item) {
          item.classList.remove("header__lang-option_active");
        });
        option.classList.add("header__lang-option_active");
        current.textContent = option.textContent.trim();
        lang.classList.remove("header__lang_open");
      });
    });

    document.addEventListener("click", function (event) {
      if (!lang.contains(event.target)) {
        lang.classList.remove("header__lang_open");
      }
    });
  }

  const burger = document.querySelector(".header__hamburger-button");
  const menu = document.querySelector(".menu");
  const overlay = document.querySelector(".overlay");
  if (burger && menu) {
    const close = menu.querySelector(".menu__close");

    function openMenu() {
      menu.classList.add("menu_open");
      if (overlay) overlay.classList.add("overlay_open");
      document.body.classList.add("overflow__hidden");
    }
    function closeMenu() {
      menu.classList.remove("menu_open");
      if (overlay) overlay.classList.remove("overlay_open");
      document.body.classList.remove("overflow__hidden");
    }

    burger.addEventListener("click", openMenu);
    if (close) close.addEventListener("click", closeMenu);
    if (overlay) overlay.addEventListener("click", closeMenu);
    menu.addEventListener("click", function (event) {
      if (event.target === menu) closeMenu();
    });
    menu.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", closeMenu);
    });
    menu.querySelectorAll(".menu__link_toggle").forEach(function (toggle) {
      toggle.addEventListener("click", function () {
        toggle.closest(".menu__item").classList.toggle("menu__item_open");
      });
    });
  }

  const cookies = document.querySelector(".cookies");
  if (cookies) {
    const accept = cookies.querySelector(".cookies__accept");
    if (accept) {
      accept.addEventListener("click", function () {
        cookies.classList.add("cookies_hidden");
      });
    }
  }

  const dials = document.querySelectorAll(".features__dial");
  if (dials.length) {
    const SIZE = 450;
    const STROKE = 30;
    const GAP = 80;
    const CX = SIZE / 2;
    const CY = SIZE / 2;
    const R = (SIZE - STROKE) / 2 - 6;
    const START = 180 + GAP / 2;
    const SWEEP = 360 - GAP;
    const NEEDLE_BASE = { x: 8.7, y: 11.2 };
    const NEEDLE_TIP = { x: 164.577, y: 70.0769 };
    const NEEDLE_NATURAL =
      (Math.atan2(NEEDLE_TIP.x - NEEDLE_BASE.x, -(NEEDLE_TIP.y - NEEDLE_BASE.y)) * 180) / Math.PI;
    const CAP_OFFSET = ((STROKE / 2 / R) * 180) / Math.PI;

    function polar(angle) {
      const a = (angle * Math.PI) / 180;
      return [CX + R * Math.sin(a), CY - R * Math.cos(a)];
    }
    function arcPath(a0, a1) {
      const [x0, y0] = polar(a0);
      const [x1, y1] = polar(a1);
      const large = a1 - a0 > 180 ? 1 : 0;
      return "M " + x0 + " " + y0 + " A " + R + " " + R + " 0 " + large + " 1 " + x1 + " " + y1;
    }
    function renderDial(dial, value) {
      const frac = Math.max(0, Math.min(1, value / Number(dial.dataset.max)));
      const fillEnd = START + SWEEP * frac;
      dial.querySelector(".features__dial-track").setAttribute("d", arcPath(START, START + SWEEP));
      dial.querySelector(".features__dial-fill").setAttribute("d", frac <= 0.001 ? "" : arcPath(START, fillEnd));
      dial.querySelector(".features__dial-needle").style.transform =
        "rotate(" + (fillEnd - NEEDLE_NATURAL + CAP_OFFSET) + "deg)";
      dial.querySelector(".features__readout-value").textContent = value.toFixed(2);
    }
    function animateDial(dial) {
      const from = Number(dial.dataset.from);
      const to = Number(dial.dataset.to);
      const duration = 1400;
      let start = null;

      function frame(time) {
        if (start === null) start = time;
        const elapsed = (time - start) % (duration * 2);
        let p = elapsed < duration ? elapsed / duration : 1 - (elapsed - duration) / duration;
        p = p * p * (3 - 2 * p);
        renderDial(dial, from + (to - from) * p);
        dial._raf = requestAnimationFrame(frame);
      }
      dial._raf = requestAnimationFrame(frame);
    }

    dials.forEach(function (dial) {
      renderDial(dial, Number(dial.dataset.from));
    });

    if ("IntersectionObserver" in window) {
      const observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            const dial = entry.target;
            if (entry.isIntersecting && !dial._raf) {
              animateDial(dial);
            } else if (!entry.isIntersecting && dial._raf) {
              cancelAnimationFrame(dial._raf);
              dial._raf = null;
            }
          });
        },
        { threshold: 0.3 },
      );
      dials.forEach(function (dial) {
        observer.observe(dial);
      });
    }
  }

  const gauges = document.querySelectorAll(".hero__gauge");
  gauges.forEach(function (gauge) {
    const needle = gauge.querySelector(".hero__gauge-needle");
    const value = gauge.querySelector(".hero__gauge-value");
    if (!needle) return;

    const from = 1000;
    const to = 970;
    const maxTilt = -5;
    const duration = 1300;
    let start = null;

    function frame(time) {
      if (start === null) start = time;
      const elapsed = (time - start) % (duration * 2);
      let p = elapsed < duration ? elapsed / duration : 1 - (elapsed - duration) / duration;
      p = p * p * (3 - 2 * p);
      needle.style.transform = "rotate(" + maxTilt * p + "deg)";
      if (value) {
        value.textContent = String(Math.round(from + (to - from) * p)).replace(
          /\B(?=(\d{3})+(?!\d))/g,
          " ",
        );
      }
      requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);
  });

  // Numbers page — phone-number mask: one digit per cell, auto-advance
  const maskCells = document.querySelectorAll(".numbers__mask-cell");
  if (maskCells.length) {
    maskCells.forEach(function (cell, i) {
      cell.addEventListener("beforeinput", function (event) {
        if (event.data && /\D/.test(event.data)) event.preventDefault();
      });
      cell.addEventListener("input", function () {
        const digit = cell.value.replace(/\D/g, "").slice(0, 1);
        if (digit !== cell.value) cell.value = digit;
        if (digit && maskCells[i + 1]) maskCells[i + 1].focus();
      });
      cell.addEventListener("keydown", function (event) {
        if (event.key === "Backspace" && !cell.value && maskCells[i - 1]) {
          maskCells[i - 1].focus();
        }
      });
    });
  }
}

/* ============================================================
   Merged from custom.js — kept in its original order.
   ============================================================ */

function initBlock2() {
  // ==========================================================
  // OFFICES — interactive locator (Yandex map + filter + list)
  // ==========================================================
  const mapEl = document.getElementById("offices-map");
  const hasMap = mapEl && typeof ymaps !== "undefined";
  if (mapEl) {
    const POINTS = [
      { id: 1, type: "office", title: "Центральный офис", address: "г. Ташкент, ул. Шевченко, 21", hours: "Пн–Вс 09:00–21:00", region: "Ташкент", city: "Ташкент", lat: 41.311, lng: 69.24 },
      { id: 2, type: "office", title: "Офис Нукус", address: "г. Нукус, центральный офис обслуживания", hours: "Пн–Сб 09:00–19:00", region: "Каракалпакстан", city: "Нукус", lat: 42.46, lng: 59.617 },
      { id: 3, type: "office", title: "Офис Ургенч", address: "г. Ургенч, офис обслуживания", hours: "Пн–Сб 09:00–19:00", region: "Хорезм", city: "Ургенч", lat: 41.55, lng: 60.631 },
      { id: 4, type: "office", title: "Офис Самарканд", address: "г. Самарканд, ул. Регистан, 5", hours: "Пн–Сб 09:00–19:00", region: "Самарканд", city: "Самарканд", lat: 39.654, lng: 66.96 },
      { id: 5, type: "office", title: "Офис Бухара", address: "г. Бухара, ул. Накшбанди, 12", hours: "Пн–Сб 09:00–19:00", region: "Бухара", city: "Бухара", lat: 39.767, lng: 64.421 },
      { id: 6, type: "office", title: "Офис Наманган", address: "г. Наманган, ул. Уйчи, 3", hours: "Пн–Сб 09:00–19:00", region: "Наманган", city: "Наманган", lat: 40.998, lng: 71.672 },
      { id: 7, type: "office", title: "Офис Андижан", address: "г. Андижан, пр. Бабура, 8", hours: "Пн–Сб 09:00–19:00", region: "Андижан", city: "Андижан", lat: 40.783, lng: 72.344 },
      { id: 8, type: "office", title: "Офис Фергана", address: "г. Фергана, ул. Мустакиллик, 20", hours: "Пн–Сб 09:00–19:00", region: "Фергана", city: "Фергана", lat: 40.389, lng: 71.783 },
      { id: 9, type: "office", title: "Офис Карши", address: "г. Карши, ул. Мустакиллик, 7", hours: "Пн–Сб 09:00–19:00", region: "Кашкадарья", city: "Карши", lat: 38.86, lng: 65.789 },
      { id: 10, type: "office", title: "Офис Термез", address: "г. Термез, ул. Ат-Термизи, 14", hours: "Пн–Сб 09:00–19:00", region: "Сурхандарья", city: "Термез", lat: 37.224, lng: 67.278 },
      { id: 11, type: "dealer", title: "Дилер №3", address: "г. Ташкент, ул. Шевченко, 21", region: "Ташкент", city: "Ташкент", lat: 41.326, lng: 69.279 },
      { id: 12, type: "dealer", title: "Дилер №4", address: "г. Нукус, ул. Дослик, 2", region: "Каракалпакстан", city: "Нукус", lat: 42.48, lng: 59.6 },
      { id: 13, type: "dealer", title: "Дилер №5", address: "г. Ургенч, ул. Аль-Хорезми, 9", region: "Хорезм", city: "Ургенч", lat: 41.56, lng: 60.65 },
      { id: 14, type: "dealer", title: "Дилер №6", address: "г. Самарканд, ул. Гагарина, 30", region: "Самарканд", city: "Самарканд", lat: 39.64, lng: 66.98 },
      { id: 15, type: "dealer", title: "Дилер №7", address: "г. Бухара, ул. Ибн Сино, 5", region: "Бухара", city: "Бухара", lat: 39.78, lng: 64.43 },
      { id: 16, type: "dealer", title: "Дилер №8", address: "г. Андижан, ул. Навои, 18", region: "Андижан", city: "Андижан", lat: 40.79, lng: 72.36 },
      { id: 17, type: "dealer", title: "Дилер №9", address: "г. Ташкент, ул. Амира Темура, 42", region: "Ташкент", city: "Ташкент", lat: 41.34, lng: 69.28 },
      { id: 18, type: "dealer", title: "Дилер №10", address: "г. Самарканд, ул. Мирзо Улугбека, 11", region: "Самарканд", city: "Самарканд", lat: 39.66, lng: 66.94 },
      { id: 19, type: "dealer", title: "Дилер №11", address: "г. Бухара, ул. Бахоуддина, 7", region: "Бухара", city: "Бухара", lat: 39.775, lng: 64.415 },
      { id: 20, type: "dealer", title: "Дилер №12", address: "г. Наманган, ул. Навои, 25", region: "Наманган", city: "Наманган", lat: 41.005, lng: 71.66 },
      { id: 21, type: "dealer", title: "Дилер №13", address: "г. Фергана, ул. Аль-Фаргоний, 9", region: "Фергана", city: "Фергана", lat: 40.38, lng: 71.79 },
      { id: 22, type: "dealer", title: "Дилер №14", address: "г. Карши, ул. Узбекистан, 3", region: "Кашкадарья", city: "Карши", lat: 38.87, lng: 65.8 },
      { id: 23, type: "dealer", title: "Дилер №15", address: "г. Термез, ул. Навои, 12", region: "Сурхандарья", city: "Термез", lat: 37.23, lng: 67.29 },
      { id: 24, type: "dealer", title: "Дилер №16", address: "г. Ургенч, ул. Гагарина, 4", region: "Хорезм", city: "Ургенч", lat: 41.545, lng: 60.64 },
    ];

    const listEl = document.getElementById("offices-list");
    const resultEl = document.getElementById("offices-result");
    const tabs = document.querySelectorAll(".offices-filter__tab");
    const searchInput = document.querySelector(".offices-filter__search-input");
    const locateBtn = document.querySelector(".offices-filter__locate");
    const hintEl = document.querySelector(".offices-filter__hint");
    const regionSelect = document.getElementById("offices-region");
    const citySelect = document.getElementById("offices-city");

    let filterType = "all";
    let filterRegion = "";
    let filterCity = "";
    let query = "";

    // populate region / city selects from the data
    function fillSelect(sel, values, allLabel) {
      if (!sel) return;
      sel.innerHTML = "";
      const opts = [allLabel].concat(values);
      opts.forEach(function (v) {
        const o = document.createElement("option");
        o.textContent = v;
        sel.appendChild(o);
      });
    }
    const uniq = function (arr) { return arr.filter(function (v, i) { return arr.indexOf(v) === i; }).sort(); };
    fillSelect(regionSelect, uniq(POINTS.map(function (p) { return p.region; })), "Все регионы");
    fillSelect(citySelect, uniq(POINTS.map(function (p) { return p.city; })), "Все города");

    // --- map (only when the Yandex API loaded) ---
    const map = hasMap
      ? new ymaps.Map(mapEl, {
          center: [41.6, 64.5],
          zoom: 6,
          controls: ["zoomControl"],
        }, { suppressMapOpenBlock: true })
      : null;
    if (map) map.behaviors.disable("scrollZoom");

    const PIN =
      '<svg viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg">' +
      '<path d="M16 0C7.7 0 1 6.7 1 15c0 10 15 25 15 25s15-15 15-25C31 6.7 24.3 0 16 0z" fill="#E60000"/>' +
      '<circle cx="16" cy="15" r="6" fill="#fff"/></svg>';

    function popupHtml(p) {
      return (
        '<span class="map__popup-tag' + (p.type === "dealer" ? " map__popup-tag_dealer" : "") + '">' +
        (p.type === "dealer" ? "Дилер" : "Офис") + "</span>" +
        '<h3 class="map__popup-title">' + p.title + "</h3>" +
        '<p class="map__popup-text">' + p.address + (p.hours ? "<br>" + p.hours : "") + "</p>" +
        '<button type="button" class="map__popup-btn" data-locate>' +
        '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' +
        '<path d="M12 21s7-6.4 7-11a7 7 0 10-14 0c0 4.6 7 11 7 11z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>' +
        '<circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.8"/></svg>Определить меня</button>'
      );
    }

    const markers = {};
    if (hasMap) POINTS.forEach(function (p) {
      const m = new ymaps.Placemark([p.lat, p.lng], { balloonContent: popupHtml(p) }, {
        iconLayout: ymaps.templateLayoutFactory.createClass('<div class="map-pin">' + PIN + "</div>"),
        iconShape: { type: "Rectangle", coordinates: [[-16, -40], [16, 0]] },
      });
      m.events.add("click", function () {
        highlightCard(p.id);
      });
      markers[p.id] = m;
    });

    // --- rendering ---
    function plural(n, one, few, many) {
      const m10 = n % 10, m100 = n % 100;
      if (m10 === 1 && m100 !== 11) return one;
      if (m10 >= 2 && m10 <= 4 && (m100 < 10 || m100 >= 20)) return few;
      return many;
    }

    function resetPage() {
      page = 1;
    }

    function matches(p) {
      if (filterType === "office" && p.type !== "office") return false;
      if (filterType === "dealer" && p.type !== "dealer") return false;
      if (filterRegion && p.region !== filterRegion) return false;
      if (filterCity && p.city !== filterCity) return false;
      if (query) {
        const q = query.toLowerCase();
        if (p.title.toLowerCase().indexOf(q) === -1 && p.address.toLowerCase().indexOf(q) === -1) return false;
      }
      return true;
    }

    function cardHtml(p) {
      return (
        '<li class="office-card' + (p.type === "dealer" ? " office-card_dealer" : "") + '" data-id="' + p.id + '" tabindex="0" role="button">' +
        '<span class="office-card__tag' + (p.type === "dealer" ? " office-card__tag_dealer" : "") + '">' +
        (p.type === "dealer" ? "Дилер" : "Офис") + "</span>" +
        '<h3 class="office-card__title">' + p.title + "</h3>" +
        '<p class="office-card__address">' + p.address + "</p></li>"
      );
    }

    // Production pages through the found points instead of scrolling the list.
    const pagerEl = document.getElementById("offices-pagination");
    const PER_PAGE = 16;
    let page = 1;

    function pagerHtml(total) {
      if (total <= 1) return "";
      const item = function (label, cls, target) {
        return '<button type="button" class="vac-pagination__item' + cls +
          '" data-page="' + target + '">' + label + "</button>";
      };
      const arrow = function (dir) {
        return '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M' +
          (dir === "prev" ? "15 6l-6 6 6 6" : "9 6l6 6-6 6") +
          '" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      };
      const pages = [];
      for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || Math.abs(i - page) <= 1) pages.push(i);
        else if (pages[pages.length - 1] !== "…") pages.push("…");
      }
      return (
        item(arrow("prev"), page === 1 ? " vac-pagination__item_disabled" : "", page - 1) +
        pages.map(function (i) {
          return i === "…"
            ? '<span class="vac-pagination__ellipsis">…</span>'
            : item(i, i === page ? " vac-pagination__item_active" : "", i);
        }).join("") +
        item(arrow("next"), page === total ? " vac-pagination__item_disabled" : "", page + 1)
      );
    }

    function render() {
      const items = POINTS.filter(matches);
      const totalPages = Math.max(1, Math.ceil(items.length / PER_PAGE));
      if (page > totalPages) page = totalPages;
      const slice = items.slice((page - 1) * PER_PAGE, page * PER_PAGE);
      listEl.innerHTML = slice.map(cardHtml).join("");
      if (pagerEl) {
        pagerEl.innerHTML = pagerHtml(totalPages);
        pagerEl.querySelectorAll(".vac-pagination__item").forEach(function (btn) {
          btn.addEventListener("click", function () {
            const target = Number(btn.dataset.page);
            if (!target || target === page || target < 1 || target > totalPages) return;
            page = target;
            render();
          });
        });
      }
      if (resultEl) resultEl.textContent = items.length + " " + plural(items.length, "точка", "точки", "точек");

      if (map) POINTS.forEach(function (p) {
        const on = map.geoObjects.indexOf(markers[p.id]) !== -1;
        if (matches(p) && !on) map.geoObjects.add(markers[p.id]);
        else if (!matches(p) && on) map.geoObjects.remove(markers[p.id]);
      });

      listEl.querySelectorAll(".office-card").forEach(function (card) {
        const id = Number(card.dataset.id);
        card.addEventListener("click", function () {
          selectPoint(id);
        });
        card.addEventListener("keydown", function (e) {
          if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            selectPoint(id);
          }
        });
      });
    }

    function selectPoint(id) {
      const p = POINTS.find(function (x) { return x.id === id; });
      if (!p) return;
      if (map) {
        map.setCenter([p.lat, p.lng], 12, { duration: 800 });
        markers[id].balloon.open();
      }
      highlightCard(id);
    }

    function highlightCard(id) {
      listEl.querySelectorAll(".office-card").forEach(function (c) {
        c.classList.toggle("office-card_active", Number(c.dataset.id) === id);
      });
      const active = listEl.querySelector(".office-card_active");
      if (active) active.scrollIntoView({ block: "nearest", behavior: "smooth" });
    }

    // --- filter tabs ---
    tabs.forEach(function (tab) {
      tab.addEventListener("click", function () {
        tabs.forEach(function (t) {
          t.classList.remove("offices-filter__tab_active");
          t.setAttribute("aria-selected", "false");
        });
        tab.classList.add("offices-filter__tab_active");
        tab.setAttribute("aria-selected", "true");
        const label = tab.textContent.trim();
        filterType = label === "Офисы" ? "office" : label === "Дилеры" ? "dealer" : "all";
        resetPage();
        render();
      });
    });

    // --- search ---
    if (searchInput) {
      searchInput.addEventListener("input", function () {
        query = searchInput.value.trim();
        resetPage();
        render();
      });
    }

    // --- region / city selects ---
    if (regionSelect) {
      regionSelect.addEventListener("change", function () {
        filterRegion = regionSelect.selectedIndex === 0 ? "" : regionSelect.value;
        resetPage();
        render();
      });
    }
    if (citySelect) {
      citySelect.addEventListener("change", function () {
        filterCity = citySelect.selectedIndex === 0 ? "" : citySelect.value;
        resetPage();
        render();
      });
    }

    // --- geolocation ---
    function locateMe() {
      if (!navigator.geolocation) {
        if (hintEl) hintEl.textContent = "Геолокация не поддерживается вашим браузером.";
        return;
      }
      if (hintEl) hintEl.textContent = "Определяем ваше местоположение…";
      navigator.geolocation.getCurrentPosition(
        function (pos) {
          const lat = pos.coords.latitude, lng = pos.coords.longitude;
          L.circleMarker([lat, lng], { radius: 8, color: "#fff", weight: 3, fillColor: "#e60000", fillOpacity: 1 }).addTo(map);
          const nearest = POINTS.slice().sort(function (a, b) {
            return (a.lat - lat) ** 2 + (a.lng - lng) ** 2 - ((b.lat - lat) ** 2 + (b.lng - lng) ** 2);
          })[0];
          map.flyTo([lat, lng], 10, { duration: 0.8 });
          if (hintEl) hintEl.textContent = "Показаны ближайшие к вам точки.";
          if (nearest) highlightCard(nearest.id);
        },
        function () {
          if (hintEl) hintEl.textContent = "Не удалось определить местоположение.";
        }
      );
    }
    if (locateBtn) locateBtn.addEventListener("click", locateMe);
    document.addEventListener("click", function (e) {
      if (e.target.closest && e.target.closest("[data-locate]")) locateMe();
    });

    render();
    // ensure correct sizing after the layout settles
    if (map) setTimeout(function () { map.container.fitToViewport(); }, 250);
  }

  // ==========================================================
  // COVERAGE — live map + city selector
  // ==========================================================
  const covEl = document.getElementById("coverage-map");
  if (covEl && typeof ymaps !== "undefined") {
    const CITIES = {
      "Ташкент": [41.311, 69.24],
      "Самарканд": [39.654, 66.96],
      "Бухара": [39.767, 64.421],
      "Нукус": [42.46, 59.617],
      "Ургенч": [41.55, 60.631],
    };
    const covMap = new ymaps.Map(covEl, {
      center: [41.6, 64.5],
      zoom: 6,
      controls: ["zoomControl"],
    }, { suppressMapOpenBlock: true });
    covMap.behaviors.disable("scrollZoom");

    // coverage zones — semi-transparent red circles over covered cities
    Object.keys(CITIES).forEach(function (name) {
      covMap.geoObjects.add(new ymaps.Circle([CITIES[name], 26000], {
        hintContent: "Зона покрытия · " + name,
      }, {
        strokeColor: "#e60000",
        strokeWidth: 1.5,
        fillColor: "#e60000",
        fillOpacity: 0.18,
      }));
    });

    const covForm = document.querySelector(".coverage-search");
    const covSelect = covForm ? covForm.querySelector(".select__control") : null;
    function flyToCity() {
      const c = covSelect && CITIES[covSelect.value];
      if (c) covMap.setCenter(c, 10, { duration: 800 });
    }
    if (covSelect) covSelect.addEventListener("change", flyToCity);
    if (covForm) covForm.addEventListener("submit", function (e) { e.preventDefault(); flyToCity(); });

    setTimeout(function () { covMap.container.fitToViewport(); }, 250);
  }

  // ==========================================================
  // HELP HUB (частые вопросы) — tabs, mobile drawer, char counter
  // ==========================================================
  const helpSection = document.querySelector(".help");
  if (helpSection) {
    const navItems = helpSection.querySelectorAll(".help__nav-item");
    const tabs = helpSection.querySelectorAll(".help__tab");
    const toggle = helpSection.querySelector(".help__aside-tab");

    function activateTab(name) {
      navItems.forEach(function (b) {
        const on = b.dataset.helpTab === name;
        b.classList.toggle("help__nav-item_active", on);
        b.setAttribute("aria-selected", on ? "true" : "false");
      });
      tabs.forEach(function (t) {
        const on = t.dataset.helpPanel === name;
        t.classList.toggle("help__tab_active", on);
        if (on) t.removeAttribute("hidden");
        else t.setAttribute("hidden", "");
      });
    }

    navItems.forEach(function (b) {
      b.addEventListener("click", function () {
        activateTab(b.dataset.helpTab);
        helpSection.classList.remove("help_drawer-open");
      });
    });

    if (toggle) {
      toggle.addEventListener("click", function () {
        const open = helpSection.classList.toggle("help_drawer-open");
        toggle.setAttribute("aria-expanded", open ? "true" : "false");
      });
    }

    const textarea = helpSection.querySelector(".field__textarea");
    const counter = helpSection.querySelector("[data-help-counter]");
    if (textarea && counter) {
      const max = Number(textarea.getAttribute("maxlength")) || 500;
      textarea.addEventListener("input", function () {
        counter.textContent = max - textarea.value.length;
      });
    }
  }


  // ==========================================================
  // SHARED — company tab strip
  // The strip scrolls on a phone because four tabs are wider than the 354px
  // pill. The frame for each page shows its own tab in view, so bring the
  // active one into the mask instead of leaving it off-screen.
  // ==========================================================
  document.querySelectorAll(".company-nav").forEach(function (nav) {
    const active = nav.querySelector(".company-nav__tab_active");
    if (!active) return;
    const overflow = nav.scrollWidth - nav.clientWidth;
    if (overflow <= 0) return;
    const centred =
      active.offsetLeft - (nav.clientWidth - active.offsetWidth) / 2;
    nav.scrollLeft = Math.max(0, Math.min(centred, overflow));
  });

  // ==========================================================
  // SHARED — filter-search (chips + text search) for
  // actions / news / services / faq / devices catalogs
  // ==========================================================
  document.querySelectorAll(".filter-search").forEach(function (fs) {
    const section = fs.closest("section") || document;
    const grid = section.querySelector(
      ".promo-grid, .news-grid, .services__grid, .faq-accordion, .brand-grid"
    );
    if (!grid) return;
    const chips = fs.querySelectorAll(".filter-search__chip");
    const input = fs.querySelector(".filter-search__input");
    const cards = Array.prototype.slice.call(grid.children);
    let activeCat = "";
    let query = "";

    function cardCategory(card) {
      const el = card.querySelector(
        ".promo-card__cat, .news-card__cat, .service-card__cat, .brand-card__cat"
      );
      return el ? el.textContent.trim().toLowerCase() : "";
    }
    function chipLabel(chip) {
      return chip.textContent.replace(/\s*\d+\s*$/, "").trim().toLowerCase();
    }
    function apply() {
      cards.forEach(function (card) {
        let show = true;
        const cat = cardCategory(card);
        if (activeCat && cat && cat !== activeCat) show = false;
        if (query && card.textContent.toLowerCase().indexOf(query) === -1) show = false;
        card.style.display = show ? "" : "none";
      });
    }
    chips.forEach(function (chip) {
      chip.addEventListener("click", function () {
        chips.forEach(function (c) {
          c.classList.remove("filter-search__chip_active");
          c.setAttribute("aria-selected", "false");
        });
        chip.classList.add("filter-search__chip_active");
        chip.setAttribute("aria-selected", "true");
        const label = chipLabel(chip);
        activeCat = label === "все" ? "" : label;
        apply();
      });
    });
    if (input) {
      input.addEventListener("input", function () {
        query = input.value.trim().toLowerCase();
        apply();
      });
    }
  });

  // ==========================================================
  // VACANCIES — category filter chips
  // ==========================================================
  const vacFilter = document.querySelector(".vac-filter");
  const vacGrid = document.querySelector(".vac-grid");
  if (vacFilter && vacGrid) {
    const chips = vacFilter.querySelectorAll(".vac-filter__chip");
    const items = Array.prototype.slice.call(vacGrid.children);
    const countEl = document.querySelector(".vac__count b");
    function vacPlural(n) {
      const m10 = n % 10, m100 = n % 100;
      if (m10 === 1 && m100 !== 11) return "вакансия";
      if (m10 >= 2 && m10 <= 4 && (m100 < 10 || m100 >= 20)) return "вакансии";
      return "вакансий";
    }
    chips.forEach(function (chip) {
      chip.addEventListener("click", function () {
        chips.forEach(function (c) {
          c.classList.remove("vac-filter__chip_active");
          c.setAttribute("aria-selected", "false");
        });
        chip.classList.add("vac-filter__chip_active");
        chip.setAttribute("aria-selected", "true");
        const label = chip.textContent.trim().toLowerCase();
        let shown = 0;
        items.forEach(function (li) {
          const cat = li.querySelector(".vac-card__cat");
          const c = cat ? cat.textContent.trim().toLowerCase() : "";
          const show = label === "все" || c === label;
          li.style.display = show ? "" : "none";
          if (show) shown++;
        });
        if (countEl) countEl.textContent = shown + " " + vacPlural(shown);
      });
    });
  }
}

/* ============================================================
   CDMA — tariff and service rails
   Real Swiper carousels with a draggable scrollbar; the cards keep
   their drawn 372px width, so slidesPerView is "auto".
   ============================================================ */
function initBlock3() {
  if (typeof Swiper === "undefined") return;

  // Card width is Swiper's job: slidesPerView per breakpoint replaces the
  // fixed widths the stylesheet used to carry.
  //
  // The card stays around the 340-370px the frames draw at every width and the
  // number of visible cards changes instead. The rail bleeds to the viewport
  // edge while .container steps its max-width, so its width does not grow
  // evenly with the screen — hence a step wherever that jump happens, rather
  // than four round numbers that leave 194px cards in the middle range.
  document.querySelectorAll(".cdma-rail").forEach(function (rail) {
    new Swiper(rail, {
      spaceBetween: 16,
      watchOverflow: true,
      freeMode: true,
      breakpoints: {
        0: { slidesPerView: 1.06, spaceBetween: 16 },
        440: { slidesPerView: 1.2, spaceBetween: 16 },
        520: { slidesPerView: 1.42, spaceBetween: 16 },
        600: { slidesPerView: 1.6, spaceBetween: 20 },
        769: { slidesPerView: 2.05, spaceBetween: 20 },
        900: { slidesPerView: 2.25, spaceBetween: 20 },
        993: { slidesPerView: 2.7, spaceBetween: 24 },
        1201: { slidesPerView: 3.2, spaceBetween: 24 },
        1401: { slidesPerView: 3.75, spaceBetween: 24 },
        1700: { slidesPerView: 4.5, spaceBetween: 24 },
      },
      scrollbar: {
        el: rail.querySelector(".cdma-rail__bar"),
        draggable: true,
      },
    });
  });
}

/* ============================================================
   TARIFF CONNECT MODAL (from production)
   Triggers carry their data on data-* attributes; the static
   build ships fixed buttons instead of the CMS-driven list.
   ============================================================ */
function initBlock4() {
  const tariffModal = document.getElementById("tariffConnectModal");
  if (!tariffModal) return;
  const nameEl = tariffModal.querySelector(".tariff-modal__name");
  const priceValue = tariffModal.querySelector(".tariff-modal__price-value");
  const pricePeriod = tariffModal.querySelector(".tariff-modal__price-period");

  function openTariffModal(trigger) {
    const d = trigger.dataset;
    nameEl.textContent = d.name || "";
    priceValue.textContent = d.price || "";
    pricePeriod.textContent = d.period || "";
    tariffModal.classList.add("tariff-modal_open");
    tariffModal.setAttribute("aria-hidden", "false");
    document.body.classList.add("overflow__hidden");
  }
  function closeTariffModal() {
    tariffModal.classList.remove("tariff-modal_open");
    tariffModal.setAttribute("aria-hidden", "true");
    document.body.classList.remove("overflow__hidden");
  }

  document.querySelectorAll(".js-tariff-connect").forEach(function (btn) {
    btn.addEventListener("click", function (event) {
      event.preventDefault();
      openTariffModal(btn);
    });
  });
  tariffModal.querySelectorAll("[data-modal-close]").forEach(function (el) {
    el.addEventListener("click", closeTariffModal);
  });
  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape" && tariffModal.classList.contains("tariff-modal_open")) {
      closeTariffModal();
    }
  });
}


export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.hook('page:finish', () => {
  initBlock1()
  initBlock2()
  initBlock3()
  initBlock4()
  })
})
