export interface LandingCardItem {
  title: string
  image?: string | null
}

export interface LandingModule {
  title: string
  content: string
  primary_icon?: string | null
  secondary_text?: string | null
  secondary_icon?: string | null
}

export interface LandingConfig {
  title: string
  company_name: string
  slug: string
  settings: {
    primary_color: string
    logo?: string | null
  }
  content: {
    hero: {
      enabled: boolean
      eyebrow: string
      title: string
      subtitle: string
      image?: string | null
      image_alt?: string | null
    }
    goals: {
      section_title: string
      items: LandingCardItem[]
    }
    functionality: {
      section_title: string
      description: string
    }
    modules: LandingModule[]
    structure: {
      section_title: string
      home_title: string
      home_description: string
      services_title: string
      services_description: string
      communications_title: string
      communications_description: string
    }
    advantages: {
      section_title: string
      items: LandingCardItem[]
    }
  }
}