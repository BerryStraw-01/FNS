import React, {Context, useContext, useEffect, useState} from "react";
import {fetchJson} from "./jsonHook";
import {initRoot, Root} from "../response/json";
import {createURL} from "../util/util";


const RootJsonContext: Context<Root> = React.createContext(initRoot())
let setRootJson: (rootJson: Root) => void = () => {
}

export async function fetchRootJson() {
    fetchJson(
        createURL(document.getElementById("baseurl").innerText),
        setRootJson,
        initRoot
    )

}

export function RootJsonProvider(props: any) {
    const [rootJson, setState] = useState<Root>(initRoot)
    setRootJson = setState
    useEffect(() => {
        fetchRootJson().then()
    }, [setRootJson])

    const rootJson1 = rootJson ? rootJson : initRoot()

    return <RootJsonContext.Provider value={rootJson1}>
        {props.children}
    </RootJsonContext.Provider>
}

export function useRootJson(): Root {
    return useContext(RootJsonContext)
}