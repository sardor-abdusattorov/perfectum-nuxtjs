<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ titleKey: 'seo.tender' })
</script>

<template>
  <!-- TENDER DETAIL -->
  <section class="article">
      <div class="container">
          <div class="article__inner">
              <div class="article__topline">
                  <NuxtLink class="article__back" :to="localePath('/procurement')">
                      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                          aria-hidden="true">
                          <path d="M19 12H5M11 6l-6 6 6 6" stroke="currentColor" stroke-width="1.8"
                              stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                      Назад к закупкам
                  </NuxtLink>
                  <span class="article__cat article__cat_red">Открыт</span>
              </div>

              <h1 class="article__title">Конкурс по выбору поставщика сплит кондиционеров мощностью 18000 BTU Low</h1>
              <p class="article__date">
                  <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor"
                          stroke-width="1.7" />
                      <path d="M3 9h18M8 3v4M16 3v4" stroke="currentColor" stroke-width="1.7"
                          stroke-linecap="round" />
                  </svg>
                  02.06.2026
              </p>

              <div class="article__body">
                  <p>ООО «RWC» (торговая марка Perfectum) объявляет конкурс по выбору поставщика сплит
                      кондиционеров мощностью 18000 BTU Low для офисов продаж и технических помещений
                      компании.</p>

                  <h2 class="article__subtitle">Предмет закупки</h2>
                  <p>Поставка, доставка и пусконаладка сплит-систем в количестве и по адресам, указанным в
                      техническом задании. Оборудование должно быть новым, не бывшим в эксплуатации, с
                      действующей гарантией производителя.</p>

                  <h2 class="article__subtitle">Требования к участникам</h2>
                  <p>К участию допускаются юридические лица и индивидуальные предприниматели,
                      зарегистрированные в Республике Узбекистан, не находящиеся в процессе ликвидации и не
                      имеющие задолженности по налогам и обязательным платежам.</p>

                  <h2 class="article__subtitle">Порядок подачи заявок</h2>
                  <p>Коммерческие предложения принимаются в электронном виде на адрес
                      <a href="mailto:info@perfectum.uz">info@perfectum.uz</a> с пометкой «Конкурс на поставку
                      кондиционеров». К предложению прилагаются учредительные документы, прайс-лист и
                      гарантийные условия.</p>

                  <div class="callout">
                      <p>Приём заявок открыт до 31.07.2026 включительно. Организатор вправе запросить у
                          участника дополнительные документы и уточнения по предложению.</p>
                  </div>

                  <h2 class="article__subtitle">Контакты организатора</h2>
                  <p>По вопросам участия — отдел закупок: <a href="tel:+998981270077">+998 98 127 0077</a>,
                      <a href="mailto:info@perfectum.uz">info@perfectum.uz</a>.</p>
              </div>
          </div>
      </div>
  </section>
</template>
