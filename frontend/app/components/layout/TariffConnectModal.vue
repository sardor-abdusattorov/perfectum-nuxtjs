<template>
  <div class="tariff-modal" id="tariffConnectModal" aria-hidden="true">
      <div class="tariff-modal__overlay" data-modal-close></div>
      <div class="tariff-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="tariffModalName">
          <button type="button" class="tariff-modal__close" data-modal-close aria-label="Закрыть">
              <svg viewBox="0 0 24 24" fill="none">
                  <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
              </svg>
          </button>
          <h3 class="tariff-modal__name" id="tariffModalName"></h3>
          <div class="tariff-modal__pricing">
              <span class="tariff-modal__price-value"></span>
              <span class="tariff-modal__price-currency">сум</span>
              <span class="tariff-modal__price-period"></span>
          </div>
          <h2 class="tariff-modal__heading">Подключить любым способом:</h2>
          <div class="tariff-modal__buttons">
              <a class="tariff-modal__btn" href="#">Личный кабинет</a>
              <a class="tariff-modal__btn" href="tel:077">Звонок на 077</a>
              <a class="tariff-modal__btn" href="#">Офис продаж</a>
          </div>
      </div>
  </div>
</template>
