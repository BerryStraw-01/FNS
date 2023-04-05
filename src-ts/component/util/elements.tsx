import {useTheme} from "../../hooks/themeHook";
import React, {CSSProperties} from "react";
import {getDefault} from "../../util/util";

interface Props {
    inStyle?: CSSProperties | undefined
    inClass?: string | undefined
    outStyle?: CSSProperties | undefined
    outClass?: string | undefined
    backColor?: string
    frameColor?: string
    children: any
    removePadding?: boolean
    inWidthClass?: string
    outWidthClass?: string
}

export function BlockFrame(props: Props) {
    const theme = useTheme()

    const outStyle = getDefault(props.outStyle, {})
    outStyle.backgroundColor = getDefault(props.backColor, theme.theme1)

    const inStyle = getDefault(props.inStyle, {})
    inStyle.borderColor = getDefault(props.frameColor, theme.theme3)

    let inClass = getDefault(props.inClass, "")
    let outClass = getDefault(props.outClass, "")

    if (!props.removePadding) {
        outClass = outClass + " p-1"
    }

    inClass = inClass + " " + getDefault(props.inWidthClass, "w-full")
    outClass = outClass + " " + getDefault(props.outWidthClass, "w-min")

    return <div className={"min-w-min " + outClass} style={outStyle}>
        <div className={"border-2 min-w-min " + inClass}
             style={inStyle}>
            {props.children}
        </div>
    </div>
}

export function ButtonFrame(props: Props) {
    const newProps = {...props}
    newProps.outClass = getDefault(newProps.outClass, "") + " hover:drop-shadow-lg"
    return BlockFrame(newProps)
}