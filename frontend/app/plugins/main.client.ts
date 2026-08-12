import Swiper from 'swiper/bundle'

const documentHandlers = new Map()
const teardown = []

function bindDocument(key, type, handler) {
  const previous = documentHandlers.get(key)

  if (previous) {
    document.removeEventListener(type, previous)
  }

  documentHandlers.set(key, handler)
  document.addEventListener(type, handler)
}

function onFrame(callback) {
  let handle = requestAnimationFrame(function step(time) {
    callback(time)
    handle = requestAnimationFrame(step)
  })

  teardown.push(function () {
    cancelAnimationFrame(handle)
  })
}

function observe(observer) {
  teardown.push(function () {
    observer.disconnect()
  })

  return observer
}

function delay(callback, ms) {
  const handle = setTimeout(callback, ms)

  teardown.push(function () {
    clearTimeout(handle)
  })
}

function release() {
  teardown.splice(0).forEach(function (stop) {
    stop()
  })
}

function initBlock1() {
  const marquee = document.querySelector(".marquee");
  if (marquee) {
    const track = marquee.querySelector(".marquee__track");
    const original = track.innerHTML;
    const setWidth = track.scrollWidth;

    if (!track.dataset.filled && setWidth > 0) {
      const copies = Math.ceil((marquee.offsetWidth + setWidth) / setWidth) - 1;
      track.insertAdjacentHTML("beforeend", original.repeat(Math.max(copies, 0)));
      track.dataset.filled = "1";
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

    teardown.push(stop);

    marquee.addEventListener("mouseenter", stop);
    marquee.addEventListener("mouseleave", start);
    start();
  }

  const heroSlider = document.querySelector(".hero__slider");
  if (heroSlider && typeof Swiper !== "undefined") {
    const still = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    new Swiper(heroSlider, {
      slidesPerView: 1,
      loop: true,
      speed: 700,
      watchOverflow: true,
      autoHeight: false,
      autoplay: still ? false : { delay: 6000, disableOnInteraction: false },
      pagination: {
        el: ".hero__pagination",
        clickable: true,
        bulletElement: "button",
      },
      a11y: {
        containerMessage: "Главный баннер",
        prevSlideMessage: "Предыдущий слайд",
        nextSlideMessage: "Следующий слайд",
        paginationBulletMessage: "Перейти к слайду {{index}}",
      },
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
      const observer = observe(new IntersectionObserver(
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
      ));
      dials.forEach(function (dial) {
        observer.observe(dial);
        teardown.push(function () {
          cancelAnimationFrame(dial._raf);
          dial._raf = null;
        });
      });
    }
  }

  const needles = document.querySelectorAll(".hero__gauge .hero__gauge-needle");
  if (needles.length) {
    const DIP = 0.03;
    const readouts = [];

    document.querySelectorAll(".hero__gauge .hero__gauge-value").forEach(function (el) {
      const peak = Number((el.dataset.value ?? el.textContent).replace(/[^\d.]/g, ""));
      if (peak > 0) {
        el.dataset.value = String(peak);
        readouts.push({ el, peak });
      }
    });

    const maxTilt = -5;
    const duration = 1300;
    let start = null;

    onFrame(function (time) {
      if (start === null) start = time;
      const elapsed = (time - start) % (duration * 2);
      let p = elapsed < duration ? elapsed / duration : 1 - (elapsed - duration) / duration;
      p = p * p * (3 - 2 * p);

      const transform = "rotate(" + maxTilt * p + "deg)";
      needles.forEach(function (needle) {
        needle.style.transform = transform;
      });

      readouts.forEach(function (readout) {
        const value = readout.peak * (1 - DIP * (1 - p));
        readout.el.textContent = String(Math.round(value)).replace(/\B(?=(\d{3})+(?!\d))/g, " ");
      });
    });
  }

}

function initBlock2() {
  const covEl = document.getElementById("coverage-map");
  if (covEl) {
    const CITIES = {
      "Ташкент": [41.311, 69.24],
      "Самарканд": [39.654, 66.96],
      "Бухара": [39.767, 64.421],
      "Нукус": [42.46, 59.617],
      "Ургенч": [41.55, 60.631],
    };
    let covMap = null;

    function buildCoverage() {
      covMap = new ymaps.Map(covEl, {
        center: [41.6, 64.5],
        zoom: 6,
        controls: [],
      }, {
        suppressMapOpenBlock: true,
        balloonPanelMaxMapArea: 400 * 400,
      });
      covMap.behaviors.disable("scrollZoom");

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

      delay(function () { covMap.container.fitToViewport(); }, 250);
    }

    document.querySelectorAll(".map_coverage .map__zoom-btn").forEach(function (btn) {
      btn.addEventListener("click", function () {
        if (!covMap) return;
        covMap.setZoom(covMap.getZoom() + (btn.dataset.zoom === "in" ? 1 : -1), {
          duration: 200,
        });
      });
    });

    if (typeof ymaps !== "undefined" && typeof ymaps.ready === "function") {
      ymaps.ready(buildCoverage);
    }

    delay(function () {
      if (covMap) return;
      covEl.innerHTML =
        '<p class="map__fallback">Карта временно недоступна.<br />' +
        "Проверить покрытие можно у оператора поддержки.</p>";
    }, 8000);

    const covForm = document.querySelector(".coverage-search");
    const covSelect = covForm ? covForm.querySelector(".select__control") : null;
    function flyToCity() {
      const c = covSelect && CITIES[covSelect.value];
      if (c && covMap) covMap.setCenter(c, 10, { duration: 800 });
    }
    if (covSelect) covSelect.addEventListener("change", flyToCity);
    if (covForm) covForm.addEventListener("submit", function (e) { e.preventDefault(); flyToCity(); });

    const covFind = covForm && covForm.querySelector(".coverage-search__find");
    if (covFind) {
      const covField = covFind.querySelector(".coverage-search__field");
      const covInput = covFind.querySelector(".coverage-search__input");
      const covBtn = covFind.querySelector(".coverage-search__btn");
      const covClose = covFind.querySelector(".coverage-search__close");

      function openFind() {
        covFind.classList.add("coverage-search__find_open");
        covForm.classList.add("coverage-search_searching");
        covInput.focus();
      }
      function closeFind() {
        covFind.classList.remove("coverage-search__find_open");
        covForm.classList.remove("coverage-search_searching");
        covInput.value = "";
        if (covFind.contains(document.activeElement)) document.activeElement.blur();
      }

      covBtn.addEventListener("click", function (e) {
        const shown = getComputedStyle(covField).visibility === "visible";
        if (!shown) {
          e.preventDefault();
          openFind();
          return;
        }
        covFind.classList.add("coverage-search__find_open");
        covForm.classList.add("coverage-search_searching");
        covInput.focus();
      });
      covClose.addEventListener("click", closeFind);
      covInput.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeFind();
      });
      bindDocument("coverage-outside", "click", function (e) {
        if (!covFind.contains(e.target) && !covInput.value) closeFind();
      });
    }
  }

  const helpSection = document.querySelector(".help");
  if (helpSection) {
    const toggle = helpSection.querySelector(".help__aside-tab");

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

  document.querySelectorAll(".company-nav").forEach(function (nav) {
    const active = nav.querySelector(".company-nav__tab_active");
    if (!active) return;
    const overflow = nav.scrollWidth - nav.clientWidth;
    if (overflow <= 0) return;
    const centred =
      active.offsetLeft - (nav.clientWidth - active.offsetWidth) / 2;
    nav.scrollLeft = Math.max(0, Math.min(centred, overflow));
  });

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

function initBlock3() {
  if (typeof Swiper === "undefined") return;

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


export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.hook('page:finish', () => {
    release()
    initBlock1()
    initBlock2()
    initBlock3()
  })
})
