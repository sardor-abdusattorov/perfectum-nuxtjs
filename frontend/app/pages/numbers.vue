<script setup lang="ts">
useSeo({ titleKey: 'seo.numbers' })
</script>

<template>
  <section class="page-hero page-hero_numbers">
      <div class="container">
          <div class="page-hero__inner">
              <p class="page-hero__eyebrow">Perfectum 5G — Мобильная связь</p>
              <h1 class="page-hero__title section__title">Свободные<br /><span class="page-hero__title-red">номера</span></h1>
              <p class="page-hero__subtitle">Выберите понравившийся номер. Для приобретения обратитесь в офис или войдите в личный кабинет.</p>
          </div>
          <span class="page-hero__watermark" aria-hidden="true">5G</span>
      </div>
  </section>

  <section class="numbers">
      <div class="container">
          <div class="numbers__body">
              <div class="numbers__prices">
                  <button type="button" class="numbers__price numbers__price_active">Бесплатно</button>
                  <button type="button" class="numbers__price">200 000</button>
                  <button type="button" class="numbers__price">600 000</button>
                  <button type="button" class="numbers__price">1 000 000</button>
                  <button type="button" class="numbers__price">3 000 000</button>
                  <button type="button" class="numbers__price">5 000 000</button>
                  <button type="button" class="numbers__price">8 000 000</button>
                  <button type="button" class="numbers__price">12 000 000</button>
              </div>

              <form class="numbers__controls">
                  <input type="checkbox" id="numbersMode" class="numbers__mode" checked hidden />
                  <label for="numbersMode" class="numbers__toggle">
                      <span class="numbers__toggle-text">Выбор номера</span>
                      <span class="numbers__switch"></span>
                      <span class="numbers__toggle-text">По маске</span>
                  </label>
                  <div class="numbers__mask">
                      <span class="numbers__mask-prefix">(80)</span>
                      <input type="text" inputmode="numeric" class="numbers__mask-cell" maxlength="1" placeholder="*" aria-label="Цифра" />
                      <input type="text" inputmode="numeric" class="numbers__mask-cell" maxlength="1" placeholder="*" aria-label="Цифра" />
                      <input type="text" inputmode="numeric" class="numbers__mask-cell" maxlength="1" placeholder="*" aria-label="Цифра" />
                      <input type="text" inputmode="numeric" class="numbers__mask-cell" maxlength="1" placeholder="*" aria-label="Цифра" />
                      <input type="text" inputmode="numeric" class="numbers__mask-cell" maxlength="1" placeholder="*" aria-label="Цифра" />
                      <input type="text" inputmode="numeric" class="numbers__mask-cell" maxlength="1" placeholder="*" aria-label="Цифра" />
                      <input type="text" inputmode="numeric" class="numbers__mask-cell" maxlength="1" placeholder="*" aria-label="Цифра" />
                      <button type="button" class="numbers__submit"><svg viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="6.2" stroke="currentColor" stroke-width="1.8"/><path d="M14 14l4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>Подобрать</button>
                  </div>
              </form>
              <div class="numbers__grid">
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)42445-19</span>
                  <span class="numbers__card-price"><b>3 000 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)85319-06</span>
                  <span class="numbers__card-price"><b>200 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)70239-12</span>
                  <span class="numbers__card-price"><b>1 000 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)76387-07</span>
                  <span class="numbers__card-price"><b>600 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)04914-11</span>
                  <span class="numbers__card-price"><b>3 000 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)54810-08</span>
                  <span class="numbers__card-price"><b>600 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)11889-70</span>
                  <span class="numbers__card-price"><b>3 000 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)07747-72</span>
                  <span class="numbers__card-price"><b>200 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)29260-80</span>
                  <span class="numbers__card-price"><b>200 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)75642-74</span>
                  <span class="numbers__card-price"><b>3 000 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)06499-28</span>
                  <span class="numbers__card-price"><b>200 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)72963-17</span>
                  <span class="numbers__card-price"><b>1 000 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)54937-18</span>
                  <span class="numbers__card-price"><b>200 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)74830-39</span>
                  <span class="numbers__card-price"><b>600 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)13507-74</span>
                  <span class="numbers__card-price"><b>600 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)48810-12</span>
                  <span class="numbers__card-price"><b>200 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)73972-07</span>
                  <span class="numbers__card-price"><b>600 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)65066-87</span>
                  <span class="numbers__card-price"><b>3 000 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)41175-59</span>
                  <span class="numbers__card-price"><b>3 000 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)47393-38</span>
                  <span class="numbers__card-price"><b>600 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)23562-89</span>
                  <span class="numbers__card-price"><b>600 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)10728-73</span>
                  <span class="numbers__card-price"><b>1 000 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)68838-63</span>
                  <span class="numbers__card-price"><b>1 000 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)95609-57</span>
                  <span class="numbers__card-price"><b>1 000 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)79817-09</span>
                  <span class="numbers__card-price"><b>200 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)67100-53</span>
                  <span class="numbers__card-price"><b>600 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)99239-43</span>
                  <span class="numbers__card-price"><b>600 000</b> сум</span>
              </div>
              <div class="numbers__card">
                  <span class="numbers__card-number">(80)64089-53</span>
                  <span class="numbers__card-price"><b>200 000</b> сум</span>
              </div>
              </div>

              <div class="numbers__pager">
                  <button type="button" class="numbers__pager-btn" aria-label="Назад"><svg viewBox="0 0 8 14" fill="none"><path d="M7 1L1 7l6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                  <span class="numbers__pager-current">Страница 2</span>
                  <button type="button" class="numbers__pager-btn" aria-label="Вперёд"><svg viewBox="0 0 8 14" fill="none"><path d="M1 1l6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
              </div>

          </div>
      </div>
  </section>
</template>
