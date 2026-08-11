<script setup lang="ts">
const localePath = useLocalePath()
useSeo({ title: "Perfectum | Вакансия" })
</script>

<template>
  <!-- VACANCY -->
  <section class="vacancy">
      <div class="container">
          <div class="vacancy__inner">
              <NuxtLink class="vacancy__back" :to="localePath('/careers')">
                  <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  Вернуться на главную
              </NuxtLink>

              <div class="vacancy__hero">
                  <span class="vacancy__badge">Мобильная связь</span>
                  <h1 class="vacancy__title">Карьера</h1>
              </div>

              <article class="vacancy__card">
                  <div class="vacancy__section">
                      <h2 class="vacancy__heading">Обязанности:</h2>
                      <ul class="vacancy__list">
                          <li>Совершение исходящих звонков по готовой базе</li>
                          <li>Информирование абонентов о продуктах и услугах компании</li>
                          <li>Проведение опросов, консультаций и уточнение данных</li>
                          <li>Фиксация результатов звонков в системе</li>
                          <li>Поддержание высокого уровня сервиса при общении с клиентами</li>
                      </ul>
                  </div>

                  <div class="vacancy__section">
                      <h2 class="vacancy__heading">Требования:</h2>
                      <ul class="vacancy__list">
                          <li>Грамотная устная речь</li>
                          <li>Владение русским и узбекским языками</li>
                          <li>Уверенное пользование компьютером</li>
                          <li>Коммуникабельность, стрессоустойчивость, ответственность</li>
                          <li>Умение работать по скриптам и соблюдать регламенты</li>
                          <li>Желание обучаться и развиваться</li>
                          <li>Опыт работы в исходящем колл-центре — преимущество, но не обязателен</li>
                      </ul>
                  </div>

                  <div class="vacancy__section">
                      <h2 class="vacancy__heading">Мы предлагаем:</h2>
                      <ul class="vacancy__list">
                          <li>Стабильную заработную плату и бонусы за результаты</li>
                          <li>Удобный график работы</li>
                          <li>Обучение и поддержку на старте</li>
                          <li>Комфортные условия и дружный коллектив</li>
                      </ul>
                  </div>

                  <div class="vacancy__cta">
                      <p class="vacancy__cta-line">Присоединяйтесь к нашей команде и развивайтесь вместе с
                          нами!</p>
                      <p class="vacancy__cta-line">Отправляйте резюме уже сегодня на <a
                              class="vacancy__mail" href="mailto:hr@perfectum.uz">e-mail</a> или звоните по
                          тел: <b>+998 90 305 12 21</b></p>
                  </div>
              </article>
          </div>
      </div>
  </section>
</template>
