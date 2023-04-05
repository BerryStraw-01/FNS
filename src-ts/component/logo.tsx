import React from "react";
import {useTheme} from "../hooks/themeHook";
import {Link} from "react-router-dom";
import {siteName} from "../index";
import {Pages} from "./contents";
import {ButtonFrame} from "./util/elements";

interface Prop {
}

export function Logo(props: Prop) {
    const theme = useTheme()
    return <div className={" text-center w-32 text-xl"}
                style={{color: theme.accent}}>
        <Link to={Pages.top}>
            <ButtonFrame inClass={"text-md px-2"} frameColor={"#00000000"} backColor={""} removePadding>
                {siteName}
            </ButtonFrame>
        </Link>
    </div>
}
