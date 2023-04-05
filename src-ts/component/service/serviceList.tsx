import React from "react";
import {useJson} from "../../hooks/jsonHook";
import {useRootJson} from "../../hooks/rootJsonHook";
import {initServiceList} from "../../response/json";
import {useTheme} from "../../hooks/themeHook";
import {ButtonFrame} from "../util/elements";
import {Link} from "react-router-dom";
import {Pages} from "../contents";
import {mapReactPath} from "../../util/util";

interface Prop {
}

export function ServiceList(prop: Prop) {
    const services = useJson(useRootJson().serviceList, initServiceList)
    const theme = useTheme()
    const elements: JSX.Element[] = []

    for (const service of services.serviceList) {
        elements.push(
            <Link to={mapReactPath(Pages.service, "id", service.strId)}
                  key={service.strId}>
                <ButtonFrame inClass={"h-48 flex items-center"} outClass={"m-5"}
                             inWidthClass={"w-60"}
                             inStyle={{borderColor: theme.theme3}}>
                    <div className={"w-full"}>
                        <h2 className={"text-center text-xl"}>
                            {service.title}
                        </h2>
                        <p className={"text-center"}>
                            {service.subtitle}
                        </p>
                    </div>
                </ButtonFrame>
            </Link>
        )
    }

    return <div className={" max-w-4xl mx-auto"}>
        <h1 style={{color: theme.accent}}
            className={"my-5 text-6xl text-center"}>services</h1>
        <div className={" flex"}>
            {elements}
        </div>
    </div>
}
