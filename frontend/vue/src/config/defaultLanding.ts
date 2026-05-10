import type { LandingConfig } from '@/types/landing'

export const defaultLanding: LandingConfig = {
  title: 'Корпоративный лендинг',
  company_name: 'Acme Corp',
  slug: 'acme-corp',
  settings: {
    primary_color: '#1D65C1',
    logo: null,
  },
  content: {
    hero: {
      enabled: true,
      eyebrow: 'Корпоративное приложение',
      title: 'Единое цифровое пространство для вашей компании',
      subtitle:
        'Соберите коммуникации, сервисы и ключевые процессы в одном удобном мобильном интерфейсе.',
      image: null,
      image_alt: 'Превью приложения',
    },
    goals: {
      section_title: 'Цели приложения',
      items: [
        { title: 'Собрать ключевые сервисы в одном интерфейсе', image: null },
        { title: 'Упростить доступ сотрудников к информации', image: null },
        { title: 'Ускорить внутренние коммуникации и процессы', image: null },
      ],
    },
    functionality: {
      section_title: 'Функционал приложения',
      description:
        'Набор модулей можно адаптировать под процессы компании и роль сотрудников.',
    },
    modules: [
      {
        title: 'Новости компании',
        content: 'Публикуйте важные обновления и централизуйте коммуникацию.',
        primary_icon: 'news-icon',
        secondary_text: 'Push-уведомления и лента новостей',
        secondary_icon: 'second-news-icon',
      },
      {
        title: 'Онбординг',
        content: 'Ускорьте адаптацию сотрудников и стандартизируйте обучение.',
        primary_icon: 'on-boarding-icon',
        secondary_text: 'Чек-листы, материалы и инструкции',
        secondary_icon: 'second-on-boarding-icon',
      },
      {
        title: 'Сервисы для сотрудников',
        content: 'Соберите заявки, справки и обращения в одном месте.',
        primary_icon: 'shop-icon',
        secondary_text: 'Быстрый доступ к внутренним сервисам',
        secondary_icon: 'second-shop-icon',
      },
    ],
    structure: {
      section_title: 'Структура приложения',
      home_title: 'Главный экран',
      home_description:
        'Главные новости, быстрые действия, важные уведомления и персональные виджеты сотрудника.',
      services_title: 'Страница сервисов',
      services_description:
        'Каталог внутренних сервисов, заявок, справок и рабочих сценариев с быстрым доступом.',
      communications_title: 'Раздел коммуникаций',
      communications_description:
        'Лента новостей, объявления, обсуждения и каналы связи между сотрудниками и отделами.',
    },
    advantages: {
      section_title: 'Что получает бизнес',
      items: [
        { title: 'Рост эффективности', image: null },
        { title: 'Контроль и безопасность', image: null },
        { title: 'Улучшение коммуникации', image: null },
      ],
    },
  },
}