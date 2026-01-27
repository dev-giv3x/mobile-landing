<script lang="ts">
import {defineComponent} from 'vue'

export default defineComponent({
  name: "AdventageBlock"
})
</script>

<template>
  <section class="px-4">
    <div class="flex justify-center mt-[112px] items-start">
      <div class="max-[780px]:hidden w-[265px] h-px mr-[46.5px] bg-[linear-gradient(90deg,#FEFEFF_0%,#EDEDED_100%)] mt-[18px]"></div>
      <h2 class="text-[#1A2B4B] max-[426px]:text-[18px] text-3xl whitespace-nowrap">Преимущества для вашего бизнеса</h2>
      <div class="max-[780px]:hidden w-[265px] h-px ml-[46.5px] bg-[linear-gradient(90deg,#EDEDED_0%,#EFF4FB_100%)] mt-[18px]"></div>
    </div>

    <div class="flex flex-wrap justify-center gap-10 mt-12 max-w-[1200px] mx-auto">

      <div class="text-center">
        <div class="h-[150px] flex items-center justify-center">
          <img src="@/assets/image/efficiency-up.png" class="max-h-full object-contain block" alt="">
        </div>
        <p class="text-[#1A2B4B] mt-4 whitespace-nowrap">Рост эффективности</p>
      </div>

      <div class="text-center">
        <div class="h-[150px] flex items-center justify-center">
          <img src="@/assets/image/control-security.png" class="max-h-full object-contain block" alt="">
        </div>
        <p class="text-[#1A2B4B] mt-4 whitespace-nowrap">Контроль и безопасность</p>
      </div>

      <div class="text-center">
        <div class="h-[150px] flex items-center justify-center">
          <img src="@/assets/image/communication-up.png" class="max-h-full object-contain block" alt="">
        </div>
        <p class="text-[#1A2B4B] mt-4 whitespace-nowrap">Улучшение коммуникации</p>
      </div>

    </div>
  </section>
</template>