<script setup lang="ts">
const block = useBlock('home', 'choose')

const cards = computed(() => published(block.value.cards))
</script>

<template>
  <section class="choose">
    <div class="container">
      <div class="section__head">
        <h2 class="choose__title section__title">{{ block.title }}</h2>
        <LayoutArrowLink :link="block.link" link-class="choose__btn arrow-link">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="14" viewBox="0 0 18 14" fill="none">
            <path
              d="M16 6.70715L16.7071 6.00005L17.4142 6.70715L16.7071 7.41426L16 6.70715ZM1 7.70715C0.447715 7.70715 0 7.25944 0 6.70715C0 6.15487 0.447715 5.70715 1 5.70715V6.70715V7.70715ZM10 0.707153L10.7071 4.65512e-05L16.7071 6.00005L16 6.70715L15.2929 7.41426L9.29289 1.41426L10 0.707153ZM16 6.70715L16.7071 7.41426L10.7071 13.4143L10 12.7072L9.29289 12L15.2929 6.00005L16 6.70715ZM16 6.70715V7.70715H1V6.70715V5.70715H16V6.70715Z"
              fill="currentColor" />
          </svg>
        </LayoutArrowLink>
      </div>

      <div class="choose__cards">
        <LayoutCardLink
          v-for="(card, index) in cards"
          :key="index"
          :url="card.url"
          :class="['choose__card', card.color && `choose__card_${card.color}`]"
        >
          <div class="choose__top">
            <span class="choose__cat">{{ card.category }}</span>
            <span class="choose__corner">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                <path d="M1.5 11.5L11.5 1.5M1.5 1.5H11.5V11.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </span>
          </div>
          <span class="choose__name"><LayoutLines :value="card.name" /></span>
        </LayoutCardLink>
      </div>
    </div>
  </section>
</template>
