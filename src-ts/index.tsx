import React from "react";
import ReactDOM from "react-dom";
import {ThemeProvider} from "./hooks/themeHook";
import {BrowserRouter} from "react-router-dom";
import {Header} from "./component/header";
import {RootJsonProvider} from "./hooks/rootJsonHook";
import {Contents} from "./component/contents";

export const siteName = "kigawa.net"
export const debug = true


function Index() {
    return <BrowserRouter>
        <RootJsonProvider>
            <ThemeProvider>
                <Header/>
                <Contents/>
            </ThemeProvider>
        </RootJsonProvider>
    </BrowserRouter>
}

ReactDOM.render(<Index/>, document.getElementById("react"))

