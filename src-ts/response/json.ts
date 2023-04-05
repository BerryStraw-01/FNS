export  type UrlTemplate = string
export type UrlString = string

export interface Root {
    imageList: UrlString
    serviceList: UrlString
    service: UrlTemplate
    themeUrl: UrlString
}

export function initRoot(): Root {
    return {
        imageList: "",
        service: "",
        serviceList: "",
        themeUrl: "",
    }
}

export interface Images {
}

export function initImages(): Images {
    return {}
}

export interface ServiceList {
    serviceList: Service[]
}

export function initServiceList(): ServiceList {
    return {
        serviceList: []
    }
}

export interface Service {
    topImg: UrlString
    title: string
    titleColor: string
    strId: string
    subtitle: string
}

export function initService(): Service {
    return {
        topImg: "",
        title: "",
        titleColor: "",
        strId: "",
        subtitle: "",
    }
}

export interface Theme {
    thinTheme: string
    theme1: string
    theme2: string
    theme3: string
    accent: string
    plain: string
    baseText: string
    fonts: Font[]
}


export function initTheme(): Theme {
    return {
        thinTheme: "",
        theme1: "",
        theme2: "",
        theme3: "",
        plain: "",
        baseText: "",
        fonts: [],
        accent: ""
    }
}

export interface Font {
    name: string
    url: UrlString
}

export function initFont(): Font {
    return {
        name: "",
        url: "",
    }
}