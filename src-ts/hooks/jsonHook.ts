import {createURL, getValue} from "../util/util";
import {UrlString} from "../response/json";
import {useEffect, useState} from "react";
import {debug} from "../index";

export function fetchJson<T = any>(url: URL | UrlString, setState: (state: any) => void, defaultValue: T | (() => T)) {
    if (!(url instanceof URL)) url = createURL(url)
    if (url == undefined) return getValue(defaultValue)
    const currentUrl = createURL(window.location.href)

    if (url.host == currentUrl.host) {
        url.protocol = currentUrl.protocol
    }

    if (debug) console.debug("fetch: " + url)

    fetch(url)
        .then((res: Response) => {
            if (!res.ok) {
                console.error(res.statusText)
                return;
            }
            res.json().then((json) => {
                if (debug) console.debug(json)
                setState(json)
            }).catch((reason) => {
                console.error(url + ": " + reason)
            })
        })
}


export function useJson<T = any>(url: URL | UrlString, defaultValue: T | (() => T)): T {
    const [restObj, setObj] = useState<T>()
    if (!(url instanceof URL)) url = createURL(url)
    let strUrl = url == undefined ? "" : url.href

    useEffect(() => {
        if (url == undefined) return

        fetchJson<T>(url, setObj, defaultValue)
    }, [strUrl, setObj])

    if (restObj) return restObj

    return getValue(defaultValue)
}
