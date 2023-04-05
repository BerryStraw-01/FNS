import React from "react";
import {useTheme} from "../hooks/themeHook";
import {Logo} from "./logo";
import {Link} from "react-router-dom";
import {Pages} from "./contents";
import {ButtonFrame} from "./util/elements";

interface Prop {
}

export function Header(props: Prop) {
    const theme = useTheme()

    return <div className={"pb-1 h-10 flex text-center"}
                style={{backgroundColor: theme.theme1}}>
        <div className={"mr-16 mt-auto"}>
            <Logo/>
        </div>

        <Link to={Pages.serviceList} className={"mt-auto"}>
            <ButtonFrame outClass={"pb-0"} inClass={"text-md px-2"}
                         frameColor={"#00000000"} backColor={""} removePadding>
                <p>services</p>
            </ButtonFrame>
        </Link>
    </div>
}
