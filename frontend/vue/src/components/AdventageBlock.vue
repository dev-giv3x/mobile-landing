<script lang="ts">
import {defineComponent} from 'vue'

export default defineComponent({
  name: "AdventageBlock"
})
</script>

<template>
      <section>
        <div class="flex justify-center mt-[112px]">
          <div class="w-[265px] h-px mr-[46.5px] bg-[linear-gradient(90deg,#FEFEFF_0%,#EDEDED_100%)] mt-[18px]"></div>
          <h2 class="text-[#1A2B4B] text-3xl">Преимущества для вашего бизнеса</h2>
          <div class="w-[265px] h-px ml-[46.5px] bg-[linear-gradient(90deg,#EDEDED_0%,#EFF4FB_100%)] mt-[18px]"></div>
        </div>
        <div class="flex justify-center gap-[133px] mt-12">
          <div class="text-center">
            <div class="h-[150px] flex items-center justify-center">
              <img src="@/assets/image/efficiency-up.png" class="max-h-full object-contain block" alt="">
            </div>
            <p class="text-[#1A2B4B] mt-4">Рост эффективности</p>
          </div>
          <div class="text-center">
            <div class="h-[150px] flex items-center justify-center">
              <img src="@/assets/image/control-security.png" class="max-h-full object-contain block" alt="">
            </div>
            <p class="text-[#1A2B4B] mt-4">Контроль и безопасность</p>
          </div>
          <div class="text-center">
            <div class="h-[150px] flex items-center justify-center">
              <img src="@/assets/image/communication-up.png" class="max-h-full object-contain block" alt="">
            </div>
            <p class="text-[#1A2B4B] mt-4">Улучшение коммуникации</p>
          </div>
        </div>
      </section>
</template>