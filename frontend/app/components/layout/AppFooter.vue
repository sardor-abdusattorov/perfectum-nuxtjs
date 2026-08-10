<template>
  <footer class="footer">
      <div class="container">
          <div class="footer__row">
              <div class="footer__brand">
                  <a href="#" class="footer__logo">
                      <img src="/images/logo.svg" alt="Perfectum 5G" />
                  </a>
                  <NuxtLink class="footer__about" :to="localePath('/about-company')">Подробнее о нас</NuxtLink>
                  <div class="footer__contacts">
                      <div class="footer__contact">
                          <h5 class="footer__contact-title">Головной офис</h5>
                          <p class="footer__contact-text">Узбекистан, Ташкент, ул. Тараса Шевченко 21</p>
                          <a class="footer__contact-link" href="#">Показать на карте</a>
                      </div>
                      <div class="footer__contact">
                          <h5 class="footer__contact-title">Телефоны поддержки</h5>
                          <ul class="footer__contact-list">
                              <li><a class="footer__contact-phone" href="#">+998 98 127 0077</a></li>
                              <li><a class="footer__contact-phone" href="#">+998 98 305 1111</a></li>
                          </ul>
                          <p class="footer__contact-text">Для абонентов Perfectum — <a
                                  class="footer__contact-phone" href="#">077</a></p>
                      </div>
                      <div class="footer__contact">
                          <h5 class="footer__contact-title">Чат с оператором в Telegram</h5>
                          <a class="footer__contact-link" href="#">@Perfectum_Support</a>
                      </div>
                      <div class="footer__contact footer__contact_email">
                          <h5 class="footer__contact-title">Email</h5>
                          <p class="footer__contact-text">Для получения информации —
                              info@perfectum.uz<br />Горячая линия для жалоб и обращений — hotline@perfectum.uz
                          </p>
                          <p class="footer__contact-text">ООО "RWC" (Торговая марка Perfectum) | Все права
                              защищены | Услуги лицензированы | Цены указаны с учетом всех налогов.</p>
                      </div>
                  </div>
              </div>

              <div class="footer__nav">
                  <div class="footer__menu">
                      <h6 class="footer__heading">Домашний интернет</h6>
                      <ul class="footer__list">
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/coverage-area')">Карта покрытия</NuxtLink>
                          </li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/offices')">Офисы продаж</NuxtLink>
                          </li>
                          <li class="footer__list-item"><a class="footer__list-link" href="#">Личный кабинет</a>
                          </li>
                      </ul>
                  </div>
                  <div class="footer__menu">
                      <h6 class="footer__heading">Мобильная связь</h6>
                      <ul class="footer__list">
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/tariffs')">Тарифы</NuxtLink></li>
                          <li class="footer__list-item"><a class="footer__list-link" href="#">Корпоративным
                                  клиентам</a></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/services')">Услуги</NuxtLink></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/numbers')">Свободные номера</NuxtLink>
                          </li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/device-catalog')">Устройства</NuxtLink></li>
                          <li class="footer__list-item"><a class="footer__list-link" href="#">Сегодня в
                                  продаже</a></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/coverage-area')">Зона покрытия</NuxtLink>
                          </li>
                      </ul>
                  </div>
                  <div class="footer__menu">
                      <h6 class="footer__heading">Полезное</h6>
                      <ul class="footer__list">
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/actions')">Акции</NuxtLink></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/news')">Новости</NuxtLink></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/offices')">Офисы</NuxtLink></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/offices')">Дилеры</NuxtLink></li>
                          <li class="footer__list-item"><a class="footer__list-link" href="#">Как подключиться</a>
                          </li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/faq')">Полезно знать</NuxtLink>
                          </li>
                      </ul>
                  </div>
                  <div class="footer__menu">
                      <h6 class="footer__heading">Информация</h6>
                      <ul class="footer__list">
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/procurement')">Закупки</NuxtLink></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/careers')">Карьера</NuxtLink></li>
                          <li class="footer__list-item"><NuxtLink class="footer__list-link" :to="localePath('/contacts')">Контакты</NuxtLink></li>
                          <li class="footer__list-item"><a class="footer__list-link" href="#">Юридические
                                  документы</a></li>
                      </ul>
                  </div>
                  <div class="footer__widgets">
                      <div class="footer__widget">
                          <h6 class="footer__heading">Мобильное приложение</h6>
                          <div class="footer__stores">
                              <a class="store store_small" href="#">
                                  <img src="/images/google_play.svg" alt="Google Play" />
                              </a>
                              <a class="store store_small" href="#">
                                  <img src="/images/apple.svg" alt="App Store" />
                              </a>
                          </div>
                      </div>
                      <div class="footer__widget">
                          <h6 class="footer__heading">Социальные сети</h6>
                          <div class="footer__social">
                              <a class="footer__social-link" href="#" aria-label="Facebook">
                                  <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                      xmlns="http://www.w3.org/2000/svg">
                                      <path
                                          d="M12.1263 21V11.4216H15.34L15.8222 7.68764H12.1263V5.30404C12.1263 4.22331 12.4251 3.4868 13.9767 3.4868L15.9523 3.48599V0.146191C15.6106 0.101793 14.4378 0 13.0728 0C10.2225 0 8.27113 1.73981 8.27113 4.93423V7.68764H5.04761V11.4216H8.27113V21H12.1263Z"
                                          fill="currentColor" />
                                  </svg>
                              </a>
                              <a class="footer__social-link" href="#" aria-label="Instagram">
                                  <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                      xmlns="http://www.w3.org/2000/svg">
                                      <path
                                          d="M20.9795 6.17406C20.9302 5.05821 20.7498 4.29115 20.4913 3.62641C20.2246 2.92084 19.8144 2.28916 19.277 1.76402C18.7518 1.23071 18.1159 0.816288 17.4185 0.553819C16.75 0.29537 15.9869 0.114866 14.8711 0.0657199C13.7469 0.0123071 13.3901 0 10.5389 0C7.68777 0 7.33091 0.0123071 6.21092 0.0615355C5.09512 0.110764 4.32802 0.29135 3.66348 0.549635C2.95771 0.816288 2.32603 1.22652 1.80088 1.76402C1.26757 2.28912 0.853359 2.92499 0.590684 3.62239C0.332235 4.29115 0.151772 5.05411 0.102585 6.16987C0.0492134 7.29404 0.0368652 7.65091 0.0368652 10.5021C0.0368652 13.3532 0.0492133 13.7101 0.0984007 14.83C0.147629 15.9459 0.328215 16.7129 0.586705 17.3777C0.853359 18.0833 1.26757 18.7149 1.80088 19.2401C2.32603 19.7734 2.96189 20.1878 3.65929 20.4503C4.32798 20.7087 5.09094 20.8892 6.20694 20.9384C7.32672 20.9878 7.68379 20.9999 10.5349 20.9999C13.3861 20.9999 13.7429 20.9878 14.8629 20.9384C15.9787 20.8892 16.7458 20.7088 17.4104 20.4503C18.1083 20.1804 18.7422 19.7677 19.2714 19.2386C19.8005 18.7095 20.2133 18.0756 20.4832 17.3777C20.7415 16.709 20.9221 15.9458 20.9713 14.83C21.0205 13.7101 21.0328 13.3532 21.0328 10.5021C21.0328 7.65091 21.0286 7.294 20.9795 6.17406ZM19.0884 14.748C19.0432 15.7736 18.8709 16.3274 18.7273 16.6966C18.3744 17.6114 17.6484 18.3376 16.7335 18.6905C16.3643 18.834 15.8065 19.0063 14.7849 19.0513C13.6772 19.1007 13.3451 19.1128 10.5431 19.1128C7.74115 19.1128 7.40479 19.1007 6.30113 19.0513C5.27554 19.0063 4.72172 18.834 4.35251 18.6905C3.89727 18.5222 3.48289 18.2555 3.1465 17.9068C2.7978 17.5663 2.53114 17.1561 2.36286 16.7008C2.21928 16.3316 2.04702 15.7736 2.00202 14.7522C1.95263 13.6445 1.94049 13.3122 1.94049 10.5102C1.94049 7.70826 1.95263 7.37191 2.00202 6.26845C2.04702 5.24286 2.21928 4.68904 2.36286 4.31983C2.53114 3.86439 2.7978 3.45009 3.15068 3.11361C3.49101 2.76491 3.90125 2.49826 4.3567 2.33018C4.72591 2.1866 5.28391 2.0143 6.30532 1.96913C7.41296 1.91991 7.74533 1.9076 10.5471 1.9076C13.3532 1.9076 13.6854 1.91991 14.789 1.96913C15.8146 2.01434 16.3685 2.18656 16.7377 2.33014C17.1929 2.49826 17.6073 2.76491 17.9437 3.11361C18.2924 3.45415 18.559 3.86439 18.7273 4.31983C18.8709 4.68904 19.0432 5.24684 19.0883 6.26845C19.1376 7.37609 19.1499 7.70826 19.1499 10.5102C19.1499 13.3122 19.1376 13.6404 19.0884 14.748Z"
                                          fill="currentColor" />
                                      <path
                                          d="M10.5389 5.10743C7.56066 5.10743 5.14429 7.52364 5.14429 10.502C5.14429 13.4804 7.56066 15.8967 10.5389 15.8967C13.5172 15.8967 15.9335 13.4804 15.9335 10.502C15.9335 7.52364 13.5172 5.10743 10.5389 5.10743ZM10.5389 14.0014C8.60676 14.0014 7.0395 12.4343 7.0395 10.502C7.0395 8.56975 8.60676 7.00272 10.5389 7.00272C12.4712 7.00272 14.0382 8.56975 14.0382 10.502C14.0382 12.4343 12.4711 14.0014 10.5389 14.0014ZM17.4063 4.89411C17.4063 5.58962 16.8424 6.15354 16.1467 6.15354C15.4513 6.15354 14.8874 5.58962 14.8874 4.89411C14.8874 4.19851 15.4513 3.63477 16.1468 3.63477C16.8424 3.63477 17.4063 4.19847 17.4063 4.89411Z"
                                          fill="currentColor" />
                                  </svg>
                              </a>
                              <a class="footer__social-link" href="#" aria-label="Telegram">
                                  <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                      xmlns="http://www.w3.org/2000/svg">
                                      <path
                                          d="M8.23979 13.2832L7.89241 18.1692C8.38941 18.1692 8.60466 17.9557 8.86279 17.6994L11.1929 15.4725L16.0212 19.0084C16.9067 19.5019 17.5305 19.242 17.7694 18.1937L20.9387 3.34324L20.9395 3.34237C21.2204 2.03337 20.4662 1.52149 19.6034 1.84262L0.974661 8.97474C-0.296714 9.46824 -0.277464 10.177 0.758536 10.4981L5.52116 11.9795L16.5838 5.05737C17.1044 4.71262 17.5778 4.90337 17.1884 5.24812L8.23979 13.2832Z"
                                          fill="currentColor" />
                                  </svg>
                              </a>
                              <a class="footer__social-link" href="#" aria-label="LinkedIn">
                                  <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                      xmlns="http://www.w3.org/2000/svg">
                                      <path
                                          d="M2.79899 0.0146484C1.25678 0.0146484 0.00112918 1.27005 0 2.81348C0 4.35691 1.25565 5.61256 2.79899 5.61256C4.34178 5.61256 5.59678 4.35691 5.59678 2.81348C5.59678 1.27022 4.3417 0.0146484 2.79899 0.0146484ZM4.89967 6.73714H0.697994C0.650451 6.73714 0.603374 6.74651 0.559451 6.7647C0.515528 6.7829 0.47562 6.80957 0.442005 6.84319C0.408391 6.87681 0.381729 6.91673 0.363543 6.96065C0.345356 7.00458 0.336001 7.05166 0.336012 7.0992V20.6231C0.336012 20.7192 0.374149 20.8112 0.442034 20.8791C0.509919 20.947 0.601991 20.9851 0.697994 20.9851H4.89959C4.9956 20.9851 5.08766 20.947 5.15556 20.8791C5.22345 20.8112 5.26161 20.7192 5.26165 20.6231V7.0992C5.26169 7.05165 5.25235 7.00456 5.23417 6.96063C5.21599 6.91669 5.18933 6.87677 5.15571 6.84314C5.12209 6.80952 5.08217 6.78285 5.03824 6.76466C4.99431 6.74647 4.94722 6.73712 4.89967 6.73714ZM15.6411 6.57792C14.1035 6.57792 12.7522 7.04613 11.9277 7.80962V7.0992C11.9277 6.89918 11.7656 6.73714 11.5656 6.73714H7.53518C7.48763 6.73714 7.44055 6.7465 7.39662 6.7647C7.35269 6.7829 7.31278 6.80956 7.27916 6.84318C7.24554 6.87681 7.21887 6.91672 7.20067 6.96065C7.18248 7.00457 7.17311 7.05166 7.17311 7.0992V20.6231C7.17311 20.6707 7.18248 20.7178 7.20068 20.7617C7.21887 20.8056 7.24554 20.8455 7.27916 20.8791C7.31279 20.9127 7.3527 20.9394 7.39663 20.9576C7.44055 20.9758 7.48763 20.9851 7.53518 20.9851H11.7328C11.8288 20.9851 11.9209 20.947 11.9888 20.8791C12.0567 20.8112 12.0948 20.7192 12.0949 20.6231V13.9322C12.0949 12.0124 12.448 10.8225 14.2102 10.8225C15.9464 10.8246 16.0764 12.1006 16.0764 14.0469V20.6231C16.0764 20.6707 16.0858 20.7178 16.104 20.7617C16.1222 20.8056 16.1488 20.8455 16.1825 20.8791C16.2161 20.9128 16.256 20.9394 16.3 20.9576C16.3439 20.9758 16.391 20.9851 16.4385 20.9851H20.6381C20.7341 20.9851 20.8262 20.947 20.894 20.8791C20.9619 20.8112 21.0001 20.7191 21.0001 20.6231V13.2047C21 10.1194 20.3909 6.57792 15.6411 6.57792Z"
                                          fill="currentColor" />
                                  </svg>
                              </a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </footer>
</template>
