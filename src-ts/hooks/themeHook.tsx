import React, {Context, useContext, useEffect, useState} from "react";
import {initTheme, Root, Theme} from "../response/json";
import {fetchJson} from "./jsonHook";
import {useRootJson} from "./rootJsonHook";
import {debug} from "../index";
import type {} from "css-font-loading-module"

let ThemeContext: Context<Theme> = React.createContext(initTheme())
let setThemeFunc: (theme: Theme) => void = () => {
}

export function ThemeProvider(props: any) {
    const [theme, setState] = useState(initTheme)
    const [font, setFont] = useState("")
    const root = useRootJson()
    setThemeFunc = setState
    useEffect(() => {
        resetTheme(root)
    }, [root])

    const nonNullTheme = theme == undefined ? initTheme() : theme

    useEffect(() => {

        const promises = theme.fonts.map((font) => {
            if (debug) console.debug(font)
            return new FontFace(font.name, "url(" + font.url + ")").load()
        })
        Promise.all(promises).then((fontFaces) => {
            fontFaces.forEach((font)=>{
                document.fonts.add(font)
            })
            let fontsStr = ""
            for (const font of theme.fonts) {
                fontsStr = fontsStr == "" ? fontsStr + font.name : fontsStr + "," + font.name
            }
            setFont(fontsStr)
        }, (e: DOMException) => {
            console.error(e)
        })
    }, [theme])

    return <ThemeContext.Provider value={nonNullTheme}>
        <div style={{
            color: nonNullTheme.baseText, backgroundColor: nonNullTheme.thinTheme,
            fontFamily: font
        }}>
            {props.children}
        </div>
    </ThemeContext.Provider>
}

export function resetTheme(root: Root) {
    fetchJson(root.themeUrl, setThemeFunc, initTheme)
}

export function useTheme(): Theme {
    return useContext(ThemeContext)
}