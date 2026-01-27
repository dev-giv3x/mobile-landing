<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const icons = import.meta.glob('../assets/icons/*.svg', { eager: true })

const getIcon = (name: string) => {
  return icons[`../assets/icons/${name}.svg`]?.default || ''
}

const modules = ref([])

onMounted(async () => {
  try {
    const response = await axios.get('http://localhost:80/api/modules')
    modules.value = response.data
  } catch (error) {
    console.error('Ошибка загрузки модулей:', error)
  }
})
</script>

<template>
  <section>
  <div class="flex justify-center mt-[112px]">
    <div class="w-[174px] h-px mr-[46.5px] bg-[linear-gradient(90deg,#F1F5FB_0%,#EDEDED_100%)] mt-[18px]"></div>
    <h2 class="text-[#1A2B4B] text-3xl">Решение:<span class="text-[#1D65C1]"> Единое корпоративное приложение</span></h2>
    <div class="w-[174px] h-px ml-[46.5px] bg-[linear-gradient(90deg,#EDEDED_0%,#FCFDFE_100%)] mt-[18px]"></div>
  </div>
  <div class="flex justify-center">
    <p class="text-[#5F738C] text-lg">Все инструменты для сотрудников в одном месте</p>
  </div>
  <div class="grid grid-cols-3 gap-6 mt-12">
    <div class="w-[357px] h-[192px] bg-white rounded-xl" v-for="m in modules" :key="m.id">
      <div class="flex items-center gap-3 pl-[25px] px-[25px] pt-[25px]">
      <img :src="getIcon(m.primary_icon)" alt="" />
      <h3 class=" text-lg text-[#1A2B4B] ">{{ m.title }}</h3>
      </div>
      <div class="w-[307.33px] h-px bg-[#F3F4F6] ml-[25px] mt-4 mb-3"></div>
      <p class=" text-base text-[#5F738C] pl-[25px]"> {{ m.content }} </p>
      <div class="w-[307.33px] h-px bg-[#F3F4F6] ml-[25px] mt-4 mb-3"></div>
      <div class="flex items-center gap-3 pl-[25px]" >
      <img :src="getIcon(m.secondary_icon)" alt="" />
      <p class="text-sm text-[#5F738C]">{{ m.secondary_content }}</p>
      </div>
    </div>
  </div>
  </section>
</template>