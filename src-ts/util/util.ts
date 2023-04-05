import {UrlString, UrlTemplate} from "../response/json";

export function mapSpringUrl(urlTemplate: UrlTemplate, key: string, value: string): URL {
    if (urlTemplate == undefined) return null
    return createURL(urlTemplate.replace("{" + key + "}", value))
}

export function mapReactPath(urlTemplate: UrlTemplate, key: string, value: string): string {
    if (urlTemplate == undefined) return null
    return urlTemplate.replace(":" + key, value)
}

export function createURL(url: URL | UrlString | string) {
    if (url == undefined) return undefined
    if (url == "") return undefined

    try {
        return new URL(url, window.location.href)
    } catch (e) {
        console.error(e)
        return null
    }
}

export function getValue<T>(value: T | (() => T)) {
    return value instanceof Function ? value() : value
}

export function getDefault<T>(prop: T | undefined, defaultValue: T): T {
    let value = prop
    if (value == undefined) {
        value = defaultValue
    }
    return value
}