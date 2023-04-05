import {Service} from "./service";
import React from "react";
import {useParams} from "react-router-dom";

interface Props {
}

export function ServicePage(props: Props) {
    return <Service strId={useParams().id}/>
}