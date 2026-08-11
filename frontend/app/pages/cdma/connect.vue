<script setup lang="ts">
const localePath = useLocalePath()
definePageMeta({ layout: 'cdma' })
useSeo({ title: "Perfectum CDMA | Qanday ulanish kerak" })
</script>

<template>
      <section class="cdma-hero cdma-hero_slim">
          <div class="container">
              <nav class="cdma-crumbs" aria-label="Havolalar zanjiri">
                  <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma')">Asosiy</NuxtLink>
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <NuxtLink class="cdma-crumbs__link" :to="localePath('/cdma/connect')">Qanday ulanish kerak</NuxtLink>
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="cdma-crumbs__current">Bizning sotuv ofislarimiz</span>
              </nav>
              <h1 class="cdma-hero__title">Bizning sotuv ofislarimiz</h1>
          </div>
      </section>

      <!-- CDMA CONNECT -->
      <section class="cdma-connect">
          <div class="container">
              <p class="cdma-connect__date">1 Yanvar, 2023</p>
              <h2 class="cdma-connect__title">Bizning sotuv ofislarimiz</h2>

              <!-- Ofisni ish tartibi -->
              <div class="cdma-table">
                  <p class="cdma-table__head" id="connect-hours">Ofisni ish tartibi</p>
                  <div class="cdma-table__scroll">
                      <table class="cdma-table__grid cdma-table__grid_hours" aria-labelledby="connect-hours">
                          <tbody>
                              <tr>
                                  <th class="cdma-table__label" scope="row" rowspan="2">ish kunlari</th>
                                  <td class="cdma-table__cell">Bosh ofis (Toshkent sh.)</td>
                                  <td class="cdma-table__cell cdma-table__cell_time">09:00-19:00</td>
                              </tr>
                              <tr>
                                  <td class="cdma-table__cell">Boshqa ofislar</td>
                                  <td class="cdma-table__cell cdma-table__cell_time">09:00-18:00</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">shanba</th>
                                  <td class="cdma-table__cell"></td>
                                  <td class="cdma-table__cell cdma-table__cell_time">10:00-17:00</td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>

              <!-- Manzil / Telefon -->
              <div class="cdma-table">
                  <div class="cdma-table__scroll">
                      <table class="cdma-table__grid cdma-table__grid_offices">
                          <caption class="visually-hidden">Toshkent shahridagi ofislar</caption>
                          <thead>
                              <tr>
                                  <th class="cdma-table__col" scope="col"></th>
                                  <th class="cdma-table__col" scope="col">Manzil</th>
                                  <th class="cdma-table__col" scope="col">Telefon</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Markaziy ofis</th>
                                  <td class="cdma-table__cell">100060, Uzbekistan,<br />Toshkent Sh., T.
                                      Shevchenko Ko'ch., 21</td>
                                  <td class="cdma-table__cell cdma-table__cell_phone"><span
                                          class="cdma-table__code">+998</span> 98 305-11-11,<br /><span
                                          class="cdma-table__code">+998</span> 98 127-00-77,<br />«Perfectum»
                                      Mobil Telefonidan: 077 (Bepul) I 099 - 84,20 Sum/ Daq.</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Chilonzor ofis</th>
                                  <td class="cdma-table__cell">Gagarina ko'ch., 40 uy</td>
                                  <td class="cdma-table__cell cdma-table__cell_phone"><span
                                          class="cdma-table__code">+998</span> 98 127-00-22</td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>

              <!-- Viloyatdagi ofislar -->
              <div class="cdma-table">
                  <p class="cdma-table__head" id="connect-regions">Viloyatdagi ofislar</p>
                  <div class="cdma-table__scroll">
                      <table class="cdma-table__grid cdma-table__grid_regions" aria-labelledby="connect-regions">
                          <tbody>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Andijon</th>
                                  <td class="cdma-table__cell">Boburshox Prospekt, 22g</td>
                                  <td class="cdma-table__cell cdma-table__cell_phone" rowspan="13"><span
                                          class="cdma-table__code">+998</span> 98 305-11-11</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Buxoro</th>
                                  <td class="cdma-table__cell">Mustakillik ko'ch., uy 28/2,blok 4 (mo'ljal: k/t
                                      «Buxoro»)</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Guliston</th>
                                  <td class="cdma-table__cell">Sayxun ko'ch., 17 uy</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Jizzax</th>
                                  <td class="cdma-table__cell">Sh.Rashidova ko'ch., 300</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Qarshi</th>
                                  <td class="cdma-table__cell">Shodlik MFY, Nasaf k.</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Qo'kon</th>
                                  <td class="cdma-table__cell">Qo'qon sh, Turkiston ko'chasi 136 E uy. Mo'ljal:
                                      G'isht ko'prik, yong'in xizmati yonida.</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Navoi</th>
                                  <td class="cdma-table__cell">Islom Karimov ko'ch., uy 103</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Namangan</th>
                                  <td class="cdma-table__cell">3 pr. I.Karimova, d.1a</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Nukus</th>
                                  <td class="cdma-table__cell">Karakalpakstan ko'ch., 20 uy</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Samarqand</th>
                                  <td class="cdma-table__cell">Shoxrux Mirzo ko'ch., 62a</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Termez</th>
                                  <td class="cdma-table__cell">Bog'i shamol, Sulton Saodat ko'ch. 183 uy</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Urg'anch</th>
                                  <td class="cdma-table__cell">Paxlovon Maxmud ko'ch., 17</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Farg'ona</th>
                                  <td class="cdma-table__cell">B.Margiloniy ko'ch., Mash'al 29 M.F.Y</td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>

              <!-- Call-markaz -->
              <div class="cdma-table">
                  <p class="cdma-table__head" id="connect-call">Viloyma'lumotlar xizmati (call-markaz) ning ish vaqtlari:</p>
                  <div class="cdma-table__scroll">
                      <table class="cdma-table__grid cdma-table__grid_call" aria-labelledby="connect-call">
                          <tbody>
                              <tr>
                                  <th class="cdma-table__label" scope="row"><span
                                          class="cdma-table__time">07:00</span> dan<br /><span
                                          class="cdma-table__time">23:00</span> gacha</th>
                                  <td class="cdma-table__cell cdma-table__cell_phone"><span
                                          class="cdma-table__code">+998</span> 98 305-11-11, <span
                                          class="cdma-table__code">+998</span> 98 127-00-77, 077 (Perfectum
                                      abonentlari uchun bepul), 099 (84.20 so'm/daq.)</td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>

              <!-- Murojaatlar jadvali -->
              <div class="cdma-table">
                  <p class="cdma-table__head" id="connect-reception">Fuqarolarning murojaatlarini qabul qilish jadvali.</p>
                  <div class="cdma-table__scroll">
                      <table class="cdma-table__grid cdma-table__grid_reception" aria-labelledby="connect-reception">
                          <tbody>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Seshanba, Juma</th>
                                  <td class="cdma-table__cell cdma-table__cell_time">10:00-12:00</td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>

              <!-- Ulanish narxlari -->
              <div class="cdma-table">
                  <div class="cdma-table__scroll">
                      <table class="cdma-table__grid cdma-table__grid_price">
                          <caption class="visually-hidden">Ulanish narxlari</caption>
                          <thead>
                              <tr>
                                  <th class="cdma-table__col" scope="col">Ulanish turi</th>
                                  <th class="cdma-table__col" scope="col">Tarmoqqa ulanish narxi*</th>
                                  <th class="cdma-table__col" scope="col">Ulanishdagi minimal dastlabki to'lov*
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Mahalliy</th>
                                  <td class="cdma-table__cell" rowspan="2">0 so'm /6 000 so'm (kompaniya
                                      RUIM-kartasi bilan ulanish)</td>
                                  <td class="cdma-table__cell">8 000 so'm</td>
                              </tr>
                              <tr>
                                  <th class="cdma-table__label" scope="row">Xalqaro</th>
                                  <td class="cdma-table__cell">25 000 so'm</td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>

              <p class="cdma-connect__note">*Kompaniya o'tkazilayotgan marketing aksiyalar shartlariga muvofiq
                  to'lov miqdorini almashtirish huquqiga egadir.</p>

              <div class="cdma-connect__legal">
                  <p>10 000 so'm kamayganda, xalqaro chiqish qo'ng'iroqlarini amalga oshirish cheklanadi.<br />1
                      000 so'm kamayganda raqam tarmoqdan to'liq o'chiriladi (kirish qo'ng'iroqlari bepul bo'lgan
                      tarif rejalardan tashqari).<br />420 so'm kamayganda kirish qo'ng'iroqlari bepul bo'lgan
                      tarif rejalarda raqam tarmoqdan to'liq o'chiriladi.<br />1 000 so'm kamayganda kirish
                      qo'ng'iroqlari bepul bo'lgan tarif rejalarda chiqish aloqasi cheklanadi.</p>
                  <p>Aktivlashtirish chegarasi - 1 500 so'm<br />Narxlar QQSni hisobga olgan holda
                      ko'rsatilgan.</p>
                  <p>Ulanish uchun Siz pasport yoki haydovchilik guvohnomasi (yangi shakli, O'zR VM 02.03.2017
                      &#8470;116 qarori bilan tasdiqlangan)<br />Ommaviy Oferta matni bilan russ tilida shu erda,
                      o'zbek tilida &ndash; shu erda tanishishingiz mumkin.</p>
                  <p><b><u>DIQQAT</u></b><br />Ko'chma radiotelefon (uyali) aloqa xizmatlarini taqdim qilish uchun
                      Ommaviy oferta (Shartnoma)ga asosan, Siz O'zbekiston Respublikasining amaldagi qonunlari
                      shartlariga muvofiq, &ldquo;PERFECTUM&rdquo; TARMOG'IGA SIZNING NOMINGIZGA ULANGAN BARCHA
                      RAQAMLAR UCHUN TO'LIQ JAVOBGAR BO'LISHINGIZNI eslatib o'tamiz.</p>
                  <p><b><u>BU MUHIM:</u></b><br />Abonentlik raqamidan qonunga xilof maqsadlarda foydalanmaslik,
                      shuningdek &ldquo;frod&rdquo; sifatida baholanadigan harakatlarga yo'l qo'ymaslik
                      kerak.*<br />Pasport, haydovchilik guvohnomasi (yangi shakli), manzil va h.k. o'zgartirilgan
                      taqdirda, Operatorni 3 (uch) bank kuni mobaynida barcha o'zgarishlar to'g'risida xabardor
                      qilish va taalluqli hujjatning asl nusxasini taqdim qilish zarur.<br />Boshqa shaxs nomiga
                      rasmiylashtirilgan abonentlik raqamidan foydalanilgan taqdirda, Kompaniya qoidalariga
                      muvofiq, uni o'z nomingizga rasmiylashtirish lozim.</p>
                  <p>Shartnomani faqat kompaniya ofislarida qayta tuzish mumkin. Buning uchun quyidagi hujjatlar
                      taqdim qilingan holda, har ikki shaxs &ndash; nomidan qayta tuzish amalga oshiriladigan
                      abonent va nomiga qayta tuzish amalga oshiriladigan abonent ishtirok qilishi zarur:</p>
                  <p><b>Raqam rasmiylashtirgan abonent:</b><br />jismoniy shaxs &ndash; pasportning asl nusxasi
                      yoki haydovchilik guvohnomasi (yangi shakli)<br />yuridik shaxs &ndash; rahbar imzosi va
                      muhri bilan tasdiqlangan qayta tuzish uchun ariza.</p>
                  <p><b>Yangi abonent:</b><br />jismoniy shaxs &ndash; pasportning asl nusxasi yoki haydovchilik
                      guvohnomasi (yangi shakli)<br />yuridik shaxs, agar tarmoq abonenti hisoblanmasa &ndash;
                      yuridik shaxs ro'yxatdan o'tkazilganligi to'g'risida guvohnoma, rahbar imzosi va yuridik
                      shaxs muhri bilan tasdiqlangan qayta tuzishga rozilik to'g'risida barcha rekvizitlar qayd
                      etilgan xat, yuridik shaxs rahbari pasportining nusxasi.</p>
                  <p>Shartnomani qayta tuzish barcha qarzdorliklar to'langandan so'ng amalga oshiriladi, agar
                      bunday qarzlar mavjud bo'lsa. Shartnomani qayta tuzishda yangi abonent xizmat uchun
                      kompaniyaning amaldagi tarif rejalariga asosan, xar bir abonent raqami va imtiyozli
                      raqamning 100% qiymatini to'laydi, Kompaniya qoidalariga muvofiq imtiyozli raqam qiymati
                      undirilmaydigan holatlar bundan mustasno.</p>
                  <p><b>* Frod</b> &ndash; Abonentning Operator tomonidan taqdim qilinadigan xizmatlardan
                      tegishlicha to'lovlarsiz, belgilangan tartib-qoidalar buzilgan holda foydalanishga olib
                      keladigan, xuddi shuningdek Operator, Operator abonentlari va/yoki boshqa uchinchi
                      shaxslarning pul mablag'lari va ma'lumotlarini qonunga xilof ravishda egallashga qaratilgan
                      qasddan amalga oshirgan harakatlari va/yoki harakatsizligi.</p>
                  <p><b>3.1.3-band</b> &ldquo;Abonent manzillar, bank yoki boshqa rekvizitlar barcha
                      o'zgartirishlari to'g'risida Operatorni 3 (uch) bank kuni mobaynida xabardor qilish
                      majburiyatini oladi. Bunda o'zgarishlar to'g'risidagi xabarnoma Abonent tomonidan
                      Operatorning taalluqli tasdiqnomasi olingandan so'ng Operatorga yetkazib berilgan
                      hisoblanadi. Abonent tomonidan qayd etilgan harakatlar bajarilmagan taqdirda, ushbu Abonent
                      Operatorga jarima to'lash kunida O'zbekiston Respublikasida belgilangan eng kam ish haqining
                      ikki baravari miqdorida jarima to'laydi, shuningdek Abonentning yangi manzili, bank yoki
                      boshqa rekvizitlarini belgilash bilan bog'liq sarf-xarajatlar o'rnini qoplaydi. Operator
                      tomonidan unga so'nggi ma'lum bo'lgan Abonent manzili bo'yicha yuborilgan barcha schyotlar
                      va/yoki bildirish xatlari (da'vo-shikoyatlar) tegishli tarzda jo'natilgan hisoblanadi, bunda
                      ular olinmagan taqdirda Operator hech qanday da'vo-shikoyatlarni qabul
                      qilmaydi&rdquo;.</p>
                  <p>&laquo;Telekommunikatsiya xizmatlarini ko'rsatish qoidalari&raquo; (ro'yxat raqami 3762,
                      26.01.2026 y.) bilan bu erda tanishishingiz mumkin. Ommaviy oferta matni bilan rus va
                      o'zbek tillarida bu erda tanishishingiz mumkin.</p>
              </div>
          </div>
      </section>

      <section class="cdma-footer">
      <div class="container">
          <nav class="cdma-footer__nav" aria-label="CDMA bo'limlari">
              <NuxtLink class="cdma-footer__back" aria-label="Bosh sahifaga" :to="localePath('/')">
                  <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M19 12H5M11 18l-6-6 6-6" stroke="currentColor" stroke-width="1.6"
                          stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
              </NuxtLink>
              <NuxtLink class="cdma-footer__link" :to="localePath('/cdma')">CDMA</NuxtLink>
              <NuxtLink class="cdma-footer__link" :to="localePath('/cdma/dealers')">Dilerlar</NuxtLink>
              <NuxtLink class="cdma-footer__link" :to="localePath('/about-company')">Kompaniya haqida</NuxtLink>
          </nav>
          <span class="cdma-footer__copy">ООО «RWC» (Торговая марка Perfectum)</span>
      </div>
  </section>
</template>
