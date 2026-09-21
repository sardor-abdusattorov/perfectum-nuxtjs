import { gaugeAngle, gaugeLabel, gaugeNeedle, gaugeValue } from '~/utils/gauge'

const teardown = []

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

  const gauges = [];
  document.querySelectorAll(".hero__gauge").forEach(function (svg) {
    const needle = svg.querySelector(".hero__gauge-needle");
    const peak = Number(needle?.dataset.peak);

    if (needle && peak > 0) {
      gauges.push({ needle, readout: svg.querySelector(".hero__gauge-value"), rest: gaugeAngle(peak) });
    }
  });

  if (gauges.length) {
    const DIP = 4;
    const duration = 1300;
    let start = null;

    onFrame(function (time) {
      if (start === null) start = time;
      const elapsed = (time - start) % (duration * 2);
      let p = elapsed < duration ? elapsed / duration : 1 - (elapsed - duration) / duration;
      p = p * p * (3 - 2 * p);

      gauges.forEach(function (gauge) {
        const angle = gauge.rest - DIP * (1 - p);

        gauge.needle.style.transform = "rotate(" + gaugeNeedle(angle) + "deg)";

        if (gauge.readout) {
          gauge.readout.textContent = gaugeLabel(gaugeValue(angle));
        }
      });
    });
  }
}

function initBlock2() {

  const helpSection = document.querySelector(".help");
  if (helpSection) {
    const toggle = helpSection.querySelector(".help__aside-tab");

    if (toggle) {
      toggle.addEventListener("click", function () {
        const open = helpSection.classList.toggle("help_drawer-open");
        toggle.setAttribute("aria-expanded", open ? "true" : "false");
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
}

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.hook('page:finish', () => {
    release()
    initBlock1()
    initBlock2()
  })
})
